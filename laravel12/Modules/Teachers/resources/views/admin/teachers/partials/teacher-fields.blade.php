<div class="form-row">
    <div class="form-group">
        <label class="form-label">Teacher No.</label>
        <input type="text"
               name="teacher_no"
               class="form-input"
               value="{{ old('teacher_no', $teacher->teacher_no ?? '') }}">
        @error('teacher_no')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Specialization</label>
        <input type="text"
               name="specialization"
               class="form-input"
               value="{{ old('specialization', $teacher->specialization ?? '') }}">
        @error('specialization')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Subject Specialty</label>
        <input type="text"
               name="subject_specialty"
               class="form-input"
               value="{{ old('subject_specialty', $teacher->subject_specialty ?? '') }}">
        @error('subject_specialty')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">License No.</label>
        <input type="text"
               name="license_no"
               class="form-input"
               value="{{ old('license_no', $teacher->license_no ?? '') }}">
        @error('license_no')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Major</label>
        <input type="text"
               name="major"
               class="form-input"
               value="{{ old('major', $teacher->major ?? '') }}">
        @error('major')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Rank Title</label>
        <input type="text"
               name="rank_title"
               class="form-input"
               value="{{ old('rank_title', $teacher->rank_title ?? '') }}">
        @error('rank_title')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Date Hired as Teacher</label>
        <input type="date"
               name="date_hired_as_teacher"
               class="form-input"
               value="{{ old('date_hired_as_teacher', isset($teacher) && $teacher->date_hired_as_teacher ? $teacher->date_hired_as_teacher->format('Y-m-d') : '') }}">
        @error('date_hired_as_teacher')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label style="display:flex; align-items:center; gap:8px; margin-top:30px;">
            <input type="checkbox"
                   name="is_adviser"
                   value="1"
                   {{ old('is_adviser', $teacher->is_adviser ?? false) ? 'checked' : '' }}>
            <span>Adviser</span>
        </label>
        @error('is_adviser')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
