@extends('errors.layout')

@section('title', 'Service Unavailable')

@section('content')
<div style="max-width:600px;margin:80px auto;text-align:center;">
    <h1>503</h1>

    <p>
        The system is temporarily unavailable due to maintenance.
        Please try again later.
    </p>

    <a href="{{ url('/') }}" class="btn btn-primary">
        Return Home
    </a>
</div>
@endsection