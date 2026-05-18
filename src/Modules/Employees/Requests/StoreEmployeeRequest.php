<?php

namespace Modules\Employees\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for creating employees.
 *
 * This request preserves the existing validation behavior from the original
 * employee controller, including optional user account creation rules.
 *
 * Module: Employees
 * Layer: Request
 */
class StoreEmployeeRequest extends FormRequest
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
            'is_adviser' => $this->boolean('is_adviser'),
            'create_user_account' => $this->boolean('create_user_account'),
        ]);
    }

    /**
     * Get validation rules for creating an employee.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'max:20'],
            'birthdate' => ['nullable', 'date'],
            'civil_status' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],

            'employment_date' => ['required', 'date'],
            'first_department_id' => ['required', 'exists:departments,id'],
            'current_department_id' => ['nullable', 'exists:departments,id'],
            'employee_type' => ['required', 'in:teaching,non_teaching'],
            'employment_status' => ['required', 'in:active,inactive,resigned,retired'],

            'teacher_no' => ['nullable', 'string', 'max:50', 'unique:teachers,teacher_no'],
            'specialization' => ['nullable', 'string', 'max:150'],
            'subject_specialty' => ['nullable', 'string', 'max:150'],
            'license_no' => ['nullable', 'string', 'max:100'],
            'major' => ['nullable', 'string', 'max:100'],
            'rank_title' => ['nullable', 'string', 'max:100'],
            'is_adviser' => ['nullable', 'boolean'],
            'date_hired_as_teacher' => ['nullable', 'date'],

            'create_user_account' => ['nullable', 'boolean'],
            'username' => ['nullable', 'string', 'max:50', 'unique:users,username'],
            'user_email' => [
                $this->boolean('create_user_account') ? 'required' : 'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                $this->boolean('create_user_account') ? 'required' : 'nullable',
                'string',
                'min:8',
            ],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ];
    }
}