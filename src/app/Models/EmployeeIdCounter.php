<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeIdCounter extends Model
{
    protected $fillable = [
        'year',
        'department_code',
        'last_number',
    ];
}