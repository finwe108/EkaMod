<?php

namespace App\Http\Controllers\Admin\Legacy;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    public function index(Request $request)
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

        return view('admin.sections.index', compact(
            'sections',
            'schoolYears',
            'schoolYearId',
            'activeSchoolYear'
        ));
    }

    public function show(Request $request, Section $section)
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

        // If selected section does not belong to selected school year,
        // redirect to the first available section for that school year.
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

        return view('admin.sections.show', compact(
            'section',
            'students',
            'schoolYears',
            'schoolYearId',
            'activeSchoolYear',
            'availableSections'
        ));
    }

    public function create()
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYears = SchoolYear::orderByDesc('is_active')->orderByDesc('starts_on')->get();
        $gradeLevels = GradeLevel::orderBy('sort_order')->get();
        $teachers = Teacher::with('employee')
            ->join('employees', 'teachers.employee_id_ref', '=', 'employees.id')
            ->orderBy('employees.last_name')
            ->orderBy('employees.first_name')
            ->select('teachers.*')
            ->get();

        return view('admin.sections.create', compact('schoolYears', 'gradeLevels', 'teachers', 'activeSchoolYear'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => ['nullable', 'exists:school_years,id'],
            'grade_level_id' => ['nullable', 'exists:grade_levels,id'],
            'name'           => [
                'required', 
                'string', 
                'max:100',
                Rule::unique ('sections', 'name')->where(function($query) use ($request){ 
                    return $query->where('school_year_id', $request->school_year_id);
                    
                }),
            ],
            'teacher_id'     => ['nullable', 'exists:teachers,id'],
            'capacity'       => ['nullable', 'integer', 'min:1'],
        ], [
            'name.unique' => 'This section name already exists for the selected school year. ',
        ]);

        Section::create($validated);

        return redirect()
            ->route('admin.sections.index', ['school_year_id' => $validated['school_year_id']])
            ->with('success', 'Section created successfully.');
    }

    public function edit(Section $section)
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYears = SchoolYear::orderByDesc('is_active')->orderByDesc('starts_on')->get();
        $gradeLevels = GradeLevel::orderBy('sort_order')->get();
        $teachers = Teacher::with('employee')
            ->join('employees', 'teachers.employee_id_ref', '=', 'employees.id')
            ->orderBy('employees.last_name')
            ->orderBy('employees.first_name')
            ->select('teachers.*')
            ->get();


        return view('admin.sections.edit', compact('section', 'schoolYears', 'gradeLevels', 'teachers', 'activeSchoolYear'));
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'school_year_id' => ['nullable', 'exists:school_years,id'],
            'grade_level_id' => ['nullable', 'exists:grade_levels,id'],
            'name'           => [
                'required', 
                'string', 
                'max:100',
                Rule::unique('sections', 'name')
                    ->where(function($query) use ($request) {
                        return $query->where('school_year_id', $request->school_year_id);
                    })
                    ->ignore($section->id), 
            ],
            'teacher_id'     => ['nullable', 'exists:teachers,id'],
            'capacity'       => ['nullable', 'integer', 'min:1'],
        ],[
            'name.unique' => 'This section name already exists for the selected school year. ',
        ]);

        $section->update($validated);

        return redirect()
            ->route('admin.sections.index', ['school_year_id' => $validated['school_year_id']])
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        $hasStudents = $section->enrollments()->exists();
        
        if($hasStudents){
            return redirect() 
               ->route('admin.sections.index', ['school_year_id' => $section->school_year_id])
               ->with('error', 'This section cannot be deleted because it has enrolled students.');
        }
        
        $schoolYearId = $section->school_year_id;
        $section->delete();

        return redirect()
            ->route('admin.sections.index', ['school_year_id' => $schoolYearId])
            ->with('success', 'Section deleted successfully.');
    }
}