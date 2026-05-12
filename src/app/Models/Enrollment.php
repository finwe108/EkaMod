<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'school_year_id',
        'grade_level_id',
        'section_id',
        'enrollment_date',
        'student_type',
        'status',
        'date_enrolled',
        'date_dropped',
        'date_transferred_out',
        'remarks',
    ];

    protected $casts = [
        'date_enrolled' => 'date',
        'date_dropped' => 'date',
        'date_transferred_out' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}