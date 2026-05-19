<?php

namespace Modules\SchoolYears\Services;

use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;
use Modules\SchoolYears\Actions\CreateSchoolYearAction;
use Modules\SchoolYears\Actions\DeleteSchoolYearAction;
use Modules\SchoolYears\Actions\UpdateSchoolYearAction;

/**
 * Handles school year write workflows.
 *
 * This service preserves the important business rule that only one school
 * year may be active at a time.
 *
 * Module: SchoolYears
 * Layer: Service
 */
class SchoolYearService
{
    /**
     * Create a school year.
     *
     * If the new school year is active, all other school years are deactivated
     * first to preserve the single-active-school-year rule.
     *
     * @param array<string, mixed> $data
     * @return SchoolYear
     */
    public function create(array $data): SchoolYear
    {
        return DB::transaction(function () use ($data) {
            if (! empty($data['is_active'])) {
                SchoolYear::query()->update(['is_active' => false]);
            }

            return app(CreateSchoolYearAction::class)->execute($data);
        });
    }

    /**
     * Update a school year.
     *
     * If this school year is marked active, all other school years are
     * deactivated to preserve the single-active-school-year rule.
     *
     * @param SchoolYear $schoolYear
     * @param array<string, mixed> $data
     * @return SchoolYear
     */
    public function update(SchoolYear $schoolYear, array $data): SchoolYear
    {
        return DB::transaction(function () use ($schoolYear, $data) {
            if (! empty($data['is_active'])) {
                SchoolYear::where('id', '!=', $schoolYear->id)
                    ->update(['is_active' => false]);
            }

            return app(UpdateSchoolYearAction::class)
                ->execute($schoolYear, $data);
        });
    }

    /**
     * Delete a school year.
     *
     * @param SchoolYear $schoolYear
     * @return void
     */
    public function delete(SchoolYear $schoolYear): void
    {
        app(DeleteSchoolYearAction::class)->execute($schoolYear);
    }
}