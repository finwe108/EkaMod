<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdmissionApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'school_year_id' => ['nullable', 'exists:school_years,id'],
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'student_type' => ['required', 'in:new,transferee,returning'],

            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:30'],
            'sex' => ['required', 'in:Male,Female'],
            'birth_date' => ['required', 'date'],
            'birth_place' => ['nullable', 'string', 'max:150'],
            'mother_tongue' => ['nullable', 'string', 'max:100'],
            'is_ip' => ['nullable', 'boolean'],
            'ethnic_group' => ['required_if:is_ip,1', 'nullable', 'string', 'max:100'],
            'religion' => ['nullable', 'string', 'max:100'],
            'lrn' => ['nullable', 'string', 'max:20'],

            'email' => ['required', 'email', 'max:150'],
            'contact_number' => ['required', 'string', 'max:30'],

            'address' => ['nullable', 'string', 'max:255'],
            'house_street' => ['nullable', 'string', 'max:150'],
            'barangay' => ['nullable', 'string', 'max:150'],
            'municipality_city' => ['nullable', 'string', 'max:150'],
            'province' => ['nullable', 'string', 'max:150'],

            'father_name' => ['nullable', 'string', 'max:150'],
            'father_contact' => ['nullable', 'string', 'max:30'],
            'mother_name' => ['nullable', 'string', 'max:150'],
            'mother_contact' => ['nullable', 'string', 'max:30'],

            'guardian_name' => ['required', 'string', 'max:150'],
            'guardian_relationship' => ['nullable', 'string', 'max:80'],
            'guardian_contact' => ['required', 'string', 'max:30'],
            'parent_guardian_contact' => ['nullable', 'string', 'max:30'],

            'last_school_attended' => ['nullable', 'string', 'max:180'],
            'last_grade_level_completed' => ['nullable', 'string', 'max:80'],
            'strand_or_track' => ['nullable', 'string', 'max:100'],

            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}