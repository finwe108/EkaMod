@extends('layouts.app')

@section('title', 'Edit School Year | EduCore')
@section('page_title', 'Edit School Year')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Edit School Year</div>
                <div class="card-subtitle">Update academic period details</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.school_years.update', $schoolYear) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text"
                           name="name"
                           class="form-input"
                           value="{{ old('name', $schoolYear->name) }}"
                           required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="date"
                               name="starts_on"
                               class="form-input"
                               value="{{ old('starts_on', optional($schoolYear->starts_on)->format('Y-m-d')) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="date"
                               name="ends_on"
                               class="form-input"
                               value="{{ old('ends_on', optional($schoolYear->ends_on)->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $schoolYear->is_active) ? 'checked' : '' }}>
                        Set as active school year
                    </label>
                </div>

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.school_years.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update School Year</button>
                </div>
            </form>
        </div>
    </div>
@endsection