<?php

namespace Modules\Students\Actions;

use App\Models\DocumentRequirementRule;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Support\Files\FileCleanup;
use Illuminate\Http\UploadedFile;

/**
 * Handles storing a student document upload.
 *
 * This action preserves the existing public/uploads storage behavior and
 * update-or-create database behavior from the monolith controller.
 *
 * Module: Students
 * Layer: Action
 */
class UploadStudentDocumentAction
{
    /**
     * Upload and register a student document.
     *
     * @param Student $student
     * @param DocumentRequirementRule $documentRequirementRule
     * @param UploadedFile $file
     * @return StudentDocument
     */
    public function execute(
        Student $student,
        DocumentRequirementRule $documentRequirementRule,
        UploadedFile $file
    ): StudentDocument {
        $documentType = $documentRequirementRule->documentType;

        abort_if(! $documentType || ! $documentType->is_active, 404, 'Document type not found.');

        $existing = StudentDocument::where('student_id', $student->id)
            ->where('document_type_id', $documentType->id)
            ->first();

        /*
         * If a student replaces a document, delete the old public file first
         * so unused uploads do not accumulate on the server.
         */
        if ($existing) {
            FileCleanup::deletePublicFile($existing->file_path);
        }

        $uploadPath = public_path('uploads/student-documents/' . $student->id);

        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $filename = 'document_'
            . $documentType->id
            . '_'
            . time()
            . '_'
            . uniqid()
            . '.'
            . $file->getClientOriginalExtension();

        $relativePath = 'uploads/student-documents/' . $student->id . '/' . $filename;

        $file->move($uploadPath, $filename);

        return StudentDocument::updateOrCreate(
            [
                'student_id' => $student->id,
                'document_type_id' => $documentType->id,
            ],
            [
                'file_path' => $relativePath,
                'original_filename' => $file->getClientOriginalName(),
                'source' => 'uploaded',
                'status' => 'submitted',
                'is_verified' => false,
                'verified_by' => null,
                'verified_at' => null,
            ]
        );
    }
}