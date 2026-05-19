@php
    $isEdit = isset($employee);
@endphp

@include('employees::partials.validation-errors')

@include('employees::partials.employment-fields')

@include('employees::partials.personal-fields')

@include('employees::partials.contact-fields')

@include('employees::partials.user-account-fields')

<div style="display:flex; gap:10px; margin-top:16px;">
    <a href="{{ route('admin.employees.index') }}" class="btn btn-ghost">Cancel</a>
    <button type="submit" class="btn btn-primary">
        {{ $isEdit ? 'Update Employee' : 'Save Employee' }}
    </button>
</div>