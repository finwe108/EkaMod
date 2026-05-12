<?php

namespace App\Services;

use App\Models\TeacherLoadSchedule;

class TeacherLoadConflictService
{
    public static function findConflicts(
        int $teacherId,
        array $sectionIds,
        int $schoolYearId,
        array $termNumbers,
        array $schedules,
        ?int $ignoreTeacherLoadId = null
    ): array {
        $conflicts = [];
        $termNumbers = self::normalizeTermNumbers($termNumbers);

        foreach ($schedules as $index => $schedule) {
            $dayOfWeek = (int) ($schedule['day_of_week'] ?? 0);
            $timeStart = $schedule['time_start'] ?? null;
            $timeEnd = $schedule['time_end'] ?? null;
            $room = trim((string) ($schedule['room'] ?? ''));

            if (!$dayOfWeek || !$timeStart || !$timeEnd) {
                continue;
            }

            $baseQuery = TeacherLoadSchedule::query()
                ->with([
                    'teacherLoad.teacher',
                    'teacherLoad.section',
                    'teacherLoad.loadSections.section',
                    'teacherLoad.loadSubjects.subject',
                    'teacherLoad.terms',
                ])
                ->where('day_of_week', $dayOfWeek)
                ->where('time_start', '<', $timeEnd)
                ->where('time_end', '>', $timeStart)
                ->whereHas('teacherLoad', function ($query) use ($schoolYearId, $termNumbers, $ignoreTeacherLoadId) {
                    $query->where('school_year_id', $schoolYearId);

                    if ($ignoreTeacherLoadId) {
                        $query->where('id', '!=', $ignoreTeacherLoadId);
                    }

                    $query->where(function ($termQuery) use ($termNumbers) {
                        $termQuery
                            ->whereHas('terms', function ($query) use ($termNumbers) {
                                $query->whereIn('term_no', $termNumbers);
                            })
                            ->orDoesntHave('terms');
                    });
                });

            $teacherConflict = (clone $baseQuery)
                ->whereHas('teacherLoad', function ($query) use ($teacherId) {
                    $query->where('teacher_id', $teacherId);
                })
                ->first();

            if ($teacherConflict) {
                $conflicts[] = [
                    'row' => $index,
                    'type' => 'teacher',
                    'message' => self::messageWithTerms('Teacher already has a conflicting schedule', $teacherConflict, $termNumbers),
                    'record' => $teacherConflict,
                ];
                continue;
            }

            if (!empty($sectionIds)) {
                $sectionConflict = (clone $baseQuery)
                    ->whereHas('teacherLoad', function ($query) use ($sectionIds) {
                        $query->where(function ($sectionQuery) use ($sectionIds) {
                            $sectionQuery
                                ->whereIn('section_id', $sectionIds)
                                ->orWhereHas('loadSections', function ($query) use ($sectionIds) {
                                    $query->whereIn('section_id', $sectionIds);
                                });
                        });
                    })
                    ->first();

                if ($sectionConflict) {
                    $conflicts[] = [
                        'row' => $index,
                        'type' => 'section',
                        'message' => self::messageWithTerms('Section already has another subject scheduled at this time', $sectionConflict, $termNumbers),
                        'record' => $sectionConflict,
                    ];
                    continue;
                }
            }

            if ($room !== '') {
                $roomConflict = (clone $baseQuery)
                    ->where('room', $room)
                    ->first();

                if ($roomConflict) {
                    $conflicts[] = [
                        'row' => $index,
                        'type' => 'room',
                        'message' => self::messageWithTerms('Room is already in use at this time', $roomConflict, $termNumbers),
                        'record' => $roomConflict,
                    ];
                }
            }
        }

        return $conflicts;
    }

    protected static function normalizeTermNumbers(array $termNumbers): array
    {
        $normalized = collect($termNumbers)
            ->map(fn ($termNo) => (int) $termNo)
            ->filter(fn ($termNo) => in_array($termNo, [1, 2, 3], true))
            ->unique()
            ->sort()
            ->values()
            ->all();

        return $normalized ?: [1, 2, 3];
    }

    protected static function messageWithTerms(string $message, TeacherLoadSchedule $schedule, array $selectedTerms): string
    {
        $existingTerms = $schedule->teacherLoad?->termNumbers() ?? [1, 2, 3];
        $overlappingTerms = array_values(array_intersect($selectedTerms, $existingTerms));
        $termLabel = self::termLabel($overlappingTerms ?: $existingTerms);

        return "{$message} ({$termLabel}).";
    }

    protected static function termLabel(array $termNumbers): string
    {
        $termNumbers = self::normalizeTermNumbers($termNumbers);

        if ($termNumbers === [1, 2, 3]) {
            return 'Whole Year';
        }

        return collect($termNumbers)
            ->map(fn ($termNo) => 'Term '.$termNo)
            ->implode(', ');
    }

    public static function hasInternalConflicts(array $schedules): array
    {
        $conflicts = [];

        foreach ($schedules as $i => $a) {
            foreach ($schedules as $j => $b) {
                if ($i >= $j) {
                    continue;
                }

                if (
                    (int) ($a['day_of_week'] ?? 0) === (int) ($b['day_of_week'] ?? 0) &&
                    !empty($a['time_start']) &&
                    !empty($a['time_end']) &&
                    !empty($b['time_start']) &&
                    !empty($b['time_end']) &&
                    $a['time_start'] < $b['time_end'] &&
                    $a['time_end'] > $b['time_start']
                ) {
                    $conflicts[] = [
                        'message' => 'Two schedule rows overlap.',
                        'rows' => [$i, $j],
                    ];
                }
            }
        }

        return $conflicts;
    }
}
