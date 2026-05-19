<?php

namespace Modules\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Students\Services\StudentDashboardService;

/**
 * Handles the student dashboard page.
 *
 * Module: StudentDashboard
 * Layer: HTTP Controller
 */
class StudentDashboardController extends Controller
{
    public function index(StudentDashboardService $studentDashboardService): View
    {
        $student = auth()->user()->student;

        abort_if(!$student, 404, 'Student profile not found.');

        return view(
            'students::dashboard',
            $studentDashboardService->dataFor($student)
        );
    }
}