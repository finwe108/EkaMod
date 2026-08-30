<?php

namespace App\Models;

use App\Enums\DocumentRequestItemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentRequestItem extends Model
{
    protected $fillable = [
        'document_request_id',
        'document_type_id',

        /*
         * Item lifecycle.
         */
        'status',
        'processing_started_at',
        'processed_by_user_id',
        'ready_at',

        /*
         * Item release information.
         */
        'released_at',
        'released_to_name',
        'release_notes',

        /*
         * Alternative terminal outcome.
         */
        'unavailable_reason',

        /*
         * General item remarks.
         */
        'remarks',
    ];

    protected $casts = [
        'status' => DocumentRequestItemStatus::class,
        'processing_started_at' => 'datetime',
        'ready_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    /**
     * Get the parent document request.
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(
            DocumentRequest::class,
            'document_request_id'
        );
    }

    /**
     * Get the type of document being requested.
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Get the user who processed this document item.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'processed_by_user_id'
        );
    }

    
    /**
     * Get the lifecycle history for this document item.
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(
            DocumentRequestItemStatusHistory::class
        )->orderBy('created_at')->orderBy('id');
    }


    /**
     * Determine whether this item has reached a final outcome.
     */
    public function isTerminal(): bool
    {
        return in_array(
            $this->status,
            [
                DocumentRequestItemStatus::RELEASED,
                DocumentRequestItemStatus::UNAVAILABLE,
                DocumentRequestItemStatus::CANCELLED,
            ],
            true
        );
    }
}
