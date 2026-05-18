<?php

namespace Modules\Students\Services;

use App\Models\User;
use Modules\Students\Actions\UpdateStudentAccountAction;

/**
 * Handles student account workflows.
 *
 * Module: StudentAccounts
 * Layer: Service
 */
class StudentAccountService
{
    public function update(User $user, array $data): User
    {
        return app(UpdateStudentAccountAction::class)->execute($user, $data);
    }
}