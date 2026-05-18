<?php

namespace App\Http\Controllers\Admin\Legacy;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\TeacherLoad;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherScheduleController extends Controller
{
    public function show(Request $request, Teacher $teacher)
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYears = SchoolYear::orderByDesc('name')->get();

        $schoolYearId = $request->school_year_id ?: optional($activeSchoolYear)->id;

        $teacherLoads = TeacherLoad::with([
                'teacher.employee',
                'section',
                'schoolYear',
                'loadSubjects.subject',
                'schedules',
            ])
            ->where('teacher_id', $teacher->id)
            ->when($schoolYearId, function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->get();

        $dayLabels = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        $timePoints = collect();
        $scheduleItems = collect();

        foreach ($teacherLoads as $load) {
            $subjectNames = $load->loadSubjects
                ->pluck('subject.name')
                ->filter()
                ->values();

            $isMapeh = $subjectNames->count() === 4
                && collect(['Music', 'Arts', 'PE', 'Health'])->diff($subjectNames)->isEmpty();

            $displaySubject = $isMapeh
                ? 'MAPEH'
                : ($subjectNames->implode(' / ') ?: '—');

            foreach ($load->schedules as $schedule) {
                $timePoints->push(substr($schedule->time_start, 0, 5));
                $timePoints->push(substr($schedule->time_end, 0, 5));

                $scheduleItems->push([
                    'teacher_load_id' => $load->id,
                    'day_of_week' => (int) $schedule->day_of_week,
                    'day_label' => $dayLabels[(int) $schedule->day_of_week] ?? $schedule->day_of_week,
                    'time_start' => $schedule->time_start,
                    'time_end' => $schedule->time_end,
                    'time_start_hm' => substr($schedule->time_start, 0, 5),
                    'time_end_hm' => substr($schedule->time_end, 0, 5),
                    'subject_label' => $displaySubject,
                    'subject_details' => $subjectNames->implode(' / '),
                    'section_name' => $load->section?->name ?? '—',
                    'school_year_name' => $load->schoolYear?->name ?? '—',
                    'room' => $schedule->room ?: '—',
                ]);
            }
        }

        $timeSlots = $timePoints
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $matrix = [];
        $timeSlotArray = $timeSlots->values()->all();

        foreach ($timeSlotArray as $timeSlot) {
            foreach ($dayLabels as $dayNumber => $dayName) {
                $matrix[$timeSlot][$dayNumber] = [
                    'type' => 'empty',
                    'items' => [],
                    'rowspan' => 1,
                ];
            }
        }

        foreach ($scheduleItems as $item) {
            $startIndex = array_search($item['time_start_hm'], $timeSlotArray, true);
            $endIndex = array_search($item['time_end_hm'], $timeSlotArray, true);

            if ($startIndex === false) {
                continue;
            }

            if ($endIndex === false || $endIndex <= $startIndex) {
                $rowspan = 1;
            } else {
                $rowspan = $endIndex - $startIndex + 1;
            }

            $startSlot = $timeSlotArray[$startIndex];
            $dayNumber = $item['day_of_week'];

            $matrix[$startSlot][$dayNumber] = [
                'type' => 'start',
                'items' => [$item],
                'rowspan' => $rowspan,
            ];

            for ($i = $startIndex + 1; $i <= $startIndex + $rowspan - 1; $i++) {
                if (!isset($timeSlotArray[$i])) {
                    continue;
                }

                $coveredSlot = $timeSlotArray[$i];
                $matrix[$coveredSlot][$dayNumber] = [
                    'type' => 'skip',
                    'items' => [],
                    'rowspan' => 0,
                ];
            }
        }

        $scheduleList = $scheduleItems
            ->sortBy([
                ['day_of_week', 'asc'],
                ['time_start', 'asc'],
            ])
            ->values();

        return view('admin.teachers.schedule', compact(
            'teacher',
            'schoolYears',
            'activeSchoolYear',
            'schoolYearId',
            'dayLabels',
            'timeSlots',
            'matrix',
            'scheduleList'
        ));
    }
}