<?php

namespace Modules\Students\Actions\Admin;

use App\Mail\StudentAccountCreatedMail;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Handles creation of a student user account.
 *
 * Module: Students
 * Layer: Action
 */
class CreateStudentCredentialAction
{
    /**
     * Create a user account for the student.
     *
     * @param Student $student
     * @return array{user:User|null,password:string,email_sent:bool}
     */
    public function execute(Student $student): array
    {
        if ($student->user) {
            return [
                'user' => $student->user,
                'password' => '',
                'email_sent' => false,
            ];
        }

        $temporaryPassword = Str::random(10);
        $createdUser = null;
        $emailSent = false;

        DB::transaction(function () use ($student, $temporaryPassword, &$createdUser, &$emailSent) {
            $createdUser = User::create([
                'name' => $student->full_name,
                'username' => $student->student_id,
                'email' => $student->email ?: null,
                'password' => Hash::make($temporaryPassword),
                'student_id' => $student->id,
                'is_active' => true,
                'must_change_password' => true,
            ]);

            $studentRoleId = DB::table('roles')
                ->where('name', 'student')
                ->value('id');

            if ($studentRoleId) {
                DB::table('user_roles')->insert([
                    'user_id' => $createdUser->id,
                    'role_id' => $studentRoleId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (! empty($createdUser->email)) {
                try {
                    Mail::to($createdUser->email)->send(
                        new StudentAccountCreatedMail(
                            $createdUser->name,
                            $createdUser->username,
                            $temporaryPassword
                        )
                    );

                    $emailSent = true;
                } catch (\Throwable $e) {
                    \Log::error('Student account email failed', [
                        'student_id' => $student->id,
                        'user_id' => $createdUser->id,
                        'email' => $createdUser->email,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        return [
            'user' => $createdUser,
            'password' => $temporaryPassword,
            'email_sent' => $emailSent,
        ];
    }
}