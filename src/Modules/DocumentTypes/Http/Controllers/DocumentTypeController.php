<?php

namespace Modules\DocumentTypes\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\DocumentTypes\Requests\StoreDocumentTypeRequest;
use Modules\DocumentTypes\Requests\UpdateDocumentTypeRequest;
use Modules\DocumentTypes\Services\DocumentTypeService;

/**
 * Handles administrative document type management operations.
 *
 * Module: DocumentTypes
 * Layer: HTTP Controller
 */
class DocumentTypeController extends Controller
{
    /**
     * Display a paginated listing of document types.
     *
     * Document types are ordered first by sort order and then alphabetically
     * by name to preserve the existing administrative display behavior.
     *
     * @return View
     */
    public function index(): View
    {
        $documentTypes = DocumentType::orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('document_types::index', compact('documentTypes'));
    }

    /**
     * Show the document type creation form.
     *
     * Uses the existing Blade view to preserve the current administrative UI
     * during modular migration.
     *
     * @return View
     */
    public function create(): View
    {
        return view('document_types::create');
    }

    /**
     * Store a newly created document type.
     *
     * Validation rules intentionally preserve the original uniqueness
     * constraint on the document type name to prevent duplicate records.
     *
     * The is_active checkbox is normalized using Request::boolean()
     * because unchecked HTML checkboxes are not submitted in requests.
     *
     * @param StoreDocumentTypeRequest $request
     * @return RedirectResponse
     */
    public function store(StoreDocumentTypeRequest $request,DocumentTypeService $documentTypeService): RedirectResponse 
    {
        $validated = $request->validated();

        /*
        * Keep checkbox normalization in the controller for now because this
        * preserves the exact behavior from the original monolith controller.
        */
        $validated['is_active'] = $request->boolean('is_active');

        $documentTypeService->create($validated);

        return redirect()
            ->route('admin.document-types.index')
            ->with('success', 'Document type created successfully.');
    }

    /**
     * Redirect the show route to the edit screen.
     *
     * The existing system currently uses the edit page as the primary
     * detail management interface for document types.
     *
     * This behavior is intentionally preserved to avoid breaking existing
     * admin workflows and route expectations.
     *
     * @param DocumentType $documentType
     * @return RedirectResponse
     */
    public function show(DocumentType $documentType): RedirectResponse
    {
        return redirect()->route('admin.document-types.edit', $documentType);
    }

    /**
     * Show the document type edit form.
     *
     * Uses Laravel route model binding to retrieve the existing document type.
     *
     * @param DocumentType $documentType
     * @return View
     */
    public function edit(DocumentType $documentType): View
    {
        return view('document_types::edit', compact('documentType'));
    }

    /**
     * Update an existing document type.
     *
     * The uniqueness validation rule excludes the current record ID so that
     * administrators can retain the existing document type name without
     * triggering a duplicate validation error.
     *
     * Checkbox input is normalized to a strict boolean value for consistency.
     *
     * @param UpdateDocumentTypeRequest $request
     * @param DocumentType $documentType
     * @return RedirectResponse
     */
    public function update(UpdateDocumentTypeRequest $request, DocumentType $documentType, DocumentTypeService $documentTypeService): RedirectResponse
    {
        $validated = $request->validated();

        /*
         * Normalizing checkbox input to ensure the database always receives
         * a consistent boolean value regardless of form submission state.
         */
        $validated['is_active'] = $request->boolean('is_active');

        $documentTypeService->update($documentType, $validated);

        return redirect()
            ->route('admin.document-types.index')
            ->with('success', 'Document type updated successfully.');
    }

    /**
     * Delete an existing document type.
     *
     * This currently performs a direct delete operation. Do not introduce
     * soft deletes or cascading business logic changes during this migration
     * phase unless explicitly planned separately.
     *
     * @param DocumentType $documentType
     * @return RedirectResponse
     */
    public function destroy(DocumentType $documentType, DocumentTypeService $documentTypeService): RedirectResponse
    {
        $documentTypeService->delete($documentType);

        return redirect()
            ->route('admin.document-types.index')
            ->with('success', 'Document type deleted successfully.');
    }
}
               