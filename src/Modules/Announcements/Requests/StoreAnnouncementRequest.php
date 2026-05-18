<?php

namespace Modules\Announcements\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for announcement creation.
 *
 * Module: Announcements
 * Layer: Request
 */
class StoreAnnouncementRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to perform this request.
     *
     * Authorization currently relies on route middleware and existing RBAC.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validation rules for announcement creation.
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