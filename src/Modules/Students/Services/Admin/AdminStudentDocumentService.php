<?php

namespace Modules\Students\Services\Admin;

use App\Models\DocumentRequirementRule;
use App\Models\DocumentType;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Support\Files\FileCleanup;
use Illuminate\Http\UploadedFile;

/**
 * Handles admin-side student document upload workflows.
 *
 * This service supports uploading scanned/physical documents on behalf of
 * students, while preserving existing student document verification behavior.
 *
 * Module: Students
 * Layer: Service
 */
class AdminStudentDocumentService
{
    /**
     * Upload or replace a student document.
     *
     * Admin-uploaded documents are saved as submitted and unverified by
     * default, so the existing verification workflow can still be followed.
     *
     * @param Student $student
     * @param DocumentRequirementRule $documentRequirementRule
     * @param UploadedFile $file
     * @param int|null $uploadedBy
     * @param string|null $remarks
     * @return StudentDocument
     */
    public function upload(
        Student $student,
        DocumentRequirementRule $documentRequirementRule,
        UploadedFile $file,
        ?int $uploadedBy = null,
        ?string $remarks = null
    ): StudentDocument {
        $documentType = $documentRequirementRule->documentType;

        abort_if(! $documentType || ! $documentType->is_active, 404, 'Document type not found.');

        return $this->storeDocumentFile(
            student: $student,
            documentType: $documentType,
            file: $file,
            source: 'admin_uploaded',
            remarks: $remarks
        );
    }

    /**
     * Upload or replace a student document directly by document type.
     *
     * This supports admin-side document filing where the document may not be
     * required by the student's current requirement rules.
     *
     * @param Student $student
     * @param DocumentType $documentType
     * @param UploadedFile $file
     * @param int|null $uploadedBy
     * @param string|null $remarks
     * @return StudentDocument
     */
    public function uploadByDocumentType(
        Student $student,
        DocumentType $documentType,
        UploadedFile $file,
        ?int $uploadedBy = null,
        ?string $remarks = null
    ): StudentDocument {
        abort_if(! $documentType->is_active, 404, 'Document type not found.');

        return $this->storeDocumentFile(
            student: $student,
            documentType: $documentType,
            file: $file,
            source: 'admin_uploaded',
            remarks: $remarks
        );
    }

    /**
     * Store or replace a student document file.
     *
     * @param Student $student
     * @param DocumentType $documentType
     * @param UploadedFile $file
     * @param string $source
     * @param string|null $remarks
     * @return StudentDocument
     */
    protected function storeDocumentFile(
        Student $student,
        DocumentType $documentType,
        UploadedFile $file,
        string $source,
        ?string $remarks = null
    ): StudentDocument {
        $existing = StudentDocument::where('student_id', $student->id)
            ->where('document_type_id', $documentType->id)
            ->first();

        if ($existing) {
            FileCleanup::deletePublicFile($existing->file_path);
        }

        $uploadPath = public_path('uploads/student-documents/' . $student->id);

        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $filename = 'admin_document_'
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
                'source' => $source,
                'status' => 'submitted',
                'is_verified' => false,
                'verified_by' => null,
                'verified_at' => null,
                'remarks' => $remarks,
            ]
        );
    }
}