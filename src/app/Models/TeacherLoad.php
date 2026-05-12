<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherLoad extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'section_id',
        'school_year_id',
        'schedule_days',
        'schedule_time',
        'room',
        'is_active',
        'is_multi_grade',
        'is_combined',
        'load_type',
        'remarks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_multi_grade' => 'boolean',
        'is_combined' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function schedules()
    {
        return $this->hasMany(TeacherLoadSchedule::class);
    }

    public function loadSections()
    {
        return $this->hasMany(TeacherLoadSection::class);
    }

    public function loadSubjects()
    {
        return $this->hasMany(TeacherLoadSubject::class);
    }

    public function terms()
    {
        return $this->hasMany(TeacherLoadTerm::class);
    }

    public function termNumbers(): array
    {
        $terms = $this->relationLoaded('terms')
            ? $this->terms
            : $this->terms()->get();

        $termNumbers = $terms
            ->pluck('term_no')
            ->map(fn ($termNo) => (int) $termNo)
            ->unique()
            ->sort()
            ->values()
            ->all();

        return $termNumbers ?: [1, 2, 3];
    }

    public function termLabel(): string
    {
        $termNumbers = $this->termNumbers();

        if ($termNumbers === [1, 2, 3]) {
            return 'Whole Year';
        }

        return collect($termNumbers)
            ->map(fn ($termNo) => 'Term '.$termNo)
            ->implode(', ');
    }
}
