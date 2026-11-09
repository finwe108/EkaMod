@if($search || $requestStatus || $itemStatus)

    <div class="active-filter-bar mb-4">

        <div class="active-filter-title">
            Active Filters
        </div>

        <div class="active-filter-list">

            @if($search)
                <span class="active-filter-chip">
                    <span class="chip-label">Search</span>
                    <strong>{{ $search }}</strong>
                </span>
            @endif


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
                                {{ str_replace('_', ' ', ucwords($requestStatus, '_')) }}
                        @endswitch
                    </strong>

                </span>
            @endif


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
                                {{ str_replace('_', ' ', ucwords($itemStatus, '_')) }}
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