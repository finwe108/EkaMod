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