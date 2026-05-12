@php
    $isEdit = isset($employee);
@endphp

@if ($errors->any())
    <div class="card" style="margin-bottom: 18px; border-color: rgba(240,96,96,.35);">
        <div class="card-header">
            <div>
                <div class="card-title">Please review the form</div>
                <div class="card-subtitle">Some fields have errors and need correction</div>
            </div>
        </div>
        <div class="card-body">
            <ul style="margin: 0; padding-left: 18px; color: var(--red); line-height: 1.7;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="card" style="margin-bottom: 18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Employment Information</div>
            <div class="card-subtitle">Employment date, department assignment, and employee classification</div>
        </div>
    </div>

    <div class="card-body">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="employment_date">Employment Date</label>
                <input
                    type="date"
                    name="employment_date"
                    id="employment_date"
                    class="form-input"
                    value="{{ old('employment_date', isset($employee->employment_date) ? optional($employee->employment_date)->format('Y-m-d') : '') }}"
                    {{ $isEdit ? 'readonly' : 'required' }}
                >
                @if($isEdit)
                    <small class="text-muted">Used for employee ID generation. Do not change.</small>
                @endif
                @error('employment_date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="employee_type">Employee Type</label>
                <select name="employee_type" id="employee_type" class="form-input" required>
                    <option value="non_teaching" {{ old('employee_type', $employee->employee_type ?? 'non_teaching') === 'non_teaching' ? 'selected' : '' }}>Non-Teaching</option>
                    <option value="teaching" {{ old('employee_type', $employee->employee_type ?? '') === 'teaching' ? 'selected' : '' }}>Teaching</option>
                </select>
                @error('employee_type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="first_department_id">First Department</label>
                <select
                    name="first_department_id"
                    id="first_department_id"
                    class="form-input"
                    {{ $isEdit ? 'disabled' : 'required' }}
                >
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ (string) old('first_department_id', $employee->first_department_id ?? '') === (string) $department->id ? 'selected' : '' }}>
                            {{ $department->name }} ({{ $department->code }})
                        </option>
                    @endforeach
                </select>
                @if($isEdit)
                    <input type="hidden" name="first_department_id" value="{{ $employee->first_department_id }}">
                    <small class="text-muted">Locked because it is part of the employee ID history.</small>
                @endif
                @error('first_department_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="current_department_id">Current Department</label>
                <select name="current_department_id" id="current_department_id" class="form-input">
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ (string) old('current_department_id', $employee->current_department_id ?? '') === (string) $department->id ? 'selected' : '' }}>
                            {{ $department->name }} ({{ $department->code }})
                        </option>
                    @endforeach
                </select>
                @error('current_department_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="employment_status">Employment Status</label>
                <select name="employment_status" id="employment_status" class="form-input" required>
                    <option value="active" {{ old('employment_status', $employee->employment_status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('employment_status', $employee->employment_status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="resigned" {{ old('employment_status', $employee->employment_status ?? '') === 'resigned' ? 'selected' : '' }}>Resigned</option>
                    <option value="retired" {{ old('employment_status', $employee->employment_status ?? '') === 'retired' ? 'selected' : '' }}>Retired</option>
                </select>
                @error('employment_status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Employee Information</div>
            <div class="card-subtitle">Basic personal profile details</div>
        </div>
    </div>

    <div class="card-body">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="first_name">First Name</label>
                <input
                    type="text"
                    name="first_name"
                    id="first_name"
                    class="form-input"
                    value="{{ old('first_name', $employee->first_name ?? '') }}"
                    required
                >
                @error('first_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="middle_name">Middle Name</label>
                <input
                    type="text"
                    name="middle_name"
                    id="middle_name"
                    class="form-input"
                    value="{{ old('middle_name', $employee->middle_name ?? '') }}"
                >
                @error('middle_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="last_name">Last Name</label>
                <input
                    type="text"
                    name="last_name"
                    id="last_name"
                    class="form-input"
                    value="{{ old('last_name', $employee->last_name ?? '') }}"
                    required
                >
                @error('last_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="suffix">Suffix</label>
                <input
                    type="text"
                    name="suffix"
                    id="suffix"
                    class="form-input"
                    value="{{ old('suffix', $employee->suffix ?? '') }}"
                >
                @error('suffix')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="gender">Gender</label>
                <select name="gender" id="gender" class="form-input">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ old('gender', $employee->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $employee->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                </select>
                @error('gender')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="birthdate">Birthdate</label>
                <input
                    type="date"
                    name="birthdate"
                    id="birthdate"
                    class="form-input"
                    value="{{ old('birthdate', isset($employee->birthdate) ? optional($employee->birthdate)->format('Y-m-d') : '') }}"
                >
                @error('birthdate')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="civil_status">Civil Status</label>
                <input
                    type="text"
                    name="civil_status"
                    id="civil_status"
                    class="form-input"
                    value="{{ old('civil_status', $employee->civil_status ?? '') }}"
                >
                @error('civil_status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Contact Information</div>
            <div class="card-subtitle">Personal email, phone number, and address</div>
        </div>
    </div>

    <div class="card-body">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="email">Personal Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-input"
                    value="{{ old('email', $employee->email ?? '') }}"
                >
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Phone</label>
                <input
                    type="text"
                    name="phone"
                    id="phone"
                    class="form-input"
                    value="{{ old('phone', $employee->phone ?? '') }}"
                >
                @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="address">Address</label>
            <textarea
                name="address"
                id="address"
                rows="3"
                class="form-input"
            >{{ old('address', $employee->address ?? '') }}</textarea>
            @error('address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 18px;">
    <div class="card-header">
        <div>
            <div class="card-title">System User Account</div>
            <div class="card-subtitle">Login credentials and role assignment for system access</div>
        </div>
    </div>

    <div class="card-body">
        @if(!$isEdit)
            <div class="form-group">
                <label style="display:flex; align-items:center; gap:8px;">
                    <input
                        type="checkbox"
                        name="create_user_account"
                        id="create_user_account"
                        value="1"
                        {{ old('create_user_account') ? 'checked' : '' }}
                    >
                    <span>Create system user account</span>
                </label>
            </div>
        @endif

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-input"
                    value="{{ old('username', $employee->user->username ?? '') }}"
                >
                @error('username')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="user_email">Login Email</label>
                <input
                    type="email"
                    name="user_email"
                    id="user_email"
                    class="form-input"
                    value="{{ old('user_email', $employee->user->email ?? '') }}"
                >
                @error('user_email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="password">{{ $isEdit ? 'New Password' : 'Password' }}</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-input"
                >
                @if($isEdit)
                    <small class="text-muted">Leave blank to keep the current password.</small>
                @endif
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Roles</label>
            <div style="display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:8px;">
                @foreach($roles as $role)
                    <label style="display:flex; align-items:center; gap:8px; font-size:12px;">
                        <input
                            type="checkbox"
                            name="roles[]"
                            value="{{ $role->id }}"
                            {{ in_array($role->id, old('roles', isset($employee) && $employee->user ? $employee->user->roles->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                        >
                        <span>{{ $role->display_name }}</span>
                    </label>
                @endforeach
            </div>
            @error('roles')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            @error('roles.*')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>

<div style="display:flex; gap:10px; margin-top:16px;">
    <a href="{{ route('admin.employees.index') }}" class="btn btn-ghost">Cancel</a>
    <button type="submit" class="btn btn-primary">
        {{ $isEdit ? 'Update Employee' : 'Save Employee' }}
    </button>
</div>