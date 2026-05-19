<?php

namespace Modules\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Students\Requests\UpdateStudentPasswordRequest;
use Modules\Students\Services\StudentPasswordService;

/**
 * Handles student password change pages.
 *
 * Module: StudentPasswords
 * Layer: HTTP Controller
 */
class StudentPasswordController extends Controller
{
    public function edit(): View
    {
        return view('students::password.edit');
    }

    public function update(
        UpdateStudentPasswordRequest $request,
        StudentPasswordService $studentPasswordService
    ): RedirectResponse {
        $studentPasswordService->update(
            $request->user(),
            $request->validated()['password']
        );

        return redirect()
            ->route('student.dashboard')
            ->with('success', 'Password changed successfully.');
    }
}