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

@push('styles')
    @vite('Modules/DocumentRequests/resources/css/document-request.css')
@endpush

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


    {{-- WORK QUEUE SUMMARY --}}
    @include('document_requests::components.summary-cards')

    {{-- FILTERS --}}
    @include('document_requests::components.filters')

    {{-- ACTIVE FILTERS --}}
    @include('document_requests::components.active-filters')

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

                                @include(
                                    'document_requests::components.request-row',
                                    ['request' => $request]
                                )

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                @include('document_requests::components.pagination')

            @else

                {{-- EMPTY STATE --}}
                @include('document_requests::components.empty-state')

            @endif

        </div>

    </div>

</div>

@endsection


{{-- PAGE STYLES --}}
{{-- Loaded from Modules/DocumentRequests/resources/css/document-request.css via Vite. --}}