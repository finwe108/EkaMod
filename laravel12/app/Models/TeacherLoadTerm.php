<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherLoadTerm extends Model
{
    protected $fillable = [
        'teacher_load_id',
        'term_no',
    ];

    protected $casts = [
        'term_no' => 'integer',
    ];

    public function teacherLoad()
    {
        return $this->belongsTo(TeacherLoad::class);
    }
}