@extends('layouts.app')

@section('title', 'Section Class List | MMCI')
@section('page_title', 'Section Class List')

@section('topbar_actions')
    <a href="{{ route('admin.sections.enroll.search', $section) }}" target="_blank" class="btn btn-primary">
        + Enroll Student to Section
    </a>
@endsection

@section('content')
    <div class="card" style="margin-bottom: 18px;">
        <div class="card-body">

            <form method="GET" action="{{ route('admin.sections.show', $section) }}" id="sectionFilterForm">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="school_year_id">School Year</label>
                        <select name="school_year_id" id="school_year_id" class="form-input">
                            @foreach($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->id }}"
                                    {{ (string) $schoolYearId === (string) $schoolYear->id ? 'selected' : '' }}>
                                    {{ $schoolYear->name }}{{ optional($activeSchoolYear)->id === $schoolYear->id ? ' (Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="section_id">Section</label>
                        <select name="section_id" id="section_id" class="form-input">
                            @foreach($availableSections as $availableSection)
                                <option value="{{ $availableSection->id }}"
                                    {{ (string) $section->id === (string) $availableSection->id ? 'selected' : '' }}>
                                    {{ $availableSection->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">{{ $section->name }}</div>
                <div class="card-subtitle">Students enrolled in this section for the selected school year</div>
            </div>
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>LRN</th>
                        <th>Full Name</th>
                        <th>Grade Level</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $enrollment)
                        <tr 
                            @if($enrollment->student)
                                onclick="window.location='{{ route('admin.students.show', $enrollment->student) }}'"
                            @endif
                            style="cursor:pointer;"
                        >
                            <td>{{ $enrollment->student?->student_id }}</td>
                            <td>{{ $enrollment->student?->lrn }}</td>
                            <td>{{ $enrollment->student?->formal_name }}</td>
                            <td>{{ $enrollment->gradeLevel?->name }}</td>
                            <td>{{ ucfirst($enrollment->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No students enrolled in this section for the selected school year.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $students->links() }}
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const schoolYearSelect = document.getElementById('school_year_id');
            const sectionSelect = document.getElementById('section_id');

            schoolYearSelect.addEventListener('change', function () {
                document.getElementById('sectionFilterForm').submit();
            });

            sectionSelect.addEventListener('change', function () {
                const sectionId = this.value;
                const schoolYearId = schoolYearSelect.value;

                window.location.href = "{{ url('/admin/sections') }}/" + sectionId + "?school_year_id=" + schoolYearId;
            });
        });
    </script>
@endpush