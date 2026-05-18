@extends('layouts.app')

@section('title', 'Edit Section | MMCI')
@section('page_title', 'Edit Section')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Edit Section</div>
                <div class="card-subtitle">Update class section details</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.sections.update', $section) }}">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">School Year</label>
                        <select name="school_year_id" class="form-select">
                            <option value="">Select school year</option>
                            @foreach($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->id }}" @selected(old('school_year_id', $section->school_year_id) == $schoolYear->id)>
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
                                <option value="{{ $gradeLevel->id }}" @selected(old('grade_level_id', $section->grade_level_id) == $gradeLevel->id)>
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
                               value="{{ old('name', $section->name) }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Adviser</label>
                        <select name="teacher_id" class="form-select">
                            <option value="">Select adviser</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $section->teacher_id) == $teacher->id)>
                                    {{ $teacher->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">

                    <div class="form-group">
                        <label class="form-label">Capacity</label>
                        <input type="number"
                               name="capacity"
                               class="form-input"
                               value="{{ old('capacity', $section->capacity) }}"
                               min="1">
                    </div>
                </div>

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.sections.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Section</button>
                </div>
            </form>
        </div>
    </div>
@endsection