<?php

namespace Modules\DocumentRequests\Services;

use App\Enums\DocumentRequestStatus;
use App\Models\DocumentRequest;
use App\Models\DocumentRequestStatusHistory;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Handles document request lifecycle transitions.
 *
 * Module: DocumentRequests
 * Layer: Service
 */
class DocumentRequestLifecycleService
{
    /**
     * Define the allowed lifecycle transitions.
     *
     * Each key represents the current status and the associated
     * values represent statuses that may legally follow it.
     *
     * @var array<string, array<int, string>>
     */
    protected array $transitions = [
        DocumentRequestStatus::PENDING->value => [
            DocumentRequestStatus::VERIFICATION->value,
            DocumentRequestStatus::CANCELLED->value,
        ],

        DocumentRequestStatus::VERIFICATION->value => [
            DocumentRequestStatus::VERIFIED->value,
            DocumentRequestStatus::REJECTED->value,
            DocumentRequestStatus::CANCELLED->value,
        ],

        DocumentRequestStatus::VERIFIED->value => [
            DocumentRequestStatus::PROCESSING->value,
            DocumentRequestStatus::CANCELLED->value,
        ],

        DocumentRequestStatus::PROCESSING->value => [
            DocumentRequestStatus::READY_FOR_RELEASE->value,
            DocumentRequestStatus::CANCELLED->value,
        ],

        DocumentRequestStatus::READY_FOR_RELEASE->value => [
            DocumentRequestStatus::RELEASED->value,
            DocumentRequestStatus::CANCELLED->value,
        ],

        DocumentRequestStatus::RELEASED->value => [],

        DocumentRequestStatus::CANCELLED->value => [],

        DocumentRequestStatus::REJECTED->value => [],
    ];

    /**
     * Transition a document request to a new status.
     *
     * The status change and its history record are committed
     * together in a single database transaction.
     *
     * @throws InvalidArgumentException
     */
    public function transition(
        DocumentRequest $request,
        DocumentRequestStatus|string $newStatus,
        ?int $userId = null,
        ?string $remarks = null
    ): DocumentRequest {
        $enumStatus = $newStatus instanceof DocumentRequestStatus
            ? $newStatus
            : DocumentRequestStatus::tryFrom($newStatus);

        if (! $enumStatus) {
            throw new InvalidArgumentException(
                "Invalid document request status: {$newStatus}"
            );
        }

        $newStatus = $enumStatus->value;

        $currentStatus = $request->status;

        $allowedTransitions = $this->transitions[$currentStatus] ?? [];

        if (! in_array($newStatus, $allowedTransitions, true)) {
            throw new InvalidArgumentException(
                "Cannot transition document request "
                . "{$request->request_number} "
                . "from {$currentStatus} to {$newStatus}."
            );
        }

        return DB::transaction(function () use (
            $request,
            $currentStatus,
            $newStatus,
            $userId,
            $remarks
        ): DocumentRequest {
            $request->status = $newStatus;

            /*
             * Processing marks the point at which the Registrar
             * begins actual document preparation.
             */
            if (
                $newStatus === DocumentRequestStatus::PROCESSING->value
                && ! $request->processed_at
            ) {
                $request->processed_at = now();
                $request->processed_by_user_id = $userId;
            }

            /*
             * Released marks the actual release of the documents
             * to the requester.
             */
            if (
                $newStatus === DocumentRequestStatus::RELEASED->value
                && ! $request->released_at
            ) {
                $request->released_at = now();
            }

            $request->save();

            DocumentRequestStatusHistory::create([
                'document_request_id' => $request->id,
                'from_status' => $currentStatus,
                'to_status' => $newStatus,
                'changed_by_user_id' => $userId,
                'remarks' => $remarks,
            ]);

            return $request->fresh();
        });
    }

    public function startVerification(
        DocumentRequest $request,
        ?int $userId = null,
        ?string $remarks = null
    ): DocumentRequest {
        return $this->transition(
            $request,
            DocumentRequestStatus::VERIFICATION,
            $userId,
            $remarks
        );
    }

    public function verify(
        DocumentRequest $request,
        ?int $userId = null,
        ?string $remarks = null
    ): DocumentRequest {
        return $this->transition(
            $request,
            DocumentRequestStatus::VERIFIED,
            $userId,
            $remarks
        );
    }

    public function startProcessing(
        DocumentRequest $request,
        ?int $userId = null,
        ?string $remarks = null
    ): DocumentRequest {
        return $this->transition(
            $request,
            DocumentRequestStatus::PROCESSING,
            $userId,
            $remarks
        );
    }

    public function markReadyForRelease(
        DocumentRequest $request,
        ?int $userId = null,
        ?string $remarks = null
    ): DocumentRequest {
        return $this->transition(
            $request,
            DocumentRequestStatus::READY_FOR_RELEASE,
            $userId,
            $remarks
        );
    }

    public function release(
        DocumentRequest $request,
        string $releasedToName,
        ?int $userId = null,
        ?string $releaseNotes = null,
        ?string $remarks = null
    ): DocumentRequest {
        if (trim($releasedToName) === '') {
            throw new InvalidArgumentException(
                'The name of the person receiving the documents is required.'
            );
        }

        $currentStatus = $request->status;
        $newStatus = DocumentRequestStatus::RELEASED->value;

        $allowedTransitions = $this->transitions[$currentStatus] ?? [];

        if (! in_array($newStatus, $allowedTransitions, true)) {
            throw new InvalidArgumentException(
                "Cannot transition document request "
                . "{$request->request_number} "
                . "from {$currentStatus} to {$newStatus}."
            );
        }

        return DB::transaction(function () use (
            $request,
            $currentStatus,
            $newStatus,
            $releasedToName,
            $userId,
            $releaseNotes,
            $remarks
        ): DocumentRequest {
            $request->status = $newStatus;

            if (! $request->released_at) {
                $request->released_at = now();
            }

            $request->released_to_name = trim($releasedToName);
            $request->release_notes = $releaseNotes;

            $request->save();

            DocumentRequestStatusHistory::create([
                'document_request_id' => $request->id,
                'from_status' => $currentStatus,
                'to_status' => $newStatus,
                'changed_by_user_id' => $userId,
                'remarks' => $remarks,
            ]);

            return $request->fresh();
        });
    }

    public function cancel(
        DocumentRequest $request,
        ?int $userId = null,
        ?string $remarks = null
    ): DocumentRequest {
        return $this->transition(
            $request,
            DocumentRequestStatus::CANCELLED,
            $userId,
            $this->requireRemarks($remarks, 'cancelling')
        );
    }


    public function reject(
        DocumentRequest $request,
        ?int $userId = null,
        ?string $remarks = null
    ): DocumentRequest {
        return $this->transition(
            $request,
            DocumentRequestStatus::REJECTED,
            $userId,
            $this->requireRemarks($remarks, 'rejecting')
        );
    }


    protected function requireRemarks(
    ?string $remarks,
    string $action
    ): string {
    $remarks = trim((string) $remarks);

    if ($remarks === '') {
        throw new InvalidArgumentException(
            "A reason is required when {$action} a document request."
        );
    }

    return $remarks;


    }


    /**
     * Resolve a string or enum into the canonical status enum.
     *
     * @throws InvalidArgumentException
     */
    protected function resolveStatus(
        DocumentRequestStatus|string $status
    ): DocumentRequestStatus {
        if ($status instanceof DocumentRequestStatus) {
            return $status;
        }

        try {
            return DocumentRequestStatus::from($status);
        } catch (\ValueError) {
            throw new InvalidArgumentException(
                "Invalid document request status: {$status}"
            );
        }
    }
}
