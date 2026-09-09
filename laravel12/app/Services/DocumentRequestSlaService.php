<?php

namespace App\Services;

use App\Models\DocumentRequestItem;
use Carbon\CarbonInterface;

class DocumentRequestSlaService
{
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
     * Calculate SLA timestamps without changing the requested item.
     *
     * @return array{sla_started_at: CarbonInterface, sla_due_at: CarbonInterface}|null
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
}
