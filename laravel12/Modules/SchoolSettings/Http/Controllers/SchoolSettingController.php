<?php

namespace Modules\SchoolSettings\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\SchoolSettings\Requests\UpdateSchoolSettingRequest;
use Modules\SchoolSettings\Services\SchoolSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Handles administrative school setting operations.
 *
 * This controller is part of the School Settings module and preserves
 * the existing edit and update behavior from the original monolith
 * implementation.
 *
 * Existing route names, URLs, middleware, validation rules, views,
 * redirects, and model interactions must remain backward-compatible
 * during this migration phase.
 *
 * Module: SchoolSettings
 * Layer: HTTP Controller
 */
class SchoolSettingController extends Controller
{
    /**
     * Display the school settings edit form.
     *
     * Retrieves the current school setting record using the existing
     * SchoolSetting::current() business rule.
     *
     * The current() method is intentionally preserved because the
     * application assumes there is a single active school configuration
     * record throughout the system.
     *
     * @return View
     */
    public function edit(SchoolSettingService $schoolSettingService): View
    {
        $schoolSetting = $schoolSettingService->current();

        return view('school_settings::edit', compact('schoolSetting'));
    }

    /**
     * Update the current school settings.
     *
     * Uses the existing UpdateSchoolSettingRequest validation logic to
     * preserve all existing validation rules and authorization behavior.
     *
     * The update process intentionally modifies the current active school
     * setting record instead of creating a new one to preserve existing
     * system assumptions and integration behavior.
     *
     * @param UpdateSchoolSettingRequest $request
     * @return RedirectResponse
     */
    public function update(
        UpdateSchoolSettingRequest $request,
        SchoolSettingService $schoolSettingService
    ): RedirectResponse {
        $data = $request->validated();

        /*
        * Uploaded files must never be passed directly to Eloquent update().
        * Depending on the form/request, the file may appear as logo or logo_path.
        */
        unset($data['logo'], $data['logo_path']);

        $schoolSettingService->updateCurrent(
            $data,
            $request->file('logo') ?: $request->file('logo_path')
        );

        return redirect()
            ->route('admin.school-settings.edit')
            ->with('success', 'School settings updated successfully.');
    }
}