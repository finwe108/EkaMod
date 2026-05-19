<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'grade_level_id',
    ];

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function teacherLoads()
    {
        return $this->hasMany(TeacherLoad::class);
    }

    public function gradingProfile()
    {
        return $this->belongsTo(GradingProfile::class);
    }
}