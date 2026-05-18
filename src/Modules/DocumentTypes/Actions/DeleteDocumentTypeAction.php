<?php

namespace Modules\DocumentTypes\Actions;

use App\Models\DocumentType;

/**
 * Handles deletion of document type records.
 *
 * Module: DocumentTypes
 * Layer: Action
 */
class DeleteDocumentTypeAction
{
    /**
     * Execute document type deletion.
     *
     * @param DocumentType $documentType
     * @return void
     */
    public function execute(DocumentType $documentType): void
    {
        $documentType->delete();
    }
}