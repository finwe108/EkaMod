<?php

namespace Modules\SchoolYears\Actions;

use App\Models\SchoolYear;

/**
 * Handles deletion of school year records.
 *
 * Module: SchoolYears
 * Layer: Action
 */
class DeleteSchoolYearAction
{
    /**
     * Delete a school year.
     *
     * @param SchoolYear $schoolYear
     * @return void
     */
    public function execute(SchoolYear $schoolYear): void
    {
        $schoolYear->delete();
    }
}