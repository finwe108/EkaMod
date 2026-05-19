<?php

namespace Modules\Students\Services;

use App\Models\Student;
use Modules\Students\Actions\UpdateStudentProfileAction;

/**
 * Handles student profile workflows.
 *
 * Module: StudentProfiles
 * Layer: Service
 */
class StudentProfileService
{
    public function update(Student $student, array $data): Student
    {
        return app(UpdateStudentProfileAction::class)->execute($student, $data);
    }
}