<?php

namespace Modules\SchoolSettings\Requests;

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
            'short_name' => $this->filled('short_name') ? trim((string) $this->short_name) : null,
            'tagline' => $this->filled('tagline') ? trim((string) $this->tagline) : null,
            'phone' => $this->filled('phone') ? trim((string) $this->phone) : null,
            'email' => $this->filled('email') ? trim((string) $this->email) : null,
            'address' => $this->filled('address') ? trim((string) $this->address) : null,
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
            'short_name' => ['nullable', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}