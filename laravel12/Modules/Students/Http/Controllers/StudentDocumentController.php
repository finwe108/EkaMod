<?php

namespace Modules\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequirementRule;
use App\Models\StudentDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Students\Requests\UploadStudentDocumentRequest;
use Modules\Students\Services\StudentDocumentUploadService;

/**
 * Handles student-facing document upload pages and submissions.
 *
 * Module: StudentDocumentUploads
 * Layer: HTTP Controller
 */
class StudentDocumentController extends Controller
{
    /**
     * Display required document uploads for the authenticated student.
     *
     * @return View
     */
    public function index(): View
    {
        $student = auth()->user()->student;

        abort_if(!$student, 404, 'Student profile not found.');

        $currentEnrollment = $student->currentEnrollment()
            ->with(['schoolYear', 'gradeLevel', 'section'])
            ->first();

        $latestEnrollment = $student->latestEnrollment()
            ->with(['schoolYear', 'gradeLevel', 'section'])
            ->first();

        $displayEnrollment = $currentEnrollment ?: $latestEnrollment;

        $gradeLevelId = $displayEnrollment?->grade_level_id;
        $studentType = $displayEnrollment?->student_type;

        $rules = DocumentRequirementRule::with('documentType')
            ->where('is_required', true)
            ->whereHas('documentType', function ($query) {
                $query->where('is_active', true);
            })
            ->where(function ($query) use ($gradeLevelId) {
                $query->whereNull('grade_level_id')
                    ->orWhere('grade_level_id', $gradeLevelId);
            })
            ->where(function ($query) use ($studentType) {
                $query->whereNull('student_type')
                    ->orWhere('student_type', $studentType);
            })
            ->get()
            ->unique('document_type_id')
            ->values();

        $uploads = $student->documents()
            ->with('documentType')
            ->get()
            ->keyBy('document_type_id');

        return view('students::documents.index', compact(
            'student',
            'currentEnrollment',
            'latestEnrollment',
            'displayEnrollment',
            'rules',
            'uploads'
        ));
    }

    /**
     * Upload or replace a student document.
     *
     * @param UploadStudentDocumentRequest $request
     * @param DocumentRequirementRule $documentRequirementRule
     * @param StudentDocumentUploadService $service
     * @return RedirectResponse
     */
    public function upload(
        UploadStudentDocumentRequest $request,
        DocumentRequirementRule $documentRequirementRule,
        StudentDocumentUploadService $service
    ): RedirectResponse {
        $student = auth()->user()->student;

        abort_if(!$student, 404, 'Student profile not found.');

        $service->upload(
            $student,
            $documentRequirementRule,
            $request->file('file')
        );

        return back()->with('success', 'Document uploaded successfully.');
    }
}