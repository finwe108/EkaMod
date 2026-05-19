<?php

namespace Modules\DocumentRequirementRules\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequirementRule;
use App\Models\DocumentType;
use App\Models\GradeLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\DocumentRequirementRules\Requests\StoreDocumentRequirementRuleRequest;
use Modules\DocumentRequirementRules\Requests\UpdateDocumentRequirementRuleRequest;
use Modules\DocumentRequirementRules\Services\DocumentRequirementRuleService;

/**
 * Handles administrative document requirement rule management.
 *
 * Module: DocumentRequirementRules
 * Layer: HTTP Controller
 */
class DocumentRequirementRuleController extends Controller
{
    public function index(): View
    {
        $rules = DocumentRequirementRule::with(['documentType', 'gradeLevel'])
            ->latest()
            ->paginate(20);

        return view('document_requirement_rules::index', compact('rules'));
    }

    public function create(): View
    {
        $documentTypes = DocumentType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $gradeLevels = GradeLevel::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('document_requirement_rules::create', compact(
            'documentTypes',
            'gradeLevels'
        ));
    }

    public function store(
        StoreDocumentRequirementRuleRequest $request,
        DocumentRequirementRuleService $documentRequirementRuleService
    ): RedirectResponse {
        $validated = $request->validated();

        $validated['is_required'] = $request->boolean('is_required');
        $validated['require_if_no_existing_copy'] = $request->boolean('require_if_no_existing_copy');

        $documentRequirementRuleService->create($validated);

        return redirect()
            ->route('admin.document-requirement-rules.index')
            ->with('success', 'Document requirement rule created successfully.');
    }

    public function edit(DocumentRequirementRule $documentRequirementRule): View
    {
        $documentTypes = DocumentType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $gradeLevels = GradeLevel::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('document_requirement_rules::edit', compact(
            'documentRequirementRule',
            'documentTypes',
            'gradeLevels'
        ));
    }

    public function update(
        UpdateDocumentRequirementRuleRequest $request,
        DocumentRequirementRule $documentRequirementRule,
        DocumentRequirementRuleService $documentRequirementRuleService
    ): RedirectResponse {
        $validated = $request->validated();

        $validated['is_required'] = $request->boolean('is_required');
        $validated['require_if_no_existing_copy'] = $request->boolean('require_if_no_existing_copy');

        $documentRequirementRuleService->update($documentRequirementRule, $validated);

        return redirect()
            ->route('admin.document-requirement-rules.index')
            ->with('success', 'Document requirement rule updated successfully.');
    }

    public function destroy(
        DocumentRequirementRule $documentRequirementRule,
        DocumentRequirementRuleService $documentRequirementRuleService
    ): RedirectResponse {
        $documentRequirementRuleService->delete($documentRequirementRule);

        return redirect()
            ->route('admin.document-requirement-rules.index')
            ->with('success', 'Document requirement rule deleted successfully.');
    }
}