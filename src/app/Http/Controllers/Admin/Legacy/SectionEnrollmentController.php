<?php

namespace App\Http\Controllers\Admin\Legacy;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class SectionEnrollmentController extends Controller
{
    public function search(Request $request, Section $section)
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

        return view('admin.sections.enroll_search', compact('section', 'students', 'query'));
    }

    public function enrollExisting(Request $request, Section $section)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $section->load(['schoolYear', 'gradeLevel']);

        $student = Student::findOrFail($validated['student_id']);

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