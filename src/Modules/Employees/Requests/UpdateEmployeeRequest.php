<?php

namespace Modules\Employees\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Handles validation for updating employees.
 *
 * This request preserves existing employee, teacher profile, and linked user
 * account validation behavior.
 *
 * Module: Employees
 * Layer: Request
 */
class UpdateEmployeeRequest extends FormRequest
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
        ]);
    }

    /**
     * Get validation rules for updating an employee.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $employee = $this->route('employee');

        if (! $employee instanceof Employee) {
            $employee = Employee::with(['teacher', 'user'])->find($employee);
        }

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

            'current_department_id' => ['nullable', 'exists:departments,id'],
            'employee_type' => ['required', 'in:teaching,non_teaching'],
            'employment_status' => ['required', 'in:active,inactive,resigned,retired'],

            'teacher_no' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('teachers', 'teacher_no')->ignore($employee?->teacher?->id),
            ],
            'specialization' => ['nullable', 'string', 'max:150'],
            'subject_specialty' => ['nullable', 'string', 'max:150'],
            'license_no' => ['nullable', 'string', 'max:100'],
            'major' => ['nullable', 'string', 'max:100'],
            'rank_title' => ['nullable', 'string', 'max:100'],
            'is_adviser' => ['nullable', 'boolean'],
            'date_hired_as_teacher' => ['nullable', 'date'],

            'username' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($employee?->user?->id),
            ],
            'user_email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($employee?->user?->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ];
    }
}