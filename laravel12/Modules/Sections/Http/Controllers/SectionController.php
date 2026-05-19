<?php

namespace Modules\Sections\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Sections\Requests\StoreSectionRequest;
use Modules\Sections\Requests\UpdateSectionRequest;
use Modules\Sections\Services\SectionService;

/**
 * Handles administrative section management.
 *
 * This controller preserves existing section workflows while moving
 * ownership into the Sections module.
 *
 * Module: Sections
 * Layer: HTTP Controller
 */
class SectionController extends Controller
{
    /**
     * Display a paginated list of sections.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYears = SchoolYear::orderByDesc('name')->get();

        $schoolYearId = $request->school_year_id ?: optional($activeSchoolYear)->id;

        $sections = Section::with(['gradeLevel', 'schoolYear', 'adviser'])
            ->withCount('enrollments')
            ->when($schoolYearId, function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('sections::index', compact(
            'sections',
            'schoolYears',
            'schoolYearId',
            'activeSchoolYear'
        ));
    }

    /**
     * Display a section and its enrolled students for a selected school year.
     *
     * @param Request $request
     * @param Section $section
     * @return View|RedirectResponse
     */
    public function show(Request $request, Section $section): View|RedirectResponse
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        $schoolYears = SchoolYear::orderByDesc('id')->get();

        $schoolYearId = $request->query(
            'school_year_id',
            $activeSchoolYear?->id
        );

        $availableSections = Section::where('school_year_id', $schoolYearId)
            ->orderBy('name')
            ->get();

        /*
         * If the selected section does not belong to the selected school year,
         * redirect to the first available section for that year. This preserves
         * the existing cross-school-year navigation behavior.
         */
        if (
            $schoolYearId &&
            $section->school_year_id != $schoolYearId &&
            $availableSections->isNotEmpty()
        ) {
            return redirect()->route('admin.sections.show', [
                'section' => $availableSections->first(),
                'school_year_id' => $schoolYearId,
            ]);
        }

        $students = Enrollment::with(['student', 'gradeLevel'])
            ->join('students', 'students.id', '=', 'enrollments.student_id')
            ->where('enrollments.school_year_id', $schoolYearId)
            ->where('enrollments.section_id', $section->id)
            ->orderBy('students.last_name')
            ->orderBy('students.first_name')
            ->select('enrollments.*')
            ->paginate(25)
            ->withQueryString();

        return view('sections::show', compact(
            'section',
            'students',
            'schoolYears',
            'schoolYearId',
            'activeSchoolYear',
            'availableSections'
        ));
    }

    /**
     * Show the section creation form.
     *
     * @return View
     */
    public function create(): View
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        $schoolYears = SchoolYear::orderByDesc('is_active')
            ->orderByDesc('starts_on')
            ->get();

        $gradeLevels = GradeLevel::orderBy('sort_order')->get();

        $teachers = Teacher::with('employee')
            ->join('employees', 'teachers.employee_id_ref', '=', 'employees.id')
            ->orderBy('employees.last_name')
            ->orderBy('employees.first_name')
            ->select('teachers.*')
            ->get();

        return view('sections::create', compact(
            'schoolYears',
            'gradeLevels',
            'teachers',
            'activeSchoolYear'
        ));
    }

    /**
     * Store a new section.
     *
     * @param StoreSectionRequest $request
     * @param SectionService $sectionService
     * @return RedirectResponse
     */
    public function store(
        StoreSectionRequest $request,
        SectionService $sectionService
    ): RedirectResponse {
        $validated = $request->validated();

        $sectionService->create($validated);

        return redirect()
            ->route('admin.sections.index', ['school_year_id' => $validated['school_year_id']])
            ->with('success', 'Section created successfully.');
    }

    /**
     * Show the section edit form.
     *
     * @param Section $section
     * @return View
     */
    public function edit(Section $section): View
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        $schoolYears = SchoolYear::orderByDesc('is_active')
            ->orderByDesc('starts_on')
            ->get();

        $gradeLevels = GradeLevel::orderBy('sort_order')->get();

        $teachers = Teacher::with('employee')
            ->join('employees', 'teachers.employee_id_ref', '=', 'employees.id')
            ->orderBy('employees.last_name')
            ->orderBy('employees.first_name')
            ->select('teachers.*')
            ->get();

        return view('sections::edit', compact(
            'section',
            'schoolYears',
            'gradeLevels',
            'teachers',
            'activeSchoolYear'
        ));
    }

    /**
     * Update an existing section.
     *
     * @param UpdateSectionRequest $request
     * @param Section $section
     * @param SectionService $sectionService
     * @return RedirectResponse
     */
    public function update(
        UpdateSectionRequest $request,
        Section $section,
        SectionService $sectionService
    ): RedirectResponse {
        $validated = $request->validated();

        $sectionService->update($section, $validated);

        return redirect()
            ->route('admin.sections.index', ['school_year_id' => $validated['school_year_id']])
            ->with('success', 'Section updated successfully.');
    }

    /**
     * Delete a section if it has no enrollments.
     *
     * @param Section $section
     * @param SectionService $sectionService
     * @return RedirectResponse
     */
    public function destroy(
        Section $section,
        SectionService $sectionService
    ): RedirectResponse {
        $hasStudents = $section->enrollments()->exists();

        if ($hasStudents) {
            return redirect()
                ->route('admin.sections.index', ['school_year_id' => $section->school_year_id])
                ->with('error', 'This section cannot be deleted because it has enrolled students.');
        }

        $schoolYearId = $section->school_year_id;

        $sectionService->delete($section);

        return redirect()
            ->route('admin.sections.index', ['school_year_id' => $schoolYearId])
            ->with('success', 'Section deleted successfully.');
    }
}