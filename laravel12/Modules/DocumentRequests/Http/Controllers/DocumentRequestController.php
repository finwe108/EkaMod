<?php

namespace Modules\DocumentRequests\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use App\Models\DocumentRequestItem;
use App\Models\DocumentType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\DocumentRequests\Requests\ReasonDocumentRequestItemRequest;
use Modules\DocumentRequests\Requests\ReasonDocumentRequestRequest;
use Modules\DocumentRequests\Requests\ReleaseDocumentRequestItemRequest;
use Modules\DocumentRequests\Requests\ReleaseDocumentRequestRequest;
use Modules\DocumentRequests\Requests\StoreDocumentRequestRequest;
use Modules\DocumentRequests\Requests\TransitionDocumentRequestItemRequest;
use Modules\DocumentRequests\Requests\TransitionDocumentRequestRequest;
use Modules\DocumentRequests\Services\DocumentRequestItemLifecycleService;
use Modules\DocumentRequests\Services\DocumentRequestLifecycleService;
use Modules\DocumentRequests\Services\DocumentRequestService;
use InvalidArgumentException;


/**
 * Displays and monitors registrar document requests.
 *
 * Module: DocumentRequests
 * Layer: HTTP Controller
 */
class DocumentRequestController extends Controller
{
    /**
     * Display document request monitoring.
     *
     * Supports filtering by:
     *
     * - overall request status
     * - individual document item/work queue status
     * - request number
     * - student name
     * - student ID
     * - LRN
     * - requester name
     *
     * The work-queue summary cards are clickable and use the
     * item_status query parameter as their filter.
     *
     * @return View
     */
    public function index(): View
    {
        /*
         * ============================================================
         * FILTER VALUES
         * ============================================================
         */

        $requestStatus = trim(
            (string) request('status')
        );

        $itemStatus = trim(
            (string) request('item_status')
        );

        $search = trim(
            (string) request('search')
        );


        /*
         * ============================================================
         * BASE QUERY
         * ============================================================
         *
         * This query contains filters that describe the overall
         * search scope:
         *
         * - Search
         * - Request Status
         *
         * IMPORTANT:
         *
         * item_status is intentionally NOT applied here.
         *
         * This allows the summary cards to continue showing useful
         * counts even when one work-queue card is currently selected.
         *
         * Example:
         *
         * Search = Juan
         * Work Queue = Needs Processing
         *
         * The summary cards will still show:
         *
         * Needs Processing
         * Processing
         * Ready for Release
         * Released
         *
         * for Juan's requests.
         */

        $baseQuery = DocumentRequest::query()
            ->when(
                $search !== '',
                function ($query) use ($search) {

                    $query->where(function ($query) use ($search) {

                        /*
                         * Request number
                         */
                        $query->where(
                            'request_number',
                            'like',
                            "%{$search}%"
                        )

                        /*
                         * Requester name
                         */
                        ->orWhere(
                            'requested_by_name',
                            'like',
                            "%{$search}%"
                        )

                        /*
                         * Student information
                         */
                        ->orWhereHas(
                            'student',
                            function ($studentQuery) use ($search) {

                                $studentQuery
                                    ->where(
                                        'student_id',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'lrn',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'first_name',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'middle_name',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'last_name',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        );
                    });
                }
            )

            /*
             * Overall parent request status.
             */
            ->when(
                $requestStatus !== '',
                function ($query) use ($requestStatus) {

                    $query->where(
                        'status',
                        $requestStatus
                    );
                }
            );


        /*
         * ============================================================
         * WORK QUEUE SUMMARY COUNTS
         * ============================================================
         *
         * These counts represent REQUESTS.
         *
         * A request is counted once if it has at least one document
         * item with the corresponding status.
         *
         * Example:
         *
         * Request DR-001
         * - Form 137 = pending
         * - Good Moral = pending
         *
         * This counts as:
         *
         * Needs Processing = 1 request
         *
         * NOT:
         *
         * Needs Processing = 2
         *
         * This makes the summary cards useful as a request-monitoring
         * dashboard.
         */

        $totalRequests = (clone $baseQuery)
            ->count();


        $needsProcessing = (clone $baseQuery)
            ->whereHas(
                'items',
                function ($query) {
                    $query->where(
                        'status',
                        'pending'
                    );
                }
            )
            ->count();


        $currentlyProcessing = (clone $baseQuery)
            ->whereHas(
                'items',
                function ($query) {
                    $query->where(
                        'status',
                        'processing'
                    );
                }
            )
            ->count();


        $readyForRelease = (clone $baseQuery)
            ->whereHas(
                'items',
                function ($query) {
                    $query->where(
                        'status',
                        'ready_for_release'
                    );
                }
            )
            ->count();


        $releasedItems = (clone $baseQuery)
            ->whereHas(
                'items',
                function ($query) {
                    $query->where(
                        'status',
                        'released'
                    );
                }
            )
            ->count();


        /*
         * ============================================================
         * REQUEST LIST QUERY
         * ============================================================
         *
         * Start from the same base query used by the summary cards.
         */

        $requestsQuery = (clone $baseQuery)
            ->with([
                'student',
                'requestedBy',
                'processedBy',
                'items.documentType',
            ]);


        /*
         * ============================================================
         * WORK QUEUE FILTER
         * ============================================================
         *
         * This is the filter used by:
         *
         * - Needs Processing card
         * - Processing card
         * - Ready for Release card
         * - Released card
         * - Work Queue dropdown
         *
         * A request appears if it contains at least one document
         * item matching the selected status.
         */

        if ($itemStatus !== '') {

            $requestsQuery->whereHas(
                'items',
                function ($itemQuery) use ($itemStatus) {

                    $itemQuery->where(
                        'status',
                        $itemStatus
                    );
                }
            );
        }


        /*
         * ============================================================
         * PAGINATION
         * ============================================================
         */

        $requests = $requestsQuery
            ->latest('requested_at')
            ->paginate(25)
            ->withQueryString();


        /*
         * ============================================================
         * VIEW
         * ============================================================
         */

        return view(
            'document_requests::index',
            compact(
                'requests',
                'requestStatus',
                'itemStatus',
                'search',
                'totalRequests',
                'needsProcessing',
                'currentlyProcessing',
                'readyForRelease',
                'releasedItems'
            )
        );
    }


    /**
     * Show the document request creation form.
     *
     * @return View
     */
    public function create(): View
    {
        $students = \App\Models\Student::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $documentTypes = DocumentType::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'document_requests::create',
            compact('students', 'documentTypes')
        );
    }

    /**
     * Store a new document request.
     *
     * @param StoreDocumentRequestRequest $request
     * @param DocumentRequestService $documentRequestService
     * @return RedirectResponse
     */
    public function store(
        StoreDocumentRequestRequest $request,
        DocumentRequestService $documentRequestService
    ): RedirectResponse {
        $validated = $request->validated();

        /*
         * Record the authenticated user when the request is submitted
         * through the registrar/admin interface.
         */
        $validated['requested_by_user_id'] = auth()->id();

        $documentRequest = $documentRequestService->create($validated);

        return redirect()
            ->route('admin.document-requests.index')
            ->with(
                'success',
                'Document request ' . $documentRequest->request_number . ' created successfully.'
            );
    }

    /**
     * Display a document request and its lifecycle history.
     *
     * @param DocumentRequest $documentRequest
     * @return View
     */
    public function show(
        DocumentRequest $documentRequest
    ): View {
        $documentRequest->load([
            'student',
            'requestedBy',
            'processedBy',
            'items.documentType',
            'items.processedBy',
            'items.statusHistories.changedBy',
            'statusHistories.changedBy',
        ]);

        return view(
            'document_requests::show',
            compact('documentRequest')
        );
    }

    # Document request lifecycle controller methods
    /**
     * Start verification of a pending document request.
     */
    # startVerification with invalid-transition handling

    public function startVerification(
        TransitionDocumentRequestRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestLifecycleService $lifecycleService
    ): RedirectResponse {
        try {
            $lifecycleService->startVerification(
                $documentRequest,
                auth()->id(),
                $request->input('remarks')
            );
        } catch (InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'lifecycle' => $e->getMessage(),
                ]);
        }

        return redirect()
            ->route('admin.document-requests.show', $documentRequest)
            ->with('success', 'Document request moved to verification.');
    }



    /**
     * Mark a document request as verified.
     */
    public function verify(
        TransitionDocumentRequestRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestLifecycleService $lifecycleService
    ): RedirectResponse {
        $lifecycleService->verify(
            $documentRequest,
            auth()->id(),
            $request->input('remarks')
        );

        return redirect()
            ->route('admin.document-requests.show', $documentRequest)
            ->with('success', 'Document request verified successfully.');
    }

    /**
     * Start document preparation/processing.
     */
    public function startProcessing(
        TransitionDocumentRequestRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestLifecycleService $lifecycleService
    ): RedirectResponse {
        $lifecycleService->startProcessing(
            $documentRequest,
            auth()->id(),
            $request->input('remarks')
        );

        return redirect()
            ->route('admin.document-requests.show', $documentRequest)
            ->with('success', 'Document request processing started.');
    }

    /**
     * Mark the request as ready for release.
     */
    public function markReadyForRelease(
        TransitionDocumentRequestRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestLifecycleService $lifecycleService
    ): RedirectResponse {
        $lifecycleService->markReadyForRelease(
            $documentRequest,
            auth()->id(),
            $request->input('remarks')
        );

        return redirect()
            ->route('admin.document-requests.show', $documentRequest)
            ->with('success', 'Documents are now ready for release.');
    }

    /**
     * Release documents to the receiving person.
     */
    public function release(
        ReleaseDocumentRequestRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestLifecycleService $lifecycleService
    ): RedirectResponse {
        $validated = $request->validated();

        $lifecycleService->release(
            $documentRequest,
            $validated['released_to_name'],
            auth()->id(),
            $validated['release_notes'] ?? null,
            $validated['remarks'] ?? null
        );

        return redirect()
            ->route('admin.document-requests.show', $documentRequest)
            ->with('success', 'Documents released successfully.');
    }

    /**
     * Reject a document request.
     */
    public function reject(
        ReasonDocumentRequestRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestLifecycleService $lifecycleService
    ): RedirectResponse {
        $lifecycleService->reject(
            $documentRequest,
            auth()->id(),
            $request->validated('remarks')
        );

        return redirect()
            ->route('admin.document-requests.show', $documentRequest)
            ->with('success', 'Document request rejected.');
    }

    /**
     * Cancel a document request.
     */
    public function cancel(
        ReasonDocumentRequestRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestLifecycleService $lifecycleService
    ): RedirectResponse {
        $lifecycleService->cancel(
            $documentRequest,
            auth()->id(),
            $request->validated('remarks')
        );

        return redirect()
            ->route('admin.document-requests.show', $documentRequest)
            ->with('success', 'Document request cancelled.');
    }

    /**
     * Start processing an individual requested document.
     */
    public function startItemProcessing(
        TransitionDocumentRequestItemRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestItem $documentRequestItem,
        DocumentRequestItemLifecycleService $lifecycleService
    ): RedirectResponse {
        $this->ensureItemBelongsToRequest(
            $documentRequest,
            $documentRequestItem
        );

        try {
            $lifecycleService->startProcessing(
                $documentRequestItem,
                auth()->id(),
                $request->input('remarks')
            );
        } catch (InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'item_lifecycle' => $e->getMessage(),
                ]);
        }

        return redirect()
            ->route(
                'admin.document-requests.show',
                $documentRequest
            )
            ->with(
                'success',
                'Document item processing started.'
            );
    }

    /**
     * Mark an individual document as ready for release.
     */
    public function markItemReadyForRelease(
        TransitionDocumentRequestItemRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestItem $documentRequestItem,
        DocumentRequestItemLifecycleService $lifecycleService
    ): RedirectResponse {
        $this->ensureItemBelongsToRequest(
            $documentRequest,
            $documentRequestItem
        );

        try {
            $lifecycleService->markReadyForRelease(
                $documentRequestItem,
                auth()->id(),
                $request->input('remarks')
            );
        } catch (InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'item_lifecycle' => $e->getMessage(),
                ]);
        }

        return redirect()
            ->route(
                'admin.document-requests.show',
                $documentRequest
            )
            ->with(
                'success',
                'Document item is now ready for release.'
            );
    }

    /**
     * Release an individual document to the receiving person.
     */
    public function releaseItem(
        ReleaseDocumentRequestItemRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestItem $documentRequestItem,
        DocumentRequestItemLifecycleService $lifecycleService
    ): RedirectResponse {
        $this->ensureItemBelongsToRequest(
            $documentRequest,
            $documentRequestItem
        );

        $validated = $request->validated();

        try {
            $lifecycleService->release(
                $documentRequestItem,
                $validated['released_to_name'],
                auth()->id(),
                $validated['release_notes'] ?? null,
                $validated['remarks'] ?? null
            );
        } catch (InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'item_lifecycle' => $e->getMessage(),
                ]);
        }

        return redirect()
            ->route(
                'admin.document-requests.show',
                $documentRequest
            )
            ->with(
                'success',
                'Document item released successfully.'
            );
    }

    /**
     * Mark an individual document as unavailable.
     */
    public function markItemUnavailable(
        ReasonDocumentRequestItemRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestItem $documentRequestItem,
        DocumentRequestItemLifecycleService $lifecycleService
    ): RedirectResponse {
        $this->ensureItemBelongsToRequest(
            $documentRequest,
            $documentRequestItem
        );

        $validated = $request->validated();

        try {
            $lifecycleService->markUnavailable(
                $documentRequestItem,
                $validated['reason'],
                auth()->id(),
                $validated['remarks'] ?? null
            );
        } catch (InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'item_lifecycle' => $e->getMessage(),
                ]);
        }

        return redirect()
            ->route(
                'admin.document-requests.show',
                $documentRequest
            )
            ->with(
                'success',
                'Document item marked as unavailable.'
            );
    }

    /**
     * Cancel an individual requested document.
     */
    public function cancelItem(
        ReasonDocumentRequestItemRequest $request,
        DocumentRequest $documentRequest,
        DocumentRequestItem $documentRequestItem,
        DocumentRequestItemLifecycleService $lifecycleService
    ): RedirectResponse {
        $this->ensureItemBelongsToRequest(
            $documentRequest,
            $documentRequestItem
        );

        $validated = $request->validated();

        try {
            $lifecycleService->cancel(
                $documentRequestItem,
                $validated['reason'],
                auth()->id()
            );
        } catch (InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'item_lifecycle' => $e->getMessage(),
                ]);
        }

        return redirect()
            ->route(
                'admin.document-requests.show',
                $documentRequest
            )
            ->with(
                'success',
                'Document item cancelled.'
            );
    }



// helper
    /**
     * Ensure that a document request item belongs to the given request.
     */
    protected function ensureItemBelongsToRequest(
        DocumentRequest $documentRequest,
        DocumentRequestItem $documentRequestItem
    ): void {
        if (
            $documentRequestItem->document_request_id
            !== $documentRequest->id
        ) {
            abort(404);
        }
    }

}