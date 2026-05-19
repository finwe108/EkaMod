@extends('layouts.app')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem; gap:1rem;">
    <div>
        <h1 style="margin:0;">{{ $employee->full_name }}</h1>
        <p style="margin:.25rem 0 0;">Employee ID: {{ $employee->employee_id }}</p>
    </div>

    <div style="display:flex; gap:.5rem;">
        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('admin.employees.index') }}" class="btn btn-light">Back</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

<div style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
    <div class="card" style="padding:1.25rem;">
        <h3 style="margin-top:0;">Employee Details</h3>

        <table class="table" style="width:100%;">
            <tr>
                <th style="text-align:left; width:35%;">Employee ID</th>
                <td>{{ $employee->employee_id }}</td>
            </tr>
            <tr>
                <th style="text-align:left;">Full Name</th>
                <td>{{ $employee->full_name }}</td>
            </tr>
            <tr>
                <th style="text-align:left;">Gender</th>
                <td>{{ $employee->gender ?: '—' }}</td>
            </tr>
            <tr>
                <th style="text-align:left;">Birthdate</th>
                <td>{{ $employee->birthdate ? $employee->birthdate->format('Y-m-d') : '—' }}</td>
            </tr>
            <tr>
                <th style="text-align:left;">Civil Status</th>
                <td>{{ $employee->civil_status ?: '—' }}</td>
            </tr>
            <tr>
                <th style="text-align:left;">Phone</th>
                <td>{{ $employee->phone ?: '—' }}</td>
            </tr>
            <tr>
                <th style="text-align:left;">Email</th>
                <td>{{ $employee->email ?: '—' }}</td>
            </tr>
            <tr>
                <th style="text-align:left;">Address</th>
                <td>{{ $employee->address ?: '—' }}</td>
            </tr>
        </table>
    </div>

    <div class="card" style="padding:1.25rem;">
        <h3 style="margin-top:0;">Employment Details</h3>

        <table class="table" style="width:100%;">
            <tr>
                <th style="text-align:left; width:35%;">Employment Date</th>
                <td>{{ $employee->employment_date ? $employee->employment_date->format('Y-m-d') : '—' }}</td>
            </tr>
            <tr>
                <th style="text-align:left;">Employee Type</th>
                <td>{{ ucfirst(str_replace('_', ' ', $employee->employee_type)) }}</td>
            </tr>
            <tr>
                <th style="text-align:left;">Status</th>
                <td>{{ ucfirst($employee->employment_status) }}</td>
            </tr>
            <tr>
                <th style="text-align:left;">First Department</th>
                <td>{{ $employee->firstDepartment?->name ?? $employee->first_department_name }}</td>
            </tr>
            <tr>
                <th style="text-align:left;">Current Department</th>
                <td>{{ $employee->currentDepartment?->name ?? '—' }}</td>
            </tr>
        </table>
    </div>

    <div class="card" style="padding:1.25rem; grid-column: span 2;">
        <h3 style="margin-top:0;">System Account</h3>

        @if($employee->user)
            <table class="table" style="width:100%;">
                <tr>
                    <th style="text-align:left; width:20%;">Name</th>
                    <td>{{ $employee->user->name }}</td>
                </tr>
                <tr>
                    <th style="text-align:left;">Username</th>
                    <td>{{ $employee->user->username ?: '—' }}</td>
                </tr>
                <tr>
                    <th style="text-align:left;">Login Email</th>
                    <td>{{ $employee->user->email }}</td>
                </tr>
                <tr>
                    <th style="text-align:left;">Active</th>
                    <td>{{ $employee->user->is_active ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th style="text-align:left;">Roles</th>
                    <td>{{ $employee->user->roles->pluck('display_name')->join(', ') ?: 'No roles assigned' }}</td>
                </tr>
            </table>
        @else
            <p style="margin:0;">No system account linked.</p>
        @endif
    </div>
</div>
@endsection