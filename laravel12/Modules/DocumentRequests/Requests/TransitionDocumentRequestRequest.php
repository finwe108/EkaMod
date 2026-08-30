<?php

namespace Modules\DocumentRequests\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates ordinary document request lifecycle transitions.
 *
 * Module: DocumentRequests
 * Layer: Request
 */
class TransitionDocumentRequestRequest extends FormRequest
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