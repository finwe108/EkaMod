<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name',
        'school_year_id',
        'grade_level_id',
        'teacher_id',
        'capacity', 
    ];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function adviser()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teacherLoads()
    {
        return $this->hasMany(TeacherLoad::class);
    }
}