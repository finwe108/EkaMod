@extends('layouts.landing')

@section('title', 'Student Admission Form | ' . config('school.name'))

@section('body_class', 'admission-page')

@section('content')

<section class="form-hero">
    <div class="container form-hero__inner">
        <div>
            <p class="eyebrow">Admissions</p>
            <h1>Student Admission Form</h1>
            <p>Complete the form to apply for enrollment. The registrar will review your application before account creation.</p>
        </div>
        <img src="{{ asset('assets/images/mmci-logo.png') }}" alt="{{ config('school.short') }} logo">
    </div>
</section>

<section class="section">
    <div class="container admission-layout">

        <aside class="admission-note">
            <h2>Requirements</h2>

            <div class="requirements-panel">
                <div class="requirement-group">
                    <h3>New Students</h3>
                    <ul class="requirement-list">
                        <li>PSA Birth Certificate</li>
                        <li>2x2 ID Picture</li>
                        <li>Parent/Guardian valid contact number</li>
                    </ul>
                </div>

                <div class="requirement-group">
                    <h3>Transferees / Returning</h3>
                    <ul class="requirement-list">
                        <li>Report Card / SF9</li>
                        <li>Good Moral Certificate</li>
                        <li>PSA Birth Certificate</li>
                        <li>LRN, if available</li>
                    </ul>
                </div>

                <p class="requirement-note">
                    After submission, please wait for registrar review and approval.
                </p>
            </div>
        </aside>

        <div class="sf1-form">

            @if ($errors->any())
                <div class="alert alert--error" role="alert" style="margin-bottom: 18px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('public.admission.store') }}">
                @csrf

                <input type="hidden" name="school_year_id" value="{{ old('school_year_id', optional($activeSchoolYear)->id) }}">

                <fieldset>
                    <legend>Application Details</legend>

                    <div class="form-grid">
                        <div class="form-field">
                            <label for="grade_level_id">Grade Level Applying For</label>
                            <select id="grade_level_id" name="grade_level_id" required>
                                <option value="">Select grade level</option>
                                @foreach($gradeLevels as $gradeLevel)
                                    <option value="{{ $gradeLevel->id }}" @selected(old('grade_level_id') == $gradeLevel->id)>
                                        {{ $gradeLevel->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-field">
                            <label for="student_type">Student Type</label>
                            <select id="student_type" name="student_type" required>
                                <option value="new" @selected(old('student_type', 'new') === 'new')>New</option>
                                <option value="transferee" @selected(old('student_type') === 'transferee')>Transferee</option>
                                <option value="returning" @selected(old('student_type') === 'returning')>Returning</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label for="last_school_attended">Last School Attended</label>
                            <input id="last_school_attended" type="text" name="last_school_attended" value="{{ old('last_school_attended') }}">
                        </div>

                        <div class="form-field">
                            <label for="last_grade_level_completed">Last Grade Level Completed</label>
                            <input id="last_grade_level_completed" type="text" name="last_grade_level_completed" value="{{ old('last_grade_level_completed') }}">
                        </div>

                        <div class="form-field form-field--wide">
                            <label for="strand_or_track">Strand / Track / Program Interest</label>
                            <select id="strand_or_track" name="strand_or_track">
                                <option value="">Select Program</option>

                                @php
                                    $programs = ['Preschool', 'GS', 'JHS', 'STEM', 'HUMSS', 'BE', 'TEd'];
                                @endphp

                                @foreach($programs as $program)
                                    <option value="{{ $program }}"
                                        @selected(old('strand_or_track', request('program')) === $program)>
                                        {{ $program }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Student Information</legend>

                    <div class="form-grid">
                        <div class="form-field">
                            <label for="first_name">First Name</label>
                            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required>
                        </div>

                        <div class="form-field">
                            <label for="middle_name">Middle Name</label>
                            <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}">
                        </div>

                        <div class="form-field">
                            <label for="last_name">Last Name</label>
                            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required>
                        </div>

                        <div class="form-field">
                            <label for="suffix">Suffix</label>
                            <input id="suffix" type="text" name="suffix" value="{{ old('suffix') }}" placeholder="Jr., Sr., III">
                        </div>

                        <div class="form-field">
                            <label for="sex">Sex</label>
                            <select id="sex" name="sex" required>
                                <option value="">Select</option>
                                <option value="Male" @selected(old('sex') === 'Male')>Male</option>
                                <option value="Female" @selected(old('sex') === 'Female')>Female</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label for="birth_date">Birth Date</label>
                            <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date') }}" required>
                        </div>

                        <div class="form-field">
                            <label for="birth_place">Birth Place</label>
                            <input id="birth_place" type="text" name="birth_place" value="{{ old('birth_place') }}">
                        </div>

                        <div class="form-field">
                            <label for="lrn">LRN</label>
                            <input id="lrn" type="text" name="lrn" value="{{ old('lrn') }}">
                        </div>

                        <div class="form-field">
                            <label for="mother_tongue">Mother Tongue</label>
                            <input id="mother_tongue" type="text" name="mother_tongue" value="{{ old('mother_tongue') }}">
                        </div>

                        <div class="form-field">
                            <label for="religion">Religion</label>
                            <input id="religion" type="text" name="religion" value="{{ old('religion') }}">
                        </div>

                        <div class="form-field">
                            <label>
                                <input type="hidden" name="is_ip" value="0">
                                <input type="checkbox" id="is_ip" name="is_ip" value="1" @checked(old('is_ip'))>
                                Member of IP Community
                            </label>
                        </div>

                        <div class="form-field">
                            <label for="ethnic_group">Ethnic Group</label>
                            <input
                                id="ethnic_group"
                                type="text"
                                name="ethnic_group"
                                value="{{ old('ethnic_group') }}"
                                {{ old('is_ip') ? '' : 'disabled' }}>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Contact and Address</legend>

                    <div class="form-grid">
                        <div class="form-field">
                            <label for="email">Email Address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-field">
                            <label for="contact_number">Contact Number</label>
                            <input id="contact_number" type="text" name="contact_number" value="{{ old('contact_number') }}" required>
                        </div>

                        <div class="form-field">
                            <label for="house_street">House No. / Street / Sitio</label>
                            <input id="house_street" type="text" name="house_street" value="{{ old('house_street') }}">
                        </div>

                        <div class="form-field">
                            <label for="barangay">Barangay</label>
                            <input id="barangay" type="text" name="barangay" value="{{ old('barangay') }}">
                        </div>

                        <div class="form-field">
                            <label for="municipality_city">Municipality / City</label>
                            <input id="municipality_city" type="text" name="municipality_city" value="{{ old('municipality_city') }}">
                        </div>

                        <div class="form-field">
                            <label for="province">Province</label>
                            <input id="province" type="text" name="province" value="{{ old('province') }}">
                        </div>

                        <div class="form-field form-field--wide">
                            <label for="address">Complete Address</label>
                            <input id="address" type="text" name="address" value="{{ old('address') }}">
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Parents / Guardian</legend>

                    <div class="form-grid">
                        <div class="form-field">
                            <label for="father_name">Father Name</label>
                            <input id="father_name" type="text" name="father_name" value="{{ old('father_name') }}">
                        </div>

                        <div class="form-field">
                            <label for="father_contact">Father Contact</label>
                            <input id="father_contact" type="text" name="father_contact" value="{{ old('father_contact') }}">
                        </div>

                        <div class="form-field">
                            <label for="mother_name">Mother Name</label>
                            <input id="mother_name" type="text" name="mother_name" value="{{ old('mother_name') }}">
                        </div>

                        <div class="form-field">
                            <label for="mother_contact">Mother Contact</label>
                            <input id="mother_contact" type="text" name="mother_contact" value="{{ old('mother_contact') }}">
                        </div>

                        <div class="form-field form-field--wide">
                            <label>
                                <input type="checkbox" id="guardian_same_as_father">
                                Guardian is the father
                            </label>

                            <label>
                                <input type="checkbox" id="guardian_same_as_mother">
                                Guardian is the mother
                            </label>
                        </div>
                        <div class="form-field">
                            <label for="guardian_name">Guardian Name</label>
                            <input id="guardian_name" type="text" name="guardian_name" value="{{ old('guardian_name') }}" required>
                        </div>

                        <div class="form-field">
                            <label for="guardian_relationship">Guardian Relationship</label>
                            <input id="guardian_relationship" type="text" name="guardian_relationship" value="{{ old('guardian_relationship') }}">
                        </div>

                        <div class="form-field">
                            <label for="guardian_contact">Guardian Contact</label>
                            <input id="guardian_contact" type="text" name="guardian_contact" value="{{ old('guardian_contact') }}" required>
                        </div>

                        <div class="form-field">
                            <label for="parent_guardian_contact">Parent/Guardian Contact</label>
                            <input id="parent_guardian_contact" type="text" name="parent_guardian_contact" value="{{ old('parent_guardian_contact') }}">
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Remarks</legend>

                    <div class="form-grid">
                        <div class="form-field form-field--wide">
                            <label for="remarks">Additional Notes</label>
                            <textarea id="remarks" name="remarks" rows="4">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                </fieldset>

                <div class="form-actions">
                    <button class="button button--primary" type="submit">
                        Submit Application
                    </button>

                    <a class="button button--outline" href="{{ route('public.home') }}">
                        Back to Home
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fatherCheck = document.getElementById('guardian_same_as_father');
    const motherCheck = document.getElementById('guardian_same_as_mother');

    const fatherName = document.getElementById('father_name');
    const fatherContact = document.getElementById('father_contact');

    const motherName = document.getElementById('mother_name');
    const motherContact = document.getElementById('mother_contact');

    const guardianName = document.getElementById('guardian_name');
    const guardianContact = document.getElementById('guardian_contact');
    const guardianRelationship = document.getElementById('guardian_relationship');

    function useFatherAsGuardian() {
        motherCheck.checked = false;

        guardianName.value = fatherName.value;
        guardianContact.value = fatherContact.value;
        guardianRelationship.value = 'Father';
    }

    function useMotherAsGuardian() {
        fatherCheck.checked = false;

        guardianName.value = motherName.value;
        guardianContact.value = motherContact.value;
        guardianRelationship.value = 'Mother';
    }

    fatherCheck.addEventListener('change', function () {
        if (this.checked) {
            useFatherAsGuardian();
        }
    });

    motherCheck.addEventListener('change', function () {
        if (this.checked) {
            useMotherAsGuardian();
        }
    });

    fatherName.addEventListener('input', function () {
        if (fatherCheck.checked) useFatherAsGuardian();
    });

    fatherContact.addEventListener('input', function () {
        if (fatherCheck.checked) useFatherAsGuardian();
    });

    motherName.addEventListener('input', function () {
        if (motherCheck.checked) useMotherAsGuardian();
    });

    motherContact.addEventListener('input', function () {
        if (motherCheck.checked) useMotherAsGuardian();
    });
});
</script>
@endpush