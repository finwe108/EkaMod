<?php

namespace App\Http\Controllers\Teacher\Legacy;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\TeacherLoad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Request $request, TeacherLoad $teacherLoad): View
    {
        $gradingSystem = $request->get('grading_system', 'term') === 'quarter' ? 'quarter' : 'term';
        $periodNo = (int) $request->get($gradingSystem === 'quarter' ? 'quarter_no' : 'term_no', 1);
        $maxPeriods = $gradingSystem === 'quarter' ? 4 : 3;

        if ($periodNo < 1 || $periodNo > $maxPeriods) {
            $periodNo = 1;
        }

        $user = $request->user()->loadMissing([
            'employee.teacher',
            'employee.teacher.teacherLoads.subject.gradingProfile.components',
            'employee.teacher.teacherLoads.section',
            'employee.teacher.teacherLoads.schoolYear',
        ]);

        $teacher = $user->employee?->teacher;

        abort_unless($teacher, 403, 'No teacher profile is linked to this account.');
        abort_unless((int) $teacherLoad->teacher_id === (int) $teacher->id, 403, 'You are not assigned to this teaching load.');

        $teacherLoad->loadMissing([
            'subject.gradingProfile.components',
            'section',
            'schoolYear',
        ]);

        $termNo = (int) $request->integer('term_no', 1);
        if ($termNo < 1 || $termNo > 3) {
            $termNo = 1;
        }

        $students = Enrollment::query()
            ->with('student')
            ->where('section_id', $teacherLoad->section_id)
            ->where('school_year_id', $teacherLoad->school_year_id)
            ->where('status', 'enrolled')
            ->get()
            ->pluck('student')
            ->filter()
            ->sortBy([
                ['last_name', 'asc'],
                ['first_name', 'asc'],
            ])
            ->values();

        $existingGrades = Grade::query()
            ->where('teacher_load_id', $teacherLoad->id)
            ->where($gradingSystem === 'quarter' ? 'quarter' : 'term_no', $periodNo)
            ->get()
            ->keyBy('student_id');

        $gradingProfile = $teacherLoad->subject?->gradingProfile;
        $gradingComponents = $gradingProfile
            ? $gradingProfile->components->sortBy('id')->values()
            : collect();

        return view('teacher.grades_by_load', [
            'teacherLoad' => $teacherLoad,
            'students' => $students,
            'periodNo' => $periodNo,
            'gradingSystem' => $gradingSystem,
            'existingGrades' => $existingGrades,
            'gradingProfile' => $gradingProfile,
            'gradingComponents' => $gradingComponents,
        ]);
    }

    public function store(Request $request, TeacherLoad $teacherLoad): RedirectResponse
    {
        $user = $request->user()->loadMissing([
            'employee.teacher',
        ]);

        $teacher = $user->employee?->teacher;

        abort_unless($teacher, 403, 'No teacher profile is linked to this account.');
        abort_unless((int) $teacherLoad->teacher_id === (int) $teacher->id, 403, 'You are not assigned to this teaching load.');

        $teacherLoad->loadMissing([
            'subject.gradingProfile.components',
        ]);

        $validated = $request->validate([
            'term_no' => 'required|integer|min:1|max:3',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.written_work' => 'nullable|numeric|min:0|max:100',
            'grades.*.performance_task' => 'nullable|numeric|min:0|max:100',
            'grades.*.behavior' => 'nullable|numeric|min:0|max:100',
            'grades.*.long_test' => 'nullable|numeric|min:0|max:100',
            'grades.*.quarterly_exam' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($validated['grades'] as $row) {
            $writtenWork = $this->nullableScore($row['written_work'] ?? null);
            $performanceTask = $this->nullableScore($row['performance_task'] ?? null);
            $behavior = $this->nullableScore($row['behavior'] ?? null);
            $longTest = $this->nullableScore($row['long_test'] ?? null);
            $quarterlyExam = $this->nullableScore($row['quarterly_exam'] ?? null);

            $initialGrade = $this->computeInitialGrade($teacherLoad, [
                'written_work' => $writtenWork,
                'performance_task' => $performanceTask,
                'behavior' => $behavior,
                'long_test' => $longTest,
                'quarterly_exam' => $quarterlyExam,
            ]);

            $finalGrade = $initialGrade;
            $remarks = $finalGrade !== null
                ? ($finalGrade >= 75 ? 'Passed' : 'Failed')
                : null;

            $gradingSystem = $request->get('grading_system', 'term') === 'quarter' ? 'quarter' : 'term';
            $periodNo = (int) $request->get($gradingSystem === 'quarter' ? 'quarter_no' : 'term_no');

            Grade::updateOrCreate(
                [
                    'teacher_load_id' => $teacherLoad->id,
                    'student_id' => $row['student_id'],
                    $gradingSystem === 'quarter' ? 'quarter' : 'term_no' => $periodNo,
                ],
                [
                    'subject_id' => $teacherLoad->subject_id,
                    'quarter' => $gradingSystem === 'quarter' ? $periodNo : null,
                    'term_no' => $gradingSystem === 'term' ? $periodNo : null,
                    // ...component fields...
                ]
            );
        }

        return redirect()
            ->route('teacher.loads.grades.index', [
                'teacherLoad' => $teacherLoad->id,
                'term_no' => $validated['term_no'],
            ])
            ->with('success', 'Grades saved successfully.');
    }

    protected function computeInitialGrade(TeacherLoad $teacherLoad, array $scores): ?float
    {
        $subject = $teacherLoad->subject;
        $profile = $subject?->gradingProfile;

        if (!$profile) {
            return null;
        }

        $profile->loadMissing('components');

        $total = 0;
        $hasAny = false;

        foreach ($profile->components as $component) {
            $componentKey = $component->component;
            $value = $scores[$componentKey] ?? null;

            if ($value !== null && $value !== '') {
                $hasAny = true;
                $total += ((float) $value * ((float) $component->weight / 100));
            }
        }

        return $hasAny ? round($total, 2) : null;
    }

    protected function nullableScore(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return round((float) $value, 2);
    }
}