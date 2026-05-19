<?php

namespace Modules\StudentDocuments\Actions;

use App\Models\StudentDocument;

/**
 * Handles rejection of submitted student documents.
 *
 * Module: StudentDocuments
 * Layer: Action
 */
class RejectStudentDocumentAction
{
    /**
     * Mark a student document as rejected.
     *
     * @param StudentDocument $studentDocument
     * @param int|null $verifiedBy
     * @param string $remarks
     * @return StudentDocument
     */
    public function execute(
        StudentDocument $studentDocument,
        ?int $verifiedBy,
        string $remarks
    ): StudentDocument {
        $studentDocument->update([
            'status' => 'rejected',
            'is_verified' => false,
            'verified_by' => $verifiedBy,
            'verified_at' => null,
            'remarks' => $remarks,
        ]);

        return $studentDocument;
    }
}