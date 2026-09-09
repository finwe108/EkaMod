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
        'logo_path',
        'short_name',
        'tagline',
        'phone',
        'email',
        'address',
        'document_request_sla_cutoff_time',
    ];

    protected $casts = [
        'document_request_sla_cutoff_time' => 'datetime:H:i:s',
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
            'logo_path' => '',
            'short_name' => '',
            'tagline' => '',
            'phone' => '',
            'email' => '',
            'address' => '',
            'document_request_sla_cutoff_time' => '15:00:00',
        ]);
    }
}
