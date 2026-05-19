<?php

namespace Modules\Academics\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for creating subjects.
 *
 * Module: Academics
 * Layer: Request
 */
class StoreSubjectRequest extends FormRequest
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
     * Get validation rules for creating a subject.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:subjects,code'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'grade_level_id' => ['nullable', 'exists:grade_levels,id'],
        ];
    }
}