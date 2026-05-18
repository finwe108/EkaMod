<?php

namespace Modules\Teachers\Requests\Admin;

use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Handles validation for updating teacher profiles.
 *
 * Module: Teachers
 * Layer: Request
 */
class UpdateTeacherRequest extends FormRequest
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
        $teacher = $this->route('teacher');

        if (! $teacher instanceof Teacher) {
            $teacher = Teacher::find($teacher);
        }

        return [
            'teacher_no' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('teachers', 'teacher_no')->ignore($teacher?->id),
            ],
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