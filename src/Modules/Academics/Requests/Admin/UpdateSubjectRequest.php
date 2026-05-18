<?php

namespace Modules\Academics\Requests\Admin;

use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Handles validation for updating subjects.
 *
 * Module: Academics
 * Layer: Request
 */
class UpdateSubjectRequest extends FormRequest
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
     * Get validation rules for updating a subject.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $subject = $this->route('subject');

        if (! $subject instanceof Subject) {
            $subject = Subject::find($subject);
        }

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects', 'code')->ignore($subject?->id),
            ],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'grade_level_id' => ['nullable', 'exists:grade_levels,id'],
        ];
    }
}