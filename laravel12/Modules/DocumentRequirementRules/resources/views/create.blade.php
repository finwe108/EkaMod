@extends('layouts.app')

@section('title', 'Add Document Rule | MMCI')
@section('page_title', 'Add Document Rule')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">New Requirement Rule</div>
            <div class="card-subtitle">Example: SF9 required for transferees only.</div>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.document-requirement-rules.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Document Type</label>
                <select name="document_type_id" class="form-select" required>
                    <option value="">Select document</option>
                    @foreach($documentTypes as $documentType)
                        <option value="{{ $documentType->id }}" @selected(old('document_type_id') == $documentType->id)>
                            {{ $documentType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Grade Level</label>
                    <select name="grade_level_id" class="form-select">
                        <option value="">All Grade Levels</option>
                        @foreach($gradeLevels as $gradeLevel)
                            <option value="{{ $gradeLevel->id }}" @selected(old('grade_level_id') == $gradeLevel->id)>
                                {{ $gradeLevel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Student Type</label>
                    <select name="student_type" class="form-select">
                        <option value="">All Student Types</option>
                        <option value="new" @selected(old('student_type') === 'new')>New</option>
                        <option value="old" @selected(old('student_type') === 'old')>Old</option>
                        <option value="transferee" @selected(old('student_type') === 'transferee')>Transferee</option>
                        <option value="returnee" @selected(old('student_type') === 'returnee')>Returnee</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="is_required" value="1" {{ old('is_required', true) ? 'checked' : '' }}>
                    Required document
                </label>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="require_if_no_existing_copy" value="1" {{ old('require_if_no_existing_copy', true) ? 'checked' : '' }}>
                    Require only if no verified copy exists
                </label>
            </div>

            <div class="form-group">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" class="form-input" rows="3">{{ old('remarks') }}</textarea>
            </div>

            <div style="display:flex;gap:10px;margin-top:16px;">
                <a href="{{ route('admin.document-requirement-rules.index') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Rule</button>
            </div>
        </form>
    </div>
</div>
@endsection