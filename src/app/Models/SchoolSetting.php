<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'school_id',
        'region',
        'division',
        'district',
        'school_name',
        'school_head_name',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'school_id' => '',
            'region' => '',
            'division' => '',
            'district' => '',
            'school_name' => '',
            'school_head_name' => '',
        ]);
    }
}