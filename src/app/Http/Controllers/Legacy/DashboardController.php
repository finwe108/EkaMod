<?php

namespace App\Http\Controllers\Legacy;

use App\Models\Announcement;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->loadMissing([
            'roles',
            'employee.currentDepartment',
            'employee.teacher',
        ]);

        $roles = $user->roles->pluck('name')->toArray();

        $isAdminLike = in_array('super_admin', $roles)
            || in_array('admin', $roles)
            || in_array('hr', $roles);

        $isRegistrar = in_array('registrar', $roles);
        $isTeacher = in_array('teacher', $roles);

        $stats = [
            'students' => Student::count(),
            'teachers' => Teacher::count(),
            'employees' => Employee::count(),
            'school_years' => SchoolYear::count(),
            'collected' => Payment::sum('amount'),
            'attendance_rate' => 94.2,
            'sections' => Section::count(),
        ];

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

        return view('dashboard.index', compact(
            'user',
            'roles',
            'stats',
            'recentAnnouncements',
            'registrarData',
            'teacherData',
            'adminData'
        ));
    }
}