@extends('layouts.app')

@section('title', 'Required Documents')
@section('page_title', 'Required Documents')

@section('content')
@if(session('success'))
    <div class="card" style="margin-bottom:16px; border-color: rgba(45,212,160,.35);">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <div>
            <div class="card-title">Required Documents</div>
            <div class="card-subtitle">
                Documents required based on your current enrollment.
            </div>
        </div>
    </div>

    <div class="card-body">
        <p style="margin:0;color:var(--muted);">
            School Year:
            <strong>{{ $displayEnrollment?->schoolYear?->name ?? 'Not enrolled' }}</strong>
            &nbsp; | &nbsp;

            Grade Level:
            <strong>{{ $displayEnrollment?->gradeLevel?->name ?? 'N/A' }}</strong>
            &nbsp; | &nbsp;

            Section:
            <strong>{{ $displayEnrollment?->section?->name ?? 'N/A' }}</strong>
            &nbsp; | &nbsp;

            Student Type:
            <strong>{{ $displayEnrollment?->student_type ? ucfirst($displayEnrollment->student_type) : 'N/A' }}</strong>
        </p>

        @if(!$currentEnrollment && $displayEnrollment)
            <div style="margin-top:10px;color:var(--amber);font-size:13px;">
                No enrollment record found for the active school year.
                Showing latest enrollment from
                <strong>{{ $displayEnrollment->schoolYear?->name }}</strong>.
            </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Status</th>
                    <th>Uploaded File</th>
                    <th>Upload / Replace</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules as $rule)
                    @php
                        $documentType = $rule->documentType;
                        $upload = $uploads->get($documentType->id);
                    @endphp

                    <tr>
                        <td>
                            <strong>{{ $documentType->name }}</strong>

                            @if($documentType->description)
                                <div style="font-size:11px;color:var(--muted);margin-top:4px;">
                                    {{ $documentType->description }}
                                </div>
                            @endif

                            @if($rule->remarks)
                                <div style="font-size:11px;color:var(--amber);margin-top:4px;">
                                    {{ $rule->remarks }}
                                </div>
                            @endif
                        </td>

                        <td>
                            @if($upload?->is_verified)
                                <span class="badge badge-green">Verified</span>
                            @elseif($upload?->status === 'rejected')
                                <span class="badge badge-red">Rejected</span>
                            @elseif($upload)
                                <span class="badge badge-blue">Submitted</span>
                            @else
                                <span class="badge badge-amber">Missing</span>
                            @endif

                            @if($upload?->remarks)
                                <div style="font-size:11px;color:var(--muted);margin-top:4px;">
                                    {{ $upload->remarks }}
                                </div>
                            @endif
                        </td>

                        <td>
                            @if($upload?->file_path)
                                <a href="{{ asset($upload->file_path) }}" target="_blank" class="btn btn-ghost" style="font-size:11px;padding:4px 10px;">
                                    View File
                                </a>

                                <div style="font-size:11px;color:var(--muted);margin-top:4px;">
                                    {{ $upload->original_filename }}
                                </div>
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            @if(!$upload?->is_verified)
                                <form method="POST" action="{{ route('student.documents.upload', $rule) }}" enctype="multipart/form-data">
                                    @csrf

                                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                        <input type="file" name="file" class="form-input" accept=".pdf,.jpg,.jpeg,.png,.webp" required>
                                        <button type="submit" class="btn btn-primary">
                                            Upload
                                        </button>
                                    </div>
                                </form>
                            @else
                                <span style="font-size:12px;color:var(--muted);">
                                    Verified documents cannot be replaced.
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty">
                            No required documents for your current enrollment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection