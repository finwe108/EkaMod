@extends('layouts.app')

@section('title', 'Assign Teacher Load')
@section('page_title', 'Assign Teacher Load')

@section('content')
<form method="POST" action="{{ route('admin.teacher_loads.store') }}">
    @csrf
    @include('admin.teacher_loads._form')
</form>
@endsection