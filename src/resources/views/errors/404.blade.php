@extends('errors.layout')

@section('title', 'Page Not Found')

@section('content')
<div style="max-width:600px;margin:80px auto;text-align:center;">
    <h1>404</h1>

    <p>
        The page you are looking for could not be found.
    </p>

    <a href="{{ url('/') }}" class="btn btn-primary">
        Go back home
    </a>
</div>
@endsection