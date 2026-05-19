<?php

namespace Modules\Employees\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Employees\Requests\StoreEmployeeRequest;
use Modules\Employees\Requests\UpdateEmployeeRequest;
use Modules\Employees\Services\EmployeeService;

/**
 * Handles administrative employee management.
 *
 * Module: Employees
 * Layer: HTTP Controller
 */
class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::with(['firstDepartment', 'currentDepartment', 'user.roles'])
            ->latest()
            ->paginate(20);

        return view('employees::index', compact('employees'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('display_name')->get();

        return view('employees::create', compact('departments', 'roles'));
    }

    public function store(
        StoreEmployeeRequest $request,
        EmployeeService $employeeService
    ): RedirectResponse {
        $employeeService->create($request->validated());

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function edit($id): View
    {
        $employee = Employee::with('user.roles')->findOrFail($id);
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('display_name')->get();

        return view('employees::edit', compact('employee', 'departments', 'roles'));
    }

    public function update(
        UpdateEmployeeRequest $request,
        EmployeeService $employeeService,
        $id
    ): RedirectResponse {
        $employee = Employee::with('user.roles')->findOrFail($id);

        $employeeService->update($employee, $request->validated());

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function show($id): View
    {
        $employee = Employee::with([
            'firstDepartment',
            'currentDepartment',
            'user.roles',
        ])->findOrFail($id);

        return view('employees::show', compact('employee'));
    }

    public function destroy(
        EmployeeService $employeeService,
        $id
    ): RedirectResponse {
        $employee = Employee::with('user')->findOrFail($id);

        $employeeService->delete($employee);

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}