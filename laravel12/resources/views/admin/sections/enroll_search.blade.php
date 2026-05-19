@extends('layouts.app')

@section('title', 'Enroll Student to Section')
@section('page_title', 'Enroll Student to Section')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">
                {{ $section->gradeLevel?->name }} - {{ $section->name }}
            </div>
            <div class="card-subtitle">
                Search for an existing student first. If not found, create a new student.
            </div>
        </div>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.sections.enroll.search', $section) }}">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Search Student</label>
                    <input
                        type="text"
                        name="q"
                        class="form-input"
                        value="{{ $query }}"
                        placeholder="Search by Student ID, LRN, name, or email"
                        autofocus
                    >
                </div>

                <div class="form-group" style="display:flex;align-items:end;gap:10px;">
                    <button type="submit" class="btn btn-primary">Search</button>

                    <a href="{{ route('admin.students.create', [
                        'section_id' => $section->id,
                        'grade_level_id' => $section->grade_level_id,
                        'school_year_id' => $section->school_year_id,
                    ]) }}" class="btn btn-ghost">
                        + New Student
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@if($query)
    <div class="card" style="margin-top:16px;">
        <div class="card-header">
            <div>
                <div class="card-title">Search Results</div>
                <div class="card-subtitle">Results for "{{ $query }}"</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>LRN</th>
                        <th>Current Section</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->full_name }}</td>
                            <td class="mono">{{ $student->student_id }}</td>
                            <td class="mono">{{ $student->lrn }}</td>
                            <td>{{ $student->section?->name ?? 'Not assigned' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.sections.enroll.existing', $section) }}">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    <button type="submit" class="btn btn-primary">
                                        Enroll to this Section
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty">
                                No student found.
                                <a href="{{ route('admin.students.create', [
                                    'section_id' => $section->id,
                                    'grade_level_id' => $section->grade_level_id,
                                    'school_year_id' => $section->school_year_id,
                                ]) }}" style="color:var(--accent)">
                                    Create a new student instead.
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection