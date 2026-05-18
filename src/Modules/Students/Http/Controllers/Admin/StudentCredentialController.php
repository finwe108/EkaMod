<?php

namespace Modules\Students\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Students\Requests\Admin\UpdateStudentCredentialRequest;
use Modules\Students\Services\Admin\StudentCredentialService;

/**
 * Handles admin-side student login credential management.
 *
 * Module: Students
 * Layer: HTTP Controller
 */
class StudentCredentialController extends Controller
{
    /**
     * Show the student credential edit form.
     *
     * @param Student $student
     * @return View
     */
    public function edit(Student $student): View
    {
        $student->load('user');

        abort_if(! $student->user, 404, 'Student user account not found.');

        return view('students::admin.credentials.edit', compact('student'));
    }

    /**
     * Update student login credentials.
     *
     * @param UpdateStudentCredentialRequest $request
     * @param Student $student
     * @param StudentCredentialService $studentCredentialService
     * @return RedirectResponse
     */
    public function update(
        UpdateStudentCredentialRequest $request,
        Student $student,
        StudentCredentialService $studentCredentialService
    ): RedirectResponse {
        $studentCredentialService->update($student, $request->validated());

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Student login credentials updated successfully.');
    }

    /**
     * Create a student user account.
     *
     * @param Student $student
     * @param StudentCredentialService $studentCredentialService
     * @return RedirectResponse
     */
    public function store(
        Student $student,
        StudentCredentialService $studentCredentialService
    ): RedirectResponse {
        if ($student->user) {
            return back()->with('success', 'Student already has a user account.');
        }

        $result = $studentCredentialService->create($student);

        return back()
            ->with('success', 'Student user account created successfully.')
            ->with('generated_username', $result['user']?->username)
            ->with('generated_password', $result['password'])
            ->with('email_sent', $result['email_sent']);
    }
}