<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\SchoolSetting;
use App\Services\Sf1TemplateExportService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Exports\Sf1Export;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Sf1GeneratedReport;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function index(Request $request)
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

        return view('admin.enrollments.index', compact(
            'enrollments',
            'schoolYears',
            'gradeLevels',
            'sections',
            'schoolYearId',
            'activeSchoolYear'
        ));
    }

    public function create(Request $request)
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

        return view('admin.enrollments.create', compact(
            'student',
            'schoolYears',
            'gradeLevels',
            'sections',
            'activeSchoolYear'
        ));
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $data = $request->validated();

        $this->validateGradeProgression($data);

        $enrollment = Enrollment::create($data);

        return redirect()
            ->route('admin.students.show', $enrollment->student_id)
            ->with('success', 'Enrollment created successfully.');
    }

    public function edit(Enrollment $enrollment)
    {
        $enrollment->load('student');

        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYears = SchoolYear::orderByDesc('name')->get();
        $gradeLevels = GradeLevel::orderBy('sort_order')->get();

        $sections = Section::where('school_year_id', $enrollment->school_year_id)
            ->orderBy('name')
            ->get();
        

        return view('admin.enrollments.edit', compact(
            'enrollment',
            'schoolYears',
            'gradeLevels',
            'sections',
            'activeSchoolYear'
        ));
    }

    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment)
    {
        $data = $request->validated();

        $enrollment->update($data);

        Sf1GeneratedReport::query()
            ->where('school_year_id', $enrollment->school_year_id)
            ->where('grade_level_id', $enrollment->grade_level_id)
            ->where('section_id', $enrollment->section_id)
            ->update([
                'needs_regeneration' => true,
                'status' => 'outdated',
            ]);

        return redirect()
            ->route('admin.students.show', $enrollment->student_id)
            ->with('success', 'Enrollment updated successfully.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $studentId = $enrollment->student_id;

        $enrollment->delete();

        return redirect()
            ->route('admin.students.show', $studentId)
            ->with('success', 'Enrollment removed successfully.');
    }

    public function sectionsBySchoolYear(Request $request)
    {
        $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
        ]);

        $sections = Section::with('gradeLevel')
            ->where('school_year_id', $request->school_year_id)
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

    public function sf1Filter(Request $request)
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYears = SchoolYear::orderByDesc('name')->get();
        $gradeLevels = GradeLevel::orderBy('sort_order')->get();

        $schoolYearId = $request->school_year_id ?: optional($activeSchoolYear)->id;

        $sections = Section::query()
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->orderBy('name')
            ->get();

        return view('admin.reports.sf1_filter', compact(
            'activeSchoolYear',
            'schoolYears',
            'gradeLevels',
            'sections',
            'schoolYearId'
        ));
    }

    public function sf1(Request $request)
    {
        $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'grade_level_id' => ['nullable', 'exists:grade_levels,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
        ]);

        $schoolYear = SchoolYear::findOrFail($request->school_year_id);
        $gradeLevel = $request->filled('grade_level_id')
            ? GradeLevel::find($request->grade_level_id)
            : null;
        $section = $request->filled('section_id')
            ? Section::with('gradeLevel')->find($request->section_id)
            : null;

        $enrollments = Enrollment::with(['student', 'schoolYear', 'gradeLevel', 'section'])
            ->where('school_year_id', $request->school_year_id)
            ->when($request->filled('grade_level_id'), function ($query) use ($request) {
                $query->where('grade_level_id', $request->grade_level_id);
            })
            ->when($request->filled('section_id'), function ($query) use ($request) {
                $query->where('section_id', $request->section_id);
            })
            ->whereIn('status', ['enrolled', 'completed'])
            ->get()
            ->sortBy(function ($item) {
                return strtolower(
                    ($item->student->last_name ?? '') . ' ' .
                    ($item->student->first_name ?? '') . ' ' .
                    ($item->student->middle_name ?? '')
                );
            })
            ->values();

        $maleCount = $enrollments->filter(fn ($item) => strtolower($item->student->sex ?? '') === 'male')->count();
        $femaleCount = $enrollments->filter(fn ($item) => strtolower($item->student->sex ?? '') === 'female')->count();

        return view('admin.reports.sf1_print', compact(
            'enrollments',
            'schoolYear',
            'gradeLevel',
            'section',
            'maleCount',
            'femaleCount'
        ));
    }

    public function exportSf1(Request $request, \App\Services\Sf1TemplateExportService $service)
    {
        $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'section_id' => ['required', 'exists:sections,id'],
        ]);

        $schoolYear = \App\Models\SchoolYear::findOrFail($request->school_year_id);
        $gradeLevel = \App\Models\GradeLevel::findOrFail($request->grade_level_id);
        $section = \App\Models\Section::findOrFail($request->section_id);

        $enrollments = \App\Models\Enrollment::with('student')
            ->where('school_year_id', $schoolYear->id)
            ->where('grade_level_id', $gradeLevel->id)
            ->where('section_id', $section->id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->get();

        $students = $this->buildSf1StudentRows($enrollments);

        $maleCount = collect($students)->where('sex', 'male')->count();
        $femaleCount = collect($students)->where('sex', 'female')->count();

        $schoolSetting = SchoolSetting::current();

        $meta = [
            'school_id' => $schoolSetting->school_id,
            'region' => $schoolSetting->region,
            'division' => $schoolSetting->division,
            'district' => $schoolSetting->district,
            'school_name' => $schoolSetting->school_name,
            'school_year' => $schoolYear->name,
            'grade_level' => $gradeLevel->name,
            'section' => $section->name,
            'first_friday_of_june' => $this->getFirstFridayOfJune($schoolYear->name),

            'bosy_male' => $maleCount,
            'bosy_female' => $femaleCount,
            'bosy_total' => $maleCount + $femaleCount,

            'eosy_male' => $maleCount,
            'eosy_female' => $femaleCount,
            'eosy_total' => $maleCount + $femaleCount,

            'adviser_name' => '',
            'bosy_date' => now(),
            'school_head_name' => $schoolSetting->school_head_name,
            'eosy_date' => now(),
        ];

        $templatePath = storage_path('app/templates/SF1 TEMPLATE.xlsx');

        if (!file_exists($templatePath)) {
            return back()->withErrors([
                'export' => 'SF1 template file not found.',
            ])->withInput();
        }

        $safeName = 'SF1_' . preg_replace(
            '/[^A-Za-z0-9_\-]/',
            '_',
            $schoolYear->name . '_' . $gradeLevel->name . '_' . $section->name
        ) . '.xlsx';

        $reportsDir = storage_path('app/reports');

        if (!is_dir($reportsDir)) {
            mkdir($reportsDir, 0775, true);
        }

        $outputPath = $reportsDir . '/' . $safeName;

        \Log::info('SF1 export data check', [
            'report_id' => $report->id,
            'school_year' => $schoolYear?->name,
            'grade_level' => $gradeLevel?->name,
            'section' => $section?->name,
            'enrollment_count' => $enrollments->count(),
            'student_count' => count($students),
            'first_student' => $students[0] ?? null,
            'meta' => $meta,
        ]);
        
        try {
            $service->export($templatePath, $outputPath, $meta, $students);

            return redirect()
                ->route('admin.reports.sf1.filter', [
                    'school_year_id' => $schoolYear->id,
                    'grade_level_id' => $gradeLevel->id,
                    'section_id' => $section->id,
                ])
                ->with('success', 'SF1 file generated successfully.')
                ->with('sf1_download_file', $safeName);

        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors([
                'export' => 'SF1 export failed: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    public function downloadSf1(string $filename)
    {
        $filename = basename($filename);
        $path = storage_path('app/reports/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'Generated SF1 file not found.');
        }

        return response()->download($path, $filename);
    }
    protected function buildSf1StudentRows($enrollments): array
    {
        return $enrollments->map(function ($enrollment) {
            $student = $enrollment->student;

            return [
                'lrn' => $student->lrn,
                'full_name_sf1' => $this->formatSf1Name($student),
                'sort_name' => strtolower(($student->last_name ?? '') . ' ' . ($student->first_name ?? '') . ' ' . ($student->middle_name ?? '')),
                'sex' => strtolower((string) $student->sex),
                'sex_short' => strtoupper(substr((string) $student->sex, 0, 1)),
                'birth_date' => $student->birth_date,
                'age' => $student->birth_date ? Carbon::parse($student->birth_date)->age : '',
                'birth_place' => $student->birth_place,
                'mother_tongue' => $student->mother_tongue,
                'ethnic_group' => $student->ethnic_group,
                'religion' => $student->religion,
                'house_street' => $student->house_street,
                'barangay' => $student->barangay,
                'municipality_city' => $student->municipality_city,
                'province' => $student->province,
                'father_name' => $student->father_name,
                'mother_name' => $student->mother_name,
                'guardian_name' => $student->guardian_name,
                'guardian_relationship' => $student->guardian_relationship,
                'parent_guardian_contact' => $student->parent_guardian_contact ?: $student->guardian_contact,
                'code' => $this->buildSf1Code($enrollment, $student),
                'required_info' => $this->buildSf1RequiredInfo($enrollment, $student),
            ];
        })->all();
    }

    protected function formatSf1Name($student): string
    {
        return collect([
            $student->last_name,
            $student->first_name,
            $student->middle_name,
            $student->suffix,
        ])->filter()->implode(', ');
    }

    protected function buildSf1Code($enrollment, $student): string
    {
        $codes = [];

        if ($enrollment->status === 'transferred_out') {
            $codes[] = 'T/O';
        }

        if ($enrollment->student_type === 'transferee') {
            $codes[] = 'T/I';
        }

        if ($enrollment->status === 'dropped') {
            $codes[] = 'DRP';
        }

        return implode(', ', $codes);
    }

    protected function buildSf1RequiredInfo($enrollment, $student): string
    {
        $parts = [];

        if ($enrollment->status === 'transferred_out') {
            $parts[] = 'Transferred Out';
        }

        if ($enrollment->student_type === 'transferee') {
            $parts[] = 'Transferred In';
        }

        if ($enrollment->status === 'dropped') {
            $parts[] = 'Dropped';
        }

        return implode('; ', $parts);
    }

    protected function getFirstFridayOfJune(string $schoolYearName): string
    {
        preg_match('/(\d{4})/', $schoolYearName, $matches);
        $year = isset($matches[1]) ? (int) $matches[1] : now()->year;

        $date = Carbon::create($year, 6, 1);

        while ($date->dayOfWeek !== Carbon::FRIDAY) {
            $date->addDay();
        }

        return $date->toDateString();
    }

    public function prepareSf1(Request $request)
    {
        $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'section_id' => ['required', 'exists:sections,id'],
        ]);

        $schoolYear = SchoolYear::findOrFail($request->school_year_id);
        $gradeLevel = GradeLevel::findOrFail($request->grade_level_id);
        $section = Section::with('gradeLevel')->findOrFail($request->section_id);

        $enrollments = Enrollment::with(['student', 'schoolYear', 'gradeLevel', 'section'])
            ->where('school_year_id', $schoolYear->id)
            ->where('grade_level_id', $gradeLevel->id)
            ->where('section_id', $section->id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->get()
            ->sortBy(function ($enrollment) {
                return strtolower(
                    ($enrollment->student->last_name ?? '') . ' ' .
                    ($enrollment->student->first_name ?? '') . ' ' .
                    ($enrollment->student->middle_name ?? '')
                );
            })
            ->values();

        $maleCount = $enrollments->filter(fn ($item) => strtolower($item->student->sex ?? '') === 'male')->count();
        $femaleCount = $enrollments->filter(fn ($item) => strtolower($item->student->sex ?? '') === 'female')->count();

        return view('admin.reports.sf1_prepare', compact(
            'schoolYear',
            'gradeLevel',
            'section',
            'enrollments',
            'maleCount',
            'femaleCount'
        ));
    }

    public function queueSf1(Request $request)
    {
        $data = $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'grade_level_id' => ['nullable', 'exists:grade_levels,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
        ]);

        $sections = Section::query()
            ->when($data['grade_level_id'] ?? null, fn ($q) => $q->where('grade_level_id', $data['grade_level_id']))
            ->when($data['section_id'] ?? null, fn ($q) => $q->where('id', $data['section_id']))
            ->where('school_year_id', $data['school_year_id'])
            ->get();

        foreach ($sections as $section) {
            Sf1GeneratedReport::updateOrCreate(
                [
                    'school_year_id' => $data['school_year_id'],
                    'grade_level_id' => $section->grade_level_id,
                    'section_id' => $section->id,
                ],
                [
                    'status' => DB::raw("CASE WHEN status = 'completed' THEN status ELSE 'pending' END"),
                    'progress' => DB::raw("CASE WHEN status = 'completed' THEN progress ELSE 0 END"),
                    'generated_by' => auth()->id(),
                ]
            );
        }

        return redirect()
            ->route('admin.reports.sf1.generated', [
                'school_year_id' => $data['school_year_id'],
            ])
            ->with('success', 'SF1 generation list created.');
    }

    public function sf1Generated(Request $request)
    {
        $schoolYearId = $request->query('school_year_id');

        $schoolYears = SchoolYear::orderByDesc('name')->get();

        $reports = Sf1GeneratedReport::with(['schoolYear', 'gradeLevel', 'section'])
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->orderBy('grade_level_id')
            ->orderBy('section_id')
            ->get();

        return view('admin.reports.sf1_generated', compact(
            'reports',
            'schoolYears',
            'schoolYearId'
        ));
    }

    public function generateSf1Report(Sf1GeneratedReport $report, Sf1TemplateExportService $service)
    {
        try {
            $report->update([
                'status' => 'processing',
                'progress' => 10,
                'error_message' => null,
            ]);

            $schoolYear = $report->schoolYear;
            $gradeLevel = $report->gradeLevel;
            $section = $report->section;

            $enrollments = Enrollment::with('student')
                ->where('school_year_id', $schoolYear->id)
                ->where('grade_level_id', $gradeLevel->id)
                ->where('section_id', $section->id)
                ->whereIn('status', ['enrolled', 'completed'])
                ->get();

            $report->update(['progress' => 35]);

            $students = $this->buildSf1StudentRows($enrollments);

            $maleCount = collect($students)->where('sex', 'male')->count();
            $femaleCount = collect($students)->where('sex', 'female')->count();

            $schoolSetting = SchoolSetting::current();

            $meta = [
                'school_id' => $schoolSetting->school_id,
                'region' => $schoolSetting->region,
                'division' => $schoolSetting->division,
                'district' => $schoolSetting->district,
                'school_name' => $schoolSetting->school_name,
                'school_year' => $schoolYear->name,
                'grade_level' => $gradeLevel->name,
                'section' => $section->name,
                'first_friday_of_june' => $this->getFirstFridayOfJune($schoolYear->name),

                'bosy_male' => $maleCount,
                'bosy_female' => $femaleCount,
                'bosy_total' => $maleCount + $femaleCount,

                'eosy_male' => $maleCount,
                'eosy_female' => $femaleCount,
                'eosy_total' => $maleCount + $femaleCount,

                'adviser_name' => '',
                'bosy_date' => now(),
                'school_head_name' => $schoolSetting->school_head_name,
                'eosy_date' => now(),
            ];

            $report->update(['progress' => 60]);

            $templatePath = storage_path('app/templates/SF1 TEMPLATE.xlsx');

            if (!file_exists($templatePath)) {
                throw new \RuntimeException('SF1 template file not found.');
            }

            $safeName = 'SF1_' . preg_replace(
                '/[^A-Za-z0-9_\-]/',
                '_',
                $schoolYear->name . '_' . $gradeLevel->name . '_' . $section->name
            ) . '.xlsx';

            $relativePath = 'reports/sf1/' . $schoolYear->id . '/' . $safeName;
            $outputPath = storage_path('app/' . $relativePath);

            if (!is_dir(dirname($outputPath))) {
                mkdir(dirname($outputPath), 0775, true);
            }

            /*
            |--------------------------------------------------------------------------
            | Delete old generated file before regenerating
            |--------------------------------------------------------------------------
            */
            if ($report->file_path) {
                $oldPath = storage_path('app/' . $report->file_path);

                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $report->update(['progress' => 75]);

            \Log::info('SF1 export started', [
                'report_id' => $report->id,
                'template' => $templatePath,
                'output' => $outputPath,
                'student_count' => count($students),
            ]);

            $service->export($templatePath, $outputPath, $meta, $students);

            \Log::info('SF1 export finished', [
                'report_id' => $report->id,
                'output_exists' => file_exists($outputPath),
            ]);

            $report->update(['progress' => 95]);

            if (!file_exists($outputPath)) {
                throw new \RuntimeException('SF1 file was not created.');
            }

            $report->update([
                'status' => 'completed',
                'progress' => 100,
                'filename' => $safeName,
                'file_path' => $relativePath,
                'generated_at' => now(),
                'source_updated_at' => now(),
                'needs_regeneration' => false,
                'error_message' => null,
            ]);

            return response()->json([
                'ok' => true,
                'status' => 'completed',
                'progress' => 100,
            ]);

        } catch (\Throwable $e) {
            report($e);

            $report->update([
                'status' => 'failed',
                'progress' => 0,
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'status' => 'failed',
                'progress' => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function downloadGeneratedSf1(Sf1GeneratedReport $report)
    {
        abort_unless($report->status === 'completed', 404);

        $path = storage_path('app/' . $report->file_path);

        abort_unless(file_exists($path), 404);

        return response()->download($path, $report->filename);
    }

    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $data = $request->validate([
            'status' => [
                'required',
                'in:pending,enrolled,completed,promoted,retained,dropped,transferred_out',
            ],
        ]);

        $enrollment->update([
            'status' => $data['status'],
        ]);

        Sf1GeneratedReport::query()
            ->where('school_year_id', $enrollment->school_year_id)
            ->where('grade_level_id', $enrollment->grade_level_id)
            ->where('section_id', $enrollment->section_id)
            ->update([
                'needs_regeneration' => true,
                'status' => 'outdated',
            ]);

        return back()->with('success', 'Student enrollment status updated successfully.');
    }

    protected function validateGradeProgression(array $data): void
    {
        $studentId = $data['student_id'];
        $newGradeLevelId = (int) $data['grade_level_id'];
        $newSchoolYearId = (int) $data['school_year_id'];

        $previousEnrollment = Enrollment::with(['gradeLevel', 'schoolYear'])
            ->where('student_id', $studentId)
            ->where('school_year_id', '!=', $newSchoolYearId)
            ->latest('school_year_id')
            ->first();

        if (!$previousEnrollment) {
            return;
        }

        if (in_array($previousEnrollment->status, ['dropped', 'transferred_out'], true)) {
            throw ValidationException::withMessages([
                'grade_level_id' => 'This student cannot be enrolled because the previous status is ' . str_replace('_', ' ', $previousEnrollment->status) . '.',
            ]);
        }

        $previousGrade = $previousEnrollment->gradeLevel;
        $newGrade = GradeLevel::find($newGradeLevelId);

        if (!$previousGrade || !$newGrade) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | If retained, same grade level is allowed
        |--------------------------------------------------------------------------
        */
        if ($previousEnrollment->status === 'retained') {
            if ((int) $previousGrade->id !== (int) $newGrade->id) {
                throw ValidationException::withMessages([
                    'grade_level_id' => 'This student was marked as retained. They must enroll in the same grade level.',
                ]);
            }

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | If promoted/completed, only next grade level is allowed
        |--------------------------------------------------------------------------
        */
        $expectedNextGrade = GradeLevel::where('sort_order', $previousGrade->sort_order + 1)->first();

        if (!$expectedNextGrade || (int) $newGrade->id !== (int) $expectedNextGrade->id) {
            throw ValidationException::withMessages([
                'grade_level_id' => 'Invalid grade level. Previous level was ' . $previousGrade->name . '. Expected next level is ' . ($expectedNextGrade?->name ?? 'not configured') . '.',
            ]);
        }
    }

}