<?php

namespace Modules\StudentDocuments\Services;

use App\Models\StudentDocument;
use Modules\StudentDocuments\Actions\RejectStudentDocumentAction;
use Modules\StudentDocuments\Actions\VerifyStudentDocumentAction;

/**
 * Handles student document verification workflows.
 *
 * Module: StudentDocuments
 * Layer: Service
 */
class StudentDocumentVerificationService
{
    /**
     * Verify a student document.
     *
     * @param StudentDocument $studentDocument
     * @param int|null $verifiedBy
     * @param string|null $remarks
     * @return StudentDocument
     */
    public function verify(
        StudentDocument $studentDocument,
        ?int $verifiedBy,
        ?string $remarks = null
    ): StudentDocument {
        return app(VerifyStudentDocumentAction::class)
            ->execute($studentDocument, $verifiedBy, $remarks);
    }

    /**
     * Reject a student document.
     *
     * @param StudentDocument $studentDocument
     * @param int|null $verifiedBy
     * @param string $remarks
     * @return StudentDocument
     */
    public function reject(
        StudentDocument $studentDocument,
        ?int $verifiedBy,
        string $remarks
    ): StudentDocument {
        return app(RejectStudentDocumentAction::class)
            ->execute($studentDocument, $verifiedBy, $remarks);
    }
}