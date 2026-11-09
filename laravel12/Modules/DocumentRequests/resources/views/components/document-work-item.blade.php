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

    $sla = $item->sla_display ?? [];
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

    <div class="document-work-item-status">

        <span class="document-status-label {{ $itemStatusClass }}">
            {{ $itemStatusLabel }}
        </span>

        @if($sla['sla_status_label'] ?? null)

            <span
                class="document-sla-label {{ $sla['sla_status_class'] }}"
            >
                {{ $sla['sla_status_label'] }}
            </span>

        @endif

    </div>

    @if(
        ($sla['sla_status'] ?? null)
        && ($sla['sla_due_at'] ?? null)
    )

        <div class="document-sla-meta">

            <span>
                Due:
                {{ $sla['sla_due_at']->format('M d, Y h:i A') }}
            </span>

            @if($sla['sla_remaining_label'] ?? null)

                <span>
                    {{ $sla['sla_remaining_label'] }}
                </span>

            @endif

        </div>

    @endif

</div>