@extends('layouts.app')

@section('title', 'Account Settings')
@section('page_title', 'Account Settings')

@section('content')
@if(session('success'))
    <div class="card" style="margin-bottom:16px; border-color: rgba(45,212,160,.35);">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Login Account</div>
            <div class="card-subtitle">Update your login username, email, and password.</div>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('student.account.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Account Name</label>
                <input type="text" class="form-input" value="{{ $user->name }}" disabled>
                <div style="font-size:11px;color:var(--muted);margin-top:4px;">
                    Account name is managed by the registrar.
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" value="{{ old('username', $user->username) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}">
            </div>

            <div class="divider"></div>

            <h3 class="section-title">Change Password</h3>

            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-input">
            </div>

            <div style="display:flex;gap:10px;margin-top:16px;">
                <a href="{{ route('student.dashboard') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Account</button>
            </div>
        </form>
    </div>
</div>
@endsection