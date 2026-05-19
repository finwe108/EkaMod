{{-- 
    LEGACY / UNROUTED VIEW

    This view belongs to the old SF1 prepare/export/download workflow.

    It references:
    admin.reports.sf1.export

    That route is not currently registered.

    Do not use this view unless the legacy SF1 export flow is intentionally
    restored with proper routes and controller actions.
--}}

@extends('layouts.app')

@section('title', 'Prepare SF1 | MMCI')
@section('page_title', 'Prepare SF1')

@section('content')
<div class="card" style="margin-bottom:1rem;">
    <div class="card-header">
        <div>
            <div class="card-title">Create SF1 Form</div>
            <div class="card-subtitle">Review the selected section before generating the Excel file.</div>
        </div>
    </div>

    <div class="card-body">
        <p><strong>School Year:</strong> {{ $schoolYear->name }}</p>
        <p><strong>Grade Level:</strong> {{ $gradeLevel->name }}</p>
        <p><strong>Section:</strong> {{ $section->name }}</p>
        <p><strong>Total Learners:</strong> {{ $enrollments->count() }}</p>
        <p><strong>Male:</strong> {{ $maleCount }}</p>
        <p><strong>Female:</strong> {{ $femaleCount }}</p>

        <div style="display:flex; gap:.75rem; flex-wrap:wrap; margin-top:1rem;">
            <form method="GET" action="{{ route('admin.reports.sf1.print') }}" target="_blank">
                <input type="hidden" name="school_year_id" value="{{ $schoolYear->id }}">
                <input type="hidden" name="grade_level_id" value="{{ $gradeLevel->id }}">
                <input type="hidden" name="section_id" value="{{ $section->id }}">

                <button type="submit" class="btn btn-secondary">
                    Preview Printable SF1
                </button>
            </form>

            <form method="POST" action="{{ route('admin.reports.sf1.export') }}">
                @csrf
                <input type="hidden" name="school_year_id" value="{{ $schoolYear->id }}">
                <input type="hidden" name="grade_level_id" value="{{ $gradeLevel->id }}">
                <input type="hidden" name="section_id" value="{{ $section->id }}">

                <button type="submit" class="btn btn-primary">
                    Generate Excel SF1
                </button>
            </form>

            <a href="{{ route('admin.reports.sf1.filter', [
                'school_year_id' => $schoolYear->id,
                'grade_level_id' => $gradeLevel->id,
                'section_id' => $section->id,
            ]) }}" class="btn btn-ghost">
                Back to Filter
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Learners Included</div>
            <div class="card-subtitle">These records will be included in the SF1.</div>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>LRN</th>
                    <th>Name</th>
                    <th>Sex</th>
                    <th>Birth Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $index => $enrollment)
                    @php $student = $enrollment->student; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student?->lrn ?: '—' }}</td>
                        <td>{{ $student?->full_name ?: '—' }}</td>
                        <td>{{ $student?->sex ?: '—' }}</td>
                        <td>{{ optional($student?->birth_date)->format('M d, Y') ?: '—' }}</td>
                        <td>{{ ucfirst($enrollment->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">No learners found for this section.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection