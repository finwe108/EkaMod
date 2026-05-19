<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradingProfile extends Model
{
    protected $fillable = ['name', 'education_level'];

    public function components()
    {
        return $this->hasMany(GradingProfileComponent::class);
    }
}