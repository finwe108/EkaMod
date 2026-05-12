<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'gender',
        'birthdate',
        'civil_status',
        'email',
        'phone',
        'address',
        'employment_date',
        'first_department_id',
        'first_department_code',
        'first_department_name',
        'current_department_id',
        'employee_type',
        'employment_status',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'employment_date' => 'date',
    ];

    public function firstDepartment()
    {
        return $this->belongsTo(Department::class, 'first_department_id');
    }

    public function currentDepartment()
    {
        return $this->belongsTo(Department::class, 'current_department_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id_ref');
    }

    public function getFullNameAttribute()
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ])));
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'employee_id_ref');
    }
}