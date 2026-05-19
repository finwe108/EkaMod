@extends('layouts.app')

@section('title', 'Student Profile | MMCI')
@section('page_title', 'Student Profile')

@section('content')
<div class="page-header student-profile-header">
    <div class="student-profile-main">
        <img
            src="{{ $student->photo_path ? asset($student->photo_path) : 'https://via.placeholder.com/120x120.png?text=Photo' }}"
            alt="Student Photo"
            class="student-profile-photo"
        >

        <div>
            <h1 style="margin:0;">{{ $student->full_name }}</h1>

            <p style="margin:.25rem 0 0; color:var(--muted);">
                Student ID: <strong>{{ $student->student_id }}</strong>
                @if($student->lrn)
                    &nbsp; | &nbsp; LRN: <strong>{{ $student->lrn }}</strong>
                @endif
            </p>

            <p style="margin:.25rem 0 0; color:var(--muted);">
                {{ $currentEnrollment?->gradeLevel?->name ?? 'Not enrolled' }}

                @if($currentEnrollment?->section)
                    &nbsp; • &nbsp; {{ $currentEnrollment->section->name }}
                @endif
            </p>
        </div>
    </div>

    <div style="display:flex; gap:.75rem; flex-wrap:wrap;">
        <a href="{{ route('admin.enrollments.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
            + New Enrollment
        </a>

        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary">
            Edit Student
        </a>

        <a href="{{ route('admin.students.index') }}" class="btn btn-primary">
            Back to List
        </a>
    </div>
</div>

@if(session('success'))
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

@if(session('generated_username'))
    <div class="card" style="margin-bottom:16px;border-color:rgba(245,166,35,.45);">
        <div class="card-body">
            <strong>Temporary Login Credentials</strong>

            <div style="margin-top:8px;">
                Username: <strong>{{ session('generated_username') }}</strong>
            </div>

            <div>
                Temporary Password: <strong>{{ session('generated_password') }}</strong>
            </div>

            @if(session('email_sent'))
                <div style="margin-top:8px;color:var(--green);">
                    Credentials were emailed to the student.
                </div>
            @else
                <div style="margin-top:8px;color:var(--amber);">
                    No email was sent. Please give these credentials to the student manually.
                </div>
            @endif
        </div>
    </div>
@endif

{{-- TABS --}}
<div class="profile-tabs">
    <button type="button" class="tab-btn active" data-tab="profile">Profile</button>
    <button type="button" class="tab-btn" data-tab="contact">Contact</button>
    <button type="button" class="tab-btn" data-tab="guardian">Parent / Guardian</button>
    <button type="button" class="tab-btn" data-tab="account">Account</button>
    <button type="button" class="tab-btn" data-tab="documents">Documents</button>
    <button type="button" class="tab-btn" data-tab="enrollments">Enrollments</button>
</div>


{{-- PROFILE TAB --}}
<div class="tab-content active" id="tab-profile">
    <div class="card">
        <div class="card-body">
            <h3 style="margin-top:0;">Basic Information</h3>

            <table class="detail-table">
                <tr><th>Full Name</th><td>{{ $student->full_name }}</td></tr>
                <tr><th>Sex</th><td>{{ $student->sex ? ucfirst($student->sex) : '—' }}</td></tr>
                <tr><th>Birth Date</th><td>{{ optional($student->birth_date)->format('F d, Y') ?: '—' }}</td></tr>
                <tr><th>Age</th><td>{{ $student->age ?? '—' }}</td></tr>
                <tr><th>Birth Place</th><td>{{ $student->birth_place ?: '—' }}</td></tr>
                <tr><th>Religion</th><td>{{ $student->religion ?: '—' }}</td></tr>
                <tr><th>Mother Tongue</th><td>{{ $student->mother_tongue ?: '—' }}</td></tr>
                <tr><th>IP</th><td>{{ $student->is_ip ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Ethnic Group</th><td>{{ $student->ethnic_group ?: '—' }}</td></tr>
                <tr><th>Status</th><td>{{ ucfirst($student->status) }}</td></tr>
            </table>
        </div>
    </div>
</div>

{{-- CONTACT TAB --}}
<div class="tab-content" id="tab-contact">
    <div class="card">
        <div class="card-body">
            <h3 style="margin-top:0;">Contact & Address</h3>

            <table class="detail-table">
                <tr><th>Email</th><td>{{ $student->email ?: '—' }}</td></tr>
                <tr><th>Contact Number</th><td>{{ $student->contact_number ?: '—' }}</td></tr>
                <tr><th>House / Street</th><td>{{ $student->house_street ?: '—' }}</td></tr>
                <tr><th>Barangay</th><td>{{ $student->barangay ?: '—' }}</td></tr>
                <tr><th>Municipality / City</th><td>{{ $student->municipality_city ?: '—' }}</td></tr>
                <tr><th>Province</th><td>{{ $student->province ?: '—' }}</td></tr>
                <tr><th>Full Address</th><td>{{ $student->address ?: '—' }}</td></tr>
            </table>
        </div>
    </div>
</div>

{{-- GUARDIAN TAB --}}
<div class="tab-content" id="tab-guardian">
    <div class="dashboard-grid">
        <div class="card">
            <div class="card-body">
                <h3 style="margin-top:0;">Parent / Guardian</h3>

                <table class="detail-table">
                    <tr><th>Father</th><td>{{ $student->father_name ?: '—' }}</td></tr>
                    <tr><th>Father Contact</th><td>{{ $student->father_contact ?: '—' }}</td></tr>
                    <tr><th>Mother</th><td>{{ $student->mother_name ?: '—' }}</td></tr>
                    <tr><th>Mother Contact</th><td>{{ $student->mother_contact ?: '—' }}</td></tr>
                    <tr><th>Guardian</th><td>{{ $student->guardian_name ?: '—' }}</td></tr>
                    <tr><th>Relationship</th><td>{{ $student->guardian_relationship ?: '—' }}</td></tr>
                    <tr><th>Parent/Guardian Contact</th><td>{{ $student->parent_guardian_contact ?: '—' }}</td></tr>
                    <tr><th>Guardian Contact</th><td>{{ $student->guardian_contact ?: '—' }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 style="margin-top:0;">Remarks</h3>

                <div style="min-height:140px; color:var(--text); line-height:1.6;">
                    {{ $student->remarks ?: 'No remarks.' }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ACCOUNT TAB --}}
<div class="tab-content" id="tab-account">
    <div class="card">
        <div class="card-body">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap;">
                <div>
                    <h3 style="margin:0;">Student User Account</h3>
                    <p style="margin:.25rem 0 0; color:var(--muted);">
                        Login credentials connected to this student profile.
                    </p>
                </div>

                @if($student->user)
                    <a href="{{ route('admin.students.credentials.edit', $student) }}" class="btn btn-primary">
                        Edit Credentials
                    </a>
                @else
                    <form method="POST" action="{{ route('admin.students.credentials.store', $student) }}"
                          onsubmit="return confirm('Create a user account for this student? Login credentials will be emailed if email is available.');">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            Create Student Account
                        </button>
                    </form>
                @endif
            </div>

            <table class="detail-table" style="margin-top:1rem;">
                <tr>
                    <th>Account Name</th>
                    <td>{{ $student->user?->name ?? 'No user account created' }}</td>
                </tr>

                <tr>
                    <th>Username</th>
                    <td>{{ $student->user?->username ?? '—' }}</td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td>{{ $student->user?->email ?? $student->email ?? '—' }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        @if($student->user)
                            {{ $student->user->is_active ? 'Active' : 'Inactive' }}
                        @else
                            <span class="badge badge-amber">No Account</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Password</th>
                    <td>{{ $student->user ? 'Hidden for security' : '—' }}</td>
                </tr>

                <tr>
                    <th>Must Change Password</th>
                    <td>
                        @if($student->user)
                            {{ $student->user->must_change_password ? 'Yes' : 'No' }}
                        @else
                            —
                        @endif
                    </td>
                </tr>
            </table>

            @unless($student->user)
                <div style="margin-top:1rem; color:var(--muted); font-size:13px;">
                    This student does not have login access yet. Click
                    <strong>Create Student Account</strong> to generate a username and temporary password.
                </div>
            @endunless
        </div>
    </div>
</div>

{{-- DOCUMENTS TAB --}}
<div class="tab-content" id="tab-documents">
    <div class="card">
        <div class="card-body">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; gap:1rem; flex-wrap:wrap;">
                <div>
                    <h3 style="margin:0;">Required Documents</h3>
                    <p style="margin:.25rem 0 0; color:var(--muted);">
                        These are based on the student's current grade level and student type.
                    </p>
                </div>

                @if(Route::has('admin.student-documents.index'))
                    <a href="{{ route('admin.student-documents.index', ['q' => $student->student_id]) }}" class="btn btn-primary">
                        Verification Center
                    </a>
                @endif
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Status</th>
                            <th>Source</th>
                            <th>File</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($documentRules ?? [] as $rule)
                            @php
                                $documentType = $rule->documentType;

                                $document = $documentType
                                    ? (($studentDocuments ?? collect())[$documentType->id] ?? null)
                                    : null;

                                $status = $document ? $document->status : 'missing';

                                $badgeClass = match ($status) {
                                    'verified' => 'badge-green',
                                    'submitted' => 'badge-amber',
                                    'rejected' => 'badge-red',
                                    default => 'badge-red',
                                };

                                $statusLabel = match ($status) {
                                    'verified' => 'Verified',
                                    'submitted' => 'Submitted',
                                    'rejected' => 'Rejected',
                                    default => 'Missing',
                                };
                            @endphp

                            <tr>
                                <td>
                                    <strong>{{ $documentType?->name ?? 'Unknown Document' }}</strong>

                                    @if($rule->student_type)
                                        <div class="text-muted" style="font-size:11px; margin-top:3px;">
                                            Required for: {{ ucfirst(str_replace('_', ' ', $rule->student_type)) }}
                                        </div>
                                    @endif

                                    @if($rule->gradeLevel)
                                        <div class="text-muted" style="font-size:11px; margin-top:3px;">
                                            Grade: {{ $rule->gradeLevel->name }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>

                                    @if($document?->is_verified)
                                        <div class="text-muted" style="font-size:11px; margin-top:4px;">
                                            Verified {{ optional($document->verified_at)->format('M d, Y') }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $document?->source ? ucfirst(str_replace('_', ' ', $document->source)) : '—' }}
                                </td>

                                <td>
                                    @if($document?->file_path)
                                        <a href="{{ asset($document->file_path) }}" target="_blank">
                                            View file
                                        </a>

                                        @if($document->original_filename)
                                            <div class="text-muted" style="font-size:11px; margin-top:4px;">
                                                {{ $document->original_filename }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted">No file uploaded</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $document?->remarks ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    No required document rules found for this student.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:1rem;">
        <div class="card-body">
            <h3 style="margin-top:0;">All Student Document Types</h3>

            <p style="color:var(--muted); margin-top:-6px;">
                Admin can upload any active document type for this student, even if it is not required by current student-side rules.
            </p>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Source</th>
                            <th>File</th>
                            <th>Remarks</th>
                            <th style="width:280px;">Admin Upload</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($documentTypes ?? [] as $documentType)
                            @php
                                $document = ($studentDocuments ?? collect())[$documentType->id] ?? null;

                                $status = $document ? $document->status : 'not_uploaded';

                                $badgeClass = match ($status) {
                                    'verified' => 'badge-green',
                                    'submitted' => 'badge-amber',
                                    'rejected' => 'badge-red',
                                    default => 'badge-red',
                                };

                                $statusLabel = match ($status) {
                                    'verified' => 'Verified',
                                    'submitted' => 'Submitted',
                                    'rejected' => 'Rejected',
                                    default => 'Not Uploaded',
                                };
                            @endphp

                            <tr>
                                <td>
                                    <strong>{{ $documentType->name }}</strong>

                                    @if($documentType->description)
                                        <div class="text-muted" style="font-size:11px; margin-top:3px;">
                                            {{ $documentType->description }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>

                                    @if($document?->is_verified)
                                        <div class="text-muted" style="font-size:11px; margin-top:4px;">
                                            Verified {{ optional($document->verified_at)->format('M d, Y') }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $document?->source ? ucfirst(str_replace('_', ' ', $document->source)) : '—' }}
                                </td>

                                <td>
                                    @if($document?->file_path)
                                        <a href="{{ asset($document->file_path) }}" target="_blank">
                                            View file
                                        </a>

                                        @if($document->original_filename)
                                            <div class="text-muted" style="font-size:11px; margin-top:4px;">
                                                {{ $document->original_filename }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted">No file uploaded</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $document?->remarks ?? '—' }}
                                </td>

                                <td>
                                    @if(Route::has('admin.students.document-types.upload'))
                                        <form method="POST"
                                              action="{{ route('admin.students.document-types.upload', [$student, $documentType]) }}"
                                              enctype="multipart/form-data">
                                            @csrf

                                            <div style="display:flex; flex-direction:column; gap:8px;">
                                                <input type="file"
                                                       name="file"
                                                       class="form-input"
                                                       accept=".pdf,.jpg,.jpeg,.png,.webp"
                                                       required>

                                                <input type="text"
                                                       name="remarks"
                                                       class="form-input"
                                                       placeholder="Remarks (optional)">

                                                <button type="submit" class="btn btn-primary">
                                                    {{ $document ? 'Replace File' : 'Upload File' }}
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    No active document types found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ENROLLMENTS TAB --}}
<div class="tab-content" id="tab-enrollments">
    <div class="card">
        <div class="card-body">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; gap:1rem; flex-wrap:wrap;">
                <div>
                    <h3 style="margin:0;">Enrollment History</h3>
                    <p style="margin:.25rem 0 0; color:var(--muted);">
                        School-year-based enrollment records for this student.
                    </p>
                </div>

                <a href="{{ route('admin.enrollments.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                    + Add Enrollment
                </a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>School Year</th>
                            <th>Grade Level</th>
                            <th>Section</th>
                            <th>Student Type</th>
                            <th>Status</th>
                            <th>Enrollment Date</th>
                            <th style="width:180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->enrollments->sortByDesc('created_at') as $enrollment)
                            <tr>
                                <td>{{ $enrollment->schoolYear?->name }}</td>
                                <td>{{ $enrollment->gradeLevel?->name }}</td>
                                <td>{{ $enrollment->section?->name ?? '—' }}</td>
                                <td>{{ ucfirst($enrollment->student_type) }}</td>
                                <td>
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Pending',
                                            'enrolled' => 'Currently Enrolled',
                                            'completed' => 'Completed',
                                            'promoted' => 'Promoted',
                                            'retained' => 'Retained',
                                            'dropped' => 'Dropped',
                                            'transferred_out' => 'Transferred Out',
                                        ];

                                        $currentStatusLabel = $statusLabels[$enrollment->status]
                                            ?? ucfirst(str_replace('_', ' ', $enrollment->status));
                                    @endphp

                                    <form method="POST" action="{{ route('admin.enrollments.status.update', $enrollment) }}">
                                        @csrf
                                        @method('PATCH')

                                        <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                                            <div>
                                                <div style="font-size:11px;color:var(--muted);margin-bottom:4px;">
                                                    Current: <strong>{{ $currentStatusLabel }}</strong>
                                                </div>

                                                <select name="status" class="form-input" style="min-width:160px;">
                                                    @foreach($statusLabels as $value => $label)
                                                        <option value="{{ $value }}" @selected($enrollment->status === $value)>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-sm btn-primary">
                                                Update
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td>{{ optional($enrollment->date_enrolled)->format('M d, Y') }}</td>
                                <td>
                                    <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                                        <a href="{{ route('admin.enrollments.edit', $enrollment) }}" class="btn btn-sm btn-primary">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}" onsubmit="return confirm('Remove this enrollment record?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No enrollment records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .student-profile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .student-profile-main {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .student-profile-photo {
        width: 96px;
        height: 96px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid var(--border);
        background: var(--bg3);
    }

    .profile-tabs {
        display: inline-flex;
        gap: 4px;
        padding: 4px;
        margin-bottom: 24px;
        background: var(--bg3);
        border: 1px solid var(--line);
        border-radius: 8px;
    }

    .tab-btn {
        min-width: 130px;
        min-height: 42px;
        border: 0;
        border-radius: 6px;
        background: transparent;
        color: var(--muted);
        font-weight: 800;
        cursor: pointer;
    }

    .tab-btn:hover {
        border-color: var(--border2);
    }

    .tab-btn.active {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }

    .tab-content {
        display: none; 
    }

    .tab-content.active {
        display: block;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
    }

    .detail-table th,
    .detail-table td {
        padding: .6rem .4rem;
        border-bottom: 1px solid var(--border);
        vertical-align: top;
        text-align: left;
    }

    .detail-table th {
        width: 190px;
        color: var(--muted);
        font-weight: 600;
    }

    @media (max-width: 992px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .student-profile-header {
            align-items: flex-start;
        }

        .student-profile-photo {
            width: 78px;
            height: 78px;
        }

        .student-profile-header h1 {
            font-size: 1.4rem;
        }

        .tab-btn {
            flex: 1;
            min-width: 120px;
        }

        .detail-table th,
        .detail-table td {
            display: block;
            width: 100%;
        }

        .detail-table th {
            padding-bottom: .15rem;
        }

        .detail-table td {
            padding-top: .15rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.tab-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const tab = button.getAttribute('data-tab');

            document.querySelectorAll('.tab-btn').forEach(function (btn) {
                btn.classList.remove('active');
            });

            document.querySelectorAll('.tab-content').forEach(function (content) {
                content.classList.remove('active');
            });

            button.classList.add('active');

            const target = document.getElementById('tab-' + tab);

            if (target) {
                target.classList.add('active');
            }
        });
    });
});
</script>
@endpush