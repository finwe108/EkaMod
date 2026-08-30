<?php

namespace Modules\DocumentRequests\Services;

use App\Models\DocumentRequest;
use Modules\DocumentRequests\Actions\CreateDocumentRequestAction;

/**
 * Handles document request persistence operations.
 *
 * Module: DocumentRequests
 * Layer: Service
 */
class DocumentRequestService
{
    /**
     * Create a new document request.
     *
     * Expected input must already be validated before reaching this service.
     *
     * @param array<string, mixed> $data
     * @return DocumentRequest
     */
    public function create(array $data): DocumentRequest
    {
        return app(CreateDocumentRequestAction::class)->execute($data);
    }
}