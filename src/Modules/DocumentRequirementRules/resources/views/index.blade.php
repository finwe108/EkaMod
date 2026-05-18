@extends('layouts.app')

@section('title', 'Document Rules | MMCI')
@section('page_title', 'Document Rules')

@section('topbar_actions')
    <a href="{{ route('admin.document-requirement-rules.create') }}" class="btn btn-primary">+ Add Rule</a>
@endsection

@section('content')
@if(session('success'))
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Document Requirement Rules</div>
            <div class="card-subtitle">Control which documents are required by grade level and student type.</div>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Grade Level</th>
                    <th>Student Type</th>
                    <th>Required</th>
                    <th>If No Existing Copy</th>
                    <th>Remarks</th>
                    <th style="width:170px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules as $rule)
                    <tr>
                        <td>{{ $rule->documentType?->name }}</td>
                        <td>{{ $rule->gradeLevel?->name ?? 'All Grade Levels' }}</td>
                        <td>{{ $rule->student_type ? ucfirst($rule->student_type) : 'All Types' }}</td>
                        <td>
                            @if($rule->is_required)
                                <span class="badge badge-green">Yes</span>
                            @else
                                <span class="badge badge-amber">No</span>
                            @endif
                        </td>
                        <td>
                            @if($rule->require_if_no_existing_copy)
                                <span class="badge badge-blue">Yes</span>
                            @else
                                <span class="badge badge-amber">No</span>
                            @endif
                        </td>
                        <td>{{ $rule->remarks ?: '—' }}</td>
                        <td>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                <a href="{{ route('admin.document-requirement-rules.edit', $rule) }}" class="btn btn-ghost btn-sm">Edit</a>

                                <form method="POST" action="{{ route('admin.document-requirement-rules.destroy', $rule) }}" onsubmit="return confirm('Delete this requirement rule?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty">No document rules found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:16px;">
    {{ $rules->links() }}
</div>
@endsection