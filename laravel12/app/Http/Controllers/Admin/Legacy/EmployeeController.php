<?php

namespace App\Http\Controllers\Admin\Legacy;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use App\Services\EmployeeIdGenerator;
use App\Services\EmployeeTeacherSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class EmployeeController extends Controller
{
    /**
     * Display list of employees
     */
    public function index()
    {
        $employees = Employee::with(['firstDepartment', 'currentDepartment', 'user.roles'])
            ->latest()
            ->paginate(20);

        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('display_name')->get();

        return view('admin.employees.create', compact('departments', 'roles'));
    }

    /**
     * Store new employee
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:20',
            'gender' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'civil_status' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',

            'employment_date' => 'required|date',
            'first_department_id' => 'required|exists:departments,id',
            'current_department_id' => 'nullable|exists:departments,id',
            'employee_type' => 'required|in:teaching,non_teaching',
            'employment_status' => 'required|in:active,inactive,resigned,retired',

            'teacher_no' => 'nullable|string|max:50|unique:teachers,teacher_no',
            'specialization' => 'nullable|string|max:150',
            'subject_specialty' => 'nullable|string|max:150',
            'license_no' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:100',
            'rank_title' => 'nullable|string|max:100',
            'is_adviser' => 'nullable|boolean',
            'date_hired_as_teacher' => 'nullable|date',

            'create_user_account' => 'nullable|boolean',
            'username' => 'nullable|string|max:50|unique:users,username',
            'user_email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:8',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if ($request->boolean('create_user_account')) {
            $request->validate([
                'user_email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
            ]);
        }

        DB::transaction(function () use ($validated) {
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

            if (!empty($validated['create_user_account'])) {
                $user = User::create([
                    'employee_id_ref' => $employee->id,
                    'name' => trim($employee->first_name . ' ' . $employee->last_name),
                    'username' => $validated['username'] ?? null,
                    'email' => $validated['user_email'],
                    'password' => bcrypt($validated['password']),
                    'is_active' => 1,
                ]);

                if (!empty($validated['roles'])) {
                    $user->roles()->sync($validated['roles']);
                }
            }
        });

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee created successfully.');
    }
    /**
     * Show edit form
     */
    public function edit($id)
    {
        $employee = Employee::with('user.roles')->findOrFail($id);
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('display_name')->get();

        return view('admin.employees.edit', compact('employee', 'departments', 'roles'));
    }

    /**
     * Update employee
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::with('user.roles')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:20',
            'gender' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'civil_status' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',

            'current_department_id' => 'nullable|exists:departments,id',
            'employee_type' => 'required|in:teaching,non_teaching',
            'employment_status' => 'required|in:active,inactive,resigned,retired',

            'teacher_no' => 'nullable|string|max:50|unique:teachers,teacher_no,' . ($employee->teacher->id ?? 'NULL'),
            'specialization' => 'nullable|string|max:150',
            'subject_specialty' => 'nullable|string|max:150',
            'license_no' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:100',
            'rank_title' => 'nullable|string|max:100',
            'is_adviser' => 'nullable|boolean',
            'date_hired_as_teacher' => 'nullable|date',

            'username' => 'nullable|string|max:50|unique:users,username,' . ($employee->user->id ?? 'NULL'),
            'user_email' => 'nullable|email|max:255|unique:users,email,' . ($employee->user->id ?? 'NULL'),
            'password' => 'nullable|string|min:8',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        DB::transaction(function () use ($employee, $validated) {
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
                !empty($validated['user_email']) ||
                !empty($validated['username']) ||
                !empty($validated['password']) ||
                !empty($validated['roles']);

            if ($employee->user) {
                $userData = [
                    'name' => $employee->fresh()->full_name,
                    'username' => $validated['username'] ?? $employee->user->username,
                    'email' => $validated['user_email'] ?? $employee->user->email,
                ];

                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $employee->user->update($userData);

                $employee->refresh();
                EmployeeTeacherSyncService::sync($employee, $validated);

                $employee->user->roles()->sync($validated['roles'] ?? []);
            } elseif ($hasUserData && !empty($validated['user_email'])) {
                $user = User::create([
                    'employee_id_ref' => $employee->id,
                    'name' => $employee->fresh()->full_name,
                    'username' => $validated['username'] ?? null,
                    'email' => $validated['user_email'],
                    'password' => !empty($validated['password']) ? Hash::make($validated['password']) : Hash::make('ChangeMe123!'),
                    'role' => 'employee',
                    'is_active' => 1,
                ]);

                $user->roles()->sync($validated['roles'] ?? []);
            }
        });

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function show($id)
    {
        $employee = Employee::with([
            'firstDepartment',
            'currentDepartment',
            'user.roles',
        ])->findOrFail($id);

        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Delete employee
     */
    public function destroy($id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        DB::transaction(function () use ($employee) {

            if ($employee->user) {
                $employee->user->roles()->detach();
                $employee->user->delete();
            }

            $employee->delete();
        });

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}