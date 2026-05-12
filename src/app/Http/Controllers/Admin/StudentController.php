<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Sf1GeneratedReport;
use App\Services\StudentIdService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);

                $query->where(function ($q) use ($search) {
                    $q->where('student_id', 'like', "%{$search}%")
                        ->orWhere('lrn', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $selectedSectionId = request('section_id');
        $selectedGradeLevelId = request('grade_level_id');
        $selectedSchoolYearId = request('school_year_id');

        $schoolYears = SchoolYear::orderByDesc('id')->get();
        $gradeLevels = GradeLevel::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();

        return view('admin.students.create', compact(
            'schoolYears',
            'gradeLevels',
            'sections',
            'selectedSectionId',
            'selectedGradeLevelId',
            'selectedSchoolYearId'
        ));
    }

    public function store(StoreStudentRequest $request, StudentIdService $studentIdService)
    {
        $validated = $request->validated();

        $student = DB::transaction(function () use ($validated, $request, $studentIdService) {

            $studentType = strtolower($request->input('student_type', 'new'));

            /*
            |--------------------------------------------------------------------------
            | Resolve section + grade level
            |--------------------------------------------------------------------------
            */
            $section = null;

            if ($request->filled('section_id')) {
                $section = Section::findOrFail($request->input('section_id'));
            }

            $gradeLevelId = $section
                ? $section->grade_level_id
                : $request->input('grade_level_id');

            $schoolYearId = $request->input('school_year_id');

            /*
            |--------------------------------------------------------------------------
            | Handle student ID
            |--------------------------------------------------------------------------
            */
            if ($studentType === 'old') {
                if (empty($request->input('student_id'))) {
                    throw ValidationException::withMessages([
                        'student_id' => 'Existing Student ID is required for old students.',
                    ]);
                }

                $validated['student_id'] = $request->input('student_id');
            } else {
                $validated['student_id'] = $studentIdService->generate(
                    $gradeLevelId,
                    $schoolYearId
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Handle photo upload
            |--------------------------------------------------------------------------
            */
            if ($request->hasFile('photo')) {
                $validated['photo_path'] = $request->file('photo')
                    ->store('students/photos', 'public');
            }

            /*
            |--------------------------------------------------------------------------
            | Remove enrollment-only fields from students table
            |--------------------------------------------------------------------------
            */
            unset(
                $validated['grade_level_id'],
                $validated['school_year_id'],
                $validated['section_id'],
                $validated['student_type'],
                $validated['enrollment_status']
            );

            /*
            |--------------------------------------------------------------------------
            | Create student
            |--------------------------------------------------------------------------
            */
            $student = Student::create($validated);

            /*
            |--------------------------------------------------------------------------
            | Create enrollment if enrollment data exists
            |--------------------------------------------------------------------------
            */
            if ($gradeLevelId && $schoolYearId) {

                $student->enrollments()->create([
                    'school_year_id' => $schoolYearId,
                    'grade_level_id' => $gradeLevelId,
                    'section_id' => $section?->id ?? $request->input('section_id'),
                    'student_type' => $studentType,
                    'status' => $request->input('enrollment_status', 'pending'),
                ]);
            }

            return $student;
        });

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Student saved successfully.');
    }

    public function show(Student $student)
    {
        $activeSchoolYearId = SchoolYear::where('is_active', 1)->value('id');
        $currentEnrollment = $student->enrollments()
            ->where('school_year_id', $activeSchoolYearId)
            ->with(['gradeLevel', 'section'])
            ->first();

        return view('admin.students.show', compact('student', 'currentEnrollment'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validated = $request->validated(); // ✅ ADD THIS

        if ($request->hasFile('photo')) {

            // delete old photo
            if ($student->photo_path && file_exists(public_path($student->photo_path))) {
                unlink(public_path($student->photo_path));
            }

            $file = $request->file('photo');

            $filename = 'student_' . $student->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $uploadPath = public_path('uploads/students/photos');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $filename);

            $validated['photo_path'] = 'uploads/students/photos/' . $filename;
        }

        $student->update($validated); // ✅ use validated instead of $request->validated()

        $currentEnrollment = $student->currentEnrollment()->first();

        if ($currentEnrollment) {
            Sf1GeneratedReport::query()
                ->where('school_year_id', $currentEnrollment->school_year_id)
                ->where('grade_level_id', $currentEnrollment->grade_level_id)
                ->where('section_id', $currentEnrollment->section_id)
                ->update([
                    'needs_regeneration' => true,
                    'status' => 'outdated',
                ]);
        }
        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Student updated successfully.');
    }
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    private function generateStudentId($gradeLevelId, $schoolYearId): string
    {
        $gradeLevel = \App\Models\GradeLevel::findOrFail($gradeLevelId);
        $schoolYear = \App\Models\SchoolYear::findOrFail($schoolYearId);

        // Extract year (e.g. 2025 from "SY 2025-2026")
        preg_match('/\d{4}/', $schoolYear->name, $matches);
        $year = $matches[0] ?? now()->year;

        // Use your grade level code column (IMPORTANT)
        $gradeCode = $gradeLevel->code; // e.g. G01, P01, K00

        // Count students for this school year
        $count = \App\Models\Student::whereYear('created_at', $year)->count() + 1;

        $sequence = str_pad($count, 4, '0', STR_PAD_LEFT);

        return "{$gradeCode}{$year}{$sequence}";
    }

}