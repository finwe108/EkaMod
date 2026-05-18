<?php

namespace Modules\Teachers\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherLoad;
use App\Models\TeacherLoadSchedule;
use Modules\Teachers\Requests\Admin\StoreTeacherLoadRequest;
use Modules\Teachers\Requests\Admin\UpdateTeacherLoadRequest;
use Modules\Teachers\Services\Admin\TeacherLoadService;
use Illuminate\Http\Request;

class TeacherLoadController extends Controller
{
    public function index(Request $request)
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYears = SchoolYear::orderByDesc('name')->get();
        $teachers = Teacher::with('employee')
            ->get()
            ->sortBy(function ($teacher) {
                return strtolower($teacher->employee?->full_name ?? '');
            })
            ->values();

        $schoolYearId = $request->school_year_id ?: optional($activeSchoolYear)->id;
        $teacherId = $request->teacher_id;

        $teacherLoads = TeacherLoad::with([
                'teacher.employee',
                'section',
                'schoolYear',
                'loadSubjects.subject',
                'schedules',
                'terms',
            ])
            ->when($schoolYearId, function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->when($teacherId, function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('teachers::admin.teacher_loads.index', compact(
            'teacherLoads',
            'teachers',
            'schoolYears',
            'schoolYearId',
            'teacherId',
            'activeSchoolYear'
        ));
    }

    public function create()
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->firstOrFail();

        $schoolYears = \App\Models\SchoolYear::orderByDesc('name')->get();

        $teachers = Teacher::with('employee')->get();

        $sections = Section::where('school_year_id', $activeSchoolYear->id)
            ->orderBy('name')
            ->get();

        $gradeLevels = \App\Models\GradeLevel::orderBy('name')->get();

        $gradeLevelIds = $sections->pluck('grade_level_id')
            ->filter()
            ->unique()
            ->values();

        $subjects = Subject::whereIn('grade_level_id', $gradeLevelIds)
            ->orderBy('name')
            ->get();

        return view('teachers::admin.teacher_loads.create', compact(
            'activeSchoolYear',
            'schoolYears',
            'teachers',
            'gradeLevels',
            'sections',
            'subjects'
        ));
    }

    public function store(
        StoreTeacherLoadRequest $request,
        TeacherLoadService $teacherLoadService
    ) {
        $teacherLoadService->create($request->validated());

        return redirect()
            ->route('admin.teacher_loads.index')
            ->with('success', 'Teacher load assigned successfully.');
    }
    
    public function edit($id)
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->firstOrFail();
        $schoolYears = SchoolYear::orderByDesc('name')->get();

        $teacherLoad = TeacherLoad::with(['loadSubjects', 'schedules', 'terms'])->findOrFail($id);

        $teachers = Teacher::with('employee')->get();

        $sections = Section::where('school_year_id', $activeSchoolYear->id)
            ->orderBy('name')
            ->get();

        $gradeLevelIds = $sections->pluck('grade_level_id')
            ->filter()
            ->unique()
            ->values();

        $subjects = Subject::whereIn('grade_level_id', $gradeLevelIds)
            ->orderBy('name')
            ->get();

        return view('teachers::admin.teacher_loads.edit', compact(
            'teacherLoad',
            'teachers',
            'subjects',
            'sections',
            'activeSchoolYear',
            'schoolYears',
        ));
    }

    public function update(
        UpdateTeacherLoadRequest $request,
        TeacherLoadService $teacherLoadService,
        $id
    ) {
        $teacherLoad = TeacherLoad::with(['loadSubjects', 'schedules', 'terms'])->findOrFail($id);

        $teacherLoadService->update($teacherLoad, $request->validated());

        return redirect()
            ->route('admin.teacher_loads.index')
            ->with('success', 'Teacher load updated successfully.');
    }

    public function destroy(
        TeacherLoadService $teacherLoadService,
        $id
    ) {
        $teacherLoad = TeacherLoad::findOrFail($id);

        $teacherLoadService->delete($teacherLoad);

        return redirect()
            ->route('admin.teacher_loads.index')
            ->with('success', 'Teacher load removed successfully.');
    }
}
