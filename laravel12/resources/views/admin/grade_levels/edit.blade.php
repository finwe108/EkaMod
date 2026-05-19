@extends('layouts.app')

@section('title', 'Edit Grade Level | EduCore')
@section('page_title', 'Edit Grade Level')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Edit Grade Level</div>
                <div class="card-subtitle">Update academic level details</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.grade-levels.update', $gradeLevel) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text"
                           name="name"
                           class="form-input"
                           value="{{ old('name', $gradeLevel->name) }}"
                           required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number"
                               name="sort_order"
                               class="form-input"
                               value="{{ old('sort_order', $gradeLevel->sort_order) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text"
                               name="department"
                               class="form-input"
                               value="{{ old('department', $gradeLevel->department) }}">
                    </div>
                </div>

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.grade-levels.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Grade Level</button>
                </div>
            </form>
        </div>
    </div>
@endsection