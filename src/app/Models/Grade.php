<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'teacher_load_id',
        'student_id',
        'subject_id',     // legacy compatibility
        'quarter',        // legacy compatibility
        'term_no',
        'grade',          // legacy compatibility
        'written_work',
        'performance_task',
        'quarterly_exam',
        'initial_grade',
        'final_grade',
        'remarks',
    ];

    protected $casts = [
        'written_work' => 'decimal:2',
        'performance_task' => 'decimal:2',
        'quarterly_exam' => 'decimal:2',
        'initial_grade' => 'decimal:2',
        'final_grade' => 'decimal:2',
        'grade' => 'decimal:2',
    ];

    public function teacherLoad()
    {
        return $this->belongsTo(TeacherLoad::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getDisplayTermAttribute(): ?int
    {
        return $this->term_no ?? $this->quarter;
    }

    public function getDisplayGradeAttribute()
    {
        return $this->final_grade ?? $this->grade;
    }
}