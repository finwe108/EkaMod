<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherLoadSection extends Model
{
    protected $fillable = [
        'teacher_load_id',
        'section_id',
    ];

    public function teacherLoad()
    {
        return $this->belongsTo(TeacherLoad::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}