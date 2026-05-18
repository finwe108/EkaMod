<?php

namespace Modules\SchoolYears\Requests;

use App\Models\SchoolYear;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Handles validation for updating school years.
 *
 * Module: SchoolYears
 * Layer: Request
 */
class UpdateSchoolYearRequest extends FormRequest
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
     * Normalize checkbox values before validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * Get validation rules for updating a school year.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $schoolYear = $this->route('school_year');

        if (! $schoolYear instanceof SchoolYear) {
            $schoolYear = $this->route('schoolYear');
        }

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('school_years', 'name')->ignore($schoolYear?->id),
            ],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}