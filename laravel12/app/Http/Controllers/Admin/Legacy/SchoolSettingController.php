<?php

namespace App\Http\Controllers\Admin\Legacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSchoolSettingRequest;
use App\Models\SchoolSetting;

class SchoolSettingController extends Controller
{
    public function edit()
    {
        $schoolSetting = SchoolSetting::current();

        return view('admin.school_settings.edit', compact('schoolSetting'));
    }

    public function update(UpdateSchoolSettingRequest $request)
    {
        $schoolSetting = SchoolSetting::current();
        $schoolSetting->update($request->validated());

        return redirect()
            ->route('admin.school-settings.edit')
            ->with('success', 'School settings updated successfully.');
    }
}