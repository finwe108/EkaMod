<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $fillable = [
        'name', // e.g. 2025-2026
        'starts_on',
        'ends_on',
        'is_active',
    ];

    protected $casts = [
        'starts_on' => 'date',
        'ends_on' => 'date',
        'is_active' => 'boolean',
    ];
    
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function teacherLoads()
    {
        return $this->hasMany(TeacherLoad::class);
    }
}