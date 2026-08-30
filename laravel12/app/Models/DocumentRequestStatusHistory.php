<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentRequestStatusHistory extends Model
{
    protected $fillable = [
        'document_request_id',
        'from_status',
        'to_status',
        'changed_by_user_id',
        'remarks',
    ];

    public function documentRequest(): BelongsTo
    {
        return $this->belongsTo(
            DocumentRequest::class
        );
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'changed_by_user_id'
        );
    }
}