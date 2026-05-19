<?php

namespace Modules\Students\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequirementRule;
use App\Models\DocumentType;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Modules\Students\Requests\Admin\AdminUploadStudentDocumentRequest;
use Modules\Students\Services\Admin\AdminStudentDocumentService;

/**
 * Handles admin-side student document uploads.
 *
 * This controller allows authorized school staff to upload student documents
 * on behalf of students, especially for old/transferee students who already
 * submitted physical documents before the system was used.
 *
 * Module: Students
 * Layer: HTTP Controller
 */
class StudentDocumentController extends Controller
{
    /**
     * Upload or replace a student document from the admin student profile.
     *
     * @param AdminUploadStudentDocumentRequest $request
     * @param Student $student
     * @param DocumentRequirementRule $documentRequirementRule
     * @param AdminStudentDocumentService $studentDocumentService
     * @return RedirectResponse
     */
    public function upload(
        AdminUploadStudentDocumentRequest $request,
        Student $student,
        DocumentRequirementRule $documentRequirementRule,
        AdminStudentDocumentService $studentDocumentService
    ): RedirectResponse {
        $studentDocumentService->upload(
            $student,
            $documentRequirementRule,
            $request->file('file'),
            auth()->id(),
            $request->input('remarks')
        );

        return back()->with('success', 'Student document uploaded successfully.');
    }

    /**
     * Upload or replace a student document by document type.
     *
     * This is intended for admin-side uploads where the school may need to
     * attach documents that are not required by the current student-side
     * requirement rules.
     *
     * @param AdminUploadStudentDocumentRequest $request
     * @param Student $student
     * @param DocumentType $documentType
     * @param AdminStudentDocumentService $studentDocumentService
     * @return RedirectResponse
     */
    public function uploadByDocumentType(
        AdminUploadStudentDocumentRequest $request,
        Student $student,
        DocumentType $documentType,
        AdminStudentDocumentService $studentDocumentService
    ): RedirectResponse {
        $studentDocumentService->uploadByDocumentType(
            $student,
            $documentType,
            $request->file('file'),
            auth()->id(),
            $request->input('remarks')
        );

        return back()->with('success', 'Student document uploaded successfully.');
    }
}