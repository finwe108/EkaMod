<?php

namespace Modules\Teachers\Services\Admin;

use App\Models\TeacherLoad;
use Illuminate\Support\Facades\DB;

/**
 * Handles admin-side teacher load write workflows.
 *
 * This service preserves the existing teacher load creation and update
 * behavior, including:
 *
 * - parent teacher_loads record updates
 * - related load subjects
 * - related load sections
 * - related terms
 * - related schedules
 *
 * Module: Teachers
 * Layer: Service
 */
class TeacherLoadService
{
    /**
     * Create a teacher load with subjects, sections, terms, and schedules.
     *
     * @param array<string, mixed> $validated
     * @return TeacherLoad
     */
    public function create(array $validated): TeacherLoad
    {
        return DB::transaction(function () use ($validated) {
            $firstSubjectId = $validated['subject_ids'][0] ?? null;
            $firstSchedule = $validated['schedules'][0] ?? null;

            $teacherLoad = TeacherLoad::create([
                'school_year_id' => $validated['school_year_id'],
                'teacher_id' => $validated['teacher_id'],
                'subject_id' => $firstSubjectId,
                'section_id' => $validated['section_id'],
                'schedule_days' => null,
                'schedule_time' => null,
                'room' => $firstSchedule['room'] ?? null,
                'is_active' => (int) ($validated['is_active'] ?? 1),
                'is_multi_grade' => ! empty($validated['is_multi_grade']) ? 1 : 0,
                'is_combined' => count($validated['subject_ids']) > 1 ? 1 : 0,
                'load_type' => ! empty($validated['is_multi_grade'])
                    ? 'multi_grade'
                    : (count($validated['subject_ids']) > 1 ? 'combined' : 'regular'),
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $this->syncSubjects($teacherLoad, $validated['subject_ids']);

            if (array_key_exists('section_ids', $validated)) {
                $this->syncLoadSections($teacherLoad, $this->selectedSectionIds($validated));
            }

            $this->syncTerms($teacherLoad, $validated['terms']);

            $this->syncSchedules($teacherLoad, $validated['schedules']);

            return $teacherLoad;
        });
    }

    /**
     * Update a teacher load with subjects, sections, terms, and schedules.
     *
     * @param TeacherLoad $teacherLoad
     * @param array<string, mixed> $validated
     * @return TeacherLoad
     */
    public function update(TeacherLoad $teacherLoad, array $validated): TeacherLoad
    {
        return DB::transaction(function () use ($teacherLoad, $validated) {
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
                'is_multi_grade' => ! empty($validated['is_multi_grade']) ? 1 : 0,
                'is_combined' => count($validated['subject_ids']) > 1 ? 1 : 0,
                'load_type' => ! empty($validated['is_multi_grade'])
                    ? 'multi_grade'
                    : (count($validated['subject_ids']) > 1 ? 'combined' : 'regular'),
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $this->syncSubjects($teacherLoad, $validated['subject_ids']);
            $this->syncLoadSections($teacherLoad, $this->selectedSectionIds($validated));
            $this->syncTerms($teacherLoad, $validated['terms']);
            $this->syncSchedules($teacherLoad, $validated['schedules']);

            return $teacherLoad->refresh();
        });
    }

    /**
     * Delete a teacher load.
     *
     * @param TeacherLoad $teacherLoad
     * @return void
     */
    public function delete(TeacherLoad $teacherLoad): void
    {
        $teacherLoad->delete();
    }

    /**
     * Sync load subjects.
     *
     * @param TeacherLoad $teacherLoad
     * @param array<int, int|string> $subjectIds
     * @return void
     */
    protected function syncSubjects(TeacherLoad $teacherLoad, array $subjectIds): void
    {
        $teacherLoad->loadSubjects()->delete();

        foreach ($subjectIds as $subjectId) {
            $teacherLoad->loadSubjects()->create([
                'subject_id' => $subjectId,
            ]);
        }
    }

    /**
     * Sync load terms.
     *
     * @param TeacherLoad $teacherLoad
     * @param array<int, int|string> $termNumbers
     * @return void
     */
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

    /**
     * Sync multi-section load records.
     *
     * @param TeacherLoad $teacherLoad
     * @param array<int, int> $sectionIds
     * @return void
     */
    protected function syncLoadSections(TeacherLoad $teacherLoad, array $sectionIds): void
    {
        $teacherLoad->loadSections()->delete();

        foreach ($sectionIds as $sectionId) {
            $teacherLoad->loadSections()->create([
                'section_id' => $sectionId,
            ]);
        }
    }

    /**
     * Sync schedules for a teacher load.
     *
     * @param TeacherLoad $teacherLoad
     * @param array<int, array<string, mixed>> $schedules
     * @return void
     */
    protected function syncSchedules(TeacherLoad $teacherLoad, array $schedules): void
    {
        $teacherLoad->schedules()->delete();

        foreach ($schedules as $schedule) {
            $teacherLoad->schedules()->create([
                'day_of_week' => (int) $schedule['day_of_week'],
                'time_start' => $schedule['time_start'],
                'time_end' => $schedule['time_end'],
                'room' => $schedule['room'] ?? null,
            ]);
        }
    }

    /**
     * Resolve all selected section IDs for regular and multi-section loads.
     *
     * @param array<string, mixed> $validated
     * @return array<int, int>
     */
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
}