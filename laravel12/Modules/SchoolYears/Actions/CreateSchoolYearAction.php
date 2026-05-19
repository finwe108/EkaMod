<?php

namespace Modules\SchoolYears\Actions;

use App\Models\SchoolYear;

/**
 * Handles creation of school year records.
 *
 * Module: SchoolYears
 * Layer: Action
 */
class CreateSchoolYearAction
{
    /**
     * Create a new school year.
     *
     * @param array<string, mixed> $data
     * @return SchoolYear
     */
    public function execute(array $data): SchoolYear
    {
        return SchoolYear::create($data);
    }
}