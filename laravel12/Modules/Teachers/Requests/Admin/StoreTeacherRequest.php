<?php

namespace Modules\Teachers\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for creating teacher profiles.
 *
 * Module: Teachers
 * Layer: Request
 */
class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_adviser' => $this->boolean('is_adviser'),
        ]);
    }

    public function rules(): array
    {
        return [
            'employee_id_ref' => ['required', 'exists:employees,id', 'unique:teachers,employee_id_ref'],
            'teacher_no' => ['nullable', 'string', 'max:50', 'unique:teachers,teacher_no'],
            'specialization' => ['nullable', 'string', 'max:150'],
            'subject_specialty' => ['nullable', 'string', 'max:150'],
            'license_no' => ['nullable', 'string', 'max:100'],
            'major' => ['nullable', 'string', 'max:100'],
            'rank_title' => ['nullable', 'string', 'max:100'],
            'is_adviser' => ['nullable', 'boolean'],
            'date_hired_as_teacher' => ['nullable', 'date'],
        ];
    }
}