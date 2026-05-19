<?php

namespace Modules\Students\Services;

use App\Models\User;
use Modules\Students\Actions\UpdateStudentPasswordAction;

/**
 * Handles student password change workflows.
 *
 * Module: StudentPasswords
 * Layer: Service
 */
class StudentPasswordService
{
    public function update(User $user, string $password): User
    {
        return app(UpdateStudentPasswordAction::class)
            ->execute($user, $password);
    }
}