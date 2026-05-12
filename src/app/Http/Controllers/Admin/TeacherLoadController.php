<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherLoad;
use App\Models\TeacherLoadSchedule;
use App\Services\TeacherLoadConflictService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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

        return view('admin.teacher_loads.index', compact(
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

        return view('admin.teacher_loads.create', compact(
            'activeSchoolYear',
            'schoolYears',
            'teachers',
            'gradeLevels',
            'sections',
            'subjects'
        ));
    }

    public function store(Request $request)
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->firstOrFail();

        $validated = $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'section_ids' => ['nullable', 'array'],
            'section_ids.*' => ['required', 'exists:sections,id'],

            'subject_ids' => ['required', 'array', 'min:1'],
            'subject_ids.*' => ['required', 'exists:subjects,id'],

            'terms' => ['required', 'array', 'min:1'],
            'terms.*' => ['required', 'integer', 'between:1,3', 'distinct'],

            'schedules' => ['required', 'array', 'min:1'],
            'schedules.*.day_of_week' => ['required', 'integer', 'between:1,6'],
            'schedules.*.time_start' => ['required', 'date_format:H:i'],
            'schedules.*.time_end' => ['required', 'date_format:H:i'],
            'schedules.*.room' => ['nullable', 'string', 'max:100'],

            'is_active' => ['nullable', 'boolean'],
            'is_multi_grade' => ['nullable', 'boolean'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($validated['schedules'] as $index => $schedule) {
            if ($schedule['time_end'] <= $schedule['time_start']) {
                throw ValidationException::withMessages([
                    "schedules.$index.time_end" => 'End time must be later than start time.',
                ]);
            }
        }

        $internalConflicts = TeacherLoadConflictService::hasInternalConflicts($validated['schedules']);

        if (!empty($internalConflicts)) {
            $messages = [];

            foreach ($internalConflicts as $conflict) {
                $rows = collect($conflict['rows'])->map(fn ($r) => $r + 1)->implode(' and ');
                $messages['schedules'] = "Schedule rows {$rows} overlap.";
            }

            throw ValidationException::withMessages($messages);
        }

        $conflicts = TeacherLoadConflictService::findConflicts(
            teacherId: (int) $validated['teacher_id'],
            sectionIds: $this->selectedSectionIds($validated),
            schoolYearId: (int) $validated['school_year_id'],
            termNumbers: $validated['terms'],
            schedules: $validated['schedules'],
            ignoreTeacherLoadId: null
        );

        if (!empty($conflicts)) {
            $messages = [];

            foreach ($conflicts as $conflict) {
                $rowNumber = $conflict['row'] + 1;
                $messages["schedules.{$conflict['row']}.day_of_week"] =
                    "Schedule row {$rowNumber}: {$conflict['message']}";
            }

            throw ValidationException::withMessages($messages);
        }

        DB::transaction(function () use ($validated) {
            $firstSubjectId = $validated['subject_ids'][0] ?? null;
            $firstSchedule = $validated['schedules'][0] ?? null;

            $teacherLoad = TeacherLoad::create([
                'school_year_id' => $validated['school_year_id'],
                'teacher_id' => $validated['teacher_id'],
                'subject_id' => $firstSubjectId, // required by old parent table
                'section_id' => $validated['section_id'],
                'schedule_days' => null,
                'schedule_time' => null,
                'room' => $firstSchedule['room'] ?? null,
                'is_active' => (int) ($validated['is_active'] ?? 1),
                'is_multi_grade' => !empty($validated['is_multi_grade']) ? 1 : 0,
                'is_combined' => count($validated['subject_ids']) > 1 ? 1 : 0,
                'load_type' => !empty($validated['is_multi_grade'])
                    ? 'multi_grade'
                    : (count($validated['subject_ids']) > 1 ? 'combined' : 'regular'),
                'remarks' => $validated['remarks'] ?? null,
            ]);

            foreach ($validated['subject_ids'] as $subjectId) {
                $teacherLoad->loadSubjects()->create([
                    'subject_id' => $subjectId,
                ]);
            }

            if (array_key_exists('section_ids', $validated)) {
                $this->syncLoadSections($teacherLoad, $this->selectedSectionIds($validated));
            }

            $this->syncTerms($teacherLoad, $validated['terms']);

            foreach ($validated['schedules'] as $schedule) {
                $teacherLoad->schedules()->create([
                    'day_of_week' => (int) $schedule['day_of_week'],
                    'time_start' => $schedule['time_start'],
                    'time_end' => $schedule['time_end'],
                    'room' => $schedule['room'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.teacher_loads.index')
            ->with('success', 'Teacher load assigned successfully.');
    }

    protected function validateScheduleConflict(
        int $schoolYearId,
        int $teacherId,
        ?int $sectionId,
        string $dayOfWeek,
        string $timeStart,
        string $timeEnd,
        ?string $room = null,
        ?int $ignoreTeacherLoadId = null
    ): void {
        $query = TeacherLoadSchedule::query()
            ->join('teacher_loads', 'teacher_load_schedules.teacher_load_id', '=', 'teacher_loads.id')
            ->where('teacher_loads.school_year_id', $schoolYearId)
            ->where('teacher_load_schedules.day_of_week', $dayOfWeek)
            ->where('teacher_load_schedules.time_start', '<', $timeEnd)
            ->where('teacher_load_schedules.time_end', '>', $timeStart);

        if ($ignoreTeacherLoadId) {
            $query->where('teacher_loads.id', '!=', $ignoreTeacherLoadId);
        }

        $teacherConflict = (clone $query)
            ->where('teacher_loads.teacher_id', $teacherId)
            ->exists();

        if ($teacherConflict) {
            throw ValidationException::withMessages([
                'schedules' => "Teacher already has a conflicting schedule on {$dayOfWeek} from {$timeStart} to {$timeEnd}.",
            ]);
        }

        if ($sectionId) {
            $sectionConflict = (clone $query)
                ->where('teacher_loads.section_id', $sectionId)
                ->exists();

            if ($sectionConflict) {
                throw ValidationException::withMessages([
                    'schedules' => "Section already has a conflicting schedule on {$dayOfWeek} from {$timeStart} to {$timeEnd}.",
                ]);
            }
        }

        if ($room) {
            $roomConflict = (clone $query)
                ->where('teacher_load_schedules.room', $room)
                ->exists();

            if ($roomConflict) {
                throw ValidationException::withMessages([
                    'schedules' => "Room {$room} is already used on {$dayOfWeek} from {$timeStart} to {$timeEnd}.",
                ]);
            }
        }
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

        return view('admin.teacher_loads.edit', compact(
            'teacherLoad',
            'teachers',
            'subjects',
            'sections',
            'activeSchoolYear',
            'schoolYears',
        ));
    }

    public function update(Request $request, $id)
    {
        $teacherLoad = TeacherLoad::with(['loadSubjects', 'schedules', 'terms'])->findOrFail($id);

        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'section_ids' => ['nullable', 'array'],
            'section_ids.*' => ['required', 'exists:sections,id'],
            'school_year_id' => ['required', 'exists:school_years,id'],

            'subject_ids' => ['required', 'array', 'min:1'],
            'subject_ids.*' => ['required', 'exists:subjects,id'],

            'terms' => ['required', 'array', 'min:1'],
            'terms.*' => ['required', 'integer', 'between:1,3', 'distinct'],

            'schedules' => ['required', 'array', 'min:1'],
            'schedules.*.day_of_week' => ['required', 'integer', 'between:1,6'],
            'schedules.*.time_start' => ['required', 'date_format:H:i'],
            'schedules.*.time_end' => ['required', 'date_format:H:i'],
            'schedules.*.room' => ['nullable', 'string', 'max:100'],

            'is_active' => ['required', 'boolean'],
            'is_multi_grade' => ['nullable', 'boolean'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($validated['schedules'] as $index => $schedule) {
            if ($schedule['time_end'] <= $schedule['time_start']) {
                throw ValidationException::withMessages([
                    "schedules.$index.time_end" => 'End time must be later than start time.',
                ]);
            }
        }

        $internalConflicts = TeacherLoadConflictService::hasInternalConflicts($validated['schedules']);

        if (!empty($internalConflicts)) {
            $messages = [];

            foreach ($internalConflicts as $conflict) {
                $rows = collect($conflict['rows'])->map(fn ($r) => $r + 1)->implode(' and ');
                $messages['schedules'] = "Schedule rows {$rows} overlap.";
            }

            throw ValidationException::withMessages($messages);
        }

        $conflicts = TeacherLoadConflictService::findConflicts(
            teacherId: (int) $validated['teacher_id'],
            sectionIds: $this->selectedSectionIds($validated),
            schoolYearId: (int) $validated['school_year_id'],
            termNumbers: $validated['terms'],
            schedules: $validated['schedules'],
            ignoreTeacherLoadId: $teacherLoad->id
        );

        if (!empty($conflicts)) {
            $messages = [];

            foreach ($conflicts as $conflict) {
                $rowNumber = $conflict['row'] + 1;
                $messages["schedules.{$conflict['row']}.day_of_week"] =
                    "Schedule row {$rowNumber}: {$conflict['message']}";
            }

            throw ValidationException::withMessages($messages);
        }

        DB::transaction(function () use ($teacherLoad, $validated) {
            $firstSubjectId = $validated['subject_ids'][0] ?? null;
            $firstSchedule = $validated['schedules'][0] ?? null;

            $teacherLoad->update([
                'teacher_id' => $validated['teacher_id'],
                'subject_id' => $firstSubjectId,
                'section_id' => $validated['section_id'],
                'school_year_id' => $validated['school_year_id'],
                'schedule_days' => null,
                'schedule_time' => null,
                'room' => $firstSchedule['room'] ?? null,
                'is_active' => (int) $validated['is_active'],
                'is_multi_grade' => !empty($validated['is_multi_grade']) ? 1 : 0,
                'is_combined' => count($validated['subject_ids']) > 1 ? 1 : 0,
                'load_type' => !empty($validated['is_multi_grade'])
                    ? 'multi_grade'
                    : (count($validated['subject_ids']) > 1 ? 'combined' : 'regular'),
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $teacherLoad->loadSubjects()->delete();

            foreach ($validated['subject_ids'] as $subjectId) {
                $teacherLoad->loadSubjects()->create([
                    'subject_id' => $subjectId,
                ]);
            }

            if (array_key_exists('section_ids', $validated)) {
                $this->syncLoadSections($teacherLoad, $this->selectedSectionIds($validated));
            }

            $this->syncTerms($teacherLoad, $validated['terms']);

            $teacherLoad->schedules()->delete();

            foreach ($validated['schedules'] as $schedule) {
                $teacherLoad->schedules()->create([
                    'day_of_week' => (int) $schedule['day_of_week'],
                    'time_start' => $schedule['time_start'],
                    'time_end' => $schedule['time_end'],
                    'room' => $schedule['room'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.teacher_loads.index')
            ->with('success', 'Teacher load updated successfully.');
    }

    protected function syncTerms(TeacherLoad $teacherLoad, array $termNumbers): void
    {
        $teacherLoad->terms()->delete();

        collect($termNumbers)
            ->map(fn ($termNo) => (int) $termNo)
            ->filter(fn ($termNo) => in_array($termNo, [1, 2, 3], true))
            ->unique()
            ->sort()
            ->each(function ($termNo) use ($teacherLoad) {
                $teacherLoad->terms()->create([
                    'term_no' => $termNo,
                ]);
            });
    }

    protected function selectedSectionIds(array $validated): array
    {
        return collect($validated['section_ids'] ?? [])
            ->push($validated['section_id'])
            ->map(fn ($sectionId) => (int) $sectionId)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function syncLoadSections(TeacherLoad $teacherLoad, array $sectionIds): void
    {
        $teacherLoad->loadSections()->delete();

        foreach ($sectionIds as $sectionId) {
            $teacherLoad->loadSections()->create([
                'section_id' => $sectionId,
            ]);
        }
    }

    public function destroy($id)
    {
        $teacherLoad = TeacherLoad::findOrFail($id);
        $teacherLoad->delete();

        return redirect()
            ->route('admin.teacher_loads.index')
            ->with('success', 'Teacher load removed successfully.');
    }
}
