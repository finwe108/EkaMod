<?php

namespace Modules\StudentDocuments\Actions;

use App\Models\StudentDocument;

/**
 * Handles verification of submitted student documents.
 *
 * Module: StudentDocuments
 * Layer: Action
 */
class VerifyStudentDocumentAction
{
    /**
     * Mark a student document as verified.
     *
     * @param StudentDocument $studentDocument
     * @param int|null $verifiedBy
     * @param string|null $remarks
     * @return StudentDocument
     */
    public function execute(
        StudentDocument $studentDocument,
        ?int $verifiedBy,
        ?string $remarks = null
    ): StudentDocument {
        $studentDocument->update([
            'status' => 'verified',
            'is_verified' => true,
            'verified_by' => $verifiedBy,
            'verified_at' => now(),
            'remarks' => $remarks ?? $studentDocument->remarks,
        ]);

        return $studentDocument;
    }
}