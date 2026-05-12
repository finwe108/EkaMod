@extends('layouts.app')

@section('title', 'Edit Document Rule | MMCI')
@section('page_title', 'Edit Document Rule')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Edit Requirement Rule</div>
            <div class="card-subtitle">Update requirement conditions.</div>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.document-requirement-rules.update', $documentRequirementRule) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Document Type</label>
                <select name="document_type_id" class="form-select" required>
                    <option value="">Select document</option>
                    @foreach($documentTypes as $documentType)
                        <option value="{{ $documentType->id }}" @selected(old('document_type_id', $documentRequirementRule->document_type_id) == $documentType->id)>
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
                            <option value="{{ $gradeLevel->id }}" @selected(old('grade_level_id', $documentRequirementRule->grade_level_id) == $gradeLevel->id)>
                                {{ $gradeLevel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Student Type</label>
                    <select name="student_type" class="form-select">
                        <option value="">All Student Types</option>
                        <option value="new" @selected(old('student_type', $documentRequirementRule->student_type) === 'new')>New</option>
                        <option value="old" @selected(old('student_type', $documentRequirementRule->student_type) === 'old')>Old</option>
                        <option value="transferee" @selected(old('student_type', $documentRequirementRule->student_type) === 'transferee')>Transferee</option>
                        <option value="returnee" @selected(old('student_type', $documentRequirementRule->student_type) === 'returnee')>Returnee</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="is_required" value="1" {{ old('is_required', $documentRequirementRule->is_required) ? 'checked' : '' }}>
                    Required document
                </label>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="require_if_no_existing_copy" value="1" {{ old('require_if_no_existing_copy', $documentRequirementRule->require_if_no_existing_copy) ? 'checked' : '' }}>
                    Require only if no verified copy exists
                </label>
            </div>

            <div class="form-group">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" class="form-input" rows="3">{{ old('remarks', $documentRequirementRule->remarks) }}</textarea>
            </div>

            <div style="display:flex;gap:10px;margin-top:16px;">
                <a href="{{ route('admin.document-requirement-rules.index') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Rule</button>
            </div>
        </form>
    </div>
</div>
@endsection