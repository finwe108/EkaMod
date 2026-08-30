<?php

namespace App\Enums;

enum DocumentRequestStatus: string
{
    case PENDING = 'pending';
    case VERIFICATION = 'verification';
    case VERIFIED = 'verified';

    /*
     * Derived from individual document item progress.
     */
    case PROCESSING = 'processing';
    case READY_FOR_RELEASE = 'ready_for_release';

    /*
     * Every requested document was successfully released.
     */
    case RELEASED = 'released';

    /*
     * Every requested document reached a terminal outcome,
     * but one or more documents were unavailable or cancelled.
     */
    case COMPLETED = 'completed';

    /*
     * Request-level terminal decisions.
     */
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';
}