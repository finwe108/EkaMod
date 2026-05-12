@extends('errors.layout')

@section('title', 'Something went wrong')

@section('content')
<div style="max-width:600px;margin:80px auto;text-align:center;">
    <h1>Something went wrong</h1>
    <p style="color:#777;">
        Error Reference: {{ $errorId ?? 'N/A' }}
    </p>
    <p>
        We encountered a problem while processing your request.
        Please try again later or contact the school office.
    </p>

    <a href="{{ url('/') }}" class="btn btn-primary">
        Go back home
    </a>
</div>
@endsection