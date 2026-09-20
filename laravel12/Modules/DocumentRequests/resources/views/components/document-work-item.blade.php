@php
    $itemStatus = $item->status->value ?? $item->status;

    $itemStatusLabel = match ($itemStatus) {
        'pending' => 'Needs Processing',
        'processing' => 'Processing',
        'ready_for_release' => 'Ready for Release',
        'released' => 'Released',
        'unavailable' => 'Unavailable',
        'cancelled' => 'Cancelled',
        default => str_replace(
            '_',
            ' ',
            ucwords($itemStatus, '_')
        ),
    };

    $itemStatusClass = match ($itemStatus) {
        'pending' => 'document-status-pending',
        'processing' => 'document-status-processing',
        'ready_for_release' => 'document-status-ready',
        'released' => 'document-status-released',
        'unavailable' => 'document-status-unavailable',
        'cancelled' => 'document-status-cancelled',
        default => 'document-status-default',
    };
@endphp

<div class="document-work-item">

    <div class="document-name">

        <span
            class="document-status-dot {{ $itemStatusClass }}"
        ></span>

        <span>
            {{ $item->documentType?->name ?? 'Unknown document' }}
        </span>

    </div>

    <span class="document-status-label {{ $itemStatusClass }}">
        {{ $itemStatusLabel }}
    </span>

</div>