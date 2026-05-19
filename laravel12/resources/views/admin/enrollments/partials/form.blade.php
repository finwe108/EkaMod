@if ($errors->any())
    <div class="card" style="margin-bottom:1rem; background:#fff3f3; border:1px solid #f3c2c2;">
        <div class="card-body">
            <strong>Please fix the following:</strong>
            <ul style="margin:.5rem 0 0; padding-left:1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <h3 style="margin-top:0;">Enrollment Information</h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="student_id">Student <span class="text-danger">*</span></label>
                <select name="student_id" id="student_id" class="form-input" required>
                    <option value="">Select Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}"
                            {{ (string) old('student_id', $enrollment->student_id ?? request('student_id')) === (string) $student->id ? 'selected' : '' }}>
                            {{ $student->student_id ? $student->student_id . ' - ' : '' }}{{ $student->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('student_id') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="school_year_id">School Year <span class="text-danger">*</span></label>
                <select name="school_year_id" id="school_year_id" class="form-input" required>
                    <option value="">Select School Year</option>
                    @foreach($schoolYears as $schoolYear)
                        <option value="{{ $schoolYear->id }}"
                            {{ (string) old('school_year_id', $enrollment->school_year_id ?? ($activeSchoolYear->id ?? '')) === (string) $schoolYear->id ? 'selected' : '' }}>
                            {{ $schoolYear->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_year_id') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="grade_level_id">Grade Level <span class="text-danger">*</span></label>
                <select name="grade_level_id" id="grade_level_id" class="form-input" required>
                    <option value="">Select Grade Level</option>
                    @foreach($gradeLevels as $gradeLevel)
                        <option value="{{ $gradeLevel->id }}"
                            {{ (string) old('grade_level_id', $enrollment->grade_level_id ?? '') === (string) $gradeLevel->id ? 'selected' : '' }}>
                            {{ $gradeLevel->name }}
                        </option>
                    @endforeach
                </select>
                @error('grade_level_id') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="section_id">Section <span class="text-danger">*</span></label>
                <select name="section_id" id="section_id" class="form-input" required
                        data-old-value="{{ old('section_id', $enrollment->section_id ?? '') }}">
                    <option value="">Select Section</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}"
                                data-grade-level-id="{{ $section->grade_level_id }}"
                            {{ (string) old('section_id', $enrollment->section_id ?? '') === (string) $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
                @error('section_id') <div class="text-danger">{{ $message }}</div> @enderror
                <small class="text-muted">Only sections matching the selected grade level will be shown.</small>
            </div>

            <div class="form-group">
                <label for="student_type">Student Type <span class="text-danger">*</span></label>
                <select name="student_type" id="student_type" class="form-input" required>
                    <option value="">Select Student Type</option>
                    <option value="new" {{ old('student_type', $enrollment->student_type ?? '') === 'new' ? 'selected' : '' }}>New</option>
                    <option value="old" {{ old('student_type', $enrollment->student_type ?? '') === 'old' ? 'selected' : '' }}>Old</option>
                    <option value="transferee" {{ old('student_type', $enrollment->student_type ?? '') === 'transferee' ? 'selected' : '' }}>Transferee</option>
                </select>
                @error('student_type') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="status">Enrollment Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-input" required>
                    <option value="">Select Status</option>
                    <option value="enrolled" {{ old('status', $enrollment->status ?? 'enrolled') === 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                    <option value="pending" {{ old('status', $enrollment->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="withdrawn" {{ old('status', $enrollment->status ?? '') === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                </select>
                @error('status') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="date_enrolled">Date Enrolled</label>
                <input
                    type="date"
                    name="date_enrolled"
                    id="date_enrolled"
                    class="form-input"
                    value="{{ old('date_enrolled', optional($enrollment->date_enrolled ?? now())->format('Y-m-d')) }}"
                >
                @error('date_enrolled') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="date_dropped">Date Dropped</label>
                <input
                    type="date"
                    name="date_dropped"
                    id="date_dropped"
                    class="form-input"
                    value="{{ old('date_dropped', optional($enrollment->date_dropped ?? null)->format('Y-m-d')) }}"
                >
                @error('date_dropped') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="date_transferred_out">Date Transferred Out</label>
                <input
                    type="date"
                    name="date_transferred_out"
                    id="date_transferred_out"
                    class="form-input"
                    value="{{ old('date_transferred_out', optional($enrollment->date_transferred_out ?? null)->format('Y-m-d')) }}"
                >
                @error('date_transferred_out') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group form-group-full">
                <label for="remarks">Remarks</label>
                <textarea
                    name="remarks"
                    id="remarks"
                    class="form-input"
                    rows="3"
                >{{ old('remarks', $enrollment->remarks ?? '') }}</textarea>
                @error('remarks') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top:1rem; display:flex; gap:.75rem; flex-wrap:wrap;">
            <button type="submit" class="btn btn-primary">
                {{ isset($enrollment) && $enrollment->exists ? 'Update Enrollment' : 'Save Enrollment' }}
            </button>

            @if(isset($enrollment) && $enrollment->student_id)
                <a href="{{ route('admin.students.show', $enrollment->student_id) }}" class="btn btn-secondary">
                    Cancel
                </a>
            @else
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.form-grid {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: .35rem;
    min-width: 0;
}

.form-group-full {
    grid-column: 1 / -1;
}

.form-input {
    width: 100%;
    box-sizing: border-box;
}

.text-danger {
    color: #c62828;
    font-size: .9rem;
}

.text-muted {
    color: #777;
    font-size: .85rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group-full {
        grid-column: auto;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const gradeLevelSelect = document.getElementById('grade_level_id');
    const sectionSelect = document.getElementById('section_id');
    const oldSectionValue = sectionSelect?.dataset.oldValue || '';

    if (!gradeLevelSelect || !sectionSelect) return;

    const originalOptions = Array.from(sectionSelect.querySelectorAll('option')).map(option => ({
        value: option.value,
        text: option.text,
        gradeLevelId: option.dataset.gradeLevelId || '',
        selected: option.selected
    }));

    function rebuildSections() {
        const selectedGradeLevelId = gradeLevelSelect.value;
        const currentValue = sectionSelect.value || oldSectionValue;

        sectionSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Select Section';
        sectionSelect.appendChild(placeholder);

        originalOptions.forEach(option => {
            if (option.value === '') return;

            if (!selectedGradeLevelId || option.gradeLevelId === selectedGradeLevelId) {
                const el = document.createElement('option');
                el.value = option.value;
                el.textContent = option.text;
                el.dataset.gradeLevelId = option.gradeLevelId;

                if (String(option.value) === String(currentValue)) {
                    el.selected = true;
                }

                sectionSelect.appendChild(el);
            }
        });

        const stillExists = Array.from(sectionSelect.options).some(opt => opt.value === currentValue);
        if (!stillExists) {
            sectionSelect.value = '';
        }
    }

    rebuildSections();
    gradeLevelSelect.addEventListener('change', rebuildSections);
});
</script>
@endpush