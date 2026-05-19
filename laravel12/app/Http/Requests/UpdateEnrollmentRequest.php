<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Enrollment;
use App\Models\Section;

class UpdateEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $enrollmentId = $this->route('enrollment')?->id;

        return [
            'student_id' => ['required', 'exists:students,id'],
            'school_year_id' => ['required', 'exists:school_years,id'],
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'section_id' => ['required', 'exists:sections,id'],

            'student_type' => ['required', 'in:new,old,transferee'],
            'status' => ['required', 'in:enrolled,pending,withdrawn'],

            'date_enrolled' => ['nullable', 'date'],
            'date_dropped' => ['nullable', 'date'],
            'date_transferred_out' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $studentId = $this->input('student_id');
            $schoolYearId = $this->input('school_year_id');
            $enrollmentId = $this->route('enrollment')?->id;
            $sectionId = $this->input('section_id');
            $gradeLevelId = $this->input('grade_level_id');

            $exists = Enrollment::where('student_id', $studentId)
                ->where('school_year_id', $schoolYearId)
                ->where('id', '!=', $enrollmentId)
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'student_id',
                    'This student is already enrolled for the selected school year.'
                );
            }

            if ($sectionId && $gradeLevelId) {
                $section = Section::find($sectionId);

                if ($section && (int) $section->grade_level_id !== (int) $gradeLevelId) {
                    $validator->errors()->add(
                        'section_id',
                        'Selected section does not match the chosen grade level.'
                    );
                }
            }
        });
    }
}