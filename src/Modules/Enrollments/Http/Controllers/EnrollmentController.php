<?php

namespace Modules\Enrollments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Enrollments\Services\EnrollmentService;

/**
 * Handles administrative enrollment management.
 *
 * This controller owns only enrollment CRUD, status updates, and section AJAX
 * lookup. SF1 report logic remains in the legacy controller until migrated
 * separately.
 *
 * Module: Enrollments
 * Layer: HTTP Controller
 */
class EnrollmentController extends Controller
{
    /**
     * Display enrollment records.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYears = SchoolYear::orderByDesc('name')->get();
        $gradeLevels = GradeLevel::orderBy('sort_order')->get();

        $schoolYearId = $request->school_year_id ?: optional($activeSchoolYear)->id;

        $sections = Section::query()
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->orderBy('name')
            ->get();

        $enrollments = Enrollment::with(['student', 'schoolYear', 'gradeLevel', 'section'])
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when($request->filled('grade_level_id'), fn ($q) => $q->where('grade_level_id', $request->grade_level_id))
            ->when($request->filled('section_id'), fn ($q) => $q->where('section_id', $request->section_id))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('enrollments::index', compact(
            'enrollments',
            'schoolYears',
            'gradeLevels',
            'sections',
            'schoolYearId',
            'activeSchoolYear'
        ));
    }

    /**
     * Show enrollment creation form for a selected student.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        $student = Student::findOrFail($request->student_id);

        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYears = SchoolYear::orderByDesc('name')->get();
        $gradeLevels = GradeLevel::orderBy('sort_order')->get();

        $defaultSchoolYearId = old('school_year_id', optional($activeSchoolYear)->id);

        $sections = Section::query()
            ->when($defaultSchoolYearId, fn ($q) => $q->where('school_year_id', $defaultSchoolYearId))
            ->orderBy('name')
            ->get();

        return view('enrollments::create', compact(
            'student',
            'schoolYears',
            'gradeLevels',
            'sections',
            'activeSchoolYear'
        ));
    }

    /**
     * Store a new enrollment.
     *
     * @param StoreEnrollmentRequest $request
     * @param EnrollmentService $enrollmentService
     * @return RedirectResponse
     */
    public function store(
        StoreEnrollmentRequest $request,
        EnrollmentService $enrollmentService
    ): RedirectResponse {
        $enrollment = $enrollmentService->create($request->validated());

        return redirect()
            ->route('admin.students.show', $enrollment->student_id)
            ->with('success', 'Enrollment created successfully.');
    }

    /**
     * Show enrollment edit form.
     *
     * @param Enrollment $enrollment
     * @return View
     */
    public function edit(Enrollment $enrollment): View
    {
        $enrollment->load('student');

        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYears = SchoolYear::orderByDesc('name')->get();
        $gradeLevels = GradeLevel::orderBy('sort_order')->get();

        $sections = Section::where('school_year_id', $enrollment->school_year_id)
            ->orderBy('name')
            ->get();

        return view('enrollments::edit', compact(
            'enrollment',
            'schoolYears',
            'gradeLevels',
            'sections',
            'activeSchoolYear'
        ));
    }

    /**
     * Update an enrollment.
     *
     * @param UpdateEnrollmentRequest $request
     * @param Enrollment $enrollment
     * @param EnrollmentService $enrollmentService
     * @return RedirectResponse
     */
    public function update(
        UpdateEnrollmentRequest $request,
        Enrollment $enrollment,
        EnrollmentService $enrollmentService
    ): RedirectResponse {
        $enrollmentService->update($enrollment, $request->validated());

        return redirect()
            ->route('admin.students.show', $enrollment->student_id)
            ->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Delete an enrollment.
     *
     * @param Enrollment $enrollment
     * @param EnrollmentService $enrollmentService
     * @return RedirectResponse
     */
    public function destroy(
        Enrollment $enrollment,
        EnrollmentService $enrollmentService
    ): RedirectResponse {
        $studentId = $enrollment->student_id;

        $enrollmentService->delete($enrollment);

        return redirect()
            ->route('admin.students.show', $studentId)
            ->with('success', 'Enrollment removed successfully.');
    }

    /**
     * Return sections for a selected school year, optionally filtered by grade level.
     *
     * This endpoint is used by enrollment/student forms to dynamically reload
     * section options when school year or grade level changes.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sectionsBySchoolYear(Request $request): JsonResponse
    {
        $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'grade_level_id' => ['nullable', 'exists:grade_levels,id'],
        ]);

        $sections = Section::with('gradeLevel')
            ->where('school_year_id', $request->school_year_id)
            ->when($request->filled('grade_level_id'), function ($query) use ($request) {
                $query->where('grade_level_id', $request->grade_level_id);
            })
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

    /**
     * Update an enrollment status.
     *
     * @param Request $request
     * @param Enrollment $enrollment
     * @param EnrollmentService $enrollmentService
     * @return RedirectResponse
     */
    public function updateStatus(
        Request $request,
        Enrollment $enrollment,
        EnrollmentService $enrollmentService
    ): RedirectResponse {
        $data = $request->validate([
            'status' => [
                'required',
                'in:pending,enrolled,completed,promoted,retained,dropped,transferred_out',
            ],
        ]);

        $enrollmentService->updateStatus($enrollment, $data['status']);

        return back()->with('success', 'Student enrollment status updated successfully.');
    }
}