<?php

namespace Modules\Students\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for admin-side student creation.
 *
 * This request preserves the existing student creation validation rules,
 * including IP/ethnic group validation and manual student ID requirements
 * for old/transferee students.
 *
 * Module: Students
 * Layer: Request
 */

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_ip' => $this->boolean('is_ip'),
        ]);
    }

    public function rules(): array
    {
        return [
            'student_id' => ['nullable', 'string', 'max:255', 'unique:students,student_id'],
            'lrn' => ['nullable', 'string', 'max:255', 'unique:students,lrn'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],

            'status' => ['required', 'in:active,inactive'],
            'sex' => ['nullable', 'in:male,female'],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:255'],
            'mother_tongue' => ['nullable', 'string', 'max:255'],

            'is_ip' => ['nullable', 'boolean'],
            'ethnic_group' => ['nullable', 'string', 'max:255'],

            'email' => ['nullable', 'email', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'house_street' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'municipality_city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],

            'father_name' => ['nullable', 'string', 'max:255'],
            'father_contact' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'mother_contact' => ['nullable', 'string', 'max:255'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_relationship' => ['nullable', 'string', 'max:255'],
            'parent_guardian_contact' => ['nullable', 'string', 'max:255'],
            'guardian_contact' => ['nullable', 'string', 'max:255'],

            'remarks' => ['nullable', 'string'],

            'school_year_id' => ['required', 'exists:school_years,id'],
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'student_type' => ['required', 'in:new,old,transferee'],
            'enrollment_status' => ['nullable', 'in:pending,enrolled'],
            
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->boolean('is_ip') && empty($this->input('ethnic_group'))) {
                $validator->errors()->add('ethnic_group', 'Ethnic group is required when IP is checked.');
            }

            $type = $this->input('student_type');
            $studentId = $this->input('student_id');

            if (in_array($type, ['old', 'transferee']) && empty($studentId)) {
                $validator->errors()->add(
                    'student_id',
                    'Student ID is required for old and transferee students.'
                );
            }
        
        });
    }
}