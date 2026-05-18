@extends('layouts.app')

@section('title', 'SF1 Report | MMCI')
@section('page_title', 'SF1 Report')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; gap:1rem; flex-wrap:wrap;">
    <div>
        <h1 style="margin:0;">School Form 1 (SF1)</h1>
        <p style="margin:.25rem 0 0; color:#666;">
            Select a school year, grade level, or section to create SF1 generation records.
        </p>
    </div>

    <a href="{{ route('admin.reports.sf1.generated', ['school_year_id' => $schoolYearId]) }}" class="btn btn-primary">
        View Generated SF1 Files
    </a>
</div>

@if(session('success'))
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.reports.sf1.queue') }}">
            @csrf

            <div class="filters-grid">
                <div class="form-group">
                    <label for="school_year_id">School Year <span class="text-danger">*</span></label>
                    <select name="school_year_id" id="school_year_id" class="form-input" required>
                        <option value="">Select School Year</option>
                        @foreach($schoolYears as $schoolYear)
                            <option value="{{ $schoolYear->id }}"
                                {{ (string) old('school_year_id', request('school_year_id', $schoolYearId)) === (string) $schoolYear->id ? 'selected' : '' }}>
                                {{ $schoolYear->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_year_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="grade_level_id">Grade Level</label>
                    <select name="grade_level_id" id="grade_level_id" class="form-input">
                        <option value="">All Grade Levels</option>
                        @foreach($gradeLevels as $gradeLevel)
                            <option value="{{ $gradeLevel->id }}"
                                {{ (string) old('grade_level_id', request('grade_level_id')) === (string) $gradeLevel->id ? 'selected' : '' }}>
                                {{ $gradeLevel->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('grade_level_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="section_id">Section</label>
                    <select name="section_id" id="section_id" class="form-input">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}"
                                data-grade-level-id="{{ $section->grade_level_id }}"
                                {{ (string) old('section_id', request('section_id')) === (string) $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top:1rem; display:flex; gap:.75rem; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    Create SF1 Generation List
                </button>

                <button type="submit"
                        formaction="{{ route('admin.reports.sf1.print') }}"
                        formmethod="GET"
                        formtarget="_blank"
                        class="btn btn-secondary">
                    Open Printable SF1
                </button>

                <a href="{{ route('admin.reports.sf1.filter') }}" class="btn btn-secondary">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>
@endsection