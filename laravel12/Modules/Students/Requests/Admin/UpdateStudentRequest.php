<?php

namespace Modules\Students\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for admin-side student updates.
 *
 * This request preserves the existing student update validation rules,
 * including unique student ID/LRN handling and IP/ethnic group validation.
 *
 * Module: Students
 * Layer: Request
 */
class UpdateStudentRequest extends FormRequest
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
        $studentId = $this->route('student')?->id;

        return [
            'student_id' => ['nullable', 'string', 'max:255', 'unique:students,student_id,' . $studentId],
            'lrn' => ['nullable', 'string', 'max:255', 'unique:students,lrn,' . $studentId],
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
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->boolean('is_ip') && empty($this->input('ethnic_group'))) {
                $validator->errors()->add('ethnic_group', 'Ethnic group is required when IP is checked.');
            }
        });
    }
}