<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionApplication extends Model
{
    protected $fillable = [
        'application_number',
        'school_year_id',
        'grade_level_id',
        'section_id',
        'student_type',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'sex',
        'birth_date',
        'birth_place',
        'mother_tongue',
        'is_ip',
        'ethnic_group',
        'religion',
        'lrn',
        'email',
        'contact_number',
        'address',
        'house_street',
        'barangay',
        'municipality_city',
        'province',
        'father_name',
        'father_contact',
        'mother_name',
        'mother_contact',
        'guardian_name',
        'guardian_relationship',
        'guardian_contact',
        'parent_guardian_contact',
        'last_school_attended',
        'last_grade_level_completed',
        'strand_or_track',
        'application_status',
        'remarks',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'accepted_student_id',
        'created_user_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_ip' => 'boolean',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ])));
    }

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

    public function acceptedStudent()
    {
        return $this->belongsTo(Student::class, 'accepted_student_id');
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}