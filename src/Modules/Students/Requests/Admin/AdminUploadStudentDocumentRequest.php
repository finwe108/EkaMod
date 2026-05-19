<?php

namespace Modules\Students\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for admin-side student document uploads.
 *
 * Module: Students
 * Layer: Request
 */
class AdminUploadStudentDocumentRequest extends FormRequest
{
    /**
     * Authorization remains handled by existing admin middleware and RBAC.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validate uploaded student document.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}