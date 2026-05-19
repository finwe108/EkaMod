<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherLoadSchedule extends Model
{
    protected $fillable = [
        'teacher_load_id',
        'day_of_week',
        'time_start',
        'time_end',
        'room',
    ];

    public function teacherLoad()
    {
        return $this->belongsTo(TeacherLoad::class);
    }
}