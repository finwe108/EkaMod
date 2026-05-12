@extends('errors.layout')

@section('title', 'Unauthorized')

@section('content')
<div style="max-width:600px;margin:80px auto;text-align:center;">
    <h1>401</h1>

    <p>
        You must log in to access this page.
    </p>

    <a href="{{ route('login') }}" class="btn btn-primary">
        Login
    </a>
</div>
@endsection