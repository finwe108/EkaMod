<?php

namespace Modules\Employees\Services;

use App\Models\Employee;
use App\Models\Teacher;

class EmployeeTeacherSyncService
{
    public static function sync(Employee $employee, array $data = []): void
    {
        if ($employee->employee_type === 'teaching') {
            Teacher::updateOrCreate(
                ['employee_id_ref' => $employee->id],
                [
                    'teacher_no' => $data['teacher_no'] ?? $employee->teacher?->teacher_no,
                    'specialization' => $data['specialization'] ?? $employee->teacher?->specialization,
                    'subject_specialty' => $data['subject_specialty'] ?? $employee->teacher?->subject_specialty,
                    'license_no' => $data['license_no'] ?? $employee->teacher?->license_no,
                    'major' => $data['major'] ?? $employee->teacher?->major,
                    'rank_title' => $data['rank_title'] ?? $employee->teacher?->rank_title,
                    'is_adviser' => array_key_exists('is_adviser', $data)
                        ? (bool) $data['is_adviser']
                        : (bool) ($employee->teacher?->is_adviser ?? false),
                    'date_hired_as_teacher' => $data['date_hired_as_teacher']
                        ?? $employee->teacher?->date_hired_as_teacher
                        ?? $employee->employment_date,
                ]
            );
        } else {
            $employee->teacher()?->delete();
        }
    }
}