<?php

namespace Modules\Admissions\Services;

use App\Mail\StudentAccountCreatedMail;
use App\Models\AdmissionApplication;
use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AdmissionApplicationAcceptedNotification;
use App\Notifications\StudentAccountCreatedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

/**
 * Handles administrative admission review workflows.
 *
 * This service preserves the current admission review behavior:
 * - mark applications under review
 * - reject applications with reasons
 * - accept applications
 * - create student records
 * - create student user accounts
 * - assign student role
 * - create pending enrollment
 * - send notifications
 * - email temporary password
 *
 * Module: Admissions
 * Layer: Service
 */
class AdminAdmissionReviewService
{
    /**
     * Mark an admission application as under review.
     *
     * Only submitted applications are transitioned, preserving existing behavior.
     *
     * @param AdmissionApplication $application
     * @param int|null $reviewerId
     * @return AdmissionApplication
     */
    public function markUnderReview(
        AdmissionApplication $application,
        ?int $reviewerId
    ): AdmissionApplication {
        if ($application->application_status === 'submitted') {
            $application->update([
                'application_status' => 'under_review',
                'reviewed_by' => $reviewerId,
            ]);
        }

        return $application;
    }

    /**
     * Reject an admission application.
     *
     * @param AdmissionApplication $application
     * @param string $reason
     * @param int|null $reviewerId
     * @return AdmissionApplication
     */
    public function reject(
        AdmissionApplication $application,
        string $reason,
        ?int $reviewerId
    ): AdmissionApplication {
        $application->update([
            'application_status' => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
        ]);

        return $application;
    }

    /**
     * Accept an admission application.
     *
     * @param AdmissionApplication $application
     * @param int|null $reviewerId
     * @return array{student:Student|null,user:User|null,temporary_password:string|null,already_accepted:bool}
     */
    public function accept(
        AdmissionApplication $application,
        ?int $reviewerId
    ): array {
        if ($application->application_status === 'accepted') {
            return [
                'student' => $application->acceptedStudent,
                'user' => $application->createdUser,
                'temporary_password' => null,
                'already_accepted' => true,
            ];
        }

        $temporaryPassword = Str::random(10);
        $createdStudent = null;
        $createdUser = null;

        DB::transaction(function () use (
            $application,
            $reviewerId,
            $temporaryPassword,
            &$createdStudent,
            &$createdUser
        ) {
            $createdStudent = $this->createStudentFromApplication($application);

            $createdUser = $this->createStudentUser(
                $createdStudent,
                $temporaryPassword
            );

            $this->assignStudentRole($createdUser);

            $this->createPendingEnrollment($application, $createdStudent);

            $application->update([
                'application_status' => 'accepted',
                'accepted_student_id' => $createdStudent->id,
                'created_user_id' => $createdUser->id,
                'reviewed_at' => now(),
                'reviewed_by' => $reviewerId,
            ]);

            $this->notifyCreatedStudentUser($createdUser, $createdStudent);

            $this->notifyAdmissionReviewers($createdStudent, $application);

            $this->emailTemporaryPassword($createdUser, $temporaryPassword);
        });

        return [
            'student' => $createdStudent,
            'user' => $createdUser,
            'temporary_password' => $temporaryPassword,
            'already_accepted' => false,
        ];
    }

    /**
     * Create a student record from an accepted admission application.
     *
     * @param AdmissionApplication $application
     * @return Student
     */
    protected function createStudentFromApplication(AdmissionApplication $application): Student
    {
        return Student::create([
            'student_id' => $this->generateStudentId(
                $application->grade_level_id,
                $application->school_year_id
            ),
            'first_name' => $application->first_name,
            'middle_name' => $application->middle_name,
            'last_name' => $application->last_name,
            'suffix' => $application->suffix,
            'sex' => $application->sex,
            'birth_date' => $application->birth_date,
            'birth_place' => $application->birth_place,
            'mother_tongue' => $application->mother_tongue,
            'is_ip' => $application->is_ip,
            'ethnic_group' => $application->ethnic_group,
            'religion' => $application->religion,
            'lrn' => $application->lrn,
            'email' => $application->email,
            'contact_number' => $application->contact_number,
            'address' => $application->address,
            'house_street' => $application->house_street,
            'barangay' => $application->barangay,
            'municipality_city' => $application->municipality_city,
            'province' => $application->province,
            'father_name' => $application->father_name,
            'father_contact' => $application->father_contact,
            'mother_name' => $application->mother_name,
            'mother_contact' => $application->mother_contact,
            'guardian_name' => $application->guardian_name,
            'guardian_relationship' => $application->guardian_relationship,
            'guardian_contact' => $application->guardian_contact,
            'parent_guardian_contact' => $application->parent_guardian_contact,
            'remarks' => $application->remarks,
            'status' => 'active',
        ]);
    }

    /**
     * Create the student user account.
     *
     * @param Student $student
     * @param string $temporaryPassword
     * @return User
     */
    protected function createStudentUser(
        Student $student,
        string $temporaryPassword
    ): User {
        return User::create([
            'name' => $student->full_name,
            'username' => $student->student_id,
            'email' => $student->email,
            'password' => Hash::make($temporaryPassword),
            'student_id' => $student->id,
            'must_change_password' => true,
        ]);
    }

    /**
     * Assign the student role to the created user.
     *
     * @param User $user
     * @return void
     */
    protected function assignStudentRole(User $user): void
    {
        $studentRoleId = DB::table('roles')
            ->where('name', 'student')
            ->value('id');

        if (! $studentRoleId) {
            return;
        }

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $studentRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Create a pending enrollment for the accepted student.
     *
     * @param AdmissionApplication $application
     * @param Student $student
     * @return Enrollment
     */
    protected function createPendingEnrollment(
        AdmissionApplication $application,
        Student $student
    ): Enrollment {
        return Enrollment::create([
            'student_id' => $student->id,
            'school_year_id' => $application->school_year_id,
            'grade_level_id' => $application->grade_level_id,
            'section_id' => $application->section_id,
            'student_type' => $application->student_type,
            'status' => 'pending',
        ]);
    }

    /**
     * Notify the newly created student user.
     *
     * @param User $user
     * @param Student $student
     * @return void
     */
    protected function notifyCreatedStudentUser(User $user, Student $student): void
    {
        $user->notify(
            new StudentAccountCreatedNotification(
                $student->full_name,
                $user->username
            )
        );
    }

    /**
     * Notify admins and registrar that an application was accepted.
     *
     * @param Student $student
     * @param AdmissionApplication $application
     * @return void
     */
    protected function notifyAdmissionReviewers(
        Student $student,
        AdmissionApplication $application
    ): void {
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['super_admin', 'admin', 'registrar']);
        })->get();

        Notification::send(
            $admins,
            new AdmissionApplicationAcceptedNotification($student, $application)
        );
    }

    /**
     * Email the student's temporary password.
     *
     * Existing behavior lets mail failures bubble up.
     *
     * @param User $user
     * @param string $temporaryPassword
     * @return void
     */
    protected function emailTemporaryPassword(User $user, string $temporaryPassword): void
    {
        Mail::to($user->email)->send(
            new StudentAccountCreatedMail(
                $user->name,
                $user->username,
                $temporaryPassword
            )
        );
    }

    /**
     * Generate a student ID for the accepted applicant.
     *
     * This preserves the legacy admissions student ID generation behavior.
     *
     * @param int $gradeLevelId
     * @param int|null $schoolYearId
     * @return string
     *
     * @throws \Exception
     */
    protected function generateStudentId(int $gradeLevelId, ?int $schoolYearId = null): string
    {
        if (! $schoolYearId) {
            $schoolYearId = SchoolYear::where('is_active', true)->value('id');

            if (! $schoolYearId) {
                throw new \Exception('No active school year found.');
            }
        }

        $gradeLevel = GradeLevel::findOrFail($gradeLevelId);
        $schoolYear = SchoolYear::findOrFail($schoolYearId);

        $year = substr($schoolYear->name, 0, 4);

        $count = Enrollment::where('school_year_id', $schoolYearId)->count();

        $sequence = $count + 1;

        return $gradeLevel->code . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}