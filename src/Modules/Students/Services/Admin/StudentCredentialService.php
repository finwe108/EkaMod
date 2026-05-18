<?php

namespace Modules\Students\Services\Admin;

use App\Models\Student;
use App\Models\User;
use Modules\Students\Actions\Admin\CreateStudentCredentialAction;
use Modules\Students\Actions\Admin\UpdateStudentCredentialAction;

/**
 * Handles admin-side student credential workflows.
 *
 * Module: Students
 * Layer: Service
 */
class StudentCredentialService
{
    /**
     * Update an existing student user account.
     *
     * @param Student $student
     * @param array<string, mixed> $data
     * @return User
     */
    public function update(Student $student, array $data): User
    {
        return app(UpdateStudentCredentialAction::class)
            ->execute($student, $data);
    }

    /**
     * Create a new student user account.
     *
     * @param Student $student
     * @return array{user:User|null,password:string,email_sent:bool}
     */
    public function create(Student $student): array
    {
        return app(CreateStudentCredentialAction::class)
            ->execute($student);
    }
}