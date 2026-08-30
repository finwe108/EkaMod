<?php

namespace Modules\DocumentRequests\Actions;

use App\Enums\DocumentRequestStatus;
use App\Models\DocumentRequest;
use Illuminate\Support\Facades\DB;
use Modules\DocumentRequests\Services\DocumentRequestNumberGenerator;

/**
 * Handles creation of document request records.
 *
 * Module: DocumentRequests
 * Layer: Action
 */
class CreateDocumentRequestAction
{
    /**
     * Create a document request and its requested documents.
     *
     * The request and all request items are created inside one
     * database transaction so that partial requests cannot be saved.
     *
     * @param array<string, mixed> $data
     * @return DocumentRequest
     */
    public function execute(array $data): DocumentRequest
    {
        return DB::transaction(function () use ($data): DocumentRequest {
            $documentTypeIds = $data['document_type_ids'] ?? [];

            unset($data['document_type_ids']);

            $requestedAt = now();

            $data['request_number'] = app(
                DocumentRequestNumberGenerator::class
            )->generate($requestedAt);

            $data['requested_at'] = $requestedAt;

            $data['status'] = DocumentRequestStatus::PENDING->value;

            $request = DocumentRequest::create($data);

            foreach ($documentTypeIds as $documentTypeId) {
                $request->items()->create([
                    'document_type_id' => $documentTypeId,
                    'status' => DocumentRequestStatus::PENDING->value,
                ]);
            }

            return $request->load('items.documentType');
        });
    }
}