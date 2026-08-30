<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Records lifecycle transitions for an individual requested document.
 */
class DocumentRequestItemStatusHistory extends Model
{
    protected $fillable = [
        'document_request_item_id',
        'from_status',
        'to_status',
        'changed_by_user_id',
        'remarks',
    ];

    /**
     * Get the document request item whose status changed.
     */
    public function documentRequestItem(): BelongsTo
    {
        return $this->belongsTo(
            DocumentRequestItem::class
        );
    }

    /**
     * Get the user who performed the transition.
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'changed_by_user_id'
        );
    }
}
