<?php

namespace Modules\Students\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Handles updating a student's password.
 *
 * Module: StudentPasswords
 * Layer: Action
 */
class UpdateStudentPasswordAction
{
    public function execute(User $user, string $password): User
    {
        $user->update([
            'password' => Hash::make($password),
            'must_change_password' => false,
        ]);

        return $user;
    }
}