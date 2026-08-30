<?php

namespace Modules\DocumentRequests\Services;

use App\Enums\DocumentRequestItemStatus;
use App\Enums\DocumentRequestStatus;
use App\Models\DocumentRequest;
use Illuminate\Support\Facades\DB;

/**
 * Determines the overall status of a document request
 * from the lifecycle status of its individual document items.
 *
 * The parent request controls the initial workflow:
 *
 * pending -> verification -> verified
 *
 * Once verified, the overall status is derived from
 * the individual document request items.
 */
class DocumentRequestStatusAggregator
{
    /**
     * Recalculate and persist the parent request status.
     *
     * Initial and terminal request states are preserved because
     * they represent request-level decisions rather than item-level
     * processing progress.
     */
    public function refresh(
        DocumentRequest $documentRequest
    ): DocumentRequest {
        return DB::transaction(function () use (
            $documentRequest
        ): DocumentRequest {
            $documentRequest->load('items');

            $currentStatus = $this->normalizeStatus(
                $documentRequest->status
            );

            /*
             * These statuses are controlled at the request level.
             *
             * A rejected request must not be revived because an item
             * status changes, and a cancelled request remains cancelled.
             *
             * Pending and verification occur before individual document
             * processing begins.
             */
            if (in_array(
                $currentStatus,
                [
                    DocumentRequestStatus::PENDING->value,
                    DocumentRequestStatus::VERIFICATION->value,
                    DocumentRequestStatus::REJECTED->value,
                    DocumentRequestStatus::CANCELLED->value,
                ],
                true
            )) {
                return $documentRequest->fresh();
            }

            $newStatus = $this->determineStatus(
                $documentRequest
            );

            /*
             * Do not create unnecessary database updates when the
             * calculated status is already the current status.
             */
            if ($currentStatus === $newStatus->value) {
                return $documentRequest->fresh();
            }

            $documentRequest->status = $newStatus->value;

            /*
             * The parent request is released only when every requested
             * document has been successfully released.
             */
            if (
                $newStatus === DocumentRequestStatus::RELEASED
                && ! $documentRequest->released_at
            ) {
                $documentRequest->released_at = now();
            }

            /*
             * If the request becomes active again after previously
             * reaching a derived state, do not modify historical item
             * timestamps. Parent processed_at remains a historical marker
             * from the old request-level lifecycle for compatibility.
             */
            $documentRequest->save();

            return $documentRequest->fresh();
        });
    }

    /**
     * Determine the appropriate parent status from item statuses.
     */
    public function determineStatus(
        DocumentRequest $documentRequest
    ): DocumentRequestStatus {
        $items = $documentRequest->items;

        /*
         * A verified request with no items should remain verified.
         *
         * Normally this should never occur because creation requires
         * at least one document type.
         */
        if ($items->isEmpty()) {
            return DocumentRequestStatus::VERIFIED;
        }

        $statuses = $items
            ->map(fn ($item) => $this->normalizeItemStatus(
                $item->status
            ))
            ->values();

        /*
         * The request is complete only when every requested document
         * was actually released.
         */
        if ($statuses->every(
            fn (string $status) =>
                $status === DocumentRequestItemStatus::RELEASED->value
        )) {
            return DocumentRequestStatus::RELEASED;
        }

        /*
         * If any document is still actively being prepared,
         * the overall request is processing.
         */
        if ($statuses->contains(
            DocumentRequestItemStatus::PROCESSING->value
        )) {
            return DocumentRequestStatus::PROCESSING;
        }

        /*
         * A pending document means the request still has outstanding
         * work, even if other documents are already ready or released.
         */
        if ($statuses->contains(
            DocumentRequestItemStatus::PENDING->value
        )) {
            return DocumentRequestStatus::PROCESSING;
        }

        /*
         * If at least one document is ready for release and none remain
         * pending or processing, the request is ready for release.
         *
         * This also covers a mixed state such as:
         *
         * - one released
         * - one ready for release
         */
        if ($statuses->contains(
            DocumentRequestItemStatus::READY_FOR_RELEASE->value
        )) {
            return DocumentRequestStatus::READY_FOR_RELEASE;
        }

        /*
        * At this point, no item is pending, processing, or waiting
        * for release, and not every item was successfully released.
        *
        * Therefore, every item has reached a terminal outcome, but
        * one or more documents were unavailable or cancelled.
        *
        * Examples:
        *
        * - released + unavailable
        * - released + cancelled
        * - all unavailable
        * - all cancelled
        * - unavailable + cancelled
        */
        return DocumentRequestStatus::COMPLETED;
    }

    /**
     * Normalize a parent request status.
     */
    protected function normalizeStatus(
        DocumentRequestStatus|string $status
    ): string {
        return $status instanceof DocumentRequestStatus
            ? $status->value
            : $status;
    }

    /**
     * Normalize an individual document item status.
     */
    protected function normalizeItemStatus(
        DocumentRequestItemStatus|string $status
    ): string {
        return $status instanceof DocumentRequestItemStatus
            ? $status->value
            : $status;
    }
}
