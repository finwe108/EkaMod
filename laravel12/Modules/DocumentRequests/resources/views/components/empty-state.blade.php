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
