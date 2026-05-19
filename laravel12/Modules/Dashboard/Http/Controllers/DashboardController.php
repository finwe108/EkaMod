<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Dashboard\Services\DashboardNavigationService;

/**
 * Handles the role-aware system dashboard.
 *
 * Module: Dashboard
 * Layer: HTTP Controller
 */
class DashboardController extends Controller
{
    /**
     * Display a role-aware dashboard.
     *
     * @param Request $request
     * @param DashboardNavigationService $navigationService
     * @return View
     */
    public function index(
        Request $request,
        DashboardNavigationService $navigationService
    ): View {
        $user = $request->user()->loadMissing([
            'roles',
            'employee.currentDepartment',
            'employee.teacher',
            'student',
        ]);

        $roles = $user->roles->pluck('name')->toArray();

        $isAdminLike = in_array('super_admin', $roles, true)
            || in_array('admin', $roles, true)
            || in_array('hr', $roles, true);

        $isRegistrar = in_array('registrar', $roles, true);
        $isTeacher = in_array('teacher', $roles, true);
        $isStudent = in_array('student', $roles, true);

        /*
         * Dashboard statistics are role-specific.
         *
         * This prevents teachers and students from receiving admin-only
         * information such as financial totals or employee counts.
         */
        $stats = [];

        if ($isAdminLike) {
            $stats = [
                'students' => Student::count(),
                'teachers' => Teacher::count(),
                'employees' => Employee::count(),
                'school_years' => SchoolYear::count(),
                'collected' => class_exists(Payment::class) ? Payment::sum('amount') : 0,
                'attendance_rate' => 94.2,
                'sections' => Section::count(),
            ];
        } elseif ($isRegistrar) {
            $stats = [
                'students' => Student::count(),
                'school_years' => SchoolYear::count(),
                'sections' => Section::count(),
            ];
        } elseif ($isTeacher) {
            $teacher = $user->employee?->teacher;

            $stats = [
                'teacher_loads' => $teacher
                    ? $teacher->teacherLoads()->where('is_active', 1)->count()
                    : 0,
                'assigned_classes' => $teacher
                    ? $teacher->teacherLoads()
                        ->where('is_active', 1)
                        ->distinct('section_id')
                        ->count('section_id')
                    : 0,
            ];
        } elseif ($isStudent) {
            $student = $user->student;

            $stats = [
                'documents' => $student
                    ? $student->documents()->count()
                    : 0,
                'verified_documents' => $student
                    ? $student->documents()->where('is_verified', true)->count()
                    : 0,
            ];
        }

        $recentAnnouncements = Announcement::latest()->take(5)->get();

        $registrarData = [
            'show' => $isRegistrar || $isAdminLike,
            'recentStudents' => ($isRegistrar || $isAdminLike)
                ? Student::latest()->take(5)->get()
                : collect(),
        ];

        $teacherData = [
            'show' => $isTeacher,
            'teacherRecord' => $user->employee?->teacher,
        ];

        $adminData = [
            'show' => $isAdminLike,
            'recentEmployees' => $isAdminLike
                ? Employee::latest()->take(5)->get()
                : collect(),
        ];

        $studentData = [
            'show' => $isStudent,
            'studentRecord' => $user->student,
        ];

        $moduleLinks = $navigationService->linksFor($user);

        return view('dashboard::index', compact(
            'user',
            'roles',
            'stats',
            'recentAnnouncements',
            'registrarData',
            'teacherData',
            'adminData',
            'studentData',
            'moduleLinks'
        ));
    }
}