@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('page_title', 'Student Dashboard')

@section('content')

    @if(!$hasActiveEnrollment && $displayEnrollment)
        <div class="card" style="margin-bottom:16px; border-color: rgba(245,166,35,.45);">
            <div class="card-body">
                <strong>Enrollment notice:</strong>
                You do not have an enrollment record for the active school year.
                Showing your latest enrollment from
                <strong>{{ $displayEnrollment->schoolYear?->name }}</strong>.
            </div>
        </div>
    @endif

    {{-- REQUIRED DOCUMENT ALERT --}}
    @php
        $currentEnrollment = $student?->currentEnrollment()->first();

        $gradeLevelId = $currentEnrollment?->grade_level_id;
        $studentType = $currentEnrollment?->student_type;

        $requiredDocumentTypeIds = \App\Models\DocumentRequirementRule::query()
            ->where('is_required', true)
            ->whereHas('documentType', function ($query) {
                $query->where('is_active', true);
            })
            ->where(function ($query) use ($gradeLevelId) {
                $query->whereNull('grade_level_id')
                    ->orWhere('grade_level_id', $gradeLevelId);
            })
            ->where(function ($query) use ($studentType) {
                $query->whereNull('student_type')
                    ->orWhere('student_type', $studentType);
            })
            ->pluck('document_type_id')
            ->unique();

        $verifiedDocumentTypeIds = $student?->documents()
            ->whereIn('document_type_id', $requiredDocumentTypeIds)
            ->where('is_verified', true)
            ->pluck('document_type_id') ?? collect();

        $missingDocumentCount = $requiredDocumentTypeIds
            ->diff($verifiedDocumentTypeIds)
            ->count();
    @endphp

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-label">Student ID</div>
            <div class="stat-value" style="font-size:24px;">
                {{ $student?->student_id ?? 'N/A' }}
            </div>
            <div class="stat-change">Your official student number</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Grade Level</div>
            <div class="stat-value" style="font-size:24px;">
                {{ $displayEnrollment?->gradeLevel?->name ?? 'N/A' }}
            </div>
            <div class="stat-change">
                {{ $displayEnrollment?->schoolYear?->name ?? 'No enrollment record' }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Section</div>
            <div class="stat-value" style="font-size:24px;">
                {{ $displayEnrollment?->section?->name ?? 'N/A' }}
            </div>
            <div class="stat-change">
                {{ $hasActiveEnrollment ? 'Current active school year' : 'Latest enrollment shown' }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Account</div>
            <div class="stat-value" style="font-size:24px;">
                Active
            </div>
            <div class="stat-change">Student portal access</div>
        </div>
    </div>

    <div class="two-col">
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Profile</div>
                    <div class="card-subtitle">View your student information</div>
                </div>
            </div>
            <div class="card-body">
                <p>Name: {{ $student?->full_name }}</p>
                <p>LRN: {{ $student?->lrn ?? 'N/A' }}</p>
                <p>Contact: {{ $student?->contact_number ?? 'N/A' }}</p>

                <div style="margin-top:16px;">
                    <a href="{{ route('student.profile.show') }}" class="btn btn-primary">View Profile</a>
                    <a href="{{ route('student.profile.edit') }}" class="btn btn-ghost">Edit Contact Info</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Security</div>
                    <div class="card-subtitle">Update your temporary password</div>
                </div>
            </div>
            <div class="card-body">
                <p>Please change your temporary password after first login.</p>

                <div style="margin-top:16px;">
                    <a href="{{ route('student.password.edit') }}" class="btn btn-primary">Change Password</a>
                </div>
            </div>
        </div>
    </div>
@endsection