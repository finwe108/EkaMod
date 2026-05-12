<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'employee_id_ref',
        'teacher_no',
        'specialization',
        'subject_specialty',
        'license_no',
        'major',
        'rank_title',
        'is_adviser',
        'date_hired_as_teacher',
    ];

    protected $casts = [
        'is_adviser' => 'boolean',
        'date_hired_as_teacher' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id_ref');
    }

    public function getFullNameAttribute(): string
    {
        return $this->employee?->full_name ?? '';
    }

    public function teacherLoads()
    {
        return $this->hasMany(TeacherLoad::class, 'teacher_id');
    }
}