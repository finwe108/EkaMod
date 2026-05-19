<?php

namespace Modules\Employees\Services;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Handles employee write workflows.
 *
 * This service preserves the existing employee creation, update, teacher sync,
 * user account creation, role syncing, and deletion behavior.
 *
 * Module: Employees
 * Layer: Service
 */
class EmployeeService
{
    /**
     * Create a new employee.
     *
     * This operation is transactional because it may create:
     * - employee record
     * - teacher profile
     * - user account
     * - role assignments
     *
     * @param array<string, mixed> $validated
     * @return Employee
     */
    public function create(array $validated): Employee
    {
        return DB::transaction(function () use ($validated) {
            $department = Department::findOrFail($validated['first_department_id']);

            $employeeId = EmployeeIdGenerator::generate(
                $validated['employment_date'],
                $department->code
            );

            $employee = Employee::create([
                'employee_id' => $employeeId,
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'suffix' => $validated['suffix'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'birthdate' => $validated['birthdate'] ?? null,
                'civil_status' => $validated['civil_status'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'employment_date' => $validated['employment_date'],
                'first_department_id' => $department->id,
                'first_department_code' => strtoupper($department->code),
                'first_department_name' => $department->name,
                'current_department_id' => $validated['current_department_id'] ?? $department->id,
                'employee_type' => $validated['employee_type'],
                'employment_status' => $validated['employment_status'],
            ]);

            EmployeeTeacherSyncService::sync($employee, $validated);

            if (! empty($validated['create_user_account'])) {
                $user = User::create([
                    'employee_id_ref' => $employee->id,
                    'name' => trim($employee->first_name . ' ' . $employee->last_name),
                    'username' => $validated['username'] ?? null,
                    'email' => $validated['user_email'],
                    'password' => bcrypt($validated['password']),
                    'is_active' => 1,
                ]);

                if (! empty($validated['roles'])) {
                    $user->roles()->sync($validated['roles']);
                }
            }

            return $employee;
        });
    }

    /**
     * Update an existing employee.
     *
     * This operation is transactional because it may update:
     * - employee record
     * - linked user account
     * - role assignments
     * - teacher profile
     *
     * @param Employee $employee
     * @param array<string, mixed> $validated
     * @return Employee
     */
    public function update(Employee $employee, array $validated): Employee
    {
        return DB::transaction(function () use ($employee, $validated) {
            $employee->update([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'suffix' => $validated['suffix'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'birthdate' => $validated['birthdate'] ?? null,
                'civil_status' => $validated['civil_status'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'current_department_id' => $validated['current_department_id'] ?? null,
                'employee_type' => $validated['employee_type'],
                'employment_status' => $validated['employment_status'],
            ]);

            $hasUserData =
                ! empty($validated['user_email']) ||
                ! empty($validated['username']) ||
                ! empty($validated['password']) ||
                ! empty($validated['roles']);

            if ($employee->user) {
                $userData = [
                    'name' => $employee->fresh()->full_name,
                    'username' => $validated['username'] ?? $employee->user->username,
                    'email' => $validated['user_email'] ?? $employee->user->email,
                ];

                if (! empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $employee->user->update($userData);

                $employee->refresh();

                EmployeeTeacherSyncService::sync($employee, $validated);

                $employee->user->roles()->sync($validated['roles'] ?? []);
            } elseif ($hasUserData && ! empty($validated['user_email'])) {
                $user = User::create([
                    'employee_id_ref' => $employee->id,
                    'name' => $employee->fresh()->full_name,
                    'username' => $validated['username'] ?? null,
                    'email' => $validated['user_email'],
                    'password' => ! empty($validated['password'])
                        ? Hash::make($validated['password'])
                        : Hash::make('ChangeMe123!'),
                    'role' => 'employee',
                    'is_active' => 1,
                ]);

                $user->roles()->sync($validated['roles'] ?? []);
            }

            return $employee->refresh();
        });
    }

    /**
     * Delete an employee and linked user account.
     *
     * Role assignments are detached before deleting the linked user to preserve
     * the existing cleanup behavior.
     *
     * @param Employee $employee
     * @return void
     */
    public function delete(Employee $employee): void
    {
        DB::transaction(function () use ($employee) {
            if ($employee->user) {
                $employee->user->roles()->detach();
                $employee->user->delete();
            }

            $employee->delete();
        });
    }
}