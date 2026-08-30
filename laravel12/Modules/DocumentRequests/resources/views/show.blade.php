@extends('layouts.app')

@section('title', 'Document Request | MMCI')
@section('page_title', 'Document Request Details')

@section('topbar_actions')
    <a
        href="{{ route('admin.document-requests.index') }}"
        class="btn btn-ghost"
    >
        Back to Requests
    </a>
@endsection

@section('content')

@php
    /*
     * Normalize enum/string statuses for display.
     */
    $requestStatus = $documentRequest->status instanceof \BackedEnum
        ? $documentRequest->status->value
        : $documentRequest->status;

    /*
     * Overall document item counts.
     */
    $itemCounts = [
        'pending' => 0,
        'processing' => 0,
        'ready_for_release' => 0,
        'released' => 0,
        'unavailable' => 0,
        'cancelled' => 0,
    ];

    foreach ($documentRequest->items as $item) {
        $itemStatus = $item->status instanceof \BackedEnum
            ? $item->status->value
            : $item->status;

        if (array_key_exists($itemStatus, $itemCounts)) {
            $itemCounts[$itemStatus]++;
        }
    }

    /*
     * A request can only be cancelled at the request level before
     * document-level processing begins.
     */
    $canCancelRequest = in_array(
        $requestStatus,
        [
            'pending',
            'verification',
            'verified',
        ],
        true
    );

    /*
     * After verification, processing is controlled independently
     * by each requested document item.
     */
    $isItemBasedWorkflow = in_array(
        $requestStatus,
        [
            'verified',
            'processing',
            'ready_for_release',
            'released',
        ],
        true
    );
@endphp

@if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
@endif

@if($errors->has('lifecycle'))
    <div class="alert alert-danger mb-4">
        {{ $errors->first('lifecycle') }}
    </div>
@endif

@if($errors->has('item_lifecycle'))
    <div class="alert alert-danger mb-4">
        {{ $errors->first('item_lifecycle') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container-fluid">

    {{-- ================================================================
        PAGE HEADER
    ================================================================= --}}
    <div class="mb-4">

        <div class="d-flex justify-content-between align-items-start">

            <div>
                <h1 class="mb-1">
                    {{ $documentRequest->request_number }}
                </h1>

                <p class="text-muted mb-0">
                    Document request details, document processing,
                    release information, and lifecycle history.
                </p>
            </div>

        </div>

    </div>


    {{-- ================================================================
        REQUEST INFORMATION
    ================================================================= --}}
    <div class="card mb-4">

        <div class="card-header">
            <div class="card-title">
                Request Information
            </div>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">
                    <strong>Request Number:</strong>
                    {{ $documentRequest->request_number }}
                </div>

                <div class="col-md-6">
                    <strong>Overall Status:</strong>
                    {{
                        str_replace(
                            '_',
                            ' ',
                            ucwords($requestStatus, '_')
                        )
                    }}
                </div>

                <div class="col-md-6 mt-3">
                    <strong>Requested At:</strong>
                    {{
                        $documentRequest->requested_at
                            ?->format('M d, Y h:i A')
                            ?? 'Not specified'
                    }}
                </div>

                <div class="col-md-6 mt-3">
                    <strong>Purpose:</strong>
                    {{ $documentRequest->purpose ?: 'Not specified' }}
                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        DOCUMENT PROGRESS SUMMARY
    ================================================================= --}}
    @if($documentRequest->items->isNotEmpty())

        <div class="card mb-4">

            <div class="card-header">

                <div>
                    <div class="card-title">
                        Document Progress
                    </div>

                    <div class="card-subtitle">
                        Overall progress based on the lifecycle of each
                        requested document.
                    </div>
                </div>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-2 col-sm-4 mb-3">
                        <strong>
                            {{ $itemCounts['pending'] }}
                        </strong>

                        <div class="text-muted">
                            Pending
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-4 mb-3">
                        <strong>
                            {{ $itemCounts['processing'] }}
                        </strong>

                        <div class="text-muted">
                            Processing
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-4 mb-3">
                        <strong>
                            {{ $itemCounts['ready_for_release'] }}
                        </strong>

                        <div class="text-muted">
                            Ready
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-4 mb-3">
                        <strong>
                            {{ $itemCounts['released'] }}
                        </strong>

                        <div class="text-muted">
                            Released
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-4 mb-3">
                        <strong>
                            {{ $itemCounts['unavailable'] }}
                        </strong>

                        <div class="text-muted">
                            Unavailable
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-4 mb-3">
                        <strong>
                            {{ $itemCounts['cancelled'] }}
                        </strong>

                        <div class="text-muted">
                            Cancelled
                        </div>
                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- ================================================================
        REQUEST-LEVEL LIFECYCLE ACTIONS
    ================================================================= --}}
    <div class="card mb-4">

        <div class="card-header">

            <div>
                <div class="card-title">
                    Request Lifecycle
                </div>

                <div class="card-subtitle">
                    Manage verification and request-level decisions.
                </div>
            </div>

        </div>

        <div class="card-body">

            {{-- --------------------------------------------------------
                Pending
            --------------------------------------------------------- --}}
            @if($requestStatus === 'pending')

                <p class="text-muted">
                    The request is waiting for the Registrar to begin
                    verification.
                </p>

                <form
                    method="POST"
                    action="{{ route(
                        'admin.document-requests.start-verification',
                        $documentRequest
                    ) }}"
                >
                    @csrf

                    <div class="form-group">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="form-input"
                            rows="3"
                            placeholder="Optional verification remarks"
                        >{{ old('remarks') }}</textarea>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Start Verification
                    </button>

                </form>


            {{-- --------------------------------------------------------
                Verification
            --------------------------------------------------------- --}}
            @elseif($requestStatus === 'verification')

                <p class="text-muted">
                    Review the student's record and requirements before
                    approving or rejecting the request.
                </p>

                <form
                    method="POST"
                    action="{{ route(
                        'admin.document-requests.verify',
                        $documentRequest
                    ) }}"
                >
                    @csrf

                    <div class="form-group">

                        <label class="form-label">
                            Verification Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="form-input"
                            rows="3"
                            placeholder="Optional verification remarks"
                        >{{ old('remarks') }}</textarea>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Mark Request as Verified
                    </button>

                </form>

                <hr>

                <form
                    method="POST"
                    action="{{ route(
                        'admin.document-requests.reject',
                        $documentRequest
                    ) }}"
                >
                    @csrf

                    <div class="form-group">

                        <label class="form-label">
                            Reason for Rejection
                        </label>

                        <textarea
                            name="remarks"
                            class="form-input"
                            rows="3"
                            required
                            placeholder="Explain why the request is being rejected"
                        >{{ old('remarks') }}</textarea>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to reject this document request?');"
                    >
                        Reject Request
                    </button>

                </form>


            {{-- --------------------------------------------------------
                Verified / Item-based workflow
            --------------------------------------------------------- --}}
            @elseif($isItemBasedWorkflow)

                @if($requestStatus === 'verified')

                    <div class="alert alert-success mb-0">
                        The request has been verified successfully.

                        Individual documents can now be processed
                        independently below.
                    </div>

                @elseif($requestStatus === 'processing')

                    <div class="alert alert-info mb-0">
                        Document processing is currently in progress.

                        Each requested document has its own lifecycle.
                    </div>

                @elseif($requestStatus === 'ready_for_release')

                    <div class="alert alert-success mb-0">
                        All remaining active documents are ready for release.

                        Release each document individually below.
                    </div>

                @elseif($requestStatus === 'released')

                    <div class="alert alert-success mb-0">
                        All requested documents have been released.
                        This document request is complete.
                    </div>

                @endif


            {{-- --------------------------------------------------------
                Rejected
            --------------------------------------------------------- --}}
            @elseif($requestStatus === 'rejected')

                <div class="alert alert-danger mb-0">
                    This document request was rejected.

                    No further lifecycle transitions are allowed.
                </div>


            {{-- --------------------------------------------------------
                Cancelled
            --------------------------------------------------------- --}}
            @elseif($requestStatus === 'cancelled')

                <div class="alert alert-warning mb-0">
                    This document request was cancelled.

                    No further lifecycle transitions are allowed.
                </div>

            @endif


            {{-- --------------------------------------------------------
                Request cancellation
            --------------------------------------------------------- --}}
            @if($canCancelRequest)

                <hr>

                <details>

                    <summary style="cursor:pointer;">
                        Cancel this request
                    </summary>

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.document-requests.cancel',
                            $documentRequest
                        ) }}"
                        class="mt-3"
                    >
                        @csrf

                        <div class="form-group">

                            <label class="form-label">
                                Reason for Cancellation
                            </label>

                            <textarea
                                name="remarks"
                                class="form-input"
                                rows="3"
                                required
                                placeholder="Explain why this request is being cancelled"
                            >{{ old('remarks') }}</textarea>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to cancel this document request?');"
                        >
                            Cancel Document Request
                        </button>

                    </form>

                </details>

            @endif

        </div>

    </div>


    {{-- ================================================================
        STUDENT INFORMATION
    ================================================================= --}}
    <div class="card mb-4">

        <div class="card-header">
            <div class="card-title">
                Student Information
            </div>
        </div>

        <div class="card-body">

            @if($documentRequest->student)

                <strong>
                    {{ $documentRequest->student->formal_name }}
                </strong>

                <div class="text-muted">
                    Student ID:
                    {{ $documentRequest->student->student_id }}
                </div>

                <div class="text-muted">
                    LRN:
                    {{ $documentRequest->student->lrn }}
                </div>

            @else

                <span class="text-muted">
                    Student record unavailable.
                </span>

            @endif

        </div>

    </div>


    {{-- ================================================================
        REQUESTER INFORMATION
    ================================================================= --}}
    <div class="card mb-4">

        <div class="card-header">
            <div class="card-title">
                Requester Information
            </div>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">
                    <strong>Name:</strong>
                    {{ $documentRequest->requested_by_name }}
                </div>

                <div class="col-md-4">
                    <strong>Relationship:</strong>
                    {{
                        $documentRequest->requester_relationship
                        ?: 'Not specified'
                    }}
                </div>

                <div class="col-md-4">
                    <strong>Contact:</strong>
                    {{
                        $documentRequest->requested_by_contact
                        ?: 'Not specified'
                    }}
                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        REQUESTED DOCUMENTS / ITEM LIFECYCLE
    ================================================================= --}}
    <div class="card mb-4">

        <div class="card-header">

            <div>
                <div class="card-title">
                    Requested Documents
                </div>

                <div class="card-subtitle">
                    Each document is processed and released independently.
                </div>
            </div>

        </div>

        <div class="card-body">

            @if(
                in_array(
                    $requestStatus,
                    ['pending', 'verification'],
                    true
                )
            )

                <div class="alert alert-info">
                    Individual document processing will become available
                    after this request has been verified.
                </div>

            @endif


            @forelse($documentRequest->items as $item)

                @php
                    $itemStatus = $item->status instanceof \BackedEnum
                        ? $item->status->value
                        : $item->status;
                @endphp

                <div class="card mb-3">

                    <div class="card-body">

                        {{-- Item Header --}}
                        <div
                            class="d-flex justify-content-between align-items-start"
                        >

                            <div>

                                <h5 class="mb-1">
                                    {{
                                        $item->documentType?->name
                                        ?? 'Unknown document'
                                    }}
                                </h5>

                                <div class="text-muted">

                                    <strong>Status:</strong>

                                    {{
                                        str_replace(
                                            '_',
                                            ' ',
                                            ucwords($itemStatus, '_')
                                        )
                                    }}

                                </div>

                            </div>

                        </div>


                        {{-- ====================================================
                            ITEM PENDING
                        ===================================================== --}}
                        @if($itemStatus === 'pending')

                            @if(
                                in_array(
                                    $requestStatus,
                                    [
                                        'verified',
                                        'processing',
                                        'ready_for_release',
                                    ],
                                    true
                                )
                            )

                                <p class="text-muted mt-3">
                                    This document has not yet started
                                    processing.
                                </p>

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'admin.document-requests.items.start-processing',
                                        [
                                            'documentRequest' => $documentRequest,
                                            'documentRequestItem' => $item,
                                        ]
                                    ) }}"
                                >
                                    @csrf

                                    <div class="form-group">

                                        <label class="form-label">
                                            Processing Remarks
                                        </label>

                                        <textarea
                                            name="remarks"
                                            class="form-input"
                                            rows="2"
                                            placeholder="Optional remarks"
                                        >{{ old('remarks') }}</textarea>

                                    </div>

                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Start Processing
                                    </button>

                                </form>

                            @else

                                <div class="alert alert-secondary mt-3 mb-0">
                                    Waiting for request verification.
                                </div>

                            @endif


                        {{-- ====================================================
                            ITEM PROCESSING
                        ===================================================== --}}
                        @elseif($itemStatus === 'processing')

                            <p class="text-muted mt-3">
                                This document is currently being prepared.
                            </p>

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.document-requests.items.ready-for-release',
                                    [
                                        'documentRequest' => $documentRequest,
                                        'documentRequestItem' => $item,
                                    ]
                                ) }}"
                            >
                                @csrf

                                <div class="form-group">

                                    <label class="form-label">
                                        Completion Remarks
                                    </label>

                                    <textarea
                                        name="remarks"
                                        class="form-input"
                                        rows="2"
                                        placeholder="Optional completion remarks"
                                    >{{ old('remarks') }}</textarea>

                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Mark Ready for Release
                                </button>

                            </form>


                        {{-- ====================================================
                            ITEM READY FOR RELEASE
                        ===================================================== --}}
                        @elseif($itemStatus === 'ready_for_release')

                            <div class="alert alert-success mt-3">
                                This document is ready for release.
                            </div>

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.document-requests.items.release',
                                    [
                                        'documentRequest' => $documentRequest,
                                        'documentRequestItem' => $item,
                                    ]
                                ) }}"
                            >
                                @csrf

                                <div class="form-group">

                                    <label class="form-label">
                                        Released To
                                    </label>

                                    <input
                                        type="text"
                                        name="released_to_name"
                                        class="form-input"
                                        value="{{ old('released_to_name') }}"
                                        required
                                        placeholder="Name of person receiving this document"
                                    >

                                </div>

                                <div class="form-group">

                                    <label class="form-label">
                                        Release Notes
                                    </label>

                                    <textarea
                                        name="release_notes"
                                        class="form-input"
                                        rows="2"
                                        placeholder="Optional release notes"
                                    >{{ old('release_notes') }}</textarea>

                                </div>

                                <div class="form-group">

                                    <label class="form-label">
                                        Lifecycle Remarks
                                    </label>

                                    <textarea
                                        name="remarks"
                                        class="form-input"
                                        rows="2"
                                        placeholder="Optional lifecycle remarks"
                                    >{{ old('remarks') }}</textarea>

                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Release Document
                                </button>

                            </form>


                        {{-- ====================================================
                            ITEM RELEASED
                        ===================================================== --}}
                        @elseif($itemStatus === 'released')

                            <div class="alert alert-success mt-3 mb-0">

                                <strong>
                                    Released successfully.
                                </strong>

                                @if($item->released_at)

                                    <br>

                                    Released on
                                    {{
                                        $item->released_at
                                            ->format('M d, Y h:i A')
                                    }}.

                                @endif

                                @if($item->released_to_name)

                                    <br>

                                    Released to:
                                    {{ $item->released_to_name }}.

                                @endif

                                @if($item->release_notes)

                                    <br>

                                    Notes:
                                    {{ $item->release_notes }}

                                @endif

                            </div>


                        {{-- ====================================================
                            ITEM UNAVAILABLE
                        ===================================================== --}}
                        @elseif($itemStatus === 'unavailable')

                            <div class="alert alert-warning mt-3 mb-0">

                                <strong>
                                    Document Unavailable.
                                </strong>

                                @if($item->unavailable_reason)

                                    <br>

                                    Reason:
                                    {{ $item->unavailable_reason }}

                                @endif

                            </div>


                        {{-- ====================================================
                            ITEM CANCELLED
                        ===================================================== --}}
                        @elseif($itemStatus === 'cancelled')

                            <div class="alert alert-danger mt-3 mb-0">
                                This document item was cancelled.
                            </div>

                        @endif


                        {{-- ====================================================
                            ITEM UNAVAILABLE ACTION
                        ===================================================== --}}
                        @if(
                            in_array(
                                $itemStatus,
                                [
                                    'pending',
                                    'processing',
                                    'ready_for_release',
                                ],
                                true
                            )
                            &&
                            in_array(
                                $requestStatus,
                                [
                                    'verified',
                                    'processing',
                                    'ready_for_release',
                                ],
                                true
                            )
                        )

                            <hr>

                            <details>

                                <summary style="cursor:pointer;">
                                    Mark this document unavailable
                                </summary>

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'admin.document-requests.items.unavailable',
                                        [
                                            'documentRequest' => $documentRequest,
                                            'documentRequestItem' => $item,
                                        ]
                                    ) }}"
                                    class="mt-3"
                                >
                                    @csrf

                                    <div class="form-group">

                                        <label class="form-label">
                                            Reason
                                        </label>

                                        <textarea
                                            name="reason"
                                            class="form-input"
                                            rows="3"
                                            required
                                            placeholder="Explain why this document cannot be provided"
                                        >{{ old('reason') }}</textarea>

                                    </div>

                                    <div class="form-group">

                                        <label class="form-label">
                                            Additional Remarks
                                        </label>

                                        <textarea
                                            name="remarks"
                                            class="form-input"
                                            rows="2"
                                            placeholder="Optional additional remarks"
                                        >{{ old('remarks') }}</textarea>

                                    </div>

                                    <button
                                        type="submit"
                                        class="btn btn-warning"
                                        onclick="return confirm('Mark this document as unavailable?');"
                                    >
                                        Mark Unavailable
                                    </button>

                                </form>

                            </details>

                        @endif


                        {{-- ====================================================
                            ITEM CANCEL ACTION
                        ===================================================== --}}
                        @if(
                            in_array(
                                $itemStatus,
                                [
                                    'pending',
                                    'processing',
                                    'ready_for_release',
                                ],
                                true
                            )
                            &&
                            in_array(
                                $requestStatus,
                                [
                                    'verified',
                                    'processing',
                                    'ready_for_release',
                                ],
                                true
                            )
                        )

                            <hr>

                            <details>

                                <summary style="cursor:pointer;">
                                    Cancel this document
                                </summary>

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'admin.document-requests.items.cancel',
                                        [
                                            'documentRequest' => $documentRequest,
                                            'documentRequestItem' => $item,
                                        ]
                                    ) }}"
                                    class="mt-3"
                                >
                                    @csrf

                                    <div class="form-group">

                                        <label class="form-label">
                                            Reason for Cancellation
                                        </label>

                                        <textarea
                                            name="reason"
                                            class="form-input"
                                            rows="3"
                                            required
                                            placeholder="Explain why this document will no longer be processed"
                                        >{{ old('reason') }}</textarea>

                                    </div>

                                    <button
                                        type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('Cancel this document?');"
                                    >
                                        Cancel Document
                                    </button>

                                </form>

                            </details>

                        @endif


                        {{-- ====================================================
                            ITEM STATUS HISTORY
                        ===================================================== --}}
                        @if($item->statusHistories->isNotEmpty())

                            <hr>

                            <details>

                                <summary style="cursor:pointer;">
                                    View document history
                                </summary>

                                <div class="mt-3">

                                    @foreach($item->statusHistories as $history)

                                        @php
                                            $fromStatus =
                                                $history->from_status instanceof \BackedEnum
                                                    ? $history->from_status->value
                                                    : $history->from_status;

                                            $toStatus =
                                                $history->to_status instanceof \BackedEnum
                                                    ? $history->to_status->value
                                                    : $history->to_status;
                                        @endphp

                                        <div class="mb-3">

                                            <strong>
                                                {{
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        ucwords($fromStatus, '_')
                                                    )
                                                }}
                                            </strong>

                                            →

                                            <strong>
                                                {{
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        ucwords($toStatus, '_')
                                                    )
                                                }}
                                            </strong>

                                            <div class="text-muted">

                                                {{
                                                    $history->created_at
                                                        ?->format('M d, Y h:i A')
                                                }}

                                                @if($history->changedBy)
                                                    · {{ $history->changedBy->name }}
                                                @endif

                                            </div>

                                            @if($history->remarks)

                                                <div class="mt-1">
                                                    {{ $history->remarks }}
                                                </div>

                                            @endif

                                        </div>

                                    @endforeach

                                </div>

                            </details>

                        @endif

                    </div>

                </div>

            @empty

                <span class="text-muted">
                    No documents specified.
                </span>

            @endforelse

        </div>

    </div>


    {{-- ================================================================
        LEGACY REQUEST PROCESSING / RELEASE INFORMATION
    ================================================================= --}}
    <div class="card mb-4">

        <div class="card-header">

            <div>
                <div class="card-title">
                    Request Processing Summary
                </div>

                <div class="card-subtitle">
                    Request-level historical processing and release information.
                </div>
            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <strong>Processed At:</strong>

                    {{
                        $documentRequest->processed_at
                            ?->format('M d, Y h:i A')
                            ?? 'Not recorded'
                    }}

                </div>

                <div class="col-md-6">

                    <strong>Processed By:</strong>

                    {{
                        $documentRequest->processedBy?->name
                        ?? 'Not assigned'
                    }}

                </div>

                <div class="col-md-6 mt-3">

                    <strong>Released At:</strong>

                    {{
                        $documentRequest->released_at
                            ?->format('M d, Y h:i A')
                            ?? 'Not yet fully released'
                    }}

                </div>

                <div class="col-md-6 mt-3">

                    <strong>Overall Released To:</strong>

                    {{
                        $documentRequest->released_to_name
                        ?: 'Managed per document item'
                    }}

                </div>

                <div class="col-md-12 mt-3">

                    <strong>Release Notes:</strong>

                    {{
                        $documentRequest->release_notes
                        ?: 'Release details are managed per document item.'
                    }}

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        REQUEST STATUS HISTORY
    ================================================================= --}}
    <div class="card">

        <div class="card-header">

            <div>
                <div class="card-title">
                    Request Status History
                </div>

                <div class="card-subtitle">
                    Request-level verification and lifecycle history.
                </div>
            </div>

        </div>

        <div class="card-body">

            @forelse($documentRequest->statusHistories as $history)

                @php
                    $fromStatus = $history->from_status instanceof \BackedEnum
                        ? $history->from_status->value
                        : $history->from_status;

                    $toStatus = $history->to_status instanceof \BackedEnum
                        ? $history->to_status->value
                        : $history->to_status;
                @endphp

                <div class="mb-4">

                    <strong>
                        {{
                            str_replace(
                                '_',
                                ' ',
                                ucwords($fromStatus, '_')
                            )
                        }}
                    </strong>

                    →

                    <strong>
                        {{
                            str_replace(
                                '_',
                                ' ',
                                ucwords($toStatus, '_')
                            )
                        }}
                    </strong>

                    <div class="text-muted">

                        {{
                            $history->created_at
                                ?->format('M d, Y h:i A')
                        }}

                        @if($history->changedBy)
                            · {{ $history->changedBy->name }}
                        @endif

                    </div>

                    @if($history->remarks)

                        <div class="mt-1">
                            {{ $history->remarks }}
                        </div>

                    @endif

                </div>

            @empty

                <span class="text-muted">
                    No request-level status changes recorded yet.
                </span>

            @endforelse

        </div>

    </div>

</div>

@endsection
