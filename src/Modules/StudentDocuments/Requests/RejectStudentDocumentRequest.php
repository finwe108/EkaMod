<?php

namespace Modules\StudentDocuments\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for rejecting a student document.
 *
 * Module: StudentDocuments
 * Layer: Request
 */
class RejectStudentDocumentRequest extends FormRequest
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
     * Get validation rules for document rejection.
     *
     * Rejection remarks are required to preserve the existing business rule
     * that rejected documents must include a reason or note.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'remarks' => ['required', 'string'],
        ];
    }
}