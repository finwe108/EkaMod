<?php

namespace Modules\DocumentRequirementRules\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for updating document requirement rules.
 *
 * Module: DocumentRequirementRules
 * Layer: Request
 */
class UpdateDocumentRequirementRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document_type_id' => ['required', 'exists:document_types,id'],
            'grade_level_id' => ['nullable', 'exists:grade_levels,id'],
            'student_type' => ['nullable', 'string', 'max:50'],
            'is_required' => ['nullable', 'boolean'],
            'require_if_no_existing_copy' => ['nullable', 'boolean'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}