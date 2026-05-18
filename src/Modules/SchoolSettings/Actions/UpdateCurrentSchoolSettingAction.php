<?php

namespace Modules\SchoolSettings\Actions;

use App\Models\SchoolSetting;

/**
 * Handles updating the current school settings record.
 *
 * Module: SchoolSettings
 * Layer: Action
 */
class UpdateCurrentSchoolSettingAction
{
    /**
     * Execute the current school setting update.
     *
     * Expected input must already be validated by UpdateSchoolSettingRequest.
     *
     * @param SchoolSetting $schoolSetting
     * @param array<string, mixed> $data
     * @return SchoolSetting
     */
    public function execute(SchoolSetting $schoolSetting, array $data): SchoolSetting
    {
        $schoolSetting->update($data);

        return $schoolSetting;
    }
}