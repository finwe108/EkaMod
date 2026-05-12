@extends('layouts.app')

@section('title', 'Add Grade Level | EduCore')
@section('page_title', 'Add Grade Level')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">New Grade Level</div>
                <div class="card-subtitle">Create an academic level</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.grade-levels.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text"
                           name="name"
                           class="form-input"
                           value="{{ old('name') }}"
                           placeholder="Grade 7"
                           required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number"
                               name="sort_order"
                               class="form-input"
                               value="{{ old('sort_order') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text"
                               name="department"
                               class="form-input"
                               value="{{ old('department') }}"
                               placeholder="Junior High School">
                    </div>
                </div>

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.grade-levels.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Grade Level</button>
                </div>
            </form>
        </div>
    </div>
@endsection