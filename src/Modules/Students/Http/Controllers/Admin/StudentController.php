<?php

namespace Modules\Students\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Services\StudentIdService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Students\Requests\Admin\StoreStudentRequest;
use Modules\Students\Requests\Admin\UpdateStudentRequest;
use Modules\Students\Services\Admin\StudentService;

/**
 * Handles admin-side student management.
 *
 * This controller preserves existing student CRUD URLs, route names,
 * filtering, student ID generation workflow, enrollment creation, photo
 * handling, and SF1 invalidation behavior.
 *
 * Module: Students
 * Layer: HTTP Controller
 */
class StudentController extends Controller
{
    /**
     * Display students.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
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

        return view('students::admin.students.index', compact('students'));
    }

    /**
     * Show student creation form.
     *
     * @return View
     */
    public function create(): View
    {
        $selectedSectionId = request('section_id');
        $selectedGradeLevelId = request('grade_level_id');

        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $selectedSchoolYearId = request('school_year_id') ?: $activeSchoolYear?->id;

        $schoolYears = SchoolYear::orderByDesc('id')->get();
        $gradeLevels = GradeLevel::orderBy('name')->get();
        $sections = Section::with('gradeLevel')
            ->when($selectedSchoolYearId, function ($query) use ($selectedSchoolYearId) {
                $query->where('school_year_id', $selectedSchoolYearId);
            })
            ->when($selectedGradeLevelId, function ($query) use ($selectedGradeLevelId) {
                $query->where('grade_level_id', $selectedGradeLevelId);
            })
            ->orderBy('name')
            ->get(); 
        $student = null;

        return view('students::admin.students.create', compact(
            'student',
            'schoolYears',
            'gradeLevels',
            'sections',
            'selectedSectionId',
            'selectedGradeLevelId',
            'selectedSchoolYearId',
            'activeSchoolYear'
        ));
    }

    /**
     * Store a student.
     *
     * @param StoreStudentRequest $request
     * @param StudentIdService $studentIdService
     * @param StudentService $studentService
     * @return RedirectResponse
     */
    public function store(
        StoreStudentRequest $request,
        StudentIdService $studentIdService,
        StudentService $studentService
    ): RedirectResponse {
        $student = $studentService->create(
            $request->validated(),
            $request,
            $studentIdService
        );

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Student saved successfully.');
    }

    /**
     * Show student profile.
     *
     * @param Student $student
     * @return View
     */
    public function show(Student $student): View
    {
        $activeSchoolYearId = SchoolYear::where('is_active', 1)->value('id');

        $currentEnrollment = $student->enrollments()
            ->where('school_year_id', $activeSchoolYearId)
            ->with(['gradeLevel', 'section'])
            ->first();

        return view('students::admin.students.show', compact(
            'student',
            'currentEnrollment'
        ));
    }

    /**
     * Show student edit form.
     *
     * @param Student $student
     * @return View
     */
    public function edit(Student $student): View
    {
        return view('students::admin.students.edit', compact('student'));
    }

    /**
     * Update a student.
     *
     * @param UpdateStudentRequest $request
     * @param Student $student
     * @param StudentService $studentService
     * @return RedirectResponse
     */
    public function update(
        UpdateStudentRequest $request,
        Student $student,
        StudentService $studentService
    ): RedirectResponse {
        $studentService->update(
            $student,
            $request->validated(),
            $request
        );

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Delete a student.
     *
     * @param Student $student
     * @param StudentService $studentService
     * @return RedirectResponse
     */
    public function destroy(
        Student $student,
        StudentService $studentService
    ): RedirectResponse {
        $studentService->delete($student);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Return sections by school year for legacy AJAX use.
     *
     * Preserves the old route name admin.students.sections and its current
     * legacy URL structure.
     *
     * @param int $schoolYearId
     * @return JsonResponse
     */
    public function getSectionsBySchoolYear(int $schoolYearId): JsonResponse
    {
        $sections = Section::with('gradeLevel')
            ->where('school_year_id', $schoolYearId)
            ->orderBy('name')
            ->get()
            ->map(function ($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->name,
                    'grade_level_id' => $section->grade_level_id,
                    'grade_level_name' => optional($section->gradeLevel)->name,
                ];
            });

        return response()->json($sections);
    }
}