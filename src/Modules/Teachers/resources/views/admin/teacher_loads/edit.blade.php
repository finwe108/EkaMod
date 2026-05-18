@extends('layouts.app')

@section('title', 'Edit Teacher Load')
@section('page_title', 'Edit Teacher Load')

@section('content')
<form method="POST" action="{{ route('admin.teacher_loads.update', $teacherLoad->id) }}">
    @csrf
    @method('PUT')
    @include('admin.teacher_loads._form')
</form>
@endsection