<?php

namespace Modules\StudentDocuments\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for verifying a student document.
 *
 * Module: StudentDocuments
 * Layer: Request
 */
class VerifyStudentDocumentRequest extends FormRequest
{
    /**
     * Authorization is handled by existing admin middleware and RBAC.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validation rules for document verification.
     *
     * Remarks remain optional because the existing workflow allows
     * verification without additional notes.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'remarks' => ['nullable', 'string'],
        ];
    }
}