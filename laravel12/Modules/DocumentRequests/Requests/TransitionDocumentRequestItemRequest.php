<?php

namespace Modules\DocumentRequests\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates ordinary document request item lifecycle transitions.
 *
 * Used when starting processing or marking an item ready for release.
 *
 * Module: DocumentRequests
 * Layer: Request
 */
class TransitionDocumentRequestItemRequest extends FormRequest
{
    /**
     * Authorization is handled by existing admin middleware/RBAC.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validation rules.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'remarks' => [
                'nullable',
                'string',
            ],
        ];
    }
}
