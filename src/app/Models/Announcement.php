<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'posted_by',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'posted_by');
    }
}