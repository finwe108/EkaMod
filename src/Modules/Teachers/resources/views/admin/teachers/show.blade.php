@extends('layouts.app')

@section('title', 'Faculty Profile | MMCI')
@section('page_title', 'Faculty Profile')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">{{ $teacher->employee?->full_name ?? 'Teacher' }}</div>
                <div class="card-subtitle">{{ $teacher->employee?->employee_id ?? 'No employee ID' }}</div>
            </div>

            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-primary">Edit</a>
        </div>

        <div class="card-body">
            <p><strong>Teacher No:</strong> {{ $teacher->teacher_no ?? '—' }}</p>
            <p><strong>Specialization:</strong> {{ $teacher->specialization ?? '—' }}</p>
            <p><strong>Subject Specialty:</strong> {{ $teacher->subject_specialty ?? '—' }}</p>
            <p><strong>License No:</strong> {{ $teacher->license_no ?? '—' }}</p>
            <p><strong>Major:</strong> {{ $teacher->major ?? '—' }}</p>
            <p><strong>Rank:</strong> {{ $teacher->rank_title ?? '—' }}</p>
            <p><strong>Adviser:</strong> {{ $teacher->is_adviser ? 'Yes' : 'No' }}</p>
            <p><strong>Date Hired as Teacher:</strong> {{ optional($teacher->date_hired_as_teacher)->format('Y-m-d') ?? '—' }}</p>
        </div>
    </div>
@endsection