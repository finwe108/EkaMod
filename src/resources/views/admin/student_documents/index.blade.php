@extends('layouts.app')

@section('title', 'Verify Student Documents | MMCI')
@section('page_title', 'Verify Student Documents')

@section('content')
@if(session('success'))
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <div>
            <div class="card-title">Document Verification</div>
            <div class="card-subtitle">Review, verify, or reject submitted student documents.</div>
        </div>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.student-documents.index') }}">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Search Student</label>
                    <input type="text" name="q" class="form-input" value="{{ $search }}" placeholder="Student ID, LRN, or name">
                </div>

                <div class="form-group">
                    <label class="form-label">Document Type</label>
                    <select name="document_type_id" class="form-select">
                        <option value="">All Documents</option>
                        @foreach($documentTypes as $documentType)
                            <option value="{{ $documentType->id }}" @selected((string) $documentTypeId === (string) $documentType->id)>
                                {{ $documentType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" @selected($status === 'pending')>Pending</option>
                        <option value="verified" @selected($status === 'verified')>Verified</option>
                        <option value="rejected" @selected($status === 'rejected')>Rejected</option>
                        <option value="submitted" @selected($status === 'submitted')>Submitted</option>
                    </select>
                </div>

                <div class="form-group" style="display:flex;align-items:end;gap:10px;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.student-documents.index') }}" class="btn btn-ghost">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Document</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Uploaded</th>
                    <th style="width:300px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($studentDocuments as $studentDocument)
                    <tr>
                        <td>
                            <strong>{{ $studentDocument->student?->full_name }}</strong>
                            <div style="font-size:11px;color:var(--muted);margin-top:4px;">
                                {{ $studentDocument->student?->student_id }} 
                                @if($studentDocument->student?->lrn)
                                    · LRN: {{ $studentDocument->student?->lrn }}
                                @endif
                            </div>
                        </td>

                        <td>{{ $studentDocument->documentType?->name }}</td>

                        <td>
                            @if($studentDocument->file_path)
                                <a href="{{ asset($studentDocument->file_path) }}" target="_blank" class="btn btn-ghost btn-sm">
                                    View File
                                </a>
                                <div style="font-size:11px;color:var(--muted);margin-top:4px;">
                                    {{ $studentDocument->original_filename }}
                                </div>
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            @if($studentDocument->is_verified)
                                <span class="badge badge-green">Verified</span>
                            @elseif($studentDocument->status === 'rejected')
                                <span class="badge badge-red">Rejected</span>
                            @else
                                <span class="badge badge-amber">{{ ucfirst($studentDocument->status) }}</span>
                            @endif
                        </td>

                        <td>{{ $studentDocument->remarks ?: '—' }}</td>

                        <td>{{ optional($studentDocument->created_at)->format('M d, Y h:i A') }}</td>

                        <td>
                            <div style="display:grid;gap:8px;">
                                <form method="POST" action="{{ route('admin.student-documents.verify', $studentDocument) }}">
                                    @csrf
                                    @method('PUT')

                                    <input type="text" name="remarks" class="form-input" placeholder="Optional verification remarks">

                                    <button type="submit" class="btn btn-primary" style="margin-top:6px;">
                                        Verify
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.student-documents.reject', $studentDocument) }}">
                                    @csrf
                                    @method('PUT')

                                    <input type="text" name="remarks" class="form-input" placeholder="Reason for rejection" required>

                                    <button type="submit" class="btn btn-ghost" style="margin-top:6px;">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty">No student document submissions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:16px;">
    {{ $studentDocuments->links() }}
</div>
@endsection