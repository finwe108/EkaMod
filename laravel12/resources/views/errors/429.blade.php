@extends('errors.layout')

@section('title', 'Too Many Requests')

@section('content')
<div style="max-width:600px;margin:80px auto;text-align:center;">
    <h1>429</h1>

    <p>
        Too many requests were made in a short period of time.
        Please wait a moment and try again.
    </p>

    <a href="{{ url()->previous() }}" class="btn btn-primary">
        Go Back
    </a>
</div>
@endsection