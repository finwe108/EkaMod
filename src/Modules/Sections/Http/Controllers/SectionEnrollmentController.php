<?php

namespace Modules\Sections\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Handles enrollment of existing students into a section.
 *
 * This controller belongs to the Sections module because the workflow starts
 * from a section and uses section-centered routes.
 *
 * Module: Sections
 * Layer: HTTP Controller
 */
class SectionEnrollmentController extends Controller
{
    /**
     * Search existing students who can be enrolled into the selected section.
     *
     * @param Request $request
     * @param Section $section
     * @return View
     */
    public function search(Request $request, Section $section): View
    {
        $section->load(['schoolYear', 'gradeLevel']);

        $query = $request->query('q');

        $students = collect();

        if ($query) {
            $students = Student::query()
                ->where(function ($q) use ($query) {
                    $q->where('student_id', 'like', "%{$query}%")
                        ->orWhere('lrn', 'like', "%{$query}%")
                        ->orWhere('first_name', 'like', "%{$query}%")
                        ->orWhere('middle_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->orderBy('last_name')
                ->limit(20)
                ->get();
        }

        return view('sections::enroll_search', compact(
            'section',
            'students',
            'query'
        ));
    }

    /**
     * Enroll an existing student into the selected section.
     *
     * This preserves the existing behavior of updating both the student record
     * and the enrollment record. The student table update is kept for backward
     * compatibility even though enrollment should eventually be the source of
     * truth for school year, grade level, and section.
     *
     * @param Request $request
     * @param Section $section
     * @return RedirectResponse
     */
    public function enrollExisting(Request $request, Section $section): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $section->load(['schoolYear', 'gradeLevel']);

        $student = Student::findOrFail($validated['student_id']);

        /*
         * Legacy compatibility:
         * The system still stores current placement on the students table in
         * some areas, so this update is preserved during migration.
         */
        $student->update([
            'school_year_id' => $section->school_year_id,
            'grade_level_id' => $section->grade_level_id,
            'section_id' => $section->id,
            'status' => 'active',
        ]);

        Enrollment::updateOrCreate(
            [
                'student_id' => $student->id,
                'school_year_id' => $section->school_year_id,
            ],
            [
                'grade_level_id' => $section->grade_level_id,
                'section_id' => $section->id,
                'student_type' => $student->student_type ?? 'old',
                'status' => 'enrolled',
            ]
        );

        return redirect()
            ->route('admin.sections.show', $section)
            ->with('success', 'Student enrolled to section successfully.');
    }
}