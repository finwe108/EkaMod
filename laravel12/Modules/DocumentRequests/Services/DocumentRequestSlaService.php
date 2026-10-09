<?php

namespace Modules\DocumentRequests\Services;

use App\Enums\DocumentRequestItemStatus;
use App\Models\DocumentRequest;
use App\Models\DocumentRequestItem;
use App\Services\SchoolWorkingCalendarService;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

class DocumentRequestSlaService
{
    public const STATUS_NOT_STARTED = 'NOT_STARTED';
    public const STATUS_ON_TIME = 'ON_TIME';
    public const STATUS_DUE_SOON = 'DUE_SOON';
    public const STATUS_OVERDUE = 'OVERDUE';
    public const STATUS_COMPLETED_ON_TIME = 'COMPLETED_ON_TIME';
    public const STATUS_COMPLETED_LATE = 'COMPLETED_LATE';

    public const FILTER_ON_TIME = 'on_time';
    public const FILTER_DUE_SOON = 'due_soon';
    public const FILTER_OVERDUE = 'overdue';

    public function __construct(
        protected SchoolWorkingCalendarService $calendar
    ) {
    }

    /**
     * Determine whether the requested document is governed by an SLA.
     */
    public function isEnabled(DocumentRequestItem $item): bool
    {
        return (bool) $item->documentType?->sla_enabled;
    }

    /**
     * Determine whether an item currently has an active SLA.
     *
     * Cancelled and unavailable documents stop SLA monitoring.
     */
    public function hasActiveSla(DocumentRequestItem $item): bool
    {
        return $this->isEnabled($item)
            && $item->sla_started_at !== null
            && $item->sla_due_at !== null
            && $item->sla_completed_at === null
            && ! in_array(
                $item->status,
                [
                    DocumentRequestItemStatus::UNAVAILABLE,
                    DocumentRequestItemStatus::CANCELLED,
                ],
                true
            );
    }

    /**
     * Calculate SLA timestamps without changing the requested item.
     *
     * @return array{
     *     sla_started_at: CarbonInterface,
     *     sla_due_at: CarbonInterface
     * }|null
     */
    public function calculate(
        DocumentRequestItem $item,
        CarbonInterface|string $verifiedAt
    ): ?array {
        if (! $this->isEnabled($item)) {
            return null;
        }

        $workingDays = $item->documentType->sla_working_days ?? 3;

        return [
            'sla_started_at' => $this->calendar->effectiveSlaStart($verifiedAt),
            'sla_due_at' => $this->calendar->calculateDueAt(
                $verifiedAt,
                $workingDays
            ),
        ];
    }

    /**
     * Recalculate the SLA deadline for an existing active SLA item.
     *
     * This is intended for correcting derived SLA due dates after a
     * policy/calculation change. The original SLA start is preserved.
     *
     * Cancelled and unavailable items are not modified.
     *
     * @return Carbon|null The recalculated due date, or null when the
     *                     item does not have an active SLA.
     */
    public function recalculateDueAt(DocumentRequestItem $item): ?Carbon
    {
        if (! $this->hasActiveSla($item)) {
            return null;
        }

        $workingDays = $item->documentType?->sla_working_days ?? 3;

        return $this->calendar->calculateDueAtFromSlaStart(
            $item->sla_started_at,
            $workingDays
        );
    }

    /**
     * Determine the current SLA state of an item.
     */
    public function status(
        DocumentRequestItem $item,
        ?CarbonInterface $now = null
    ): ?string {
        if (! $this->isEnabled($item)) {
            return null;
        }

        if (
            $item->sla_started_at === null
            || $item->sla_due_at === null
        ) {
            return self::STATUS_NOT_STARTED;
        }

        if (
            in_array(
                $item->status,
                [
                    DocumentRequestItemStatus::UNAVAILABLE,
                    DocumentRequestItemStatus::CANCELLED,
                ],
                true
            )
            && $item->sla_completed_at === null
        ) {
            return self::STATUS_NOT_STARTED;
        }

        $now = $now
            ? Carbon::instance($now)
            : now();

        if ($item->sla_completed_at !== null) {
            return $item->sla_completed_at->lessThanOrEqualTo(
                $item->sla_due_at
            )
                ? self::STATUS_COMPLETED_ON_TIME
                : self::STATUS_COMPLETED_LATE;
        }

        if ($now->greaterThan($item->sla_due_at)) {
            return self::STATUS_OVERDUE;
        }

        if ($this->isDueSoon($item, $now)) {
            return self::STATUS_DUE_SOON;
        }

        return self::STATUS_ON_TIME;
    }

    /**
     * Apply an SLA monitoring filter to a document request query.
     *
     * The filter is applied at document-item level. A request is returned
     * when at least one of its items matches the requested SLA state.
     *
     * Completed, cancelled, and unavailable items are not considered
     * active SLA work for these filters.
     */
    public function applyRequestFilter(
        Builder $query,
        ?string $status
    ): Builder {
        if (! in_array(
            $status,
            [
                self::FILTER_ON_TIME,
                self::FILTER_DUE_SOON,
                self::FILTER_OVERDUE,
            ],
            true
        )) {
            return $query;
        }

        $now = now();

        return $query->whereHas(
            'items',
            function (Builder $itemQuery) use ($status, $now) {

                $itemQuery
                    ->whereHas(
                        'documentType',
                        function (Builder $documentTypeQuery) {
                            $documentTypeQuery->where(
                                'sla_enabled',
                                true
                            );
                        }
                    )
                    ->whereNotNull('sla_started_at')
                    ->whereNotNull('sla_due_at')
                    ->whereNull('sla_completed_at')
                    ->whereNotIn('status', [
                        DocumentRequestItemStatus::UNAVAILABLE->value,
                        DocumentRequestItemStatus::CANCELLED->value,
                    ]);

                if ($status === self::FILTER_OVERDUE) {
                    $itemQuery->where(
                        'sla_due_at',
                        '<',
                        $now
                    );

                    return;
                }

                $dueSoonBoundary = $this->dueSoonBoundary($now);

                if ($status === self::FILTER_DUE_SOON) {
                    $itemQuery
                        ->where(
                            'sla_due_at',
                            '>=',
                            $now
                        )
                        ->where(
                            'sla_due_at',
                            '<=',
                            $dueSoonBoundary
                        );

                    return;
                }

                $itemQuery
                    ->where(
                        'sla_due_at',
                        '>=',
                        $now
                    )
                    ->where(
                        'sla_due_at',
                        '>',
                        $dueSoonBoundary
                    );
            }
        );
    }

    /**
     * Determine whether an active SLA is due within one working day.
     */
    public function isDueSoon(
        DocumentRequestItem $item,
        ?CarbonInterface $now = null
    ): bool {
        if (! $this->hasActiveSla($item)) {
            return false;
        }

        $now = $now
            ? Carbon::instance($now)
            : now();

        if ($now->greaterThan($item->sla_due_at)) {
            return false;
        }

        return $item->sla_due_at->lessThanOrEqualTo(
            $this->dueSoonBoundary($now)
        );
    }

    /**
     * Determine whether the active SLA is overdue.
     */
    public function isOverdue(
        DocumentRequestItem $item,
        ?CarbonInterface $now = null
    ): bool {
        if (! $this->hasActiveSla($item)) {
            return false;
        }

        $now = $now
            ? Carbon::instance($now)
            : now();

        return $now->greaterThan($item->sla_due_at);
    }

    /**
     * Return the amount of time remaining until the SLA deadline.
     *
     * Returns null when there is no active SLA.
     */
    public function remaining(
        DocumentRequestItem $item,
        ?CarbonInterface $now = null
    ): ?int {
        if (! $this->hasActiveSla($item)) {
            return null;
        }

        $now = $now
            ? Carbon::instance($now)
            : now();

        return $now->diffInSeconds(
            $item->sla_due_at,
            false
        );
    }

    /**
     * Return a human-readable SLA status label.
     */
    public function statusLabel(?string $status): ?string
    {
        return match ($status) {
            self::STATUS_ON_TIME => 'On Time',
            self::STATUS_DUE_SOON => 'Due Soon',
            self::STATUS_OVERDUE => 'Overdue',
            self::STATUS_COMPLETED_ON_TIME => 'Completed On Time',
            self::STATUS_COMPLETED_LATE => 'Completed Late',
            self::STATUS_NOT_STARTED => 'SLA Not Started',
            default => null,
        };
    }

    /**
     * Return the CSS class associated with an SLA status.
     */
    public function statusClass(?string $status): string
    {
        return match ($status) {
            self::STATUS_ON_TIME =>
                'document-sla-on-time',

            self::STATUS_DUE_SOON =>
                'document-sla-due-soon',

            self::STATUS_OVERDUE =>
                'document-sla-overdue',

            self::STATUS_COMPLETED_ON_TIME =>
                'document-sla-completed-on-time',

            self::STATUS_COMPLETED_LATE =>
                'document-sla-completed-late',

            self::STATUS_NOT_STARTED =>
                'document-sla-not-started',

            default =>
                'document-sla-default',
        };
    }

    /**
     * Return a human-readable remaining/overdue time label.
     *
     * Examples:
     *
     * - 2d 4h remaining
     * - 5h 20m remaining
     * - 35m remaining
     * - 1d 3h overdue
     */
    public function remainingLabel(
        DocumentRequestItem $item,
        ?CarbonInterface $now = null
    ): ?string {
        $remaining = $this->remaining(
            $item,
            $now
        );

        if ($remaining === null) {
            return null;
        }

        $absoluteSeconds = abs($remaining);

        $days = intdiv(
            $absoluteSeconds,
            86400
        );

        $hours = intdiv(
            $absoluteSeconds % 86400,
            3600
        );

        $minutes = intdiv(
            $absoluteSeconds % 3600,
            60
        );

        if ($days > 0) {
            $label = "{$days}d {$hours}h";
        } elseif ($hours > 0) {
            $label = "{$hours}h {$minutes}m";
        } else {
            $label = "{$minutes}m";
        }

        return $remaining < 0
            ? "{$label} overdue"
            : "{$label} remaining";
    }

    /**
     * Prepare SLA data for document-work-item.blade.php.
     *
     * This keeps SLA presentation logic out of Blade.
     */
    public function prepareItemForDisplay(
        DocumentRequestItem $item
    ): array {
        $slaStatus = $this->status($item);

        return [
            'sla_status' => $slaStatus,

            'sla_status_label' =>
                $this->statusLabel($slaStatus),

            'sla_status_class' =>
                $this->statusClass($slaStatus),

            'sla_remaining_label' =>
                $this->remainingLabel($item),

            'sla_due_at' =>
                $item->sla_due_at,
        ];
    }

    /**
     * Prepare an aggregate SLA summary for a document request.
     *
     * Counts are based on document items rather than requests.
     */
    public function prepareRequestSummary(
        DocumentRequest $request
    ): array {
        $statuses = $request->items
            ->map(
                fn (DocumentRequestItem $item) =>
                    $this->status($item)
            )
            ->filter();

        return [
            'overdue' => $statuses
                ->filter(
                    fn ($status) =>
                        $status === self::STATUS_OVERDUE
                )
                ->count(),

            'due_soon' => $statuses
                ->filter(
                    fn ($status) =>
                        $status === self::STATUS_DUE_SOON
                )
                ->count(),

            'on_time' => $statuses
                ->filter(
                    fn ($status) =>
                        $status === self::STATUS_ON_TIME
                )
                ->count(),

            'completed_on_time' => $statuses
                ->filter(
                    fn ($status) =>
                        $status === self::STATUS_COMPLETED_ON_TIME
                )
                ->count(),

            'completed_late' => $statuses
                ->filter(
                    fn ($status) =>
                        $status === self::STATUS_COMPLETED_LATE
                )
                ->count(),
        ];
    }

    /**
     * Calculate the end of the next working day using the configured
     * school SLA cutoff time.
     */
    protected function dueSoonBoundary(
        CarbonInterface $now
    ): Carbon {
        $nextWorkingDay =
            $this->calendar->followingWorkingDay($now);

        return $this->calendarCutoff(
            $nextWorkingDay
        );
    }

    protected function calendarCutoff(
        CarbonInterface $date
    ): Carbon {
        $cutoff = $this->calendar->cutoffTime();

        [$hour, $minute, $second] = array_map(
            'intval',
            explode(':', $cutoff)
        );

        return Carbon::instance($date)
            ->setTime(
                $hour,
                $minute,
                $second
            );
    }
}