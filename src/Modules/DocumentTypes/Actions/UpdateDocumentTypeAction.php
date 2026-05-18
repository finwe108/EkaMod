<?php

namespace Modules\DocumentTypes\Actions;

use App\Models\DocumentType;

/**
 * Handles updating document type records.
 *
 * Module: DocumentTypes
 * Layer: Action
 */
class UpdateDocumentTypeAction
{
    /**
     * Execute document type update.
     *
     * @param DocumentType $documentType
     * @param array<string, mixed> $data
     * @return DocumentType
     */
    public function execute(DocumentType $documentType, array $data): DocumentType
    {
        $documentType->update($data);

        return $documentType;
    }
}