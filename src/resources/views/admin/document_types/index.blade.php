@extends('layouts.app')

@section('title', 'Document Types | MMCI')
@section('page_title', 'Document Types')

@section('topbar_actions')
    <a href="{{ route('admin.document-types.create') }}" class="btn btn-primary">+ Add Document Type</a>
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
            <div class="card-title">Document Types</div>
            <div class="card-subtitle">Manage documents that may be required from students.</div>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th style="width:170px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documentTypes as $documentType)
                    <tr>
                        <td>{{ $documentType->name }}</td>
                        <td>{{ $documentType->description ?: '—' }}</td>
                        <td>{{ $documentType->sort_order ?? '—' }}</td>
                        <td>
                            @if($documentType->is_active)
                                <span class="badge badge-green">Active</span>
                            @else
                                <span class="badge badge-amber">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                <a href="{{ route('admin.document-types.edit', $documentType) }}" class="btn btn-ghost btn-sm">Edit</a>

                                <form method="POST" action="{{ route('admin.document-types.destroy', $documentType) }}" onsubmit="return confirm('Delete this document type?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">No document types found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:16px;">
    {{ $documentTypes->links() }}
</div>
@endsection