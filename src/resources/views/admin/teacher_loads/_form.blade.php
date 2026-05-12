@php
    $isEdit = isset($teacherLoad);

    $selectedSubjectIds = old(
        'subject_ids',
        $isEdit
            ? $teacherLoad->loadSubjects->pluck('subject_id')->toArray()
            : []
    );

    $selectedTerms = collect(old(
        'terms',
        $isEdit ? $teacherLoad->termNumbers() : [1, 2, 3]
    ))->map(fn ($termNo) => (int) $termNo)->values()->all();

    $oldSchedules = old(
        'schedules',
        $isEdit && $teacherLoad->schedules->count()
            ? $teacherLoad->schedules->map(function ($schedule) {
                return [
                    'day_of_week' => $schedule->day_of_week,
                    'time_start' => \Carbon\Carbon::parse($schedule->time_start)->format('H:i'),
                    'time_end' => \Carbon\Carbon::parse($schedule->time_end)->format('H:i'),
                    'room' => $schedule->room,
                ];
            })->toArray()
            : [
                ['day_of_week' => '', 'time_start' => '', 'time_end' => '', 'room' => '']
            ]
    );

    $dayOptions = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];
@endphp

<div class="form-grid">
    <div>
        <label for="school_year_id">School Year</label>
        <select name="school_year_id" id="school_year_id" class="form-input" required>
            <option value="">Select School Year</option>
            @foreach($schoolYears as $schoolYear)
                <option value="{{ $schoolYear->id }}"
                    {{ (string) old('school_year_id', $teacherLoad->school_year_id ?? optional($activeSchoolYear)->id) === (string) $schoolYear->id ? 'selected' : '' }}>
                    {{ $schoolYear->name }}
                </option>
            @endforeach
        </select>
        @error('school_year_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="teacher_id">Teacher</label>
        <select name="teacher_id" id="teacher_id" class="form-input" required>
            <option value="">Select Teacher</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}"
                    {{ (string) old('teacher_id', $teacherLoad->teacher_id ?? '') === (string) $teacher->id ? 'selected' : '' }}>
                    {{ $teacher->employee?->last_name ?? $teacher->last_name }},
                    {{ $teacher->employee?->first_name ?? $teacher->first_name }}
                </option>
            @endforeach
        </select>
        @error('teacher_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="section_id">Section</label>
        <select name="section_id" id="section_id" class="form-input" required>
            <option value="">Select Section</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}"
                    {{ (string) old('section_id', $teacherLoad->section_id ?? '') === (string) $section->id ? 'selected' : '' }}>
                    {{ $section->name }}
                </option>
            @endforeach
        </select>
        @error('section_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="is_active">Status</label>
        <select name="is_active" id="is_active" class="form-input" required>
            <option value="1" {{ (string) old('is_active', $teacherLoad->is_active ?? 1) === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ (string) old('is_active', $teacherLoad->is_active ?? 1) === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('is_active')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div style="margin-top: 18px;">
    <label for="subject_ids">Subjects</label>
    <select name="subject_ids[]" id="subject_ids" class="form-input" multiple size="8" required>
        @foreach($subjects as $subject)
            <option value="{{ $subject->id }}"
                {{ in_array($subject->id, $selectedSubjectIds) ? 'selected' : '' }}>
                {{ $subject->code }} - {{ $subject->name }}
            </option>
        @endforeach
    </select>
    <small>Select one or more subjects. For MAPEH, select all 4 component subjects.</small>
    @error('subject_ids')
        <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('subject_ids.*')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div style="margin-top: 18px;">
    <label>Term Coverage</label>
    <div style="display:flex; gap:14px; align-items:center; flex-wrap:wrap;">
        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" id="whole_school_year" {{ empty(array_diff([1, 2, 3], $selectedTerms)) ? 'checked' : '' }}>
            Whole School Year
        </label>

        @foreach([1, 2, 3] as $termNo)
            <label style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox"
                       name="terms[]"
                       value="{{ $termNo }}"
                       class="term-checkbox"
                       {{ in_array($termNo, $selectedTerms, true) ? 'checked' : '' }}>
                Term {{ $termNo }}
            </label>
        @endforeach
    </div>
    @error('terms')
        <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('terms.*')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div style="margin-top: 18px;">
    <label style="display:flex; align-items:center; gap:8px;">
        <input type="checkbox" name="is_multi_grade" value="1"
            {{ old('is_multi_grade', $teacherLoad->is_multi_grade ?? 0) ? 'checked' : '' }}>
        Multi-grade load
    </label>
    @error('is_multi_grade')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div style="margin-top: 24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
        <label style="margin:0;">Schedules</label>
        <button type="button" class="btn btn-secondary" onclick="addScheduleRow()">+ Add Schedule</button>
    </div>

    @error('schedules')
        <div class="text-danger" style="margin-bottom:10px;">{{ $message }}</div>
    @enderror

    <div id="scheduleRows">
        @foreach($oldSchedules as $i => $schedule)
            <div class="schedule-row" style="display:grid; grid-template-columns: 1.2fr 1fr 1fr 1fr auto; gap:10px; margin-bottom:10px;">
                <div>
                    <select name="schedules[{{ $i }}][day_of_week]" class="form-input" required>
                        <option value="">Select Day</option>
                        @foreach($dayOptions as $value => $label)
                            <option value="{{ $value }}"
                                {{ (string) ($schedule['day_of_week'] ?? '') === (string) $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error("schedules.$i.day_of_week")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <input type="time"
                           name="schedules[{{ $i }}][time_start]"
                           class="form-input"
                           value="{{ $schedule['time_start'] ?? '' }}"
                           required>
                    @error("schedules.$i.time_start")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <input type="time"
                           name="schedules[{{ $i }}][time_end]"
                           class="form-input"
                           value="{{ $schedule['time_end'] ?? '' }}"
                           required>
                    @error("schedules.$i.time_end")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <input type="text"
                           name="schedules[{{ $i }}][room]"
                           class="form-input"
                           placeholder="Room"
                           value="{{ $schedule['room'] ?? '' }}">
                    @error("schedules.$i.room")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:flex; align-items:center;">
                    <button type="button" class="btn btn-danger" onclick="removeScheduleRow(this)">Remove</button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div style="margin-top: 18px;">
    <label for="remarks">Remarks</label>
    <input type="text"
           name="remarks"
           id="remarks"
           class="form-input"
           value="{{ old('remarks', $teacherLoad->remarks ?? '') }}"
           maxlength="255">
    @error('remarks')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div style="display:flex; gap:10px; margin-top:16px;">
    <a href="{{ route('admin.teacher_loads.index') }}" class="btn btn-ghost">Cancel</a>
    <button type="submit" class="btn btn-primary">
        {{ isset($teacherLoad) ? 'Update Teacher Load' : 'Save Teacher Load' }}
    </button>
</div>
<script>
    let scheduleIndex = {{ count($oldSchedules) }};

    function addScheduleRow() {
        const container = document.getElementById('scheduleRows');

        const row = document.createElement('div');
        row.className = 'schedule-row';
        row.style.display = 'grid';
        row.style.gridTemplateColumns = '1.2fr 1fr 1fr 1fr auto';
        row.style.gap = '10px';
        row.style.marginBottom = '10px';

        row.innerHTML = `
            <div>
                <select name="schedules[${scheduleIndex}][day_of_week]" class="form-input" required>
                    <option value="">Select Day</option>
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                    <option value="6">Saturday</option>
                </select>
            </div>

            <div>
                <input type="time" name="schedules[${scheduleIndex}][time_start]" class="form-input" required>
            </div>

            <div>
                <input type="time" name="schedules[${scheduleIndex}][time_end]" class="form-input" required>
            </div>

            <div>
                <input type="text" name="schedules[${scheduleIndex}][room]" class="form-input" placeholder="Room">
            </div>

            <div style="display:flex; align-items:center;">
                <button type="button" class="btn btn-danger" onclick="removeScheduleRow(this)">Remove</button>
            </div>
        `;

        container.appendChild(row);
        scheduleIndex++;
    }

    function removeScheduleRow(button) {
        const rows = document.querySelectorAll('#scheduleRows .schedule-row');
        if (rows.length <= 1) {
            return;
        }
        button.closest('.schedule-row').remove();
    }

    const wholeSchoolYear = document.getElementById('whole_school_year');
    const termCheckboxes = document.querySelectorAll('.term-checkbox');

    function refreshWholeSchoolYear() {
        wholeSchoolYear.checked = Array.from(termCheckboxes).every((checkbox) => checkbox.checked);
    }

    wholeSchoolYear.addEventListener('change', function () {
        termCheckboxes.forEach((checkbox) => {
            checkbox.checked = wholeSchoolYear.checked;
        });
    });

    termCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', refreshWholeSchoolYear);
    });
</script>
