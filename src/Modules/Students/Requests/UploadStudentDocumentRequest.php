<?php

namespace Modules\Students\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for student document uploads.
 *
 * Module: StudentDocumentUploads
 * Layer: Request
 */
class UploadStudentDocumentRequest extends FormRequest
{
    /**
     * Authorization remains handled by the existing authenticated student route.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validation rules for uploaded student documents.
     *
     * The file types and max size preserve the existing upload rules.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}