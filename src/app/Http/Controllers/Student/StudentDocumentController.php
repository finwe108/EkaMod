<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequirementRule;
use App\Models\StudentDocument;
use Illuminate\Http\Request;

class StudentDocumentController extends Controller
{
    public function index()
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

        return view('student.documents.index', compact(
            'student',
            'currentEnrollment',
            'latestEnrollment',
            'displayEnrollment',
            'rules',
            'uploads'
        ));
    }

    public function upload(Request $request, DocumentRequirementRule $documentRequirementRule)
    {
        $student = auth()->user()->student;

        abort_if(!$student, 404, 'Student profile not found.');

        $documentType = $documentRequirementRule->documentType;

        abort_if(!$documentType || !$documentType->is_active, 404, 'Document type not found.');

        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $existing = StudentDocument::where('student_id', $student->id)
            ->where('document_type_id', $documentType->id)
            ->first();

        if ($existing && $existing->file_path && file_exists(public_path($existing->file_path))) {
            unlink(public_path($existing->file_path));
        }

        $file = $request->file('file');

        $uploadPath = public_path('uploads/student-documents/' . $student->id);

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $filename = 'document_' . $documentType->id . '_' . time() . '.' . $file->getClientOriginalExtension();

        $file->move($uploadPath, $filename);

        StudentDocument::updateOrCreate(
            [
                'student_id' => $student->id,
                'document_type_id' => $documentType->id,
            ],
            [
                'file_path' => 'uploads/student-documents/' . $student->id . '/' . $filename,
                'original_filename' => $file->getClientOriginalName(),
                'source' => 'uploaded',
                'status' => 'submitted',
                'is_verified' => false,
                'verified_by' => null,
                'verified_at' => null,
            ]
        );

        return back()->with('success', 'Document uploaded successfully.');
    }
}