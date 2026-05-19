@extends('layouts.app')

@section('title', 'Add School Year | MMCI')
@section('page_title', 'Add School Year')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">New School Year</div>
                <div class="card-subtitle">Create an academic period</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.school_years.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text"
                           name="name"
                           class="form-input"
                           value="{{ old('name') }}"
                           placeholder="2025-2026"
                           required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="date"
                               name="starts_on"
                               class="form-input"
                               value="{{ old('starts_on') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="date"
                               name="ends_on"
                               class="form-input"
                               value="{{ old('ends_on') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                        Set as active school year
                    </label>
                </div>

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.school_years.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save School Year</button>
                </div>
            </form>
        </div>
    </div>
@endsection