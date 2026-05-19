@extends('layouts.app')

@section('title', 'New Enrollment | MMCI')
@section('page_title', 'New Enrollment')

@section('content')
<form method="POST" action="{{ route('admin.enrollments.store') }}">
    @csrf

    @include('admin.enrollments.partials.form', [
        'students' => collect([$student]),
        'enrollment' => null,
        'schoolYears' => $schoolYears,
        'gradeLevels' => $gradeLevels,
        'sections' => $sections,
        'activeSchoolYear' => $activeSchoolYear,
    ])

</form>
@endsection