<div class="work-summary-grid">

    <a
        href="{{ route('admin.document-requests.index', [
            'search' => $search ?: null,
            'status' => $requestStatus ?: null,
        ]) }}"
        class="work-summary-card total {{ $itemStatus === '' ? 'is-active' : '' }}"
        title="Show all document requests"
    >
        <div class="work-summary-icon">
            <span>📄</span>
        </div>

        <div class="work-summary-content">
            <div class="work-summary-label">Total Requests</div>
            <div class="work-summary-count">{{ $totalRequests }}</div>
            <div class="work-summary-description">
                All requests in current search
            </div>
        </div>
    </a>


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
            <div class="work-summary-label">Needs Processing</div>
            <div class="work-summary-count">{{ $needsProcessing }}</div>
            <div class="work-summary-description">
                Requires action
            </div>
        </div>

        @if($itemStatus === 'pending')
            <div class="summary-active-indicator">Active</div>
        @endif
    </a>


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
            <div class="work-summary-label">Processing</div>
            <div class="work-summary-count">
                {{ $currentlyProcessing }}
            </div>

            <div class="work-summary-description">
                Currently being worked on
            </div>
        </div>

        @if($itemStatus === 'processing')
            <div class="summary-active-indicator">Active</div>
        @endif
    </a>


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
            <div class="work-summary-label">Ready for Release</div>
            <div class="work-summary-count">
                {{ $readyForRelease }}
            </div>

            <div class="work-summary-description">
                Ready for requester
            </div>
        </div>

        @if($itemStatus === 'ready_for_release')
            <div class="summary-active-indicator">Active</div>
        @endif
    </a>


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
            <div class="work-summary-label">Released</div>
            <div class="work-summary-count">
                {{ $releasedItems }}
            </div>

            <div class="work-summary-description">
                Completed
            </div>
        </div>

        @if($itemStatus === 'released')
            <div class="summary-active-indicator">Active</div>
        @endif
    </a>

</div>