@extends('layouts.app')

@section('content')
<div class="page-header" style="margin-bottom:1rem;">
    <h1 style="margin:0;">Create Employee</h1>
    <p style="margin:.25rem 0 0;">Add a new employee and optionally create a system account.</p>
</div>

<form action="{{ route('admin.employees.store') }}" method="POST">
    @csrf
    @include('admin.employees._form')
</form>
@endsection