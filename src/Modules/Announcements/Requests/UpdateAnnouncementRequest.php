<?php

namespace Modules\Announcements\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for announcement updates.
 *
 * Module: Announcements
 * Layer: Request
 */
class UpdateAnnouncementRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to perform this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validation rules for announcement updates.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title'     => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
        ];
    }
}