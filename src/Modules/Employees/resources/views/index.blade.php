@extends('layouts.app')

@section('title', 'Employees')
@section('page_title', 'Employees')
@section('page_subtitle', 'Manage employee records and system access.')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <div>
        <h1 style="margin:0;">Employees</h1>
        <p style="margin:.25rem 0 0;">Manage employee records and system accounts.</p>
    </div>

    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">+ Add Employee</a>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

<div class="card" style="padding: 1rem;">
    <div style="overflow-x:auto;">
        <table class="table" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="text-align:left; padding:.75rem;">Employee ID</th>
                    <th style="text-align:left; padding:.75rem;">Name</th>
                    <th style="text-align:left; padding:.75rem;">Type</th>
                    <th style="text-align:left; padding:.75rem;">First Department</th>
                    <th style="text-align:left; padding:.75rem;">Current Department</th>
                    <th style="text-align:left; padding:.75rem;">Status</th>
                    <th style="text-align:left; padding:.75rem;">System User</th>
                    <th style="text-align:left; padding:.75rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    <tr onclick="window.location='{{ route('admin.employees.show', $employee->id) }}'" style="cursor:pointer;">
                        <td style="padding:.75rem; border-top:1px solid #e5e7eb;">
                            <a href="{{ route('admin.employees.show', $employee->id) }}">
                                {{ $employee->employee_id }}
                            </a>
                        </td>
                        <td style="padding:.75rem; border-top:1px solid #e5e7eb;">
                            <a href="{{ route('admin.employees.show', $employee->id) }}">
                                {{ $employee->full_name }}
                            </a>
                        </td>
                        <td style="padding:.75rem; border-top:1px solid #e5e7eb;">
                            {{ ucfirst(str_replace('_', ' ', $employee->employee_type)) }}
                        </td>
                        <td style="padding:.75rem; border-top:1px solid #e5e7eb;">
                            {{ $employee->first_department_name }}
                        </td>
                        <td style="padding:.75rem; border-top:1px solid #e5e7eb;">
                            {{ $employee->currentDepartment?->name ?? '—' }}
                        </td>
                        <td style="padding:.75rem; border-top:1px solid #e5e7eb;">
                            {{ ucfirst($employee->employment_status) }}
                        </td>
                        <td style="padding:.75rem; border-top:1px solid #e5e7eb;">
                            @if($employee->user)
                                <div>{{ $employee->user->email }}</div>
                                <small>
                                    {{ $employee->user->roles->pluck('display_name')->join(', ') ?: 'No roles' }}
                                </small>
                            @else
                                —
                            @endif
                        </td>
                        <td style="padding:.75rem; border-top:1px solid #e5e7eb;">
                            <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                                <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-sm btn-light">View</a>
                                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Delete this employee?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding:1rem; text-align:center; border-top:1px solid #e5e7eb;">
                            No employees found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">
        {{ $employees->links() }}
    </div>
</div>
@endsection