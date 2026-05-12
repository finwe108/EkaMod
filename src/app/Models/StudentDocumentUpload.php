<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDocumentUpload extends Model
{
    protected $fillable = [
        'student_id',
        'required_student_document_id',
        'file_path',
        'original_filename',
        'status',
        'remarks',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(RequiredStudentDocument::class, 'required_student_document_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}