@extends('layouts.app')

@section('title', 'Student Profile')
@section('page_title', 'Student Profile')

@section('content')

<style>
.profile-page {
    max-width: 900px;
    margin: auto;
    background: #ffffff;
    padding: 30px;
    border: 1px solid #ddd;

    /* ✅ FORCE readable text */
    color: #111 !important;
    font-size: 14px;
}

/* Override theme text colors */
.profile-page * {
    color: #111 !important;
}

/* Header */
.profile-header {
    display: flex;
    justify-content: space-between;
    border-bottom: 2px solid #000;
    margin-bottom: 20px;
}

.profile-pic {
    width: 120px;
    height: 120px;
    border: 2px solid #000;
    object-fit: cover;
}

/* Sections */
.section-title {
    font-weight: bold;
    font-size: 13px;
    border-left: 5px solid #4f46e5;
    padding-left: 8px;
    margin: 20px 0 10px;
    text-transform: uppercase;
}

/* Rows */
.info-row {
    display: flex;
    margin-bottom: 6px;
}

.label {
    width: 180px;
    font-weight: 600;
    color: #000 !important;
}

.value {
    flex: 1;
    border-bottom: 1px dashed #999;
    padding-left: 5px;
    color: #111 !important;
}

/* Grid */
.grid-3 {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px 20px;
}

.grid-3 .info-row {
    min-width: 0;
}

.education-history {
    display: grid;
    gap: 12px;
}

.education-item {
    border: 1px solid #ddd;
    padding: 12px;
    background: #fafafa;
}

@media print {
    .education-item {
        break-inside: avoid;
    }
}

/* Print clean */
@media print {
    .no-print {
        display: none;
    }

    body {
        background: #fff;
    }
}

@media (max-width: 768px) {
    .grid-3 {
        grid-template-columns: 1fr !important;
        gap: 0 !important;
    }

    .info-row {
        display: grid !important;
        grid-template-columns: 130px minmax(0, 1fr) !important;
        gap: 8px !important;
        align-items: start !important;
        margin-bottom: 10px !important;
        width: 100% !important;
        min-width: 0 !important;
    }

    .label {
        width: auto !important;
        min-width: 0 !important;
        font-size: 14px !important;
        line-height: 1.25 !important;
        white-space: normal !important;
    }

    .value {
        min-width: 0 !important;
        width: auto !important;
        padding-left: 0 !important;
        font-size: 14px !important;
        line-height: 1.35 !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
    }
}

@media (max-width: 420px) {
    .info-row {
        grid-template-columns: 1fr !important;
        gap: 3px !important;
    }

    .label {
        margin-bottom: 0 !important;
    }
}
</style>

<div class="profile-page">

    {{-- HEADER --}}
    <div class="profile-header">
        <div>
            <div style="font-weight:bold;">Madana Mohana Colleges, Inc.</div>
            <div>Student Information System</div>
            <div style="font-size:20px; font-weight:bold;">STUDENT PROFILE</div>
        </div>

        <img
            src="{{ $student->photo_path ? asset($student->photo_path) : 'https://via.placeholder.com/120x120.png?text=Photo' }}"
            class="profile-pic"
            alt="Student Photo"
        >
    </div>

    {{-- SCHOOL INFO --}}
    <div class="section-title">School Information</div>
    <div class="info-row"><div class="label">Student ID</div><div class="value">{{ $student->student_id }}</div></div>
    <div class="info-row"><div class="label">LRN</div><div class="value">{{ $student->lrn ?? 'N/A' }}</div></div>

    {{-- PERSONAL --}}
    <div class="section-title">Personal Information</div>

    <div class="info-row">
        <div class="label">Full Name</div>
        <div class="value"><strong>{{ $student->full_name }}</strong></div>
    </div>

    <div class="grid-3">
        <div class="info-row"><div class="label">Sex</div><div class="value">{{ $student->sex }}</div></div>
        <div class="info-row"><div class="label">Birthdate</div>
            <div class="value">{{ optional($student->birth_date)->format('F d, Y') }}</div>
        </div>
    </div>

    <div class="info-row">
        <div class="label">Place of Birth</div>
        <div class="value">{{ $student->birth_place }}</div>
    </div>

    <div class="grid-3">
        <div class="info-row"><div class="label">Mother Tongue</div><div class="value">{{ $student->mother_tongue }}</div></div>
        <div class="info-row"><div class="label">Religion</div><div class="value">{{ $student->religion }}</div></div>
    </div>

    <div class="info-row">
        <div class="label">Address</div>
        <div class="value">
            {{ $student->house_street }},
            {{ $student->barangay }},
            {{ $student->municipality_city }},
            {{ $student->province }}
        </div>
    </div>

    <div class="info-row">
        <div class="label">Contact</div>
        <div class="value">{{ $student->contact_number }}</div>
    </div>

    <div class="info-row">
        <div class="label">Email</div>
        <div class="value">{{ $student->email }}</div>
    </div>

    {{-- FAMILY --}}
    <div class="section-title">Family Background</div>

    <div class="info-row">
        <div class="label">Father</div>
        <div class="value">{{ $student->father_name }} ({{ $student->father_contact }})</div>
    </div>

    <div class="info-row">
        <div class="label">Mother</div>
        <div class="value">{{ $student->mother_name }} ({{ $student->mother_contact }})</div>
    </div>

    <div class="info-row">
        <div class="label">Guardian</div>
        <div class="value">
            {{ $student->guardian_name }} ({{ $student->guardian_contact }})
        </div>
    </div>

    {{-- LEARNER --}}
    <div class="section-title">Learner Information</div>

    <div class="grid-3">
        <div class="info-row"><div class="label">IP</div><div class="value">{{ $student->is_ip ? 'Yes' : 'No' }}</div></div>
        <div class="info-row"><div class="label">Ethnic Group</div><div class="value">{{ $student->ethnic_group }}</div></div>
    </div>

    {{-- EDUCATION HISTORY --}}
    <div class="section-title">Education History</div>

    @if($student->enrollments->isNotEmpty())
        <div class="education-history">
            @foreach($student->enrollments->sortByDesc(fn($enrollment) => $enrollment->schoolYear?->name) as $enrollment)
                <div class="education-item">
                    <div class="info-row">
                        <div class="label">School Year</div>
                        <div class="value">{{ $enrollment->schoolYear?->name ?? 'N/A' }}</div>
                    </div>

                    <div class="grid-3">
                        <div class="info-row">
                            <div class="label">Grade Level</div>
                            <div class="value">{{ $enrollment->gradeLevel?->name ?? 'N/A' }}</div>
                        </div>

                        <div class="info-row">
                            <div class="label">Section</div>
                            <div class="value">{{ $enrollment->section?->name ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="grid-3">
                        <div class="info-row">
                            <div class="label">Student Type</div>
                            <div class="value">{{ ucfirst($enrollment->student_type ?? 'N/A') }}</div>
                        </div>

                        <div class="info-row">
                            <div class="label">Status</div>
                            <div class="value">{{ ucfirst(str_replace('_', ' ', $enrollment->status ?? 'N/A')) }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="info-row">
            <div class="label">Enrollment</div>
            <div class="value">No enrollment history found.</div>
        </div>
    @endif

    {{-- REMARKS --}}
    <div class="section-title">Remarks</div>
    <div class="info-row">
        <div class="value">{{ $student->remarks ?? 'None' }}</div>
    </div>

    {{-- ACTIONS --}}
    <div class="no-print" style="margin-top:20px; display:flex; gap:10px;">
        <a href="{{ route('student.profile.edit') }}" class="btn btn-primary no-print">Edit Profile</a>
        <button onclick="window.print()" class="btn btn-primary no-print">Print</button>
    </div>

</div>

@endsection