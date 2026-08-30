<?php

namespace Modules\DocumentRequests\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates lifecycle actions requiring a reason.
 *
 * Used for rejecting and cancelling document requests.
 *
 * Module: DocumentRequests
 * Layer: Request
 */
class ReasonDocumentRequestRequest extends FormRequest
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
                'required',
                'string',
            ],
        ];
    }
}