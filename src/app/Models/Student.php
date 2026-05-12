<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
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
        'parent_guardian_contact',
        'guardian_contact',
        'remarks',
        'status',
        'sex',
        'photo_path',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_ip' => 'boolean',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function latestEnrollment()
    {
        return $this->hasOne(Enrollment::class)->latestOfMany();
    }


    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ])));
    }

    public function getFormalNameAttribute(): string
    {
        $middleInitial = $this->middle_name
            ? strtoupper(substr($this->middle_name, 0, 1)) . '.'
            : '';

        return trim(implode(' ', array_filter([
            $this->last_name . ',',
            $this->first_name,
            $middleInitial,
            $this->suffix,
        ])));
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->age : null;
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function admissionApplication()
    {
        return $this->hasOne(AdmissionApplication::class, 'accepted_student_id');
    }

    public function currentEnrollment()
    {
        $activeSchoolYearId = SchoolYear::where('is_active', 1)->value('id');

        return $this->hasOne(Enrollment::class)
            ->where('school_year_id', $activeSchoolYearId);
    }

    public function documentUploads()
    {
        return $this->hasMany(\App\Models\StudentDocumentUpload::class);
    }

    public function documents()
    {
        return $this->hasMany(\App\Models\StudentDocument::class);
    }
}