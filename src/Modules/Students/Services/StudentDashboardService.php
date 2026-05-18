<?php

namespace Modules\Students\Services;

use App\Models\Student;

/**
 * Prepares student dashboard data.
 *
 * Module: StudentDashboard
 * Layer: Service
 */
class StudentDashboardService
{
    /**
     * Load dashboard relationships and return display data.
     *
     * @param Student $student
     * @return array<string, mixed>
     */
    public function dataFor(Student $student): array
    {
        $student->load([
            'currentEnrollment.schoolYear',
            'currentEnrollment.gradeLevel',
            'currentEnrollment.section',
            'latestEnrollment.schoolYear',
            'latestEnrollment.gradeLevel',
            'latestEnrollment.section',
            'documents',
        ]);

        $displayEnrollment = $student->currentEnrollment ?: $student->latestEnrollment;

        return [
            'student' => $student,
            'displayEnrollment' => $displayEnrollment,
            'hasActiveEnrollment' => (bool) $student->currentEnrollment,
        ];
    }
}