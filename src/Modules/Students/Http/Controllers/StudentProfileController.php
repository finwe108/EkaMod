<?php

namespace Modules\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Students\Requests\UpdateStudentProfileRequest;
use Modules\Students\Services\StudentProfileService;

/**
 * Handles student self-service profile pages.
 *
 * Module: StudentProfiles
 * Layer: HTTP Controller
 */
class StudentProfileController extends Controller
{
    public function show(): View
    {
        $student = auth()->user()->student;

        abort_if(!$student, 404, 'Student profile not found.');

        $student->load([
            'enrollments.schoolYear',
            'enrollments.gradeLevel',
            'enrollments.section',
        ]);

        return view('students::profile.show', compact('student'));
    }

    public function edit(): View
    {
        $student = auth()->user()->student;

        abort_if(!$student, 404, 'Student profile not found.');

        return view('students::profile.edit', compact('student'));
    }

    public function update(
        UpdateStudentProfileRequest $request,
        StudentProfileService $studentProfileService
    ): RedirectResponse {
        $student = auth()->user()->student;

        abort_if(!$student, 404, 'Student profile not found.');

        $studentProfileService->update($student, $request->validated());

        return back()->with('success', 'Profile updated.');
    }
}