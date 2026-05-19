<?php

namespace App\Http\Controllers\Admin\Legacy;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\StudentAccountCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentCredentialController extends Controller
{
    public function edit(Student $student)
    {
        $student->load('user');

        abort_if(!$student->user, 404, 'Student user account not found.');

        return view('admin.students.credentials.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $student->load('user');

        abort_if(!$student->user, 404, 'Student user account not found.');

        $user = $student->user;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'is_active' => ['nullable', 'boolean'],
            'must_change_password' => ['nullable', 'boolean'],
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'] ?? null;
        $user->is_active = $request->boolean('is_active');
        $user->must_change_password = $request->boolean('must_change_password');

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->must_change_password = true;
        }

        $user->save();

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Student login credentials updated successfully.');
    }

    public function store(Student $student)
    {
        if ($student->user) {
            return back()->with('success', 'Student already has a user account.');
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

            if (!empty($createdUser->email)) {
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

        return back()
            ->with('success', 'Student user account created successfully.')
            ->with('generated_username', $createdUser?->username)
            ->with('generated_password', $temporaryPassword)
            ->with('email_sent', $emailSent);
    }
}