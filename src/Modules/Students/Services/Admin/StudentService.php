<?php

namespace Modules\Students\Services\Admin;

use App\Models\Section;
use App\Models\Sf1GeneratedReport;
use App\Models\Student;
use App\Services\StudentIdService;
use App\Support\Files\FileCleanup;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Handles admin-side student write workflows.
 *
 * This service preserves student creation, student ID generation,
 * optional enrollment creation, photo upload behavior, and SF1 invalidation.
 *
 * Module: Students
 * Layer: Service
 */
class StudentService
{
    /**
     * Create a student and optionally create an enrollment record.
     *
     * @param array<string, mixed> $validated
     * @param Request $request
     * @param StudentIdService $studentIdService
     * @return Student
     */
    public function create(
        array $validated,
        Request $request,
        StudentIdService $studentIdService
    ): Student {
        return DB::transaction(function () use ($validated, $request, $studentIdService) {
            $studentType = strtolower($request->input('student_type', 'new'));

            /*
             * Resolve grade level from the selected section when available.
             * Existing behavior gives section selection priority.
             */
            $section = null;

            if ($request->filled('section_id')) {
                $section = Section::findOrFail($request->input('section_id'));
            }

            $gradeLevelId = $section
                ? $section->grade_level_id
                : ($validated['grade_level_id'] ?? $request->input('grade_level_id'));

            $schoolYearId = $validated['school_year_id'] ?? $request->input('school_year_id');

            /*
             * Preserve the manual student ID rule for old students.
             * New/transferee IDs are generated using the existing service.
             */
            if ($studentType === 'old') {
                if (empty($request->input('student_id'))) {
                    throw ValidationException::withMessages([
                        'student_id' => 'Existing Student ID is required for old students.',
                    ]);
                }

                $validated['student_id'] = $request->input('student_id');
            } else {
                if (! $gradeLevelId || ! $schoolYearId) {
                    throw ValidationException::withMessages([
                        'grade_level_id' => 'Grade level and school year are required to generate a Student ID.',
                    ]);
                }

                $validated['student_id'] = $studentIdService->generate(
                    (int) $gradeLevelId,
                    (int) $schoolYearId
                );
            }

            /*
             * Store student photo during creation using the same public/uploads
             * path used during updates.
             */
            if ($request->hasFile('photo')) {
                $validated['photo_path'] = $this->storePhoto(
                    $request->file('photo'),
                    'student_new'
                );
            }

            /*
             * These fields belong to enrollment or upload handling, not the
             * students table.
             */
            unset(
                $validated['grade_level_id'],
                $validated['school_year_id'],
                $validated['section_id'],
                $validated['student_type'],
                $validated['enrollment_status'],
                $validated['photo']
            );

            $student = Student::create($validated);

            if ($gradeLevelId && $schoolYearId) {
                $student->enrollments()->create([
                    'school_year_id' => $schoolYearId,
                    'grade_level_id' => $gradeLevelId,
                    'section_id' => $section?->id ?? $request->input('section_id'),
                    'student_type' => $studentType,
                    'status' => $request->input('enrollment_status', 'pending'),
                ]);
            }

            return $student;
        });
    }

    /**
     * Update a student and invalidate related SF1 reports when needed.
     *
     * @param Student $student
     * @param array<string, mixed> $validated
     * @param Request $request
     * @return Student
     */
    public function update(
        Student $student,
        array $validated,
        Request $request
    ): Student {
        if ($request->hasFile('photo')) {
            /*
             * Delete previous profile photo before saving the replacement so
             * unused images do not accumulate on the server.
             */
            FileCleanup::deletePublicFile($student->photo_path);

            $validated['photo_path'] = $this->storePhoto(
                $request->file('photo'),
                'student_' . $student->id
            );
        }

        /*
         * The uploaded file object must not be passed into Eloquent update().
         */
        unset($validated['photo']);

        $student->update($validated);

        $this->markCurrentSf1ReportOutdated($student);

        return $student;
    }

    /**
     * Delete a student and its profile photo.
     *
     * @param Student $student
     * @return void
     */
    public function delete(Student $student): void
    {
        FileCleanup::deletePublicFile($student->photo_path);

        $student->delete();
    }

    /**
     * Store a student profile photo in public/uploads.
     *
     * @param UploadedFile $file
     * @param string $prefix
     * @return string
     */
    protected function storePhoto(UploadedFile $file, string $prefix): string
    {
        $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $uploadPath = public_path('uploads/students/photos');

        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file->move($uploadPath, $filename);

        return 'uploads/students/photos/' . $filename;
    }

    /**
     * Mark the student's current SF1 report as outdated.
     *
     * @param Student $student
     * @return void
     */
    protected function markCurrentSf1ReportOutdated(Student $student): void
    {
        $currentEnrollment = $student->currentEnrollment()->first();

        if (! $currentEnrollment) {
            return;
        }

        Sf1GeneratedReport::query()
            ->where('school_year_id', $currentEnrollment->school_year_id)
            ->where('grade_level_id', $currentEnrollment->grade_level_id)
            ->where('section_id', $currentEnrollment->section_id)
            ->update([
                'needs_regeneration' => true,
                'status' => 'outdated',
            ]);
    }
}