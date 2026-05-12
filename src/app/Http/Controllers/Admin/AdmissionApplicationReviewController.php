<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StudentAccountCreatedMail;
use App\Models\AdmissionApplication;
use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AdmissionApplicationAcceptedNotification;
use App\Notifications\StudentAccountCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AdmissionApplicationReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $applications = AdmissionApplication::with(['gradeLevel', 'schoolYear'])
            ->when($status, fn ($query) => $query->where('application_status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.admission_applications.index', compact('applications', 'status'));
    }

    public function show(AdmissionApplication $admissionApplication)
    {
        $admissionApplication->load([
            'gradeLevel',
            'schoolYear',
            'section',
            'acceptedStudent',
            'createdUser',
            'reviewer',
        ]);

        $gradeLevels = GradeLevel::orderBy('sort_order')->get();

        $sections = Section::orderBy('grade_level_id')
            ->orderBy('name')
            ->get();

        return view('admin.admission_applications.show', [
            'application' => $admissionApplication,
            'gradeLevels' => $gradeLevels,
            'sections' => $sections,
        ]);
    }

    public function markUnderReview(AdmissionApplication $admissionApplication)
    {
        if ($admissionApplication->application_status === 'submitted') {
            $admissionApplication->update([
                'application_status' => 'under_review',
                'reviewed_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Application marked as under review.');
    }

    public function accept(AdmissionApplication $admissionApplication)
    {
        if ($admissionApplication->application_status === 'accepted') {
            return back()->with('success', 'Application is already accepted.');
        }

        DB::transaction(function () use ($admissionApplication) {
            $gradeLevelId = $admissionApplication->grade_level_id;

            $student = Student::create([
                'student_id' => $this->generateStudentId($gradeLevelId, $admissionApplication->school_year_id),
                'first_name' => $admissionApplication->first_name,
                'middle_name' => $admissionApplication->middle_name,
                'last_name' => $admissionApplication->last_name,
                'suffix' => $admissionApplication->suffix,
                'sex' => $admissionApplication->sex,
                'birth_date' => $admissionApplication->birth_date,
                'birth_place' => $admissionApplication->birth_place,
                'mother_tongue' => $admissionApplication->mother_tongue,
                'is_ip' => $admissionApplication->is_ip,
                'ethnic_group' => $admissionApplication->ethnic_group,
                'religion' => $admissionApplication->religion,
                'lrn' => $admissionApplication->lrn,
                'email' => $admissionApplication->email,
                'contact_number' => $admissionApplication->contact_number,
                'address' => $admissionApplication->address,
                'house_street' => $admissionApplication->house_street,
                'barangay' => $admissionApplication->barangay,
                'municipality_city' => $admissionApplication->municipality_city,
                'province' => $admissionApplication->province,
                'father_name' => $admissionApplication->father_name,
                'father_contact' => $admissionApplication->father_contact,
                'mother_name' => $admissionApplication->mother_name,
                'mother_contact' => $admissionApplication->mother_contact,
                'guardian_name' => $admissionApplication->guardian_name,
                'guardian_relationship' => $admissionApplication->guardian_relationship,
                'guardian_contact' => $admissionApplication->guardian_contact,
                'parent_guardian_contact' => $admissionApplication->parent_guardian_contact,
                'remarks' => $admissionApplication->remarks,
                'status' => 'active',
            ]);

            $temporaryPassword = Str::random(10);

            $user = User::create([
                'name' => $student->full_name,
                'username' => $student->student_id,
                'email' => $student->email,
                'password' => Hash::make($temporaryPassword),
                'student_id' => $student->id,
                'must_change_password' => true,
            ]);

            $studentRoleId = DB::table('roles')
                ->where('name', 'student')
                ->value('id');

            DB::table('user_roles')->insert([
                'user_id' => $user->id,
                'role_id' => $studentRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Enrollment::create([
                'student_id' => $student->id,
                'school_year_id' => $admissionApplication->school_year_id,
                'grade_level_id' => $admissionApplication->grade_level_id,
                'section_id' => $admissionApplication->section_id,
                'student_type' => $admissionApplication->student_type,
                'status' => 'pending',
            ]);

            $admissionApplication->update([
                'application_status' => 'accepted',
                'accepted_student_id' => $student->id,
                'created_user_id' => $user->id,
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            /*
             * Notification for the newly created student user.
             */
            $user->notify(
                new StudentAccountCreatedNotification(
                    $student->full_name,
                    $user->username
                )
            );

            /*
             * Notification for admins/registrar.
             */
            $admins = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['super_admin', 'admin', 'registrar']);
            })->get();

            Notification::send(
                $admins,
                new AdmissionApplicationAcceptedNotification($student, $admissionApplication)
            );

            /*
             * Email temporary password.
             */
            Mail::to($user->email)->send(
                new StudentAccountCreatedMail(
                    $user->name,
                    $user->username,
                    $temporaryPassword
                )
            );

            session()->flash('temporary_student_password', $temporaryPassword);
        });

        return back()->with('success', 'Application accepted. Student account has been created.');
    }

    public function reject(Request $request, AdmissionApplication $admissionApplication)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ]);

        $admissionApplication->update([
            'application_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Application rejected.');
    }

    private function generateStudentId(int $gradeLevelId, ?int $schoolYearId = null): string
    {
        if (!$schoolYearId) {
            $schoolYearId = SchoolYear::where('is_active', true)->value('id');

            if (!$schoolYearId) {
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