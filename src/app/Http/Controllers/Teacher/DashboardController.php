<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function classes(Request $request)
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

        return view('teacher.classes', compact('user', 'teacher', 'teacherLoads'));
    }

    public function grades(Request $request)
    {
        $user = $request->user()->loadMissing([
            'roles',
            'employee.teacher',
        ]);

        $teacher = $user->employee?->teacher;

        return view('teacher.grades', compact('user', 'teacher'));
    }

    public function attendance(Request $request)
    {
        $user = $request->user()->loadMissing([
            'roles',
            'employee.teacher',
        ]);

        $teacher = $user->employee?->teacher;

        return view('teacher.attendance', compact('user', 'teacher'));
    }
}
