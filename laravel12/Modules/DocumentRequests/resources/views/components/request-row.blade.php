@php
    $itemStatuses = $request->items->map(
        fn ($item) => $item->status->value ?? $item->status
    );

    $pendingCount = $itemStatuses
        ->filter(fn ($status) => $status === 'pending')
        ->count();

    $processingCount = $itemStatuses
        ->filter(fn ($status) => $status === 'processing')
        ->count();

    $readyCount = $itemStatuses
        ->filter(fn ($status) => $status === 'ready_for_release')
        ->count();

    $releasedCount = $itemStatuses
        ->filter(fn ($status) => $status === 'released')
        ->count();

    $hasPendingWork = $pendingCount > 0;

    $rowPriority = match (true) {
        $pendingCount > 0 => 'request-row-needs-work',
        $processingCount > 0 => 'request-row-processing',
        $readyCount > 0 => 'request-row-ready',
        default => '',
    };

    $requestStatusValue =
        $request->status->value
        ?? $request->status;

    $requestStatusClass = match ($requestStatusValue) {
        'pending' => 'request-status-pending',
        'verification' => 'request-status-verification',
        'verified' => 'request-status-verified',
        'processing' => 'request-status-processing',
        'ready_for_release' => 'request-status-ready',
        'released' => 'request-status-released',
        'cancelled' => 'request-status-cancelled',
        'rejected' => 'request-status-rejected',
        default => 'request-status-default',
    };

    $requestStatusLabel = match ($requestStatusValue) {
        'pending' => 'Pending',
        'verification' => 'Verification',
        'verified' => 'Verified',
        'processing' => 'Processing',
        'ready_for_release' => 'Ready for Release',
        'released' => 'Released',
        'cancelled' => 'Cancelled',
        'rejected' => 'Rejected',
        default => str_replace(
            '_',
            ' ',
            ucwords($requestStatusValue, '_')
        ),
    };
@endphp

<tr class="{{ $rowPriority }}">

    {{-- REQUEST --}}
    <td class="request-number-cell">

        <a
            href="{{ route('admin.document-requests.show', $request) }}"
            class="request-number-link"
        >
            {{ $request->request_number }}
        </a>

        @if($hasPendingWork)
            <span class="priority-indicator">
                Needs Action
            </span>
        @endif

    </td>


    {{-- STUDENT --}}
    <td class="student-cell">

        @if($request->student)

            <div class="student-name">
                {{ $request->student->formal_name }}
            </div>

            <div class="student-meta">

                <span>
                    ID: {{ $request->student->student_id }}
                </span>

                @if($request->student->lrn)
                    <span>
                        LRN: {{ $request->student->lrn }}
                    </span>
                @endif

            </div>

        @else

            <div class="student-name unavailable">
                Student record unavailable
            </div>

        @endif

    </td>


    {{-- REQUESTER --}}
    <td class="requester-cell">

        <div class="requester-name">
            {{ $request->requested_by_name }}
        </div>

        @if($request->requester_relationship)
            <div class="requester-meta">
                {{ $request->requester_relationship }}
            </div>
        @endif

    </td>


    {{-- DOCUMENTS --}}
    <td class="documents-cell">

        @if($pendingCount || $processingCount || $readyCount || $releasedCount)

            <div class="document-status-summary">

                @if($pendingCount)
                    <span class="work-badge work-badge-pending">
                        <span class="work-badge-count">
                            {{ $pendingCount }}
                        </span>
                        <span>Needs Processing</span>
                    </span>
                @endif

                @if($processingCount)
                    <span class="work-badge work-badge-processing">
                        <span class="work-badge-count">
                            {{ $processingCount }}
                        </span>
                        <span>Processing</span>
                    </span>
                @endif

                @if($readyCount)
                    <span class="work-badge work-badge-ready">
                        <span class="work-badge-count">
                            {{ $readyCount }}
                        </span>
                        <span>Ready</span>
                    </span>
                @endif

                @if($releasedCount)
                    <span class="work-badge work-badge-released">
                        <span class="work-badge-count">
                            {{ $releasedCount }}
                        </span>
                        <span>Released</span>
                    </span>
                @endif

            </div>

        @endif


        <div class="document-items">

            @forelse($request->items as $item)

                @include(
                    'document_requests::components.document-work-item',
                    ['item' => $item]
                )

            @empty

                <div class="no-documents">
                    No documents specified
                </div>

            @endforelse

        </div>

    </td>


    {{-- REQUEST STATUS --}}
    <td class="request-status-cell">

        <span
            class="request-status-badge {{ $requestStatusClass }}"
        >
            {{ $requestStatusLabel }}
        </span>

    </td>


    {{-- DATE --}}
    <td class="date-cell">

        @if($request->requested_at)

            <div class="request-date">
                {{ $request->requested_at->format('M d, Y') }}
            </div>

            <div class="request-time">
                {{ $request->requested_at->format('h:i A') }}
            </div>

        @else

            <span class="text-muted">—</span>

        @endif

    </td>


    {{-- ACTION --}}
    <td class="action-cell">

        <a
            href="{{ route('admin.document-requests.show', $request) }}"
            class="btn btn-primary btn-sm request-open-button"
        >
            Open
        </a>

    </td>

</tr>