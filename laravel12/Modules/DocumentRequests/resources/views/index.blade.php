@extends('layouts.app')

@section('title', 'Document Request Monitoring | MMCI')
@section('page_title', 'Document Request Monitoring')

@section('topbar_actions')
    <a
        href="{{ route('admin.document-requests.create') }}"
        class="btn btn-primary"
    >
        + New Document Request
    </a>
@endsection

@section('content')

<div class="container-fluid document-request-index">

    {{-- ============================================================
         PAGE HEADER
    ============================================================= --}}
    <div class="document-page-header mb-4">

        <div>
            <h1 class="mb-1">
                Document Request Monitoring
            </h1>

            <p class="text-muted mb-0">
                Monitor document requests and quickly identify
                items that require action.
            </p>
        </div>

    </div>


    {{-- ============================================================
         WORK QUEUE SUMMARY
    ============================================================= --}}
    <div class="work-summary-grid">

        {{-- ========================================================
             TOTAL REQUESTS
        ========================================================= --}}
        <a
            href="{{ route('admin.document-requests.index', [
                'search' => $search ?: null,
                'status' => $requestStatus ?: null,
            ]) }}"
            class="work-summary-card total
                {{ $itemStatus === '' ? 'is-active' : '' }}"
            title="Show all document requests"
        >

            <div class="work-summary-icon">
                <span>📄</span>
            </div>

            <div class="work-summary-content">

                <div class="work-summary-label">
                    Total Requests
                </div>

                <div class="work-summary-count">
                    {{ $totalRequests }}
                </div>

                <div class="work-summary-description">
                    All requests in current search
                </div>

            </div>

        </a>


        {{-- ========================================================
             NEEDS PROCESSING
        ========================================================= --}}
        <a
            href="{{ route('admin.document-requests.index', [
                'search' => $search ?: null,
                'status' => $requestStatus ?: null,
                'item_status' => 'pending',
            ]) }}"
            class="work-summary-card pending
                {{ $itemStatus === 'pending' ? 'is-active' : '' }}
                {{ $needsProcessing > 0 ? 'has-work' : '' }}"
            title="Show requests with documents needing processing"
        >

            <div class="work-summary-icon">
                <span>!</span>
            </div>

            <div class="work-summary-content">

                <div class="work-summary-label">
                    Needs Processing
                </div>

                <div class="work-summary-count">
                    {{ $needsProcessing }}
                </div>

                <div class="work-summary-description">
                    Requires action
                </div>

            </div>

            @if($itemStatus === 'pending')

                <div class="summary-active-indicator">
                    Active
                </div>

            @endif

        </a>


        {{-- ========================================================
             PROCESSING
        ========================================================= --}}
        <a
            href="{{ route('admin.document-requests.index', [
                'search' => $search ?: null,
                'status' => $requestStatus ?: null,
                'item_status' => 'processing',
            ]) }}"
            class="work-summary-card processing
                {{ $itemStatus === 'processing' ? 'is-active' : '' }}"
            title="Show requests currently being processed"
        >

            <div class="work-summary-icon">
                <span>↻</span>
            </div>

            <div class="work-summary-content">

                <div class="work-summary-label">
                    Processing
                </div>

                <div class="work-summary-count">
                    {{ $currentlyProcessing }}
                </div>

                <div class="work-summary-description">
                    Currently being worked on
                </div>

            </div>

            @if($itemStatus === 'processing')

                <div class="summary-active-indicator">
                    Active
                </div>

            @endif

        </a>


        {{-- ========================================================
             READY FOR RELEASE
        ========================================================= --}}
        <a
            href="{{ route('admin.document-requests.index', [
                'search' => $search ?: null,
                'status' => $requestStatus ?: null,
                'item_status' => 'ready_for_release',
            ]) }}"
            class="work-summary-card ready
                {{ $itemStatus === 'ready_for_release' ? 'is-active' : '' }}"
            title="Show requests ready for release"
        >

            <div class="work-summary-icon">
                <span>✓</span>
            </div>

            <div class="work-summary-content">

                <div class="work-summary-label">
                    Ready for Release
                </div>

                <div class="work-summary-count">
                    {{ $readyForRelease }}
                </div>

                <div class="work-summary-description">
                    Ready for requester
                </div>

            </div>

            @if($itemStatus === 'ready_for_release')

                <div class="summary-active-indicator">
                    Active
                </div>

            @endif

        </a>


        {{-- ========================================================
             RELEASED
        ========================================================= --}}
        <a
            href="{{ route('admin.document-requests.index', [
                'search' => $search ?: null,
                'status' => $requestStatus ?: null,
                'item_status' => 'released',
            ]) }}"
            class="work-summary-card released
                {{ $itemStatus === 'released' ? 'is-active' : '' }}"
            title="Show requests with released documents"
        >

            <div class="work-summary-icon">
                <span>✓</span>
            </div>

            <div class="work-summary-content">

                <div class="work-summary-label">
                    Released
                </div>

                <div class="work-summary-count">
                    {{ $releasedItems }}
                </div>

                <div class="work-summary-description">
                    Completed
                </div>

            </div>

            @if($itemStatus === 'released')

                <div class="summary-active-indicator">
                    Active
                </div>

            @endif

        </a>

    </div>


    {{-- ============================================================
         FILTERS
    ============================================================= --}}
    <div class="card document-filter-card mb-4">

        <div class="card-header">

            <div>
                <div class="card-title">
                    Search & Work Queue
                </div>

                <div class="card-subtitle">
                    Search by requester, student name, student ID,
                    LRN, request status, or document status.
                </div>
            </div>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.document-requests.index') }}"
            >

                <div class="document-filter-grid">

                    {{-- ==================================================
                         SEARCH
                    =================================================== --}}
                    <div class="filter-field filter-search">

                        <label
                            for="search"
                            class="form-label"
                        >
                            Search
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-input"
                            value="{{ $search }}"
                            placeholder="Request no., student, ID, LRN..."
                            autocomplete="off"
                        >

                    </div>


                    {{-- ==================================================
                         REQUEST STATUS
                    =================================================== --}}
                    <div class="filter-field">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Request Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-input"
                        >

                            <option value="">
                                All Request Statuses
                            </option>

                            <option
                                value="pending"
                                @selected($requestStatus === 'pending')
                            >
                                Pending
                            </option>

                            <option
                                value="verification"
                                @selected($requestStatus === 'verification')
                            >
                                Verification
                            </option>

                            <option
                                value="verified"
                                @selected($requestStatus === 'verified')
                            >
                                Verified
                            </option>

                            <option
                                value="processing"
                                @selected($requestStatus === 'processing')
                            >
                                Processing
                            </option>

                            <option
                                value="ready_for_release"
                                @selected($requestStatus === 'ready_for_release')
                            >
                                Ready for Release
                            </option>

                            <option
                                value="released"
                                @selected($requestStatus === 'released')
                            >
                                Released
                            </option>

                            <option
                                value="cancelled"
                                @selected($requestStatus === 'cancelled')
                            >
                                Cancelled
                            </option>

                            <option
                                value="rejected"
                                @selected($requestStatus === 'rejected')
                            >
                                Rejected
                            </option>

                        </select>

                    </div>


                    {{-- ==================================================
                         ITEM STATUS
                    =================================================== --}}
                    <div class="filter-field filter-work-queue">

                        <label
                            for="item_status"
                            class="form-label"
                        >
                            Work Queue / Document Status
                        </label>

                        <select
                            id="item_status"
                            name="item_status"
                            class="form-input"
                        >

                            <option value="">
                                All Document Items
                            </option>

                            <option
                                value="pending"
                                @selected($itemStatus === 'pending')
                            >
                                Needs Processing
                            </option>

                            <option
                                value="processing"
                                @selected($itemStatus === 'processing')
                            >
                                Currently Processing
                            </option>

                            <option
                                value="ready_for_release"
                                @selected($itemStatus === 'ready_for_release')
                            >
                                Ready for Release
                            </option>

                            <option
                                value="released"
                                @selected($itemStatus === 'released')
                            >
                                Released
                            </option>

                            <option
                                value="unavailable"
                                @selected($itemStatus === 'unavailable')
                            >
                                Unavailable
                            </option>

                            <option
                                value="cancelled"
                                @selected($itemStatus === 'cancelled')
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- ==================================================
                         ACTIONS
                    =================================================== --}}
                    <div class="filter-actions">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Apply Filters
                        </button>

                        <a
                            href="{{ route('admin.document-requests.index') }}"
                            class="btn btn-ghost"
                        >
                            Clear Filters
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================
         ACTIVE FILTERS
    ============================================================= --}}
    @if($search || $requestStatus || $itemStatus)

        <div class="active-filter-bar mb-4">

            <div class="active-filter-title">
                Active Filters
            </div>

            <div class="active-filter-list">

                {{-- Search --}}
                @if($search)

                    <span class="active-filter-chip">

                        <span class="chip-label">
                            Search
                        </span>

                        <strong>
                            {{ $search }}
                        </strong>

                    </span>

                @endif


                {{-- Request Status --}}
                @if($requestStatus)

                    <span class="active-filter-chip">

                        <span class="chip-label">
                            Request Status
                        </span>

                        <strong>

                            @switch($requestStatus)

                                @case('pending')
                                    Pending
                                    @break

                                @case('verification')
                                    Verification
                                    @break

                                @case('verified')
                                    Verified
                                    @break

                                @case('processing')
                                    Processing
                                    @break

                                @case('ready_for_release')
                                    Ready for Release
                                    @break

                                @case('released')
                                    Released
                                    @break

                                @case('cancelled')
                                    Cancelled
                                    @break

                                @case('rejected')
                                    Rejected
                                    @break

                                @default
                                    {{
                                        str_replace(
                                            '_',
                                            ' ',
                                            ucwords(
                                                $requestStatus,
                                                '_'
                                            )
                                        )
                                    }}

                            @endswitch

                        </strong>

                    </span>

                @endif


                {{-- Item Status --}}
                @if($itemStatus)

                    <span class="active-filter-chip active-filter-work">

                        <span class="chip-label">
                            Work Queue
                        </span>

                        <strong>

                            @switch($itemStatus)

                                @case('pending')
                                    Needs Processing
                                    @break

                                @case('processing')
                                    Processing
                                    @break

                                @case('ready_for_release')
                                    Ready for Release
                                    @break

                                @case('released')
                                    Released
                                    @break

                                @case('unavailable')
                                    Unavailable
                                    @break

                                @case('cancelled')
                                    Cancelled
                                    @break

                                @default
                                    {{
                                        str_replace(
                                            '_',
                                            ' ',
                                            ucwords(
                                                $itemStatus,
                                                '_'
                                            )
                                        )
                                    }}

                            @endswitch

                        </strong>

                    </span>

                @endif

            </div>


            <a
                href="{{ route('admin.document-requests.index') }}"
                class="active-filter-clear"
            >
                Clear
            </a>

        </div>

    @endif


    {{-- ============================================================
         REQUEST LIST
    ============================================================= --}}
    <div class="card document-request-list-card">

        <div class="card-header request-list-header">

            <div>

                <div class="card-title">
                    Document Requests
                </div>

                <div class="card-subtitle">

                    @if($itemStatus)

                        @switch($itemStatus)

                            @case('pending')
                                Requests with documents needing processing
                                @break

                            @case('processing')
                                Requests with documents currently being processed
                                @break

                            @case('ready_for_release')
                                Requests with documents ready for release
                                @break

                            @case('released')
                                Requests with released documents
                                @break

                            @case('unavailable')
                                Requests with unavailable documents
                                @break

                            @case('cancelled')
                                Requests with cancelled documents
                                @break

                            @default
                                Filtered document requests

                        @endswitch

                    @else

                        {{ $requests->total() }}
                        {{ Str::plural('request', $requests->total()) }}
                        found

                    @endif

                </div>

            </div>


            {{-- ====================================================
                 QUEUE ALERT
            ===================================================== --}}
            @if($needsProcessing > 0)

                <div class="queue-alert">

                    <span class="queue-alert-icon">
                        !
                    </span>

                    <span>

                        <strong>
                            {{ $needsProcessing }}
                        </strong>

                        {{
                            $needsProcessing === 1
                                ? 'request has'
                                : 'requests have'
                        }}

                        documents needing processing.

                    </span>

                </div>

            @endif

        </div>


        {{-- ========================================================
             TABLE
        ========================================================= --}}
        <div class="card-body document-request-table-wrapper">

            @if($requests->count())

                <div class="table-responsive">

                    <table class="table document-request-table">

                        <thead>

                            <tr>

                                <th class="request-number-column">
                                    Request
                                </th>

                                <th class="student-column">
                                    Student
                                </th>

                                <th class="requester-column">
                                    Requested By
                                </th>

                                <th class="documents-column">
                                    Documents / Work Queue
                                </th>

                                <th class="request-status-column">
                                    Request Status
                                </th>

                                <th class="date-column">
                                    Requested
                                </th>

                                <th class="action-column">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($requests as $request)

                                @php

                                    /*
                                     * Convert enum statuses to strings when
                                     * the model uses a PHP backed enum.
                                     */
                                    $itemStatuses = $request->items
                                        ->map(function ($item) {
                                            return $item->status->value
                                                ?? $item->status;
                                        });


                                    /*
                                     * Individual item counts.
                                     */
                                    $pendingCount = $itemStatuses
                                        ->filter(
                                            fn ($status) =>
                                                $status === 'pending'
                                        )
                                        ->count();


                                    $processingCount = $itemStatuses
                                        ->filter(
                                            fn ($status) =>
                                                $status === 'processing'
                                        )
                                        ->count();


                                    $readyCount = $itemStatuses
                                        ->filter(
                                            fn ($status) =>
                                                $status === 'ready_for_release'
                                        )
                                        ->count();


                                    $releasedCount = $itemStatuses
                                        ->filter(
                                            fn ($status) =>
                                                $status === 'released'
                                        )
                                        ->count();


                                    /*
                                     * Determine whether the request contains
                                     * work requiring immediate attention.
                                     */
                                    $hasPendingWork =
                                        $pendingCount > 0;


                                    /*
                                     * Determine row priority.
                                     */
                                    if ($pendingCount > 0) {

                                        $rowPriority =
                                            'request-row-needs-work';

                                    } elseif ($processingCount > 0) {

                                        $rowPriority =
                                            'request-row-processing';

                                    } elseif ($readyCount > 0) {

                                        $rowPriority =
                                            'request-row-ready';

                                    } else {

                                        $rowPriority = '';

                                    }


                                    /*
                                     * Request status.
                                     */
                                    $requestStatusValue =
                                        $request->status->value
                                        ?? $request->status;


                                    $requestStatusClass = match (
                                        $requestStatusValue
                                    ) {

                                        'pending' =>
                                            'request-status-pending',

                                        'verification' =>
                                            'request-status-verification',

                                        'verified' =>
                                            'request-status-verified',

                                        'processing' =>
                                            'request-status-processing',

                                        'ready_for_release' =>
                                            'request-status-ready',

                                        'released' =>
                                            'request-status-released',

                                        'cancelled' =>
                                            'request-status-cancelled',

                                        'rejected' =>
                                            'request-status-rejected',

                                        default =>
                                            'request-status-default',
                                    };


                                    $requestStatusLabel = match (
                                        $requestStatusValue
                                    ) {

                                        'pending' =>
                                            'Pending',

                                        'verification' =>
                                            'Verification',

                                        'verified' =>
                                            'Verified',

                                        'processing' =>
                                            'Processing',

                                        'ready_for_release' =>
                                            'Ready for Release',

                                        'released' =>
                                            'Released',

                                        'cancelled' =>
                                            'Cancelled',

                                        'rejected' =>
                                            'Rejected',

                                        default =>
                                            str_replace(
                                                '_',
                                                ' ',
                                                ucwords(
                                                    $requestStatusValue,
                                                    '_'
                                                )
                                            ),
                                    };

                                @endphp


                                <tr class="{{ $rowPriority }}">

                                    {{-- ==================================================
                                         REQUEST
                                    =================================================== --}}
                                    <td class="request-number-cell">

                                        <a
                                            href="{{ route(
                                                'admin.document-requests.show',
                                                $request
                                            ) }}"
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


                                    {{-- ==================================================
                                         STUDENT
                                    =================================================== --}}
                                    <td class="student-cell">

                                        @if($request->student)

                                            <div class="student-name">

                                                {{ $request->student->formal_name }}

                                            </div>

                                            <div class="student-meta">

                                                <span>
                                                    ID:
                                                    {{ $request->student->student_id }}
                                                </span>

                                                @if($request->student->lrn)

                                                    <span>
                                                        LRN:
                                                        {{ $request->student->lrn }}
                                                    </span>

                                                @endif

                                            </div>

                                        @else

                                            <div class="student-name unavailable">
                                                Student record unavailable
                                            </div>

                                        @endif

                                    </td>


                                    {{-- ==================================================
                                         REQUESTER
                                    =================================================== --}}
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


                                    {{-- ==================================================
                                         DOCUMENTS / WORK QUEUE
                                    =================================================== --}}
                                    <td class="documents-cell">

                                        {{-- Work Summary --}}
                                        @if(
                                            $pendingCount ||
                                            $processingCount ||
                                            $readyCount ||
                                            $releasedCount
                                        )

                                            <div class="document-status-summary">

                                                @if($pendingCount)

                                                    <span class="work-badge work-badge-pending">

                                                        <span class="work-badge-count">
                                                            {{ $pendingCount }}
                                                        </span>

                                                        <span>
                                                            Needs Processing
                                                        </span>

                                                    </span>

                                                @endif


                                                @if($processingCount)

                                                    <span class="work-badge work-badge-processing">

                                                        <span class="work-badge-count">
                                                            {{ $processingCount }}
                                                        </span>

                                                        <span>
                                                            Processing
                                                        </span>

                                                    </span>

                                                @endif


                                                @if($readyCount)

                                                    <span class="work-badge work-badge-ready">

                                                        <span class="work-badge-count">
                                                            {{ $readyCount }}
                                                        </span>

                                                        <span>
                                                            Ready
                                                        </span>

                                                    </span>

                                                @endif


                                                @if($releasedCount)

                                                    <span class="work-badge work-badge-released">

                                                        <span class="work-badge-count">
                                                            {{ $releasedCount }}
                                                        </span>

                                                        <span>
                                                            Released
                                                        </span>

                                                    </span>

                                                @endif

                                            </div>

                                        @endif


                                        {{-- Individual Documents --}}
                                        <div class="document-items">

                                            @forelse(
                                                $request->items
                                                as $item
                                            )

                                                @php

                                                    $itemStatus =
                                                        $item->status->value
                                                        ?? $item->status;


                                                    $itemStatusLabel = match (
                                                        $itemStatus
                                                    ) {

                                                        'pending' =>
                                                            'Needs Processing',

                                                        'processing' =>
                                                            'Processing',

                                                        'ready_for_release' =>
                                                            'Ready for Release',

                                                        'released' =>
                                                            'Released',

                                                        'unavailable' =>
                                                            'Unavailable',

                                                        'cancelled' =>
                                                            'Cancelled',

                                                        default =>
                                                            str_replace(
                                                                '_',
                                                                ' ',
                                                                ucwords(
                                                                    $itemStatus,
                                                                    '_'
                                                                )
                                                            ),
                                                    };


                                                    $itemStatusClass = match (
                                                        $itemStatus
                                                    ) {

                                                        'pending' =>
                                                            'document-status-pending',

                                                        'processing' =>
                                                            'document-status-processing',

                                                        'ready_for_release' =>
                                                            'document-status-ready',

                                                        'released' =>
                                                            'document-status-released',

                                                        'unavailable' =>
                                                            'document-status-unavailable',

                                                        'cancelled' =>
                                                            'document-status-cancelled',

                                                        default =>
                                                            'document-status-default',
                                                    };

                                                @endphp


                                                <div class="document-work-item">

                                                    <div class="document-name">

                                                        <span
                                                            class="
                                                                document-status-dot
                                                                {{ $itemStatusClass }}
                                                            "
                                                        ></span>

                                                        <span>
                                                            {{
                                                                $item->documentType?->name
                                                                ?? 'Unknown document'
                                                            }}
                                                        </span>

                                                    </div>


                                                    <span
                                                        class="
                                                            document-status-label
                                                            {{ $itemStatusClass }}
                                                        "
                                                    >
                                                        {{ $itemStatusLabel }}
                                                    </span>

                                                </div>

                                            @empty

                                                <div class="no-documents">
                                                    No documents specified
                                                </div>

                                            @endforelse

                                        </div>

                                    </td>


                                    {{-- ==================================================
                                         REQUEST STATUS
                                    =================================================== --}}
                                    <td class="request-status-cell">

                                        <span
                                            class="
                                                request-status-badge
                                                {{ $requestStatusClass }}
                                            "
                                        >
                                            {{ $requestStatusLabel }}
                                        </span>

                                    </td>


                                    {{-- ==================================================
                                         REQUESTED DATE
                                    =================================================== --}}
                                    <td class="date-cell">

                                        @if($request->requested_at)

                                            <div class="request-date">
                                                {{ $request->requested_at->format('M d, Y') }}
                                            </div>

                                            <div class="request-time">
                                                {{ $request->requested_at->format('h:i A') }}
                                            </div>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ==================================================
                                         ACTION
                                    =================================================== --}}
                                    <td class="action-cell">

                                        <a
                                            href="{{ route(
                                                'admin.document-requests.show',
                                                $request
                                            ) }}"
                                            class="btn btn-primary btn-sm request-open-button"
                                        >
                                            Open
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- ====================================================
                     PAGINATION
                ===================================================== --}}
                <div class="request-pagination">

                    {{ $requests->links() }}

                </div>

            @else

                {{-- ====================================================
                     EMPTY STATE
                ===================================================== --}}
                <div class="empty-request-state">

                    <div class="empty-request-icon">
                        📄
                    </div>

                    <h5>
                        No document requests found.
                    </h5>

                    <p class="text-muted">
                        Try changing your search or filter criteria.
                    </p>

                    @if($search || $requestStatus || $itemStatus)

                        <a
                            href="{{ route(
                                'admin.document-requests.index'
                            ) }}"
                            class="btn btn-ghost"
                        >
                            Clear Filters
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>

@endsection


{{-- ================================================================
     PAGE STYLES
================================================================ --}}
@push('styles')

<style>

    /*
     * ============================================================
     * DOCUMENT REQUEST INDEX
     * ============================================================
     */

    .document-request-index {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
    }


    /*
     * ============================================================
     * PAGE HEADER
     * ============================================================
     */

    .document-page-header h1 {
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: -.02em;
    }


    /*
     * ============================================================
     * WORK QUEUE SUMMARY
     * ============================================================
     */

    .work-summary-grid {
        display: grid;
        grid-template-columns:
            repeat(5, minmax(0, 1fr));

        gap: 1rem;

        margin-bottom: 1.25rem;

        width: 100%;
    }


    /*
     * Summary cards are anchors rather than divs because
     * they function as GET filters.
     */
    .work-summary-card {
        position: relative;

        display: flex;
        align-items: center;

        gap: 1rem;

        min-width: 0;

        padding: 1rem 1.15rem;

        border: 1px solid rgba(0, 0, 0, .08);
        border-radius: .75rem;

        background: #fff;

        color: inherit;
        text-decoration: none;

        box-shadow:
            0 1px 3px rgba(0, 0, 0, .06);

        transition:
            transform .15s ease,
            box-shadow .15s ease,
            border-color .15s ease,
            background-color .15s ease;
    }


    .work-summary-card:hover {
        transform: translateY(-2px);

        color: inherit;
        text-decoration: none;

        box-shadow:
            0 5px 15px rgba(0, 0, 0, .10);
    }


    .work-summary-card:focus-visible {
        outline: 3px solid rgba(13, 110, 253, .25);
        outline-offset: 2px;
    }


    /*
     * Active card.
     */
    .work-summary-card.is-active {
        box-shadow:
            0 0 0 2px rgba(13, 110, 253, .12),
            0 5px 15px rgba(0, 0, 0, .10);
    }


    .work-summary-icon {
        flex: 0 0 42px;

        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: .65rem;

        font-size: 1.15rem;
        font-weight: 700;
    }


    .work-summary-content {
        min-width: 0;
        flex: 1;
    }


    .work-summary-label {
        margin-bottom: .15rem;

        font-size: .75rem;
        font-weight: 700;

        color: #6c757d;

        text-transform: uppercase;
        letter-spacing: .025em;

        line-height: 1.25;
    }


    .work-summary-count {
        font-size: 1.5rem;
        line-height: 1.2;

        font-weight: 700;

        color: #212529;
    }


    .work-summary-description {
        margin-top: .15rem;

        font-size: .72rem;

        color: #6c757d;

        line-height: 1.3;
    }


    /*
     * Active indicator.
     */
    .summary-active-indicator {
        position: absolute;

        top: .6rem;
        right: .65rem;

        padding: .15rem .4rem;

        border-radius: .35rem;

        background: rgba(13, 110, 253, .10);

        color: #0d6efd;

        font-size: .62rem;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .03em;
    }


    /*
     * Total.
     */
    .work-summary-card.total {
        border-left: 4px solid #6f42c1;
    }


    .work-summary-card.total .work-summary-icon {
        background: #e2d9f3;
        color: #59359a;
    }


    .work-summary-card.total.is-active {
        border-color: #6f42c1;
    }


    /*
     * Pending / Needs Processing.
     */
    .work-summary-card.pending {
        border-left: 4px solid #f0ad00;
    }


    .work-summary-card.pending .work-summary-icon {
        background: #fff3cd;
        color: #856404;
    }


    .work-summary-card.pending.is-active {
        border-color: #f0ad00;
        background: #fffdf5;
    }


    /*
     * Processing.
     */
    .work-summary-card.processing {
        border-left: 4px solid #0d6efd;
    }


    .work-summary-card.processing .work-summary-icon {
        background: #cfe2ff;
        color: #084298;
    }


    .work-summary-card.processing.is-active {
        border-color: #0d6efd;
        background: #f7faff;
    }


    /*
     * Ready.
     */
    .work-summary-card.ready {
        border-left: 4px solid #198754;
    }


    .work-summary-card.ready .work-summary-icon {
        background: #d1e7dd;
        color: #0f5132;
    }


    .work-summary-card.ready.is-active {
        border-color: #198754;
        background: #f7fcf9;
    }


    /*
     * Released.
     */
    .work-summary-card.released {
        border-left: 4px solid #20c997;
    }


    .work-summary-card.released .work-summary-icon {
        background: #d2f4ea;
        color: #087f5b;
    }


    .work-summary-card.released.is-active {
        border-color: #20c997;
        background: #f7fffc;
    }


    /*
     * ============================================================
     * FILTERS
     * ============================================================
     */

    .filter-actions {
        display: flex;
        flex-wrap: wrap;

        align-items: center;

        gap: .75rem;

        width: 100%;
    }


    .filter-actions .btn {
        flex: 0 0 auto;
    }


    /*
     * ============================================================
     * CARD WIDTH CONTROL
     * ============================================================
     */

    .document-request-index .card {
        max-width: 100%;
        min-width: 0;

        overflow: hidden;
    }


    /*
     * ============================================================
     * TABLE WRAPPER
     * ============================================================
     *
     * IMPORTANT:
     *
     * The table is intentionally wider than smaller screens.
     * Horizontal scrolling occurs inside this wrapper so that
     * Requested and Action do not disappear from the layout.
     */

    .document-request-table-wrapper {
        width: 100%;
        max-width: 100%;
        min-width: 0;

        box-sizing: border-box;

        overflow-x: auto;
        overflow-y: visible;

        padding-left: .75rem;
        padding-right: .75rem;

        -webkit-overflow-scrolling: touch;

        scrollbar-width: thin;
    }


    /*
     * ============================================================
     * REQUEST TABLE
     * ============================================================
     */

    .document-request-table {
        width: 100%;

        /*
         * Do not reduce this.
         *
         * The table needs enough width for:
         *
         * Request
         * Student
         * Requested By
         * Documents
         * Request Status
         * Requested
         * Action
         */
        min-width: 1180px;

        margin-bottom: 0;

        table-layout: fixed;
    }


    .document-request-table th,
    .document-request-table td {
        vertical-align: middle;

        box-sizing: border-box;
    }


    /*
     * ============================================================
     * TABLE COLUMNS
     * ============================================================
     */

    .document-request-table .request-number-column {
        width: 145px;
        min-width: 145px;

        white-space: nowrap;
    }


    .document-request-table .student-column {
        width: 190px;
        min-width: 190px;
    }


    .document-request-table .requester-column {
        width: 160px;
        min-width: 160px;
    }


    /*
     * Documents is the main operational column.
     */
    .document-request-table .documents-column {
        width: 330px;
        min-width: 330px;
    }


    .document-request-table .request-status-column {
        width: 155px;
        min-width: 155px;

        white-space: nowrap;
    }


    /*
     * Requested date.
     *
     * Explicit width prevents this column from collapsing
     * or disappearing when the table becomes crowded.
     */
    .document-request-table .date-column {
        width: 135px;
        min-width: 135px;

        white-space: nowrap;
    }


    /*
     * Action.
     *
     * Explicit width guarantees that the Action column remains
     * available at the far right of the horizontally scrollable
     * table.
     */
    .document-request-table .action-column {
        width: 100px;
        min-width: 100px;

        white-space: nowrap;

        text-align: center;
    }


    .document-request-table .action-column .btn {
        white-space: nowrap;
    }


    /*
     * ============================================================
     * REQUEST CELL
     * ============================================================
     */

    .request-number-cell {
        white-space: nowrap;
    }


    .request-number-link {
        display: inline-block;

        font-weight: 700;

        text-decoration: none;

        white-space: nowrap;
    }


    .request-number-link:hover {
        text-decoration: underline;
    }


    .priority-indicator {
        display: inline-block;

        margin-top: .35rem;

        padding: .15rem .4rem;

        border-radius: .3rem;

        background: #fff3cd;

        color: #856404;

        font-size: .65rem;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .02em;
    }


    /*
     * ============================================================
     * STUDENT
     * ============================================================
     */

    .student-cell,
    .requester-cell {
        overflow-wrap: anywhere;
    }


    .student-name,
    .requester-name {
        font-weight: 600;

        line-height: 1.35;
    }


    .student-name.unavailable {
        color: #6c757d;
        font-weight: 500;
    }


    .student-meta,
    .requester-meta {
        display: flex;
        flex-wrap: wrap;

        gap: .25rem .75rem;

        margin-top: .2rem;

        color: #6c757d;

        font-size: .72rem;

        line-height: 1.35;
    }


    /*
     * ============================================================
     * DOCUMENT WORK ITEMS
     * ============================================================
     */

    .documents-cell {
        overflow-wrap: anywhere;
    }


    .document-status-summary {
        display: flex;
        flex-wrap: wrap;

        gap: .3rem;

        margin-bottom: .45rem;
    }


    .work-badge {
        display: inline-flex;

        align-items: center;

        gap: .3rem;

        padding: .25rem .45rem;

        border-radius: .4rem;

        font-size: .68rem;
        font-weight: 600;

        line-height: 1.2;
    }


    .work-badge-count {
        font-weight: 800;
    }


    .work-badge-pending {
        background: #fff3cd;
        color: #856404;
    }


    .work-badge-processing {
        background: #cfe2ff;
        color: #084298;
    }


    .work-badge-ready {
        background: #d1e7dd;
        color: #0f5132;
    }


    .work-badge-released {
        background: #d2f4ea;
        color: #087f5b;
    }


    .document-items {
        width: 100%;
    }


    .document-work-item {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: .6rem;

        padding: .4rem 0;

        border-bottom: 1px solid rgba(0, 0, 0, .07);

        line-height: 1.35;
    }


    .document-work-item:last-child {
        border-bottom: none;
    }


    .document-name {
        display: flex;

        align-items: center;

        gap: .45rem;

        min-width: 0;

        font-size: .8rem;
        font-weight: 500;
    }


    .document-name > span:last-child {
        overflow-wrap: anywhere;
    }


    .document-status-dot {
        flex: 0 0 8px;

        width: 8px;
        height: 8px;

        border-radius: 50%;
    }


    .document-status-pending {
        color: #856404;
    }


    .document-status-dot.document-status-pending {
        background: #f0ad00;
    }


    .document-status-processing {
        color: #084298;
    }


    .document-status-dot.document-status-processing {
        background: #0d6efd;
    }


    .document-status-ready {
        color: #0f5132;
    }


    .document-status-dot.document-status-ready {
        background: #198754;
    }


    .document-status-released {
        color: #087f5b;
    }


    .document-status-dot.document-status-released {
        background: #20c997;
    }


    .document-status-unavailable {
        color: #842029;
    }


    .document-status-dot.document-status-unavailable {
        background: #dc3545;
    }


    .document-status-cancelled {
        color: #6c757d;
    }


    .document-status-dot.document-status-cancelled {
        background: #6c757d;
    }


    .document-status-default {
        color: #6c757d;
    }


    .document-status-label {
        flex: 0 0 auto;

        font-size: .68rem;
        font-weight: 600;

        white-space: nowrap;
    }


    .no-documents {
        color: #6c757d;

        font-size: .8rem;
    }


    /*
     * ============================================================
     * REQUEST STATUS
     * ============================================================
     */

    .request-status-cell {
        white-space: nowrap;
    }


    .request-status-badge {
        display: inline-block;

        padding: .35rem .55rem;

        border-radius: .4rem;

        font-size: .7rem;
        font-weight: 700;

        line-height: 1.2;

        white-space: nowrap;
    }


    .request-status-pending {
        background: #fff3cd;
        color: #856404;
    }


    .request-status-verification {
        background: #e2d9f3;
        color: #59359a;
    }


    .request-status-verified {
        background: #d1e7dd;
        color: #0f5132;
    }


    .request-status-processing {
        background: #cfe2ff;
        color: #084298;
    }


    .request-status-ready {
        background: #d1e7dd;
        color: #0f5132;
    }


    .request-status-released {
        background: #d2f4ea;
        color: #087f5b;
    }


    .request-status-cancelled {
        background: #e9ecef;
        color: #495057;
    }


    .request-status-rejected {
        background: #f8d7da;
        color: #842029;
    }


    .request-status-default {
        background: #e9ecef;
        color: #495057;
    }


    /*
     * ============================================================
     * DATE
     * ============================================================
     */

    .date-cell {
        width: 135px;

        white-space: nowrap;
    }


    .request-date {
        font-size: .78rem;
        font-weight: 600;

        line-height: 1.35;
    }


    .request-time {
        margin-top: .15rem;

        color: #6c757d;

        font-size: .7rem;

        line-height: 1.25;
    }


    /*
     * ============================================================
     * ACTION
     * ============================================================
     */

    .action-cell {
        width: 100px;

        text-align: center;

        white-space: nowrap;
    }


    .request-open-button {
        min-width: 58px;
    }


    /*
     * ============================================================
     * ROW PRIORITY
     * ============================================================
     */

    .request-row-needs-work td:first-child {
        border-left: 3px solid #f0ad00;
    }


    .request-row-processing td:first-child {
        border-left: 3px solid #0d6efd;
    }


    .request-row-ready td:first-child {
        border-left: 3px solid #198754;
    }


    /*
     * ============================================================
     * ACTIVE FILTER BAR
     * ============================================================
     */

    .active-filter-bar {
        display: flex;

        align-items: center;

        flex-wrap: wrap;

        gap: .65rem;

        padding: .75rem 1rem;

        border: 1px solid rgba(13, 110, 253, .15);

        border-radius: .6rem;

        background: rgba(13, 110, 253, .04);
    }


    .active-filter-title {
        font-size: .75rem;
        font-weight: 700;

        color: #495057;

        text-transform: uppercase;
        letter-spacing: .025em;
    }


    .active-filter-list {
        display: flex;

        flex-wrap: wrap;

        gap: .4rem;
    }


    .active-filter-chip {
        display: inline-flex;

        align-items: center;

        gap: .35rem;

        padding: .3rem .55rem;

        border-radius: .4rem;

        background: #fff;

        border: 1px solid rgba(0, 0, 0, .08);

        font-size: .72rem;
    }


    .chip-label {
        color: #6c757d;
    }


    .active-filter-work {
        background: #fff8e1;

        border-color: rgba(240, 173, 0, .25);
    }


    .active-filter-clear {
        margin-left: auto;

        font-size: .75rem;
        font-weight: 600;

        text-decoration: none;
    }


    /*
     * ============================================================
     * QUEUE ALERT
     * ============================================================
     */

    .request-list-header {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 1rem;

        flex-wrap: wrap;
    }


    .queue-alert {
        display: inline-flex;

        align-items: center;

        gap: .5rem;

        padding: .45rem .7rem;

        border-radius: .45rem;

        background: #fff3cd;

        color: #664d03;

        font-size: .75rem;

        border: 1px solid rgba(240, 173, 0, .2);
    }


    .queue-alert-icon {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        width: 20px;
        height: 20px;

        border-radius: 50%;

        background: #f0ad00;

        color: #fff;

        font-size: .7rem;
        font-weight: 800;
    }


    /*
     * ============================================================
     * PAGINATION
     * ============================================================
     */

    .request-pagination {
        margin-top: 1rem;
    }


    /*
     * ============================================================
     * EMPTY STATE
     * ============================================================
     */

    .empty-request-state {
        padding: 4rem 1rem;

        text-align: center;
    }


    .empty-request-icon {
        margin-bottom: .75rem;

        font-size: 2rem;
        opacity: .65;
    }


    /*
     * ============================================================
     * RESPONSIVE SUMMARY CARDS
     * ============================================================
     */

    @media (max-width: 1250px) {

        .work-summary-grid {
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
        }

    }


    @media (max-width: 900px) {

        .work-summary-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 767.98px) {

        .work-summary-grid {
            grid-template-columns: 1fr;
        }


        .work-summary-card {
            padding: .9rem 1rem;
        }


        .document-request-table-wrapper {
            padding-left: .5rem;
            padding-right: .5rem;
        }


        /*
         * Keep the table wide enough for every column.
         * The user can horizontally scroll to Requested and Action.
         */
        .document-request-table {
            min-width: 1180px;
        }


        .filter-actions {
            width: 100%;
        }


        .filter-actions .btn {
            flex: 1 1 auto;

            min-width: 130px;
        }


        .active-filter-clear {
            margin-left: 0;
        }


        .request-list-header {
            align-items: flex-start;
        }

    }

</style>

@endpush