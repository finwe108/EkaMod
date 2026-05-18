@php
    $student = $student ?? null;
@endphp

@if(!$student)
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-body">
            <h3 style="margin-top:0;">Enrollment Information</h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="school_year_id">
                        School Year <span class="text-danger">*</span>
                    </label>

                    <select name="school_year_id" id="school_year_id" class="form-input" required>
                        <option value="">Select School Year</option>

                        @foreach($schoolYears as $schoolYear)
                            <option value="{{ $schoolYear->id }}"
                                {{ (string) old('school_year_id', $selectedSchoolYearId ?? optional($activeSchoolYear ?? null)->id) === (string) $schoolYear->id ? 'selected' : '' }}>
                                {{ $schoolYear->name }}
                                @if(optional($activeSchoolYear ?? null)->id === $schoolYear->id)
                                    (Active)
                                @endif
                            </option>
                        @endforeach
                    </select>

                    <small class="text-muted">
                        Defaults to the active school year. Change this only for late or previous-year enrollment.
                    </small>

                    @error('school_year_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="grade_level_id">
                        Grade Level <span class="text-danger">*</span>
                    </label>

                    <select name="grade_level_id" id="grade_level_id" class="form-input" required>
                        <option value="">Select Grade Level</option>
                        @foreach($gradeLevels as $gradeLevel)
                            <option value="{{ $gradeLevel->id }}"
                                {{ (string) old('grade_level_id', $selectedGradeLevelId ?? '') === (string) $gradeLevel->id ? 'selected' : '' }}>
                                {{ $gradeLevel->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('grade_level_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="section_id">Section</label>

                    <select name="section_id" id="section_id" class="form-input">
                        <option value="">Select Section</option>

                        @foreach($sections as $section)
                            <option value="{{ $section->id }}"
                                {{ (string) old('section_id', $selectedSectionId ?? '') === (string) $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                                @if($section->gradeLevel)
                                    — {{ $section->gradeLevel->name }}
                                @endif
                            </option>
                        @endforeach
                    </select>

                    <small class="text-muted">
                        Sections will reload based on selected school year and grade level.
                    </small>

                    @error('section_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="enrollment_status">Enrollment Status</label>

                    <select name="enrollment_status" id="enrollment_status" class="form-input">
                        <option value="pending" {{ old('enrollment_status', 'pending') === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="enrolled" {{ old('enrollment_status') === 'enrolled' ? 'selected' : '' }}>
                            Enrolled
                        </option>
                    </select>

                    @error('enrollment_status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <h3 style="margin-top:0;">Student Information</h3>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Profile Picture</label>

                <div style="display:flex; gap:16px; align-items:center; flex-wrap:wrap;">
                    @if(!empty($student?->photo_path))
                        <img src="{{ asset('storage/' . $student->photo_path) }}"
                             alt="Student Photo"
                             style="width:120px; height:120px; object-fit:cover; border-radius:12px;">
                    @else
                        <div style="width:120px; height:120px; border-radius:12px; background:var(--bg3); color:var(--muted); display:flex; align-items:center; justify-content:center;">
                            No Photo
                        </div>
                    @endif

                    <div style="flex:1; min-width:220px;">
                        <input type="file" name="photo" class="form-input" accept="image/*">

                        <div style="font-size:11px;color:var(--muted);margin-top:6px;">
                            Accepted: JPG, PNG, WEBP. Max size: 2MB.
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="form-group">
                <label for="student_type">Type of Student <span class="text-danger">*</span></label>
                <select name="student_type" id="student_type" class="form-input" required>
                    <option value="new" {{ old('student_type', strtolower($student->student_type ?? 'new')) === 'new' ? 'selected' : '' }}>
                        New
                    </option>
                    <option value="old" {{ old('student_type', strtolower($student->student_type ?? '')) === 'old' ? 'selected' : '' }}>
                        Old
                    </option>
                    <option value="transferee" {{ old('student_type', strtolower($student->student_type ?? '')) === 'transferee' ? 'selected' : '' }}>
                        Transferee
                    </option>
                </select>
                @error('student_type') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="student_id">Student ID <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="student_id"
                    id="student_id"
                    class="form-input"
                    value="{{ old('student_id', $student->student_id ?? '') }}"
                >
                @error('student_id') <div class="text-danger">{{ $message }}</div> @enderror
                <small class="text-muted" id="student_id_help">
                    For new and transferee students, Student ID is generated automatically.
                </small>
            </div>

            <div class="form-group">
                <label for="lrn">LRN</label>
                <input type="text" name="lrn" id="lrn" class="form-input"
                       value="{{ old('lrn', $student->lrn ?? '') }}">
                @error('lrn') <div class="text-danger">{{ $message }}</div> @enderror
                <small class="text-muted">Leave blank for Preschool 1 and Preschool 2 if no LRN yet.</small>
            </div>

            <div class="form-group">
                <label for="status">Student Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-input" required>
                    <option value="active" {{ old('status', strtolower($student->status ?? 'active')) === 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="inactive" {{ old('status', strtolower($student->status ?? 'active')) === 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
                @error('status') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="first_name">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" id="first_name" class="form-input"
                       value="{{ old('first_name', $student->first_name ?? '') }}" required>
                @error('first_name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" class="form-input"
                       value="{{ old('middle_name', $student->middle_name ?? '') }}">
                @error('middle_name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" id="last_name" class="form-input"
                       value="{{ old('last_name', $student->last_name ?? '') }}" required>
                @error('last_name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="suffix">Suffix</label>
                <input type="text" name="suffix" id="suffix" class="form-input"
                       value="{{ old('suffix', $student->suffix ?? '') }}">
                @error('suffix') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="sex">Sex</label>
                <select name="sex" id="sex" class="form-input">
                    <option value="">Select Sex</option>
                    <option value="male" {{ old('sex', strtolower($student->sex ?? '')) === 'male' ? 'selected' : '' }}>
                        Male
                    </option>
                    <option value="female" {{ old('sex', strtolower($student->sex ?? '')) === 'female' ? 'selected' : '' }}>
                        Female
                    </option>
                </select>
                @error('sex') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="birth_date">Birth Date</label>
                <input type="date" name="birth_date" id="birth_date" class="form-input"
                       value="{{ old('birth_date', optional($student?->birth_date)->format('Y-m-d')) }}">
                @error('birth_date') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="birth_place">Birth Place</label>
                <input type="text" name="birth_place" id="birth_place" class="form-input"
                       value="{{ old('birth_place', $student->birth_place ?? '') }}">
                @error('birth_place') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="religion">Religion</label>
                <input type="text" name="religion" id="religion" class="form-input"
                       value="{{ old('religion', $student->religion ?? '') }}">
                @error('religion') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="mother_tongue">Mother Tongue</label>
                <input type="text" name="mother_tongue" id="mother_tongue" class="form-input"
                       value="{{ old('mother_tongue', $student->mother_tongue ?? '') }}">
                @error('mother_tongue') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="display:flex; align-items:end;">
                <label style="display:flex; gap:.5rem; align-items:center; cursor:pointer;">
                    <input type="checkbox" name="is_ip" id="is_ip" value="1"
                           {{ old('is_ip', $student->is_ip ?? false) ? 'checked' : '' }}>
                    Indigenous Peoples (IP)
                </label>
            </div>

            <div class="form-group">
                <label for="ethnic_group">Ethnic Group</label>
                <input type="text" name="ethnic_group" id="ethnic_group" class="form-input"
                       value="{{ old('ethnic_group', $student->ethnic_group ?? '') }}">
                @error('ethnic_group') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top:1rem;">
    <div class="card-body">
        <h3 style="margin-top:0;">Contact Information</h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-input"
                       value="{{ old('email', $student->email ?? '') }}">
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="form-input"
                       value="{{ old('contact_number', $student->contact_number ?? '') }}">
                @error('contact_number') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="province">Province</label>
                <input type="text" name="province" id="province" class="form-input"
                       value="{{ old('province', $student->province ?? '') }}">
                @error('province') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="house_street">House No. | Street</label>
                <input type="text" name="house_street" id="house_street" class="form-input"
                       value="{{ old('house_street', $student->house_street ?? '') }}">
                @error('house_street') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="barangay">Barangay</label>
                <input type="text" name="barangay" id="barangay" class="form-input"
                       value="{{ old('barangay', $student->barangay ?? '') }}">
                @error('barangay') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="municipality_city">Municipality / City</label>
                <input type="text" name="municipality_city" id="municipality_city" class="form-input"
                       value="{{ old('municipality_city', $student->municipality_city ?? '') }}">
                @error('municipality_city') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top:1rem;">
    <div class="card-body">
        <h3 style="margin-top:0;">Parent / Guardian Information</h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="father_name">Father's Name</label>
                <input type="text" name="father_name" id="father_name" class="form-input"
                       value="{{ old('father_name', $student->father_name ?? '') }}">
                @error('father_name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="father_contact">Father's Contact Number</label>
                <input type="text" name="father_contact" id="father_contact" class="form-input"
                       value="{{ old('father_contact', $student->father_contact ?? '') }}">
                @error('father_contact') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="mother_name">Mother's Name</label>
                <input type="text" name="mother_name" id="mother_name" class="form-input"
                       value="{{ old('mother_name', $student->mother_name ?? '') }}">
                @error('mother_name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="mother_contact">Mother's Contact Number</label>
                <input type="text" name="mother_contact" id="mother_contact" class="form-input"
                       value="{{ old('mother_contact', $student->mother_contact ?? '') }}">
                @error('mother_contact') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>Guardian Information</label>
            <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                <label style="display:flex; align-items:center; gap:.5rem; cursor:pointer;">
                    <input type="radio" name="guardian_source" value="manual"
                           {{ old('guardian_source', 'manual') === 'manual' ? 'checked' : '' }}>
                    Manual Entry
                </label>

                <label style="display:flex; align-items:center; gap:.5rem; cursor:pointer;">
                    <input type="radio" name="guardian_source" value="father"
                           {{ old('guardian_source') === 'father' ? 'checked' : '' }}>
                    Same as Father
                </label>

                <label style="display:flex; align-items:center; gap:.5rem; cursor:pointer;">
                    <input type="radio" name="guardian_source" value="mother"
                           {{ old('guardian_source') === 'mother' ? 'checked' : '' }}>
                    Same as Mother
                </label>
            </div>
        </div>

        <div class="form-grid" style="margin-top:1rem;">
            <div class="form-group">
                <label for="guardian_name">Guardian's Name</label>
                <input type="text" name="guardian_name" id="guardian_name" class="form-input guardian-field"
                       value="{{ old('guardian_name', $student->guardian_name ?? '') }}">
                @error('guardian_name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="guardian_relationship">Guardian's Relationship</label>
                <input type="text" name="guardian_relationship" id="guardian_relationship" class="form-input guardian-field"
                       value="{{ old('guardian_relationship', $student->guardian_relationship ?? '') }}">
                @error('guardian_relationship') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="parent_guardian_contact">Guardian's Contact Number</label>
                <input type="text" name="parent_guardian_contact" id="parent_guardian_contact" class="form-input guardian-field"
                       value="{{ old('parent_guardian_contact', $student->parent_guardian_contact ?? '') }}">
                @error('parent_guardian_contact') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="remarks">Remarks</label>
                <textarea name="remarks" id="remarks" class="form-input">{{ old('remarks', $student->remarks ?? '') }}</textarea>
                @error('remarks') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.form-grid {
    display: grid;
    gap: 1.25rem;
    grid-template-columns: repeat(2, 1fr);
    width: 100%;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    min-width: 0;
}

.form-input {
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

@media (min-width: 1400px) {
    .form-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isIp = document.getElementById('is_ip');
    const ethnicGroup = document.getElementById('ethnic_group');

    function toggleEthnicGroup() {
        if (!isIp || !ethnicGroup) return;

        if (isIp.checked) {
            ethnicGroup.removeAttribute('disabled');
        } else {
            ethnicGroup.value = '';
            ethnicGroup.setAttribute('disabled', 'disabled');
        }
    }

    toggleEthnicGroup();
    isIp?.addEventListener('change', toggleEthnicGroup);
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fatherName = document.getElementById('father_name');
    const fatherContact = document.getElementById('father_contact');
    const motherName = document.getElementById('mother_name');
    const motherContact = document.getElementById('mother_contact');

    const guardianName = document.getElementById('guardian_name');
    const guardianRelationship = document.getElementById('guardian_relationship');
    const guardianContact = document.getElementById('parent_guardian_contact');

    const guardianSourceInputs = document.querySelectorAll('input[name="guardian_source"]');

    function getGuardianSource() {
        const selected = document.querySelector('input[name="guardian_source"]:checked');
        return selected ? selected.value : 'manual';
    }

    function setGuardianFieldsReadOnly(isReadOnly) {
        [guardianName, guardianRelationship, guardianContact].forEach(field => {
            if (!field) return;
            field.readOnly = isReadOnly;
        });
    }

    function fillGuardianFromSource() {
        const source = getGuardianSource();

        if (source === 'father') {
            if (guardianName) guardianName.value = fatherName?.value || '';
            if (guardianRelationship) guardianRelationship.value = 'Parent';
            if (guardianContact) guardianContact.value = fatherContact?.value || '';
            setGuardianFieldsReadOnly(true);
        } else if (source === 'mother') {
            if (guardianName) guardianName.value = motherName?.value || '';
            if (guardianRelationship) guardianRelationship.value = 'Parent';
            if (guardianContact) guardianContact.value = motherContact?.value || '';
            setGuardianFieldsReadOnly(true);
        } else {
            setGuardianFieldsReadOnly(false);
        }
    }

    guardianSourceInputs.forEach(input => {
        input.addEventListener('change', fillGuardianFromSource);
    });

    [fatherName, fatherContact, motherName, motherContact].forEach(field => {
        if (!field) return;
        field.addEventListener('input', fillGuardianFromSource);
    });

    fillGuardianFromSource();
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const studentType = document.getElementById('student_type');
    const studentId = document.getElementById('student_id');
    const helpText = document.getElementById('student_id_help');

    if (!studentType || !studentId) return;

    const isEditingExistingStudent = Boolean(studentId.value);

    function toggleStudentId() {
        const type = studentType.value;

        if (type === 'new' || type === 'transferee') {
            studentId.readOnly = true;
            studentId.removeAttribute('required');
            studentId.placeholder = 'Auto-generated';

            if (!isEditingExistingStudent) {
                studentId.value = '';
            }

            if (helpText) {
                helpText.textContent = 'For new and transferee students, Student ID will be generated automatically.';
            }
        } else {
            studentId.readOnly = false;
            studentId.setAttribute('required', 'required');
            studentId.placeholder = 'Enter existing Student ID';

            if (helpText) {
                helpText.textContent = 'Required for old students.';
            }
        }
    }

    studentType.addEventListener('change', toggleStudentId);
    toggleStudentId();
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const schoolYearSelect = document.getElementById('school_year_id');
    const gradeLevelSelect = document.getElementById('grade_level_id');
    const sectionSelect = document.getElementById('section_id');

    if (!schoolYearSelect || !gradeLevelSelect || !sectionSelect) {
        return;
    }

    const initialSelectedSectionId = @json(old('section_id', $selectedSectionId ?? ''));

    function setLoadingState() {
        sectionSelect.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'Loading sections...';
        sectionSelect.appendChild(option);
        sectionSelect.disabled = true;
    }

    function setEmptyState(message = 'No sections available') {
        sectionSelect.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = message;
        sectionSelect.appendChild(option);
        sectionSelect.disabled = false;
    }

    function reloadSections() {
        const schoolYearId = schoolYearSelect.value;
        const gradeLevelId = gradeLevelSelect.value;

        sectionSelect.innerHTML = '';

        if (!schoolYearId) {
            setEmptyState('Select school year first');
            return;
        }

        if (!gradeLevelId) {
            setEmptyState('Select grade level first');
            return;
        }

        setLoadingState();

        const url = new URL(@json(route('admin.ajax.sections-by-school-year')), window.location.origin);

        url.searchParams.set('school_year_id', schoolYearId);
        url.searchParams.set('grade_level_id', gradeLevelId);

        fetch(url.toString(), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Unable to load sections.');
                }

                return response.json();
            })
            .then(function (sections) {
                sectionSelect.innerHTML = '';

                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = sections.length ? 'Select Section' : 'No sections available';
                sectionSelect.appendChild(placeholder);

                sections.forEach(function (section) {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.grade_level_name
                        ? `${section.name} — ${section.grade_level_name}`
                        : section.name;

                    if (String(section.id) === String(initialSelectedSectionId)) {
                        option.selected = true;
                    }

                    sectionSelect.appendChild(option);
                });

                sectionSelect.disabled = false;
            })
            .catch(function () {
                setEmptyState('Unable to load sections');
            });
    }

    schoolYearSelect.addEventListener('change', reloadSections);
    gradeLevelSelect.addEventListener('change', reloadSections);

    /**
     * If the page loads with both school year and grade level already selected,
     * reload sections to ensure the list matches the selected filters.
     */
    if (schoolYearSelect.value && gradeLevelSelect.value) {
        reloadSections();
    }
});
</script>
@endpush