<?php

namespace Modules\Students\Services;

use App\Models\DocumentRequirementRule;
use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Http\UploadedFile;
use Modules\Students\Actions\UploadStudentDocumentAction;

/**
 * Handles student-facing document upload workflows.
 *
 * Module: StudentDocumentUploads
 * Layer: Service
 */
class StudentDocumentUploadService
{
    /**
     * Upload a student document.
     *
     * @param Student $student
     * @param DocumentRequirementRule $documentRequirementRule
     * @param UploadedFile $file
     * @return StudentDocument
     */
    public function upload(
        Student $student,
        DocumentRequirementRule $documentRequirementRule,
        UploadedFile $file
    ): StudentDocument {
        return app(UploadStudentDocumentAction::class)
            ->execute($student, $documentRequirementRule, $file);
    }
}