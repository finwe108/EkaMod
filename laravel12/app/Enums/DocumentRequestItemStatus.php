<?php

namespace App\Enums;

/**
 * Represents the lifecycle of an individual document
 * within a document request.
 */
enum DocumentRequestItemStatus: string
{
    /*
     * The document was requested but processing has not started.
     */
    case PENDING = 'pending';

    /*
     * The Registrar is currently locating, preparing, or completing
     * the requirements for this particular document.
     */
    case PROCESSING = 'processing';

    /*
     * The document is complete and may be released to the requester.
     */
    case READY_FOR_RELEASE = 'ready_for_release';

    /*
     * The document has been physically or otherwise officially
     * released to the recipient.
     */
    case RELEASED = 'released';

    /*
     * The school cannot currently provide this particular document.
     *
     * A reason should be recorded, such as a missing archive record.
     */
    case UNAVAILABLE = 'unavailable';

    /*
     * This individual document will no longer be processed.
     *
     * For example, the requester no longer needs this document.
     */
    case CANCELLED = 'cancelled';
}
