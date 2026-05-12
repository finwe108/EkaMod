@extends('layouts.app')

@section('title', 'Change Password')
@section('page_title', 'Change Password')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Change Temporary Password</div>
            <div class="card-subtitle">For your security, please set a new password.</div>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="card" style="margin-bottom:16px; border-color: rgba(45,212,160,.35);">
                <div class="card-body">{{ session('success') }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('student.password.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Current Temporary Password</label>
                <input type="password" name="current_password" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-input" required>
            </div>

            <button type="submit" class="btn btn-primary">
                Update Password
            </button>
        </form>
    </div>
</div>
@endsection