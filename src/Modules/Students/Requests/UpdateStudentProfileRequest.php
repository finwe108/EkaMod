<?php

namespace Modules\Students\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for student self-service profile updates.
 *
 * This request intentionally allows only limited contact-related fields.
 *
 * Module: StudentProfiles
 * Layer: Request
 */
class UpdateStudentProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contact_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string', 'max:255'],
            'guardian_contact' => ['nullable', 'string', 'max:20'],
        ];
    }
}