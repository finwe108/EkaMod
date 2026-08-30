@extends('layouts.app')

@section('title', 'New Document Request | MMCI')
@section('page_title', 'New Document Request')

@section('content')

@if($errors->any())
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body">
            <strong>Please correct the following:</strong>

            <ul style="margin-top:8px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">New Document Request</div>
            <div class="card-subtitle">
                Record a document request submitted to the Registrar.
            </div>
        </div>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('admin.document-requests.store') }}">
            @csrf

            {{-- ============================================================
                 STUDENT RECORD TYPE
                 ============================================================ --}}
            <div class="form-group">
                <label class="form-label">
                    Student Record Type
                </label>

                <div style="display:flex;flex-wrap:wrap;gap:20px;">

                    <label style="display:flex;align-items:center;gap:8px;">
                        <input
                            type="radio"
                            name="student_record_type"
                            value="existing"
                            data-student-record-type
                            {{
                                old(
                                    'student_record_type',
                                    'existing'
                                ) === 'existing'
                                    ? 'checked'
                                    : ''
                            }}
                        >

                        <span>
                            Existing Student
                        </span>
                    </label>

                    <label style="display:flex;align-items:center;gap:8px;">
                        <input
                            type="radio"
                            name="student_record_type"
                            value="legacy"
                            data-student-record-type
                            {{
                                old('student_record_type') === 'legacy'
                                    ? 'checked'
                                    : ''
                            }}
                        >

                        <span>
                            Pre-System Student
                        </span>
                    </label>

                </div>

                <small class="text-muted">
                    Select Pre-System Student if the student studied
                    before this system was implemented and does not
                    have a record in the current system.
                </small>
            </div>

            {{-- ============================================================
                 EXISTING STUDENT
                 ============================================================ --}}
            <div
                id="existing-student-fields"
                style="margin-top:16px;"
            >
                <div class="form-group">
                    <label class="form-label">
                        Student
                    </label>

                    <select
                        name="student_id"
                        id="student_id"
                        class="form-input"
                    >
                        <option value="">
                            Select student
                        </option>

                        @foreach($students as $student)
                            <option
                                value="{{ $student->id }}"
                                {{
                                    old('student_id') == $student->id
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                {{ $student->formal_name }}
                                — {{ $student->student_id }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ============================================================
                 PRE-SYSTEM / LEGACY STUDENT
                 ============================================================ --}}
            <div
                id="legacy-student-fields"
                style="margin-top:16px;"
            >
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div>
                            <div class="card-title">
                                Pre-System Student Information
                            </div>

                            <div class="card-subtitle">
                                Enter the student's information as
                                available in the old school records.
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        First Name
                                    </label>

                                    <input
                                        type="text"
                                        name="legacy_first_name"
                                        id="legacy_first_name"
                                        class="form-input"
                                        value="{{ old('legacy_first_name') }}"
                                        maxlength="100"
                                    >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        Middle Name
                                        <span class="text-muted">
                                            (Optional)
                                        </span>
                                    </label>

                                    <input
                                        type="text"
                                        name="legacy_middle_name"
                                        id="legacy_middle_name"
                                        class="form-input"
                                        value="{{ old('legacy_middle_name') }}"
                                        maxlength="100"
                                    >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        Surname
                                    </label>

                                    <input
                                        type="text"
                                        name="legacy_last_name"
                                        id="legacy_last_name"
                                        class="form-input"
                                        value="{{ old('legacy_last_name') }}"
                                        maxlength="100"
                                    >
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        Birthdate
                                    </label>

                                    <input
                                        type="date"
                                        name="legacy_birth_date"
                                        id="legacy_birth_date"
                                        class="form-input"
                                        value="{{ old('legacy_birth_date') }}"
                                    >
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- ============================================================
                 REQUESTER INFORMATION
                 ============================================================ --}}
            <div class="card" style="margin-bottom:16px;">
                <div class="card-header">
                    <div>
                        <div class="card-title">
                            Requester Information
                        </div>

                        <div class="card-subtitle">
                            Information about the person requesting
                            the documents.
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    Requested By
                                </label>

                                <input
                                    type="text"
                                    name="requested_by_name"
                                    class="form-input"
                                    value="{{ old('requested_by_name') }}"
                                    required
                                >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    Contact Information
                                </label>

                                <input
                                    type="text"
                                    name="requested_by_contact"
                                    class="form-input"
                                    value="{{ old('requested_by_contact') }}"
                                >
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Relationship to Student
                        </label>

                        <input
                            type="text"
                            name="requester_relationship"
                            class="form-input"
                            value="{{ old('requester_relationship') }}"
                            placeholder="Student, Parent, Guardian, Representative, etc."
                        >
                    </div>

                </div>
            </div>

            {{-- ============================================================
                 REQUEST DETAILS
                 ============================================================ --}}
            <div class="card" style="margin-bottom:16px;">
                <div class="card-header">
                    <div>
                        <div class="card-title">
                            Request Details
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label class="form-label">
                            Purpose
                        </label>

                        <input
                            type="text"
                            name="purpose"
                            class="form-input"
                            value="{{ old('purpose') }}"
                            placeholder="Transfer, employment, scholarship, personal use, etc."
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Documents Requested
                        </label>

                        <div
                            style="
                                display:flex;
                                flex-direction:column;
                                gap:8px;
                            "
                        >
                            @foreach($documentTypes as $documentType)
                                <label
                                    style="
                                        display:flex;
                                        align-items:center;
                                        gap:8px;
                                    "
                                >
                                    <input
                                        type="checkbox"
                                        name="document_type_ids[]"
                                        value="{{ $documentType->id }}"
                                        {{
                                            in_array(
                                                $documentType->id,
                                                old(
                                                    'document_type_ids',
                                                    []
                                                )
                                            )
                                                ? 'checked'
                                                : ''
                                        }}
                                    >

                                    <span>
                                        {{ $documentType->name }}

                                        @if($documentType->description)
                                            <small class="text-muted">
                                                — {{ $documentType->description }}
                                            </small>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="form-input"
                            rows="4"
                        >{{ old('remarks') }}</textarea>
                    </div>

                </div>
            </div>

            <div
                style="
                    display:flex;
                    gap:10px;
                    margin-top:16px;
                "
            >
                <a
                    href="{{ route('admin.document-requests.index') }}"
                    class="btn btn-ghost"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Document Request
                </button>
            </div>

        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const recordTypeInputs = document.querySelectorAll(
        '[data-student-record-type]'
    );

    const existingFields = document.getElementById(
        'existing-student-fields'
    );

    const legacyFields = document.getElementById(
        'legacy-student-fields'
    );

    const existingStudentSelect = document.getElementById(
        'student_id'
    );

    const legacyFirstName = document.getElementById(
        'legacy_first_name'
    );

    const legacyLastName = document.getElementById(
        'legacy_last_name'
    );

    const legacyBirthDate = document.getElementById(
        'legacy_birth_date'
    );

    function updateStudentRecordFields() {
        const selectedInput = document.querySelector(
            '[data-student-record-type]:checked'
        );

        const recordType = selectedInput
            ? selectedInput.value
            : 'existing';

        const isExisting = recordType === 'existing';

        existingFields.style.display = isExisting
            ? ''
            : 'none';

        legacyFields.style.display = isExisting
            ? 'none'
            : '';

        /*
         * Match browser-level required validation with the
         * Laravel conditional validation rules.
         */
        existingStudentSelect.required = isExisting;

        legacyFirstName.required = !isExisting;
        legacyLastName.required = !isExisting;
        legacyBirthDate.required = !isExisting;
    }

    recordTypeInputs.forEach(function (input) {
        input.addEventListener(
            'change',
            updateStudentRecordFields
        );
    });

    updateStudentRecordFields();
});
</script>

@endsection
