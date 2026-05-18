<?php

namespace App\Http\Controllers\Student\Legacy;

use App\Http\Controllers\Controller;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        abort_if(!$student, 404, 'Student profile not found.');

        $student->load([
            'currentEnrollment.schoolYear',
            'currentEnrollment.gradeLevel',
            'currentEnrollment.section',
            'latestEnrollment.schoolYear',
            'latestEnrollment.gradeLevel',
            'latestEnrollment.section',
            'documents',
        ]);

        $displayEnrollment = $student->currentEnrollment ?: $student->latestEnrollment;
        $hasActiveEnrollment = (bool) $student->currentEnrollment;

        return view('student.dashboard', compact(
            'student',
            'displayEnrollment',
            'hasActiveEnrollment'
        ));
    }
}