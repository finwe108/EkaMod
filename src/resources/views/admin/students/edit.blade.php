@extends('layouts.app')

@section('title', 'Edit Student | MMCI')
@section('page_title', 'Edit Student')

@section('content')
<form method="POST" action="{{ route('admin.students.update', $student) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('admin.students.partials.form', ['student' => $student])

    <div style="margin-top: 1rem; display:flex; gap:.75rem;">
        <button type="submit" class="btn btn-primary">Update Student</button>
        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-primary">Cancel</a>
    </div>
</form>
@endsection