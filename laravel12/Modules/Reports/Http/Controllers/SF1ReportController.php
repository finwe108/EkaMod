<?php

namespace Modules\Reports\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Sf1GeneratedReport;
use App\Services\Sf1TemplateExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Reports\Services\SF1\SF1ReportService;

/**
 * Handles SF1 report screens, generation queue, file generation, and download.
 *
 * This controller is part of the Reports module. It preserves the existing
 * SF1 route names, URLs, generation behavior, and download behavior from the
 * legacy EnrollmentController.
 *
 * Module: Reports
 * Layer: HTTP Controller
 */
class SF1ReportController extends Controller
{
    /**
     * Show the SF1 filter screen.
     *
     * @param Request $request
     * @return View
     */
    public function filter(Request $request): View
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYears = SchoolYear::orderByDesc('name')->get();
        $gradeLevels = GradeLevel::orderBy('sort_order')->get();

        $schoolYearId = $request->school_year_id ?: optional($activeSchoolYear)->id;

        $sections = Section::query()
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->orderBy('name')
            ->get();

        return view('reports::sf1.filter', compact(
            'activeSchoolYear',
            'schoolYears',
            'gradeLevels',
            'sections',
            'schoolYearId'
        ));
    }

    /**
     * Show printable SF1 report.
     *
     * @param Request $request
     * @param SF1ReportService $sf1ReportService
     * @return View
     */
    public function print(
        Request $request,
        SF1ReportService $sf1ReportService
    ): View {
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

        $enrollments = $sf1ReportService->getEnrollments(
            (int) $request->school_year_id,
            $request->filled('grade_level_id') ? (int) $request->grade_level_id : null,
            $request->filled('section_id') ? (int) $request->section_id : null
        );

        $maleCount = $enrollments
            ->filter(fn ($item) => strtolower($item->student->sex ?? '') === 'male')
            ->count();

        $femaleCount = $enrollments
            ->filter(fn ($item) => strtolower($item->student->sex ?? '') === 'female')
            ->count();

        return view('reports::sf1.print', compact(
            'enrollments',
            'schoolYear',
            'gradeLevel',
            'section',
            'maleCount',
            'femaleCount'
        ));
    }

    /**
     * Queue SF1 generated report records for matching sections.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function queue(Request $request): RedirectResponse
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
                    /*
                     * Preserve existing behavior:
                     * completed reports stay completed; otherwise the report is
                     * reset to pending with progress 0.
                     */
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

    /**
     * Display queued/generated SF1 report records.
     *
     * @param Request $request
     * @return View
     */
    public function generated(Request $request): View
    {
        $schoolYearId = $request->query('school_year_id');

        $schoolYears = SchoolYear::orderByDesc('name')->get();

        $reports = Sf1GeneratedReport::with(['schoolYear', 'gradeLevel', 'section'])
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->orderBy('grade_level_id')
            ->orderBy('section_id')
            ->get();

        return view('reports::sf1.generated', compact(
            'reports',
            'schoolYears',
            'schoolYearId'
        ));
    }

    /**
     * Generate an SF1 Excel report file.
     *
     * @param Sf1GeneratedReport $report
     * @param Sf1TemplateExportService $templateExportService
     * @param SF1ReportService $sf1ReportService
     * @return JsonResponse
     */
    public function generate(
        Sf1GeneratedReport $report,
        Sf1TemplateExportService $templateExportService,
        SF1ReportService $sf1ReportService
    ): JsonResponse {
        try {
            $report->update([
                'status' => 'processing',
                'progress' => 10,
                'error_message' => null,
            ]);

            $schoolYear = $report->schoolYear;
            $gradeLevel = $report->gradeLevel;
            $section = $report->section;

            $enrollments = $sf1ReportService->getEnrollments(
                (int) $schoolYear->id,
                (int) $gradeLevel->id,
                (int) $section->id
            );

            $report->update(['progress' => 35]);

            $students = $sf1ReportService->buildStudentRows($enrollments);

            $counts = $sf1ReportService->countBySex($students);

            $meta = $sf1ReportService->buildMeta(
                $schoolYear,
                $gradeLevel,
                $section,
                $counts['male'],
                $counts['female']
            );

            $report->update(['progress' => 60]);

            $templatePath = storage_path('app/templates/SF1 TEMPLATE.xlsx');

            if (! file_exists($templatePath)) {
                throw new \RuntimeException('SF1 template file not found.');
            }

            $safeName = $sf1ReportService->buildFilename(
                $schoolYear,
                $gradeLevel,
                $section
            );

            $relativePath = 'reports/sf1/' . $schoolYear->id . '/' . $safeName;
            $outputPath = storage_path('app/' . $relativePath);

            if (! is_dir(dirname($outputPath))) {
                mkdir(dirname($outputPath), 0775, true);
            }

            /*
             * Delete the old generated file before regenerating to prevent
             * stale downloads when report data changes.
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

            $templateExportService->export($templatePath, $outputPath, $meta, $students);

            \Log::info('SF1 export finished', [
                'report_id' => $report->id,
                'output_exists' => file_exists($outputPath),
            ]);

            $report->update(['progress' => 95]);

            if (! file_exists($outputPath)) {
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

    /**
     * Download a generated SF1 file.
     *
     * @param Sf1GeneratedReport $report
     * @return mixed
     */
    public function downloadGenerated(Sf1GeneratedReport $report): mixed
    {
        abort_unless($report->status === 'completed', 404);

        $path = storage_path('app/' . $report->file_path);

        abort_unless(file_exists($path), 404);

        return response()->download($path, $report->filename);
    }
}