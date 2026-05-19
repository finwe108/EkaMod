@extends('layouts.auth')

@section('title', 'Login')

@section('content')

<h1 class="auth-title">Login</h1>
<p class="auth-subtitle">Sign in to your account</p>

@if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 1rem;">
        <ul style="margin: 0; padding-left: 1.2rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(request()->query('expired'))
    <div class="alert alert-warning">
        Your session expired. Please log in again.
    </div>
@endif

<form method="POST" action="{{ route('login.store') }}">
    @csrf

    <div style="margin-bottom: 1rem;">
        <label>Email or Username</label>
        <input
            type="text"
            name="login"
            value="{{ old('login') }}"
            class="form-control"
            required
            autofocus
        >
    </div>

    <div style="margin-bottom: 1rem;">
        <label>Password</label>
        <input
            type="password"
            name="password"
            class="form-control"
            required
        >
    </div>

    <div style="margin-bottom: 1rem;">
        <label style="display:flex; gap:.5rem; align-items:center;">
            <input type="checkbox" name="remember" value="1">
            <span>Remember me</span>
        </label>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;">
        Login
    </button>
</form>

@endsection