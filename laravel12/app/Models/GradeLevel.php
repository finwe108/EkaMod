<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    protected $fillable = [
        'name', // e.g. Grade 1, Grade 2
        'code',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    
    public function getDisplayNameAttribute(): string{
      return "{$this->code} - {$this->name}";
    }
}