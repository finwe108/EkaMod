<?php

namespace App\Services;

use App\Models\SchoolCalendarException;
use App\Models\SchoolSetting;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class SchoolWorkingCalendarService
{
    /**
     * Determine whether the supplied date is a working day.
     *
     * Default rule:
     * Monday-Friday = working day
     * Saturday-Sunday = non-working day
     *
     * Calendar exceptions override the default.
     */
    public function isWorkingDay(CarbonInterface|string $date): bool
    {
        $date = $this->asCarbon($date);

        $exception = SchoolCalendarException::query()
            ->whereDate('calendar_date', $date->toDateString())
            ->first();

        if ($exception !== null) {
            return $exception->isWorkingDay();
        }

        return $date->isWeekday();
    }

    /**
     * Determine whether the supplied date is a non-working day.
     */
    public function isNonWorkingDay(CarbonInterface|string $date): bool
    {
        return ! $this->isWorkingDay($date);
    }

    /**
     * Get the next working day on or after the supplied date.
     */
    public function nextWorkingDay(
        CarbonInterface|string $date
    ): Carbon {
        $current = $this->asCarbon($date)->startOfDay();

        while (! $this->isWorkingDay($current)) {
            $current->addDay();
        }

        return $current;
    }

    /**
     * Get the next working day strictly after the supplied date.
     */
    public function followingWorkingDay(
        CarbonInterface|string $date
    ): Carbon {
        $current = $this->asCarbon($date)->startOfDay()->addDay();

        return $this->nextWorkingDay($current);
    }

    /**
     * Add a number of working days.
     *
     * The starting date is counted as Day 1 when it is a working day.
     *
     * Example:
     *
     * Monday + 1 working day = Monday
     * Monday + 2 working days = Tuesday
     * Monday + 3 working days = Wednesday
     *
     * This behavior matches the agreed SLA policy.
     */
    public function addWorkingDays(
        CarbonInterface|string $date,
        int $workingDays
    ): Carbon {
        if ($workingDays < 1) {
            throw new \InvalidArgumentException(
                'Working days must be at least 1.'
            );
        }

        $current = $this->nextWorkingDay($date);

        $daysCounted = 1;

        while ($daysCounted < $workingDays) {
            $current->addDay();

            if ($this->isWorkingDay($current)) {
                $daysCounted++;
            }
        }

        return $current;
    }

    /**
     * Get the school's configured SLA cutoff time.
     *
     * The default is 3:00 PM.
     */
    public function cutoffTime(): string
    {
        $setting = SchoolSetting::current();

        if (empty($setting->document_request_sla_cutoff_time)) {
            return '15:00:00';
        }

        if ($setting->document_request_sla_cutoff_time instanceof CarbonInterface) {
            return $setting->document_request_sla_cutoff_time->format('H:i:s');
        }

        return (string) $setting->document_request_sla_cutoff_time;
    }

    /**
     * Determine the effective SLA start date/time.
     *
     * Policy:
     *
     * - Verified at or before the cutoff:
     *   The verification date counts as Day 1.
     *
     * - Verified after the cutoff:
     *   The next working day becomes Day 1.
     *
     * - Verification on a non-working day:
     *   The next working day becomes Day 1.
     *
     * The SLA anchor time is the configured cutoff time.
     */
    public function effectiveSlaStart(CarbonInterface|string $verifiedAt): Carbon
    {
        $verifiedAt = $this->asCarbon($verifiedAt);

        $cutoff = $this->cutoffCarbon($verifiedAt);

        if (
            $this->isWorkingDay($verifiedAt)
            && $verifiedAt->lessThanOrEqualTo($cutoff)
        ) {
            return $cutoff;
        }

        $nextWorkingDay = $this->followingWorkingDay($verifiedAt);

        return $this->cutoffCarbon($nextWorkingDay);
    }

    /**
     * Calculate an SLA due date/time.
     *
     * Example with 3 working days:
     *
     * Monday 2:00 PM
     * -> Tuesday Day 1
     * -> Wednesday Day 2
     * -> Thursday Day 3
     * -> Thursday 3:00 PM due
     */
    public function calculateDueAt(
        CarbonInterface|string $verifiedAt,
        int $workingDays
    ): Carbon {
        if ($workingDays < 1) {
            throw new \InvalidArgumentException(
                'Working days must be at least 1.'
            );
        }

        $start = $this->effectiveSlaStart($verifiedAt);

        return $this->calculateDueAtFromSlaStart(
            $start,
            $workingDays
        );
    }

    /**
     * Calculate an SLA deadline from an already-established SLA start.
     *
     * The SLA start day does not count as Day 1.
     *
     * Example:
     * - SLA starts Thursday
     * - 3 working days
     * - Friday = Day 1
     * - Monday = Day 2
     * - Tuesday = Day 3
     * - Tuesday at cutoff = deadline
     */
    public function calculateDueAtFromSlaStart(
        CarbonInterface|string $slaStartedAt,
        int $workingDays
    ): Carbon {
        if ($workingDays < 1) {
            throw new \InvalidArgumentException(
                'Working days must be at least 1.'
            );
        }

        $dueDate = $this->asCarbon($slaStartedAt);

        for ($daysCounted = 0; $daysCounted < $workingDays; ) {
            $dueDate->addDay();

            if ($this->isWorkingDay($dueDate)) {
                $daysCounted++;
            }
        }

        return $this->cutoffCarbon($dueDate);
    }


    /**
     * Convert a date/time value to Carbon.
     */
    protected function asCarbon(
        CarbonInterface|string $date
    ): Carbon {
        if ($date instanceof Carbon) {
            return $date->copy();
        }

        if ($date instanceof CarbonInterface) {
            return Carbon::instance($date)->copy();
        }

        return Carbon::parse($date);
    }

    /**
     * Build a Carbon instance using the configured cutoff time.
     */
    protected function cutoffCarbon(CarbonInterface $date): Carbon
    {
        [$hour, $minute, $second] = array_map(
            'intval',
            explode(':', $this->cutoffTime())
        );

        return $this->asCarbon($date)
            ->setTime($hour, $minute, $second);
    }
}