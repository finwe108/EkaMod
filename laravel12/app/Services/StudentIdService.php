<?php

namespace App\Services;

use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentIdService
{
    /**
     * Generate a student ID using the pattern GGGYYYYXXXX.
     *
     * Business rule:
     * - GGG = grade level code, e.g. P01, K00, G01
     * - YYYY = school year entry year, e.g. 2026 from SY 2026-2027
     * - XXXX = sequence number
     *
     * Important:
     * The sequence is global per school year, not per grade level.
     *
     * Example:
     * P0120260001
     * K0020260002
     * G0320260003
     *
     * @param int $gradeLevelId
     * @param int $schoolYearId
     * @return string
     */
    public function generate(int $gradeLevelId, int $schoolYearId): string
    {
        return DB::transaction(function () use ($gradeLevelId, $schoolYearId) {
            $gradeLevel = GradeLevel::query()
                ->lockForUpdate()
                ->findOrFail($gradeLevelId);

            $schoolYear = SchoolYear::query()
                ->lockForUpdate()
                ->findOrFail($schoolYearId);

            $gradeCode = $gradeLevel->code;

            if (! $gradeCode) {
                throw new \Exception('Grade level code is missing.');
            }

            $entryYear = $schoolYear->starts_on
                ? Carbon::parse($schoolYear->starts_on)->format('Y')
                : $this->extractStartYearFromSchoolYearName($schoolYear->name);

            /*
             * We intentionally search by entry year only, not by grade code.
             *
             * Old behavior:
             * P0120260001
             * K0020260001
             *
             * Correct behavior:
             * P0120260001
             * K0020260002
             *
             * The grade code changes per grade level, but the sequence is
             * shared by all students in the same school year.
             */
            $latestStudent = Student::query()
                ->where('student_id', 'like', '___' . $entryYear . '%')
                ->lockForUpdate()
                ->orderByDesc(DB::raw('RIGHT(student_id, 4)'))
                ->first();

            $nextSequence = $latestStudent
                ? ((int) Str::substr($latestStudent->student_id, -4)) + 1
                : 1;

            /*
             * Collision guard:
             * If a matching ID already exists, continue increasing the sequence.
             */
            do {
                $sequence = str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);

                $studentId = $gradeCode . $entryYear . $sequence;

                $exists = Student::where('student_id', $studentId)
                    ->lockForUpdate()
                    ->exists();

                if ($exists) {
                    $nextSequence++;
                }
            } while ($exists);

            return $studentId;
        });
    }

    /**
     * Extract the start year from a school year name.
     *
     * Example:
     * "SY 2026-2027" becomes "2026".
     *
     * @param string $schoolYearName
     * @return string
     */
    private function extractStartYearFromSchoolYearName(string $schoolYearName): string
    {
        preg_match('/\d{4}/', $schoolYearName, $matches);

        if (empty($matches[0])) {
            throw new \Exception('Cannot determine school year start year.');
        }

        return $matches[0];
    }
}