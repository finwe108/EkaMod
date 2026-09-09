<?php

namespace Modules\DocumentRequests\Services;

use App\Enums\DocumentRequestItemStatus;
use App\Models\DocumentRequestItem;
use App\Models\DocumentRequestItemStatusHistory;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Handles lifecycle transitions for individual document request items.
 *
 * Example:
 *
 * pending
 *   ↓
 * processing
 *   ↓
 * ready_for_release
 *   ↓
 * released
 *
 * Alternative outcomes:
 *
 * pending / processing / ready_for_release
 *   ↓
 * unavailable
 *
 * pending / processing / ready_for_release
 *   ↓
 * cancelled
 */
class DocumentRequestItemLifecycleService
{
    /**
     * Define the allowed transitions for an individual document item.
     *
     * @var array<string, array<int, string>>
     */
    protected array $transitions = [
        'pending' => [
            'processing',
            'unavailable',
            'cancelled',
        ],

        'processing' => [
            'ready_for_release',
            'unavailable',
            'cancelled',
        ],

        'ready_for_release' => [
            'released',
            'unavailable',
            'cancelled',
        ],

        'released' => [],

        'unavailable' => [],

        'cancelled' => [],
    ];

    protected DocumentRequestStatusAggregator $statusAggregator;

    public function __construct(
        DocumentRequestStatusAggregator $statusAggregator
    ) {
        $this->statusAggregator = $statusAggregator;
    }

    /**
     * Transition an item to a new lifecycle status.
     *
     * The item update, history record, and parent request status
     * aggregation are committed together.
     *
     * @throws InvalidArgumentException
     */
    public function transition(
        DocumentRequestItem $item,
        DocumentRequestItemStatus|string $newStatus,
        ?int $userId = null,
        ?string $remarks = null
    ): DocumentRequestItem {
        $enumStatus = $this->resolveStatus($newStatus);

        $currentStatus = $item->status instanceof DocumentRequestItemStatus
            ? $item->status->value
            : $item->status;

        $targetStatus = $enumStatus->value;

        $allowedTransitions = $this->transitions[$currentStatus] ?? [];

        if (! in_array($targetStatus, $allowedTransitions, true)) {
            throw new InvalidArgumentException(
                "Cannot transition document request item "
                . "{$item->id} from {$currentStatus} to {$targetStatus}."
            );
        }

        return DB::transaction(function () use (
            $item,
            $currentStatus,
            $targetStatus,
            $userId,
            $remarks
        ): DocumentRequestItem {
            /*
            * Reload the parent request inside the transaction.
            *
            * We use the relationship instead of relying on a previously
            * loaded model so the aggregator works with the current parent.
            */
            $documentRequest = $item->request()->firstOrFail();

            /*
            * Mark the beginning of actual preparation.
            */
            if (
                $targetStatus === DocumentRequestItemStatus::PROCESSING->value
                && ! $item->processing_started_at
            ) {
                $item->processing_started_at = now();
                $item->processed_by_user_id = $userId;
            }

            /*
            * Mark when the document became ready.
            */
            if (
                $targetStatus === DocumentRequestItemStatus::READY_FOR_RELEASE->value
                && ! $item->ready_at
            ) {
                $item->ready_at = now();
            }

            /*
            * Update the individual document item.
            */
            $item->status = $targetStatus;
            $item->save();

            /*
            * Record the individual item lifecycle transition.
            */
            DocumentRequestItemStatusHistory::create([
                'document_request_item_id' => $item->id,
                'from_status' => $currentStatus,
                'to_status' => $targetStatus,
                'changed_by_user_id' => $userId,
                'remarks' => $remarks,
            ]);

            /*
            * Recalculate the parent request status based on the
            * current status of ALL its document items.
            *
            * This happens after the item has been saved, so the
            * aggregator sees the new item status.
            */
            $this->statusAggregator->refresh($documentRequest);

            return $item->fresh();
        });
    }

    /**
     * Start processing an individual requested document.
     */
    public function startProcessing(
        DocumentRequestItem $item,
        ?int $userId = null,
        ?string $remarks = null
    ): DocumentRequestItem {
        return $this->transition(
            $item,
            DocumentRequestItemStatus::PROCESSING,
            $userId,
            $remarks
        );
    }

    /**
     * Mark an individual document as ready for release.
     */
    public function markReadyForRelease(
        DocumentRequestItem $item,
        ?int $userId = null,
        ?string $remarks = null
    ): DocumentRequestItem {
        return $this->transition(
            $item,
            DocumentRequestItemStatus::READY_FOR_RELEASE,
            $userId,
            $remarks
        );
    }

    /**
     * Release an individual document.
     *
     * The recipient name is mandatory because documents belonging
     * to the same request may be released separately.
     *
     * After releasing the item, the parent document request status
     * is recalculated from the statuses of all its items.
     *
     * @throws InvalidArgumentException
     */
    public function release(
        DocumentRequestItem $item,
        string $releasedToName,
        ?int $userId = null,
        ?string $releaseNotes = null,
        ?string $remarks = null
    ): DocumentRequestItem {
        $releasedToName = trim($releasedToName);

        if ($releasedToName === '') {
            throw new InvalidArgumentException(
                'The name of the person receiving the document is required.'
            );
        }

        $currentStatus = $item->status instanceof DocumentRequestItemStatus
            ? $item->status->value
            : $item->status;

        $targetStatus = DocumentRequestItemStatus::RELEASED->value;

        $allowedTransitions = $this->transitions[$currentStatus] ?? [];

        if (! in_array($targetStatus, $allowedTransitions, true)) {
            throw new InvalidArgumentException(
                "Cannot transition document request item "
                . "{$item->id} from {$currentStatus} to {$targetStatus}."
            );
        }

        return DB::transaction(function () use (
            $item,
            $currentStatus,
            $targetStatus,
            $releasedToName,
            $userId,
            $releaseNotes,
            $remarks
        ): DocumentRequestItem {
            /*
            * Get the parent request inside the transaction.
            */
            $documentRequest = $item->request()->firstOrFail();

            /*
            * Record the item's release.
            */
            $item->status = $targetStatus;

            $transitionedAt = now();

            if (! $item->released_at) {
                $item->released_at = $transitionedAt;
            }

            if (! $item->sla_completed_at) {
                $item->sla_completed_at = $transitionedAt;
            }

            $item->released_to_name = $releasedToName;
            $item->release_notes = $releaseNotes;

            $item->save();

            /*
            * Record the individual item lifecycle transition.
            */
            DocumentRequestItemStatusHistory::create([
                'document_request_item_id' => $item->id,
                'from_status' => $currentStatus,
                'to_status' => $targetStatus,
                'changed_by_user_id' => $userId,
                'remarks' => $remarks,
            ]);

            /*
            * Recalculate the parent request status using all
            * current document item statuses.
            *
            * This supports partial releases. For example:
            *
            * - Item A: released
            * - Item B: ready_for_release
            *
            * The parent request remains in the appropriate
            * non-terminal aggregate state until all items
            * reach their final outcome.
            */
            $this->statusAggregator->refresh($documentRequest);

            return $item->fresh();
        });
    }

    /**
     * Mark an individual document as unavailable.
     *
     * A reason is mandatory because the requester and Registrar
     * must be able to determine why this document was not supplied.
     *
     * After marking the item unavailable, the parent document request
     * status is recalculated from the statuses of all its items.
     *
     * @throws InvalidArgumentException
     */
    public function markUnavailable(
        DocumentRequestItem $item,
        string $reason,
        ?int $userId = null,
        ?string $remarks = null
    ): DocumentRequestItem {
        $reason = trim($reason);

        if ($reason === '') {
            throw new InvalidArgumentException(
                'A reason is required when marking a document unavailable.'
            );
        }

        $currentStatus = $item->status instanceof DocumentRequestItemStatus
            ? $item->status->value
            : $item->status;

        $targetStatus = DocumentRequestItemStatus::UNAVAILABLE->value;

        $allowedTransitions = $this->transitions[$currentStatus] ?? [];

        if (! in_array($targetStatus, $allowedTransitions, true)) {
            throw new InvalidArgumentException(
                "Cannot transition document request item "
                . "{$item->id} from {$currentStatus} to {$targetStatus}."
            );
        }

        return DB::transaction(function () use (
            $item,
            $currentStatus,
            $targetStatus,
            $reason,
            $userId,
            $remarks
        ): DocumentRequestItem {
            /*
            * Get the parent request inside the transaction so the
            * aggregate status can be refreshed after this item changes.
            */
            $documentRequest = $item->request()->firstOrFail();

            /*
            * Record the unavailable outcome for this specific item.
            */
            $item->status = $targetStatus;
            $item->unavailable_reason = $reason;
            $item->save();

            /*
            * Record the individual item lifecycle transition.
            *
            * If additional lifecycle remarks were supplied, preserve them.
            * Otherwise, use the unavailable reason as the audit remark.
            */
            DocumentRequestItemStatusHistory::create([
                'document_request_item_id' => $item->id,
                'from_status' => $currentStatus,
                'to_status' => $targetStatus,
                'changed_by_user_id' => $userId,
                'remarks' => $remarks ?: $reason,
            ]);

            /*
            * Recalculate the parent request based on all document items.
            *
            * Example:
            * - TOR: released
            * - Diploma: unavailable
            *
            * Both items now have final outcomes, so the aggregator can
            * determine the appropriate final parent request status.
            */
            $this->statusAggregator->refresh($documentRequest);

            return $item->fresh();
        });
    }

    /**
     * Cancel an individual document.
     *
     * A reason is mandatory to preserve an audit trail.
     *
     * @throws InvalidArgumentException
     */
    public function cancel(
        DocumentRequestItem $item,
        string $reason,
        ?int $userId = null
    ): DocumentRequestItem {
        $reason = trim($reason);

        if ($reason === '') {
            throw new InvalidArgumentException(
                'A reason is required when cancelling a requested document.'
            );
        }

        return $this->transition(
            $item,
            DocumentRequestItemStatus::CANCELLED,
            $userId,
            $reason
        );
    }

    /**
     * Resolve a string or enum into the canonical item status enum.
     *
     * @throws InvalidArgumentException
     */
    protected function resolveStatus(
        DocumentRequestItemStatus|string $status
    ): DocumentRequestItemStatus {
        if ($status instanceof DocumentRequestItemStatus) {
            return $status;
        }

        try {
            return DocumentRequestItemStatus::from($status);
        } catch (\ValueError) {
            throw new InvalidArgumentException(
                "Invalid document request item status: {$status}"
            );
        }
    }
}
