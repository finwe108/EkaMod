<?php

namespace Modules\SchoolSettings\Services;

use App\Models\SchoolSetting;
use App\Support\Files\FileCleanup;
use Illuminate\Http\UploadedFile;
use Modules\SchoolSettings\Actions\UpdateCurrentSchoolSettingAction;

/**
 * Handles school setting persistence operations.
 *
 * This service centralizes the update behavior for the current school setting
 * record while preserving the existing SchoolSetting::current() business rule.
 *
 * Module: SchoolSettings
 * Layer: Service
 */
class SchoolSettingService
{
    /**
     * Get the current active school setting record.
     *
     * @return SchoolSetting
     */
    public function current(): SchoolSetting
    {
        return SchoolSetting::current();
    }

    /**
     * Update the current school settings.
     *
     * This method also handles logo upload because file handling is an
     * application workflow concern, not just a database update.
     *
     * @param array<string, mixed> $data
     * @param UploadedFile|null $logo
     * @return SchoolSetting
     */
    public function updateCurrent(array $data, ?UploadedFile $logo = null): SchoolSetting
    {
        $schoolSetting = $this->current();

        /*
         * Uploaded files must never be passed directly into Eloquent update().
         * logo_path is managed internally after the new file is stored.
         */
        unset($data['logo'], $data['logo_path']);

        if ($logo) {
            $data['logo_path'] = $this->storeLogo($logo, $schoolSetting);
        }

        $updatedSchoolSetting = app(UpdateCurrentSchoolSettingAction::class)
            ->execute($schoolSetting, $data);

        /*
         * School settings are shared globally through cached view data.
         * Clear only this cache key so the new logo/contact settings appear
         * on the next page load.
         */
        cache()->forget('school_setting_current');

        return $updatedSchoolSetting;
    }

    /**
     * Store a new school logo and delete the old logo when it exists.
     *
     * @param UploadedFile $logo
     * @param SchoolSetting $schoolSetting
     * @return string
     */
    protected function storeLogo(UploadedFile $logo, SchoolSetting $schoolSetting): string
    {
        /*
         * Delete the previous logo so old branding files do not accumulate
         * on the server when administrators replace the school logo.
         */
        FileCleanup::deletePublicFile($schoolSetting->logo_path);

        $filename = 'school_logo_' . time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();

        $uploadPath = public_path('uploads/school');

        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $logo->move($uploadPath, $filename);

        return 'uploads/school/' . $filename;
    }
}