<?php

namespace Modules\Enrollments\Services;

use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\Sf1GeneratedReport;
use Illuminate\Validation\ValidationException;

/**
 * Handles enrollment write workflows.
 *
 * This service preserves existing enrollment behavior, including grade
 * progression validation and SF1 report invalidation.
 *
 * Module: Enrollments
 * Layer: Service
 */
class EnrollmentService
{
    /**
     * Create an enrollment record after validating grade progression.
     *
     * @param array<string, mixed> $data
     * @return Enrollment
     */
    public function create(array $data): Enrollment
    {
        $this->validateGradeProgression($data);

        return Enrollment::create($data);
    }

    /**
     * Update an enrollment and mark related SF1 reports as outdated.
     *
     * @param Enrollment $enrollment
     * @param array<string, mixed> $data
     * @return Enrollment
     */
    public function update(Enrollment $enrollment, array $data): Enrollment
    {
        $enrollment->update($data);

        $this->markSf1ReportsOutdated($enrollment);

        return $enrollment;
    }

    /**
     * Delete an enrollment.
     *
     * @param Enrollment $enrollment
     * @return void
     */
    public function delete(Enrollment $enrollment): void
    {
        $enrollment->delete();
    }

    /**
     * Update enrollment status and mark related SF1 reports as outdated.
     *
     * @param Enrollment $enrollment
     * @param string $status
     * @return Enrollment
     */
    public function updateStatus(Enrollment $enrollment, string $status): Enrollment
    {
        $enrollment->update([
            'status' => $status,
        ]);

        $this->markSf1ReportsOutdated($enrollment);

        return $enrollment;
    }

    /**
     * Mark generated SF1 reports as outdated after enrollment changes.
     *
     * @param Enrollment $enrollment
     * @return void
     */
    protected function markSf1ReportsOutdated(Enrollment $enrollment): void
    {
        Sf1GeneratedReport::query()
            ->where('school_year_id', $enrollment->school_year_id)
            ->where('grade_level_id', $enrollment->grade_level_id)
            ->where('section_id', $enrollment->section_id)
            ->update([
                'needs_regeneration' => true,
                'status' => 'outdated',
            ]);
    }

    /**
     * Validate a student's grade progression before enrollment.
     *
     * This preserves the original enrollment business rule:
     * - dropped/transferred out students cannot be enrolled
     * - retained students must stay in the same grade level
     * - promoted/completed students must move to the next grade level
     *
     * @param array<string, mixed> $data
     * @return void
     */
    protected function validateGradeProgression(array $data): void
    {
        $studentId = $data['student_id'];
        $newGradeLevelId = (int) $data['grade_level_id'];
        $newSchoolYearId = (int) $data['school_year_id'];

        $previousEnrollment = Enrollment::with(['gradeLevel', 'schoolYear'])
            ->where('student_id', $studentId)
            ->where('school_year_id', '!=', $newSchoolYearId)
            ->latest('school_year_id')
            ->first();

        if (! $previousEnrollment) {
            return;
        }

        if (in_array($previousEnrollment->status, ['dropped', 'transferred_out'], true)) {
            throw ValidationException::withMessages([
                'grade_level_id' => 'This student cannot be enrolled because the previous status is ' . str_replace('_', ' ', $previousEnrollment->status) . '.',
            ]);
        }

        $previousGrade = $previousEnrollment->gradeLevel;
        $newGrade = GradeLevel::find($newGradeLevelId);

        if (! $previousGrade || ! $newGrade) {
            return;
        }

        if ($previousEnrollment->status === 'retained') {
            if ((int) $previousGrade->id !== (int) $newGrade->id) {
                throw ValidationException::withMessages([
                    'grade_level_id' => 'This student was marked as retained. They must enroll in the same grade level.',
                ]);
            }

            return;
        }

        $expectedNextGrade = GradeLevel::where('sort_order', $previousGrade->sort_order + 1)->first();

        if (! $expectedNextGrade || (int) $newGrade->id !== (int) $expectedNextGrade->id) {
            throw ValidationException::withMessages([
                'grade_level_id' => 'Invalid grade level. Previous level was ' . $previousGrade->name . '. Expected next level is ' . ($expectedNextGrade?->name ?? 'not configured') . '.',
            ]);
        }
    }
}