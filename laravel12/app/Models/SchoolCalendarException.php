<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolCalendarException extends Model
{
    protected $fillable = [
        'calendar_date',
        'is_working_day',
        'name',
        'notes',
    ];

    protected $casts = [
        'calendar_date' => 'date',
        'is_working_day' => 'boolean',
    ];

    /**
     * Determine whether this exception makes the date a working day.
     */
    public function isWorkingDay(): bool
    {
        return $this->is_working_day;
    }

    /**
     * Determine whether this exception makes the date a non-working day.
     */
    public function isNonWorkingDay(): bool
    {
        return ! $this->is_working_day;
    }
}