@extends('layouts.app')

@section('title', 'Review Admission Application')
@section('page_title', 'Review Admission Application')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <div>
        <h1 style="margin:0;">{{ $application->full_name }}</h1>
        <p style="margin:.25rem 0 0;">{{ $application->application_number }}</p>
    </div>

    <a href="{{ route('admin.admission_applications.index') }}" class="btn btn-secondary">Back</a>
</div>

@if(session('success'))
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

@if(session('temporary_student_password'))
    <div class="card" style="margin-bottom:1rem;border:2px solid #f2cf3a;">
        <div class="card-body">
            <strong>Temporary Student Password:</strong>
            <code>{{ session('temporary_student_password') }}</code>
            <p style="margin:.5rem 0 0;">Copy this now. It will not be shown again.</p>
        </div>
    </div>
@endif

<div class="card" style="margin-bottom:1rem;">
    <div class="card-body">
        <h2 style="margin-top:0;">Application Status</h2>

        <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $application->application_status)) }}</p>
        <p><strong>Submitted:</strong> {{ $application->submitted_at?->format('M d, Y h:i A') ?? '—' }}</p>
        <p><strong>Reviewed By:</strong> {{ $application->reviewer?->name ?? '—' }}</p>
        <p><strong>Reviewed At:</strong> {{ $application->reviewed_at?->format('M d, Y h:i A') ?? '—' }}</p>

        @if($application->acceptedStudent)
            <p>
                <strong>Created Student:</strong>
                {{ $application->acceptedStudent->student_id }} - {{ $application->acceptedStudent->full_name }}
            </p>
        @endif

        @if($application->createdUser)
            <p><strong>Created User:</strong> {{ $application->createdUser->email }}</p>
        @endif

        @if($application->rejection_reason)
            <p><strong>Rejection Reason:</strong> {{ $application->rejection_reason }}</p>
        @endif
    </div>
</div>

<div class="card" style="margin-bottom:1rem;">
    <div class="card-body">
        <h2 style="margin-top:0;">Actions</h2>

        @if(in_array($application->application_status, ['submitted']))
            <form method="POST" action="{{ route('admin.admission_applications.review', $application) }}" style="margin-bottom:1rem;">
                @csrf
                <button type="submit" class="btn btn-secondary">Mark Under Review</button>
            </form>
        @endif

        @if(!in_array($application->application_status, ['accepted', 'rejected', 'cancelled']))
            <form method="POST"
                  action="{{ route('admin.admission_applications.accept', $application) }}"
                  onsubmit="return confirm('Accept this application and create student account?')"
                  style="margin-bottom:1rem;">
                @csrf

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label for="grade_level_id"><strong>Final Grade Level</strong></label>
                        <select id="grade_level_id" name="grade_level_id" class="form-input" required>
                            <option value="">Select grade level</option>
                            @foreach($gradeLevels as $gradeLevel)
                                <option value="{{ $gradeLevel->id }}"
                                    @selected(old('grade_level_id', $application->grade_level_id) == $gradeLevel->id)>
                                    {{ $gradeLevel->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('grade_level_id')
                            <div style="color:red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="section_id"><strong>Final Section</strong></label>
                        <select id="section_id" name="section_id" class="form-input" required>
                            <option value="">Select section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}"
                                    @selected(old('section_id', $application->section_id) == $section->id)>
                                    {{ $section->name }}
                                    @if($section->gradeLevel)
                                        — {{ $section->gradeLevel->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <div style="color:red;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Accept & Create Account
                </button>
            </form>
        @endif

        @if(!in_array($application->application_status, ['accepted', 'rejected', 'cancelled']))
            <form method="POST" action="{{ route('admin.admission_applications.reject', $application) }}">
                @csrf

                <label for="rejection_reason"><strong>Reject Application</strong></label>
                <textarea id="rejection_reason" name="rejection_reason" rows="3" class="form-input" required>{{ old('rejection_reason') }}</textarea>

                @error('rejection_reason')
                    <div style="color:red;">{{ $message }}</div>
                @enderror

                <button type="submit"
                        class="btn btn-danger"
                        style="margin-top:.5rem;"
                        onclick="return confirm('Reject this application?')">
                    Reject
                </button>
            </form>
        @endif

        @if(in_array($application->application_status, ['accepted', 'rejected', 'cancelled']))
            <p style="margin:0;">No further action is available for this application.</p>
        @endif
    </div>
</div>

<div class="card" style="margin-bottom:1rem;">
    <div class="card-body">
        <h2 style="margin-top:0;">Student Information</h2>

        <div class="profile-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
            <p><strong>Name:</strong><br>{{ $application->full_name }}</p>
            <p><strong>Sex:</strong><br>{{ $application->sex }}</p>
            <p><strong>Birth Date:</strong><br>{{ $application->birth_date?->format('M d, Y') }}</p>
            <p><strong>Birth Place:</strong><br>{{ $application->birth_place ?? '—' }}</p>
            <p><strong>LRN:</strong><br>{{ $application->lrn ?? '—' }}</p>
            <p><strong>Mother Tongue:</strong><br>{{ $application->mother_tongue ?? '—' }}</p>
            <p><strong>Religion:</strong><br>{{ $application->religion ?? '—' }}</p>
            <p><strong>IP Community:</strong><br>{{ $application->is_ip ? 'Yes' : 'No' }}</p>
            <p><strong>Ethnic Group:</strong><br>{{ $application->ethnic_group ?? '—' }}</p>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:1rem;">
    <div class="card-body">
        <h2 style="margin-top:0;">Application Details</h2>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
            <p><strong>School Year:</strong><br>{{ $application->schoolYear?->name ?? '—' }}</p>
            <p><strong>Applied Grade Level:</strong><br>{{ $application->gradeLevel?->name ?? '—' }}</p>
            <p><strong>Current Section:</strong><br>{{ $application->section?->name ?? '—' }}</p>
            <p><strong>Student Type:</strong><br>{{ ucfirst($application->student_type) }}</p>
            <p><strong>Last School:</strong><br>{{ $application->last_school_attended ?? '—' }}</p>
            <p><strong>Last Grade Completed:</strong><br>{{ $application->last_grade_level_completed ?? '—' }}</p>
            <p><strong>Strand / Track:</strong><br>{{ $application->strand_or_track ?? '—' }}</p>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:1rem;">
    <div class="card-body">
        <h2 style="margin-top:0;">Contact and Address</h2>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
            <p><strong>Email:</strong><br>{{ $application->email ?? '—' }}</p>
            <p><strong>Contact Number:</strong><br>{{ $application->contact_number ?? '—' }}</p>
            <p><strong>House / Street:</strong><br>{{ $application->house_street ?? '—' }}</p>
            <p><strong>Barangay:</strong><br>{{ $application->barangay ?? '—' }}</p>
            <p><strong>Municipality / City:</strong><br>{{ $application->municipality_city ?? '—' }}</p>
            <p><strong>Province:</strong><br>{{ $application->province ?? '—' }}</p>
            <p style="grid-column:1/-1;"><strong>Full Address:</strong><br>{{ $application->address ?? '—' }}</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h2 style="margin-top:0;">Parents / Guardian</h2>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
            <p><strong>Father:</strong><br>{{ $application->father_name ?? '—' }}</p>
            <p><strong>Father Contact:</strong><br>{{ $application->father_contact ?? '—' }}</p>
            <p><strong>Mother:</strong><br>{{ $application->mother_name ?? '—' }}</p>
            <p><strong>Mother Contact:</strong><br>{{ $application->mother_contact ?? '—' }}</p>
            <p><strong>Guardian:</strong><br>{{ $application->guardian_name ?? '—' }}</p>
            <p><strong>Relationship:</strong><br>{{ $application->guardian_relationship ?? '—' }}</p>
            <p><strong>Guardian Contact:</strong><br>{{ $application->guardian_contact ?? '—' }}</p>
            <p><strong>Parent/Guardian Contact:</strong><br>{{ $application->parent_guardian_contact ?? '—' }}</p>
        </div>

        @if($application->remarks)
            <hr>
            <p><strong>Remarks:</strong><br>{{ $application->remarks }}</p>
        @endif
    </div>
</div>
@endsection