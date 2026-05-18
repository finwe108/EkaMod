<?php

namespace Modules\Teachers\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Handles teacher portal overview pages.
 *
 * This controller preserves the existing teacher-facing classes, grades,
 * and attendance page behavior while moving ownership into the Teachers module.
 *
 * Module: Teachers
 * Layer: HTTP Controller
 */
class DashboardController extends Controller
{
    /**
     * Display teacher class loads.
     *
     * @param Request $request
     * @return View
     */
    public function classes(Request $request): View
    {
        $user = $request->user()->loadMissing([
            'roles',
            'employee.teacher.teacherLoads.subject',
            'employee.teacher.teacherLoads.section',
            'employee.teacher.teacherLoads.schoolYear',
            'employee.teacher.teacherLoads.loadSubjects.subject',
            'employee.teacher.teacherLoads.schedules',
            'employee.teacher.teacherLoads.terms',
        ]);

        $teacher = $user->employee?->teacher;

        $teacherLoads = $teacher
            ? $teacher->teacherLoads()
                ->with(['subject', 'section', 'schoolYear', 'loadSubjects.subject', 'schedules', 'terms'])
                ->where('is_active', 1)
                ->latest()
                ->get()
            : collect();

        return view('teachers::classes', compact('user', 'teacher', 'teacherLoads'));
    }

    /**
     * Display the teacher grades landing page.
     *
     * @param Request $request
     * @return View
     */
    public function grades(Request $request): View
    {
        $user = $request->user()->loadMissing([
            'roles',
            'employee.teacher',
        ]);

        $teacher = $user->employee?->teacher;

        // return view('teachers::grades', compact('user', 'teacher'));
        abort(404, 'Teacher grades landing page not implemented.');
    }

    /**
     * Display the teacher attendance landing page.
     *
     * @param Request $request
     * @return View
     */
    public function attendance(Request $request): View
    {
        $user = $request->user()->loadMissing([
            'roles',
            'employee.teacher',
        ]);

        $teacher = $user->employee?->teacher;

        return view('teachers::attendance', compact('user', 'teacher'));
    }

    /**
     * Display the authenticated teacher's schedule.
     *
     * This replaces the legacy broken implementation that referenced
     * a missing controller method and non-existent view.
     *
     * @param Request $request
     * @return View
     */
    public function schedule(Request $request): View
    {
        $user = $request->user()->loadMissing([
            'employee.teacher.teacherLoads.section',
            'employee.teacher.teacherLoads.schoolYear',
            'employee.teacher.teacherLoads.loadSubjects.subject',
            'employee.teacher.teacherLoads.schedules',
        ]);

        $teacher = $user->employee?->teacher;

        abort_unless($teacher, 403, 'No teacher profile is linked to this account.');

        $teacherLoads = $teacher->teacherLoads()
            ->with([
                'section',
                'schoolYear',
                'loadSubjects.subject',
                'schedules',
            ])
            ->where('is_active', 1)
            ->get();

        $dayLabels = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        $scheduleList = collect();

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
                $scheduleList->push([
                    'day_of_week' => (int) $schedule->day_of_week,
                    'day_label' => $dayLabels[(int) $schedule->day_of_week] ?? $schedule->day_of_week,
                    'time_start_hm' => substr($schedule->time_start, 0, 5),
                    'time_end_hm' => substr($schedule->time_end, 0, 5),
                    'subject_label' => $displaySubject,
                    'section_name' => $load->section?->name ?? '—',
                    'school_year_name' => $load->schoolYear?->name ?? '—',
                    'room' => $schedule->room ?: '—',
                ]);
            }
        }

        $scheduleList = $scheduleList
            ->sortBy([
                ['day_of_week', 'asc'],
                ['time_start_hm', 'asc'],
            ])
            ->values();

        return view('teachers::schedule', compact(
            'teacher',
            'scheduleList'
        ));
    }
}