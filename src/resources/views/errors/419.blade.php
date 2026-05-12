@extends('errors.layout')

@section('title', 'Session Expired')

@section('content')
<div style="max-width:600px;margin:80px auto;text-align:center;">
    <h1>419</h1>

    <p>
        Your session has expired. Please refresh the page and try again.
    </p>

    <button onclick="window.location.reload()" class="btn btn-primary">
        Refresh Page
    </button>
</div>
@endsection