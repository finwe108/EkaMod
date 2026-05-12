@extends('layouts.app')

@section('title', 'Add Student | MMCI')
@section('page_title', 'Add Student')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Student was not saved.</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.students.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.students.partials.form', ['student' => null])

    <input type="hidden" name="school_year_id" value="{{ old('school_year_id', $selectedSchoolYearId ?? $student?->school_year_id) }}">

    <input type="hidden" name="grade_level_id" value="{{ old('grade_level_id', $selectedGradeLevelId ?? $student?->grade_level_id) }}">

    <input type="hidden" name="section_id" value="{{ old('section_id', $selectedSectionId ?? $student?->section_id) }}">

    <div style="margin-top: 1rem; display:flex; gap:.75rem;">
        <button type="submit" class="btn btn-primary">Save Student</button>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection