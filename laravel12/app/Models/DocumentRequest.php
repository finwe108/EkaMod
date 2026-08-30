<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentRequest extends Model
{
    protected $fillable = [
        'request_number',

        /*
         * Student identity.
         *
         * Existing students use student_id.
         * Pre-system students use the legacy identity fields.
         */
        'student_id',
        'student_record_type',
        'legacy_first_name',
        'legacy_middle_name',
        'legacy_last_name',
        'legacy_birth_date',

        /*
         * Requester information.
         */
        'requested_by_user_id',
        'requested_by_name',
        'requested_by_contact',
        'requester_relationship',

        /*
         * Request lifecycle.
         */
        'status',
        'purpose',
        'requested_at',
        'processed_at',
        'released_at',
        'processed_by_user_id',

        /*
         * Release information.
         */
        'released_to_name',
        'release_notes',

        /*
         * General request remarks.
         */
        'remarks',
    ];

    protected $casts = [
        'legacy_birth_date' => 'date',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    /**
     * The existing system student whose records/documents
     * are being requested.
     *
     * This relationship is null for pre-system students.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Determine whether this request belongs to an existing
     * student record in the current system.
     */
    public function isExistingStudent(): bool
    {
        return $this->student_record_type === 'existing';
    }

    /**
     * Determine whether this request belongs to a student whose
     * records existed before the current system.
     */
    public function isLegacyStudent(): bool
    {
        return $this->student_record_type === 'legacy';
    }

    /**
     * Get the student's display name regardless of whether the
     * request belongs to an existing or pre-system student.
     */
    public function getStudentDisplayNameAttribute(): string
    {
        if ($this->isExistingStudent() && $this->student) {
            return $this->student->formal_name;
        }

        return trim(implode(' ', array_filter([
            $this->legacy_first_name,
            $this->legacy_middle_name,
            $this->legacy_last_name,
        ])));
    }

    /**
     * Get a secondary identifier for display purposes.
     */
    public function getStudentDisplayIdentifierAttribute(): ?string
    {
        if ($this->isExistingStudent() && $this->student) {
            return $this->student->student_id;
        }

        if ($this->isLegacyStudent()) {
            return $this->legacy_birth_date
                ? 'Born ' . $this->legacy_birth_date->format('M d, Y')
                : null;
        }

        return null;
    }

    /**
     * The system user who submitted/recorded the request.
     *
     * This is nullable because requests may be entered for
     * persons without a system account.
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'requested_by_user_id'
        );
    }

    /**
     * The employee/user who processed the request.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'processed_by_user_id'
        );
    }

    /**
     * Documents included in this request.
     */
    public function items(): HasMany
    {
        return $this->hasMany(
            DocumentRequestItem::class
        );
    }

    /**
     * Status changes recorded for this request.
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(
            DocumentRequestStatusHistory::class
        )->orderBy('created_at')->orderBy('id');
    }
}
