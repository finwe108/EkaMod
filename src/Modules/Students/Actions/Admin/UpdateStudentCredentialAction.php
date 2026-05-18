<?php

namespace Modules\Students\Actions\Admin;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Handles updating an existing student user account.
 *
 * Module: Students
 * Layer: Action
 */
class UpdateStudentCredentialAction
{
    /**
     * Update the linked user credentials for a student.
     *
     * @param Student $student
     * @param array<string, mixed> $data
     * @return User
     */
    public function execute(Student $student, array $data): User
    {
        $student->load('user');

        $user = $student->user;

        abort_if(! $user, 404, 'Student user account not found.');

        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'] ?? null;
        $user->is_active = (bool) ($data['is_active'] ?? false);
        $user->must_change_password = (bool) ($data['must_change_password'] ?? false);

        /*
         * Preserve existing behavior:
         * If admin sets a new password, the student must change it on login.
         */
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->must_change_password = true;
        }

        $user->save();

        return $user;
    }
}