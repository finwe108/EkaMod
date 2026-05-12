<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequiredStudentDocument extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_required',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];
}