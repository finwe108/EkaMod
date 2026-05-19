@extends('layouts.app')

@section('title', 'Add Faculty | MMCI')
@section('page_title', 'Add Faculty')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">New Teacher</div>
                <div class="card-subtitle">Create a faculty record</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.teachers.store') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Employee No.</label>
                        <input type="text" name="employee_no" class="form-input" value="{{ old('employee_no') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-input" value="{{ old('first_name') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-input" value="{{ old('last_name') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-input" value="{{ old('middle_name') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-input" value="{{ old('contact_number') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-input" value="{{ old('department') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" @selected(old('status') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            <option value="on leave" @selected(old('status') === 'on leave')>On Leave</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Teacher</button>
                </div>
            </form>
        </div>
    </div>
@endsection