<?php

namespace Modules\DocumentTypes\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for creating document types.
 *
 * Module: DocumentTypes
 * Layer: Request
 */
class StoreDocumentTypeRequest extends FormRequest
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
     * Get validation rules for creating a document type.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:document_types,name'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}