<?php

namespace Modules\Students\Actions;

use App\Models\Student;

/**
 * Handles student self-service profile updates.
 *
 * Module: StudentProfiles
 * Layer: Action
 */
class UpdateStudentProfileAction
{
    public function execute(Student $student, array $data): Student
    {
        $student->update($data);

        return $student;
    }
}