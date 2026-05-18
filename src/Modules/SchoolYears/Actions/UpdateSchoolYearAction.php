<?php

namespace Modules\SchoolYears\Actions;

use App\Models\SchoolYear;

/**
 * Handles updating school year records.
 *
 * Module: SchoolYears
 * Layer: Action
 */
class UpdateSchoolYearAction
{
    /**
     * Update an existing school year.
     *
     * @param SchoolYear $schoolYear
     * @param array<string, mixed> $data
     * @return SchoolYear
     */
    public function execute(SchoolYear $schoolYear, array $data): SchoolYear
    {
        $schoolYear->update($data);

        return $schoolYear;
    }
}