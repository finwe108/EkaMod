<?php

namespace Modules\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\StudentAccounts\Requests\UpdateStudentAccountRequest;
use Modules\StudentAccounts\Services\StudentAccountService;

/**
 * Handles student self-service account management.
 *
 * Module: StudentAccounts
 * Layer: HTTP Controller
 */
class StudentAccountController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();

        return view('students::account.edit', compact('user'));
    }

    public function update(
        UpdateStudentAccountRequest $request,
        StudentAccountService $studentAccountService
    ): RedirectResponse {
        $studentAccountService->update(auth()->user(), $request->validated());

        return back()->with('success', 'Account updated successfully.');
    }
}