@extends('layouts.app')

@section('content')
<div class="page-header" style="margin-bottom:1rem;">
    <h1 style="margin:0;">Edit Employee</h1>
    <p style="margin:.25rem 0 0;">Update employee information and access settings.</p>
</div>

<form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
    @csrf
    @method('PUT')

    @include('admin.employees._form')
</form>
@endsection