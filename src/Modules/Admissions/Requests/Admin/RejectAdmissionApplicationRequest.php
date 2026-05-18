<?php

namespace Modules\Admissions\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for rejecting admission applications.
 *
 * Module: Admissions
 * Layer: Request
 */
class RejectAdmissionApplicationRequest extends FormRequest
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
     * Get validation rules for admission rejection.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ];
    }
}