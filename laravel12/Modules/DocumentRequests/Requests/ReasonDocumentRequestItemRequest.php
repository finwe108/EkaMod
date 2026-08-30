<?php

namespace Modules\DocumentRequests\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates document request item lifecycle actions requiring a reason.
 *
 * Used when marking an item unavailable or cancelled.
 *
 * Module: DocumentRequests
 * Layer: Request
 */
class ReasonDocumentRequestItemRequest extends FormRequest
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
            'reason' => [
                'required',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ];
    }
}
