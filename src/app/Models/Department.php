<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'current_department_id');
    }

    public function firstEmployees()
    {
        return $this->hasMany(Employee::class, 'first_department_id');
    }
}