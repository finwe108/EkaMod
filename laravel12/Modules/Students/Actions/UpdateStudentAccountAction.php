<?php

namespace Modules\Students\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Handles updating student account credentials.
 *
 * Module: StudentAccounts
 * Layer: Action
 */
class UpdateStudentAccountAction
{
    public function execute(User $user, array $data): User
    {
        $user->username = $data['username'];
        $user->email = $data['email'] ?? null;

        /*
         * Preserve existing behavior: when the student changes password,
         * first-login password change requirement is cleared.
         */
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->must_change_password = false;
        }

        $user->save();

        return $user;
    }
}