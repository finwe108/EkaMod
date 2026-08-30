<?php

namespace Modules\DocumentRequests\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the release of a completed document request.
 *
 * Module: DocumentRequests
 * Layer: Request
 */
class ReleaseDocumentRequestRequest extends FormRequest
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
            'released_to_name' => [
                'required',
                'string',
                'max:255',
            ],

            'release_notes' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ];
    }
}