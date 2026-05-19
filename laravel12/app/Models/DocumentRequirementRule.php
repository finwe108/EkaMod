<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequirementRule extends Model
{
    protected $fillable = [
        'document_type_id',
        'grade_level_id',
        'student_type',
        'is_required',
        'require_if_no_existing_copy',
        'remarks',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'require_if_no_existing_copy' => 'boolean',
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }
}