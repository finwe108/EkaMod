<?php

namespace App\Models;

use App\Models\DocumentRequestItem;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function requirementRules()
    {
        return $this->hasMany(DocumentRequirementRule::class);
    }

    public function documentRequestItems()
    {
        return $this->hasMany(DocumentRequestItem::class);
    }
}