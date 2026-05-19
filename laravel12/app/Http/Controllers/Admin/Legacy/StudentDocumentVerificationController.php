<?php

namespace App\Http\Controllers\Admin\Legacy;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Models\StudentDocument;
use Illuminate\Http\Request;

class StudentDocumentVerificationController extends Controller
{
    public function index(Request $request)
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

        return view('admin.student_documents.index', compact(
            'studentDocuments',
            'documentTypes',
            'status',
            'documentTypeId',
            'search'
        ));
    }

    public function verify(Request $request, StudentDocument $studentDocument)
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'string'],
        ]);

        $studentDocument->update([
            'status' => 'verified',
            'is_verified' => true,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'remarks' => $validated['remarks'] ?? $studentDocument->remarks,
        ]);

        return back()->with('success', 'Student document verified successfully.');
    }

    public function reject(Request $request, StudentDocument $studentDocument)
    {
        $validated = $request->validate([
            'remarks' => ['required', 'string'],
        ]);

        $studentDocument->update([
            'status' => 'rejected',
            'is_verified' => false,
            'verified_by' => auth()->id(),
            'verified_at' => null,
            'remarks' => $validated['remarks'],
        ]);

        return back()->with('success', 'Student document rejected.');
    }
}