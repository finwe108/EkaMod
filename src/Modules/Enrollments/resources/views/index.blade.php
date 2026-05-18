@extends('layouts.app')

@section('title', 'Enrollments | MMCI')
@section('page_title', 'Enrollments')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; gap:1rem; flex-wrap:wrap;">
    <div>
        <h1 style="margin:0;">Enrollments</h1>
        <p style="margin:.25rem 0 0; color:#666;">School-year enrollment records and section placement.</p>
    </div>
</div>

@if(session('success'))
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<div class="card" style="margin-bottom:1rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.enrollments.index') }}">
            <div class="filters-grid">
                <div class="form-group">
                    <label for="school_year_id">School Year</label>
                    <select name="school_year_id" id="school_year_id" class="form-input">
                        <option value="">All School Years</option>
                        @foreach($schoolYears as $schoolYear)
                            <option value="{{ $schoolYear->id }}" {{ (string) request('school_year_id', $schoolYearId) === (string) $schoolYear->id ? 'selected' : '' }}>
                                {{ $schoolYear->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="grade_level_id">Grade Level</label>
                    <select name="grade_level_id" id="grade_level_id" class="form-input">
                        <option value="">All Grade Levels</option>
                        @foreach($gradeLevels as $gradeLevel)
                            <option value="{{ $gradeLevel->id }}" {{ (string) request('grade_level_id') === (string) $gradeLevel->id ? 'selected' : '' }}>
                                {{ $gradeLevel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="section_id">Section</label>
                    <select name="section_id" id="section_id" class="form-input">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ (string) request('section_id') === (string) $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Enrollment Status</label>
                    <select name="status" id="status" class="form-input">
                        <option value="">All Statuses</option>
                        <option value="enrolled" {{ request('status') === 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="dropped" {{ request('status') === 'dropped' ? 'selected' : '' }}>Dropped</option>
                        <option value="transferred_out" {{ request('status') === 'transferred_out' ? 'selected' : '' }}>Transferred Out</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>

            <div style="margin-top:1rem; display:flex; gap:.75rem; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>School Year</th>
                        <th>Grade Level</th>
                        <th>Section</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Enrollment Date</th>
                        <th style="width:180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enrollment)
                        <tr>
                            <td>
                                <a href="{{ route('admin.students.show', $enrollment->student) }}">
                                    {{ $enrollment->student?->full_name }}
                                </a>
                                <div style="font-size:.85rem; color:#777;">
                                    {{ $enrollment->student?->student_id }}
                                </div>
                            </td>
                            <td>{{ $enrollment->schoolYear?->name }}</td>
                            <td>{{ $enrollment->gradeLevel?->name }}</td>
                            <td>{{ $enrollment->section?->name ?? '—' }}</td>
                            <td>{{ ucfirst($enrollment->student_type) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}</td>
                            <td>{{ optional($enrollment->enrollment_date)->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                                    <a href="{{ route('admin.enrollments.edit', $enrollment) }}" class="btn btn-sm btn-secondary">Edit</a>

                                    <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}" onsubmit="return confirm('Delete this enrollment record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No enrollments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding:1rem;">
            {{ $enrollments->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.filters-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: .35rem;
}

.form-input {
    width: 100%;
}

@media (max-width: 992px) {
    .filters-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush