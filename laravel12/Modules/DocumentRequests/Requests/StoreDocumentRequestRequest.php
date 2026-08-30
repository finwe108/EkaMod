<?php

namespace Modules\DocumentRequests\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for creating document requests.
 *
 * Supports both:
 * - Existing students already stored in the system.
 * - Pre-system students whose records predate the system.
 *
 * Module: DocumentRequests
 * Layer: Request
 */
class StoreDocumentRequestRequest extends FormRequest
{
    /**
     * Authorization is handled by existing admin middleware/RBAC.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validation rules for creating a document request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            /*
             * Student record type determines which student
             * identity fields are required.
             */
            'student_record_type' => [
                'required',
                'string',
                'in:existing,legacy',
            ],

            /*
             * Existing system student.
             *
             * A student ID is required only when the request
             * is for a student already encoded in the system.
             */
            'student_id' => [
                'nullable',
                'integer',
                'exists:students,id',
                'required_if:student_record_type,existing',
            ],

            /*
             * Pre-system / legacy student identity.
             *
             * Old students may not have a Student ID or LRN in
             * the current system. Their request identity is based
             * on the required historical identity fields below.
             */
            'legacy_first_name' => [
                'nullable',
                'string',
                'max:100',
                'required_if:student_record_type,legacy',
            ],

            'legacy_middle_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'legacy_last_name' => [
                'nullable',
                'string',
                'max:100',
                'required_if:student_record_type,legacy',
            ],

            'legacy_birth_date' => [
                'nullable',
                'date',
                'required_if:student_record_type,legacy',
            ],

            /*
             * Requester information.
             */
            'requested_by_name' => [
                'required',
                'string',
                'max:255',
            ],

            'requested_by_contact' => [
                'nullable',
                'string',
                'max:100',
            ],

            'requester_relationship' => [
                'nullable',
                'string',
                'max:50',
            ],

            'purpose' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            /*
             * At least one document must be requested.
             */
            'document_type_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'document_type_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:document_types,id',
            ],
        ];
    }

    /**
     * Customize validation messages for clearer Registrar feedback.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'student_record_type.required' =>
                'Please select whether this is an existing or pre-system student.',

            'student_record_type.in' =>
                'The selected student record type is invalid.',

            'student_id.required_if' =>
                'Please select the existing student from the system.',

            'legacy_first_name.required_if' =>
                'First name is required for a pre-system student.',

            'legacy_last_name.required_if' =>
                'Surname is required for a pre-system student.',

            'legacy_birth_date.required_if' =>
                'Birthdate is required for a pre-system student.',

            'document_type_ids.required' =>
                'Please select at least one document to request.',

            'document_type_ids.min' =>
                'Please select at least one document to request.',
        ];
    }
}