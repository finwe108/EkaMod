@extends('errors.layout')

@section('title', 'Access Denied')

@section('content')
<div style="max-width:600px;margin:80px auto;text-align:center;">
    <h1>403</h1>

    <p>
        You do not have permission to access this page.
    </p>

    <a href="{{ url('/') }}" class="btn btn-primary">
        Return to dashboard
    </a>
</div>
@endsection