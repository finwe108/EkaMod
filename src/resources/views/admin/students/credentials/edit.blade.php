@extends('layouts.app')

@section('title', 'Edit Student Credentials | MMCI')
@section('page_title', 'Edit Student Credentials')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Student Login Credentials</div>
            <div class="card-subtitle">{{ $student->full_name }}</div>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.students.credentials.update', $student) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Account Name</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $student->user->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" value="{{ old('username', $student->user->username) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" value="{{ old('email', $student->user->email) }}">
            </div>

            <div class="divider"></div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-input">
            </div>

            <div class="form-group">
                <label style="display:flex;gap:8px;align-items:center;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $student->user->is_active) ? 'checked' : '' }}>
                    Active account
                </label>
            </div>

            <div class="form-group">
                <label style="display:flex;gap:8px;align-items:center;">
                    <input type="checkbox" name="must_change_password" value="1" {{ old('must_change_password', $student->user->must_change_password) ? 'checked' : '' }}>
                    Force password change on next login
                </label>
            </div>

            <div style="display:flex;gap:10px;margin-top:16px;">
                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Credentials</button>
            </div>
        </form>
    </div>
</div>
@endsection