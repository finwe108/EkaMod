<?php

namespace App\Services;

use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentIdService
{
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

            if (!$gradeCode) {
                throw new \Exception('Grade level code is missing.');
            }

            $entryYear = $schoolYear->starts_on
                ? \Carbon\Carbon::parse($schoolYear->starts_on)->format('Y')
                : $this->extractStartYearFromSchoolYearName($schoolYear->name);

            /*
             * Pattern:
             * GGGYYYYXXXX
             *
             * Example:
             * P0120250001
             * G0120250002
             * G0320260001
             */

            $prefix = $gradeCode . $entryYear;

            $latestStudent = Student::query()
                ->where('student_id', 'like', $prefix . '%')
                ->lockForUpdate()
                ->orderByDesc('student_id')
                ->first();

            if ($latestStudent) {
                $lastSequence = (int) Str::substr($latestStudent->student_id, -4);
                $nextSequence = $lastSequence + 1;
            } else {
                $nextSequence = 1;
            }

            $sequence = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

            return $prefix . $sequence;
        });
    }

    private function extractStartYearFromSchoolYearName(string $schoolYearName): string
    {
        preg_match('/\d{4}/', $schoolYearName, $matches);

        if (empty($matches[0])) {
            throw new \Exception('Cannot determine school year start year.');
        }

        return $matches[0];
    }
}