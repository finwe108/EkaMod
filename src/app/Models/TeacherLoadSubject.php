<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherLoadSubject extends Model
{
    protected $fillable = [
        'teacher_load_id',
        'subject_id',
    ];

    public function teacherLoad()
    {
        return $this->belongsTo(TeacherLoad::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}