@extends('layouts.app')

@section('title', 'Add Document Type | MMCI')
@section('page_title', 'Add Document Type')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">New Document Type</div>
            <div class="card-subtitle">Example: PSA Birth Certificate, SF9, Good Moral.</div>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.document-types.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Document Name</label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="{{ old('sort_order') }}">
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <div style="display:flex;gap:10px;margin-top:16px;">
                <a href="{{ route('admin.document-types.index') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Document Type</button>
            </div>
        </form>
    </div>
</div>
@endsection