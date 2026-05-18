<?php

namespace Modules\DocumentTypes\Actions;

use App\Models\DocumentType;

/**
 * Handles creation of document type records.
 *
 * Module: DocumentTypes
 * Layer: Action
 */
class CreateDocumentTypeAction
{
    /**
     * Execute document type creation.
     *
     * @param array<string, mixed> $data
     * @return DocumentType
     */
    public function execute(array $data): DocumentType
    {
        return DocumentType::create($data);
    }
}