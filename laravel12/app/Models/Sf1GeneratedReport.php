<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sf1GeneratedReport extends Model
{
    protected $fillable = [
        'school_year_id',
        'grade_level_id',
        'section_id',
        'status',
        'progress',
        'filename',
        'file_path',
        'error_message',
        'generated_by',
        'generated_at',
        'needs_regeneration',
        'source_updated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'source_updated_at' => 'datetime',
        'needs_regeneration' => 'boolean',
    ];

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