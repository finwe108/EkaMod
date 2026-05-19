<?php

namespace Modules\Reports\Services\SF1;

use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolSetting;
use App\Models\SchoolYear;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Handles reusable SF1 report data preparation.
 *
 * This service centralizes SF1-specific business logic such as enrollment
 * retrieval, student row formatting, report metadata building, and date
 * calculation.
 *
 * Module: Reports
 * Layer: Service
 */
class SF1ReportService
{
    /**
     * Get enrollments included in an SF1 report.
     *
     * Only enrolled and completed records are included to preserve the
     * existing SF1 report behavior.
     *
     * @param int $schoolYearId
     * @param int|null $gradeLevelId
     * @param int|null $sectionId
     * @return Collection<int, Enrollment>
     */
    public function getEnrollments(
        int $schoolYearId,
        ?int $gradeLevelId = null,
        ?int $sectionId = null
    ): Collection {
        return Enrollment::with(['student', 'schoolYear', 'gradeLevel', 'section'])
            ->where('school_year_id', $schoolYearId)
            ->when($gradeLevelId, function ($query) use ($gradeLevelId) {
                $query->where('grade_level_id', $gradeLevelId);
            })
            ->when($sectionId, function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            })
            ->whereIn('status', ['enrolled', 'completed'])
            ->get()
            ->sortBy(function ($enrollment) {
                return strtolower(
                    ($enrollment->student->last_name ?? '') . ' ' .
                    ($enrollment->student->first_name ?? '') . ' ' .
                    ($enrollment->student->middle_name ?? '')
                );
            })
            ->values();
    }

    /**
     * Build formatted student rows for SF1 Excel export.
     *
     * @param Collection<int, Enrollment> $enrollments
     * @return array<int, array<string, mixed>>
     */
    public function buildStudentRows(Collection $enrollments): array
    {
        return $enrollments->map(function ($enrollment) {
            $student = $enrollment->student;

            return [
                'lrn' => $student->lrn,
                'full_name_sf1' => $this->formatStudentName($student),
                'sort_name' => strtolower(
                    ($student->last_name ?? '') . ' ' .
                    ($student->first_name ?? '') . ' ' .
                    ($student->middle_name ?? '')
                ),
                'sex' => strtolower((string) $student->sex),
                'sex_short' => strtoupper(substr((string) $student->sex, 0, 1)),
                'birth_date' => $student->birth_date,
                'age' => $student->birth_date ? Carbon::parse($student->birth_date)->age : '',
                'birth_place' => $student->birth_place,
                'mother_tongue' => $student->mother_tongue,
                'ethnic_group' => $student->ethnic_group,
                'religion' => $student->religion,
                'house_street' => $student->house_street,
                'barangay' => $student->barangay,
                'municipality_city' => $student->municipality_city,
                'province' => $student->province,
                'father_name' => $student->father_name,
                'mother_name' => $student->mother_name,
                'guardian_name' => $student->guardian_name,
                'guardian_relationship' => $student->guardian_relationship,
                'parent_guardian_contact' => $student->parent_guardian_contact ?: $student->guardian_contact,
                'code' => $this->buildCode($enrollment),
                'required_info' => $this->buildRequiredInfo($enrollment),
            ];
        })->all();
    }

    /**
     * Count male and female students from SF1 student rows.
     *
     * @param array<int, array<string, mixed>> $students
     * @return array{male:int,female:int,total:int}
     */
    public function countBySex(array $students): array
    {
        $maleCount = collect($students)->where('sex', 'male')->count();
        $femaleCount = collect($students)->where('sex', 'female')->count();

        return [
            'male' => $maleCount,
            'female' => $femaleCount,
            'total' => $maleCount + $femaleCount,
        ];
    }

    /**
     * Build SF1 report metadata used by the template exporter.
     *
     * @param SchoolYear $schoolYear
     * @param GradeLevel $gradeLevel
     * @param Section $section
     * @param int $maleCount
     * @param int $femaleCount
     * @return array<string, mixed>
     */
    public function buildMeta(
        SchoolYear $schoolYear,
        GradeLevel $gradeLevel,
        Section $section,
        int $maleCount,
        int $femaleCount
    ): array {
        $schoolSetting = SchoolSetting::current();

        return [
            'school_id' => $schoolSetting->school_id,
            'region' => $schoolSetting->region,
            'division' => $schoolSetting->division,
            'district' => $schoolSetting->district,
            'school_name' => $schoolSetting->school_name,
            'school_year' => $schoolYear->name,
            'grade_level' => $gradeLevel->name,
            'section' => $section->name,
            'first_friday_of_june' => $this->getFirstFridayOfJune($schoolYear->name),

            'bosy_male' => $maleCount,
            'bosy_female' => $femaleCount,
            'bosy_total' => $maleCount + $femaleCount,

            'eosy_male' => $maleCount,
            'eosy_female' => $femaleCount,
            'eosy_total' => $maleCount + $femaleCount,

            'adviser_name' => '',
            'bosy_date' => now(),
            'school_head_name' => $schoolSetting->school_head_name,
            'eosy_date' => now(),
        ];
    }

    /**
     * Generate a safe SF1 filename.
     *
     * @param SchoolYear $schoolYear
     * @param GradeLevel $gradeLevel
     * @param Section $section
     * @return string
     */
    public function buildFilename(
        SchoolYear $schoolYear,
        GradeLevel $gradeLevel,
        Section $section
    ): string {
        return 'SF1_' . preg_replace(
            '/[^A-Za-z0-9_\-]/',
            '_',
            $schoolYear->name . '_' . $gradeLevel->name . '_' . $section->name
        ) . '.xlsx';
    }

    /**
     * Format student name for SF1 display.
     *
     * @param mixed $student
     * @return string
     */
    protected function formatStudentName(mixed $student): string
    {
        return collect([
            $student->last_name,
            $student->first_name,
            $student->middle_name,
            $student->suffix,
        ])->filter()->implode(', ');
    }

    /**
     * Build SF1 status code.
     *
     * @param Enrollment $enrollment
     * @return string
     */
    protected function buildCode(Enrollment $enrollment): string
    {
        $codes = [];

        if ($enrollment->status === 'transferred_out') {
            $codes[] = 'T/O';
        }

        if ($enrollment->student_type === 'transferee') {
            $codes[] = 'T/I';
        }

        if ($enrollment->status === 'dropped') {
            $codes[] = 'DRP';
        }

        return implode(', ', $codes);
    }

    /**
     * Build SF1 required information text.
     *
     * @param Enrollment $enrollment
     * @return string
     */
    protected function buildRequiredInfo(Enrollment $enrollment): string
    {
        $parts = [];

        if ($enrollment->status === 'transferred_out') {
            $parts[] = 'Transferred Out';
        }

        if ($enrollment->student_type === 'transferee') {
            $parts[] = 'Transferred In';
        }

        if ($enrollment->status === 'dropped') {
            $parts[] = 'Dropped';
        }

        return implode('; ', $parts);
    }

    /**
     * Get the first Friday of June for the school year.
     *
     * The year is extracted from the school year name to preserve the existing
     * report behavior.
     *
     * @param string $schoolYearName
     * @return string
     */
    protected function getFirstFridayOfJune(string $schoolYearName): string
    {
        preg_match('/(\d{4})/', $schoolYearName, $matches);

        $year = isset($matches[1]) ? (int) $matches[1] : now()->year;

        $date = Carbon::create($year, 6, 1);

        while ($date->dayOfWeek !== Carbon::FRIDAY) {
            $date->addDay();
        }

        return $date->toDateString();
    }
}