<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'school_id' => $this->filled('school_id') ? trim((string) $this->school_id) : null,
            'region' => $this->filled('region') ? trim((string) $this->region) : null,
            'division' => $this->filled('division') ? trim((string) $this->division) : null,
            'district' => $this->filled('district') ? trim((string) $this->district) : null,
            'school_name' => $this->filled('school_name') ? trim((string) $this->school_name) : null,
            'school_head_name' => $this->filled('school_head_name') ? trim((string) $this->school_head_name) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'school_id' => ['nullable', 'string', 'max:50'],
            'region' => ['nullable', 'string', 'max:150'],
            'division' => ['nullable', 'string', 'max:150'],
            'district' => ['nullable', 'string', 'max:150'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'school_head_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}