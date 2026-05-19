<?php

namespace Modules\Teachers\Requests\Admin;

use App\Models\TeacherLoad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Teachers\Services\Admin\TeacherLoadConflictService;

/**
 * Handles validation for updating teacher loads.
 *
 * This request preserves existing teacher load validation behavior while
 * excluding the current teacher load from conflict detection.
 *
 * Module: Teachers
 * Layer: Request
 */
class UpdateTeacherLoadRequest extends FormRequest
{
    /**
     * Authorization remains handled by existing admin middleware and RBAC.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize checkbox values before validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_multi_grade' => $this->boolean('is_multi_grade'),
        ]);
    }

    /**
     * Get validation rules for updating teacher loads.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'teacher_id' => ['required', 'exists:teachers,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'school_year_id' => ['required', 'exists:school_years,id'],

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

            'is_active' => ['required', 'boolean'],
            'is_multi_grade' => ['nullable', 'boolean'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Add schedule conflict validation after basic validation passes.
     *
     * @param mixed $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $validated = $this->validated();

            $this->validateScheduleTimes($validated);
            $this->validateInternalConflicts($validated);
            $this->validateExternalConflicts($validated);
        });
    }

    /**
     * Ensure every schedule end time is later than the start time.
     *
     * @param array<string, mixed> $validated
     * @return void
     */
    protected function validateScheduleTimes(array $validated): void
    {
        foreach ($validated['schedules'] as $index => $schedule) {
            if ($schedule['time_end'] <= $schedule['time_start']) {
                throw ValidationException::withMessages([
                    "schedules.$index.time_end" => 'End time must be later than start time.',
                ]);
            }
        }
    }

    /**
     * Detect overlaps within the submitted schedule rows.
     *
     * @param array<string, mixed> $validated
     * @return void
     */
    protected function validateInternalConflicts(array $validated): void
    {
        $internalConflicts = TeacherLoadConflictService::hasInternalConflicts(
            $validated['schedules']
        );

        if (empty($internalConflicts)) {
            return;
        }

        $messages = [];

        foreach ($internalConflicts as $conflict) {
            $rows = collect($conflict['rows'])
                ->map(fn ($row) => $row + 1)
                ->implode(' and ');

            $messages['schedules'] = "Schedule rows {$rows} overlap.";
        }

        throw ValidationException::withMessages($messages);
    }

    /**
     * Detect conflicts against existing teacher load schedules.
     *
     * @param array<string, mixed> $validated
     * @return void
     */
    protected function validateExternalConflicts(array $validated): void
    {
        $teacherLoad = $this->route('teacher_load');

        if (! $teacherLoad instanceof TeacherLoad) {
            $teacherLoad = TeacherLoad::find($teacherLoad);
        }

        $conflicts = TeacherLoadConflictService::findConflicts(
            teacherId: (int) $validated['teacher_id'],
            sectionIds: $this->selectedSectionIds($validated),
            schoolYearId: (int) $validated['school_year_id'],
            termNumbers: $validated['terms'],
            schedules: $validated['schedules'],
            ignoreTeacherLoadId: $teacherLoad?->id
        );

        if (empty($conflicts)) {
            return;
        }

        $messages = [];

        foreach ($conflicts as $conflict) {
            $rowNumber = $conflict['row'] + 1;

            $messages["schedules.{$conflict['row']}.day_of_week"] =
                "Schedule row {$rowNumber}: {$conflict['message']}";
        }

        throw ValidationException::withMessages($messages);
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