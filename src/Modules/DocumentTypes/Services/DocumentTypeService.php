<?php

namespace Modules\DocumentTypes\Services;

use App\Models\DocumentType;
use Modules\DocumentTypes\Actions\CreateDocumentTypeAction;
use Modules\DocumentTypes\Actions\DeleteDocumentTypeAction;
use Modules\DocumentTypes\Actions\UpdateDocumentTypeAction;

/**
 * Handles document type persistence operations.
 *
 * This service keeps document type write operations outside the controller
 * while preserving the existing model, validation, schema, and behavior.
 *
 * Module: DocumentTypes
 * Layer: Service
 */
class DocumentTypeService
{
    /**
     * Create a new document type.
     *
     * Expected input must already be validated before reaching this service.
     *
     * @param array<string, mixed> $data
     * @return DocumentType
     */
    public function create(array $data): DocumentType
    {
        return app(CreateDocumentTypeAction::class)->execute($data);
    }

    /**
     * Update an existing document type.
     *
     * @param DocumentType $documentType
     * @param array<string, mixed> $data
     * @return DocumentType
     */
    public function update(DocumentType $documentType, array $data): DocumentType
    {
        return app(UpdateDocumentTypeAction::class)->execute($documentType, $data);
    }

    /**
     * Delete an existing document type.
     *
     * This preserves the current hard-delete behavior.
     *
     * @param DocumentType $documentType
     * @return void
     */
    public function delete(DocumentType $documentType): void
    {
        app(DeleteDocumentTypeAction::class)->execute($documentType);
    }
}