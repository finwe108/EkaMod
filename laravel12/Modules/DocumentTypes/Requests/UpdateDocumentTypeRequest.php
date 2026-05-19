<?php

namespace Modules\DocumentTypes\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Handles validation for updating document types.
 *
 * Module: DocumentTypes
 * Layer: Request
 */
class UpdateDocumentTypeRequest extends FormRequest
{
    /**
     * Authorization is handled by existing admin middleware/RBAC.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validation rules for updating a document type.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $documentType = $this->route('document_type') ?? $this->route('documentType');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('document_types', 'name')->ignore($documentType?->id),
            ],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}