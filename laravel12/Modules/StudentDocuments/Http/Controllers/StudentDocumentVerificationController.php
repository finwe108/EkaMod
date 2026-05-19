<?php

namespace Modules\StudentDocuments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Models\StudentDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\StudentDocuments\Requests\RejectStudentDocumentRequest;
use Modules\StudentDocuments\Requests\VerifyStudentDocumentRequest;
use Modules\StudentDocuments\Services\StudentDocumentVerificationService;

/**
 * Handles administrative verification of submitted student documents.
 *
 * Module: StudentDocuments
 * Layer: HTTP Controller
 */
class StudentDocumentVerificationController extends Controller
{
    /**
     * Display student documents for administrative review.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $documentTypeId = $request->query('document_type_id');
        $search = $request->query('q');

        $documentTypes = DocumentType::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $studentDocuments = StudentDocument::with(['student', 'documentType'])
            ->when($status, function ($query) use ($status) {
                if ($status === 'verified') {
                    $query->where('is_verified', true);
                } elseif ($status === 'pending') {
                    $query->where('is_verified', false)
                        ->where('status', 'submitted');
                } else {
                    $query->where('status', $status);
                }
            })
            ->when($documentTypeId, function ($query) use ($documentTypeId) {
                $query->where('document_type_id', $documentTypeId);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('student_id', 'like', "%{$search}%")
                        ->orWhere('lrn', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('student_documents::index', compact(
            'studentDocuments',
            'documentTypes',
            'status',
            'documentTypeId',
            'search'
        ));
    }

    /**
     * Verify a submitted student document.
     *
     * @param VerifyStudentDocumentRequest $request
     * @param StudentDocument $studentDocument
     * @param StudentDocumentVerificationService $service
     * @return RedirectResponse
     */
    public function verify(
        VerifyStudentDocumentRequest $request,
        StudentDocument $studentDocument,
        StudentDocumentVerificationService $service
    ): RedirectResponse {
        $validated = $request->validated();

        $service->verify(
            $studentDocument,
            auth()->id(),
            $validated['remarks'] ?? null
        );

        return back()->with('success', 'Student document verified successfully.');
    }

    /**
     * Reject a submitted student document.
     *
     * @param RejectStudentDocumentRequest $request
     * @param StudentDocument $studentDocument
     * @param StudentDocumentVerificationService $service
     * @return RedirectResponse
     */
    public function reject(
        RejectStudentDocumentRequest $request,
        StudentDocument $studentDocument,
        StudentDocumentVerificationService $service
    ): RedirectResponse {
        $validated = $request->validated();

        $service->reject(
            $studentDocument,
            auth()->id(),
            $validated['remarks']
        );

        return back()->with('success', 'Student document rejected.');
    }
}