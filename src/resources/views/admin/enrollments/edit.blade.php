@extends('layouts.app')

@section('title', 'Edit Enrollment | MMCI')
@section('page_title', 'Edit Enrollment')

@section('content')
<form method="POST" action="{{ route('admin.enrollments.update', $enrollment) }}">
    @csrf
    @method('PUT')

    @include('admin.enrollments.partials.form', [
        'students' => collect([$enrollment->student]),
        'enrollment' => $enrollment,
        'schoolYears' => $schoolYears,
        'gradeLevels' => $gradeLevels,
        'sections' => $sections,
        'activeSchoolYear' => $activeSchoolYear,
    ])

</form>
@endsection