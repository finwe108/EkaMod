@extends('layouts.app')

@section('title', 'Add Section | MMCI')
@section('page_title', 'Add Section')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">New Section</div>
                <div class="card-subtitle">Create a class section</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.sections.store') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">School Year</label>
                        <select name="school_year_id" id="school_year_id" class="form-select">
                            <option value="">Select school year</option>
                            @foreach($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->id }}"
                                    {{ old('school_year_id', optional($activeSchoolYear)->id) == $schoolYear->id ? 'selected' : '' }}>
                                    {{ $schoolYear->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Grade Level</label>
                        <select name="grade_level_id" class="form-select">
                            <option value="">Select grade level</option>
                            @foreach($gradeLevels as $gradeLevel)
                                <option value="{{ $gradeLevel->id }}" @selected(old('grade_level_id') == $gradeLevel->id)>
                                    {{ $gradeLevel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Section Name</label>
                        <input type="text"
                               name="name"
                               class="form-input"
                               value="{{ old('name') }}"
                               placeholder="10-Rizal"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Adviser</label>
                        <select name="teacher_id" class="form-select">
                            <option value="">Select adviser</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>
                                    {{ $teacher->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                    <div class="form-group">
                        <label class="form-label">Capacity</label>
                        <input type="number"
                               name="capacity"
                               class="form-input"
                               value="{{ old('capacity') }}"
                               min="1">
                    </div>
                </div>

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.sections.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Section</button>
                </div>
            </form>
        </div>
    </div>
@endsection