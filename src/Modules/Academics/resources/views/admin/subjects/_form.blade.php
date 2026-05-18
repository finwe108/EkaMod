<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Subject Information</div>
            <div class="card-subtitle">Code, name, description, and grade level</div>
        </div>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="card" style="margin-bottom:18px; border-color: rgba(240,96,96,.35);">
                <div class="card-body">
                    <ul style="margin:0; padding-left:18px; color: var(--red); line-height:1.7;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="code">Subject Code</label>
                <input type="text" name="code" id="code" class="form-input" value="{{ old('code', $subject->code ?? '') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="name">Subject Name</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $subject->name ?? '') }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="grade_level_id">Grade Level</label>
                <select name="grade_level_id" id="grade_level_id" class="form-input">
                    <option value="">Select Grade Level</option>
                    @foreach($gradeLevels as $gradeLevel)
                        <option value="{{ $gradeLevel->id }}" {{ old('grade_level_id', $subject->grade_level_id ?? '') == $gradeLevel->id ? 'selected' : '' }}>
                            {{ $gradeLevel->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Description</label>
            <textarea name="description" id="description" rows="3" class="form-input">{{ old('description', $subject->description ?? '') }}</textarea>
        </div>

        <div style="display:flex; gap:10px; margin-top:16px;">
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">
                {{ isset($subject) ? 'Update Subject' : 'Save Subject' }}
            </button>
        </div>
    </div>
</div>