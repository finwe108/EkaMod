<?php

namespace Modules\Sections\Requests;

use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Handles validation for updating sections.
 *
 * Module: Sections
 * Layer: Request
 */
class UpdateSectionRequest extends FormRequest
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
     * Get validation rules for section updates.
     *
     * The section name must remain unique within the selected school year,
     * excluding the current section record.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $section = $this->route('section');

        if (! $section instanceof Section) {
            $section = Section::find($section);
        }

        return [
            'school_year_id' => ['nullable', 'exists:school_years,id'],
            'grade_level_id' => ['nullable', 'exists:grade_levels,id'],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('sections', 'name')
                    ->where(function ($query) {
                        return $query->where('school_year_id', $this->input('school_year_id'));
                    })
                    ->ignore($section?->id),
            ],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'capacity' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'This section name already exists for the selected school year.',
        ];
    }
}