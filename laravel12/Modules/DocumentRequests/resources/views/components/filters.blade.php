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

                {{-- SEARCH --}}
                <div class="document-filter-field">

                    <label for="search">
                        Search
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Request no., student, ID, LRN..."
                    >

                </div>


                {{-- REQUEST STATUS --}}
                <div class="document-filter-field">

                    <label for="status">
                        Request Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="form-control"
                    >
                        <option value="">All Request Statuses</option>

                        @foreach([
                            'pending' => 'Pending',
                            'verification' => 'Verification',
                            'verified' => 'Verified',
                            'processing' => 'Processing',
                            'ready_for_release' => 'Ready for Release',
                            'released' => 'Released',
                            'cancelled' => 'Cancelled',
                            'rejected' => 'Rejected',
                        ] as $value => $label)

                            <option
                                value="{{ $value }}"
                                @selected($requestStatus === $value)
                            >
                                {{ $label }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ITEM STATUS --}}
                <div class="document-filter-field">

                    <label for="item_status">
                        Item Status
                    </label>

                    <select
                        id="item_status"
                        name="item_status"
                        class="form-control"
                    >
                        <option value="">All Item Statuses</option>

                        @foreach([
                            'pending' => 'Needs Processing',
                            'processing' => 'Processing',
                            'ready_for_release' => 'Ready for Release',
                            'released' => 'Released',
                            'unavailable' => 'Unavailable',
                            'cancelled' => 'Cancelled',
                        ] as $value => $label)

                            <option
                                value="{{ $value }}"
                                @selected($itemStatus === $value)
                            >
                                {{ $label }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ACTIONS --}}
                <div class="document-filter-actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Apply Filters
                    </button>

                    <a
                        href="{{ route('admin.document-requests.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Clear Filters
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>
