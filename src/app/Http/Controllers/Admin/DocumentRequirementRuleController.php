<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequirementRule;
use App\Models\DocumentType;
use App\Models\GradeLevel;
use Illuminate\Http\Request;

class DocumentRequirementRuleController extends Controller
{
    public function index()
    {
        $rules = DocumentRequirementRule::with(['documentType', 'gradeLevel'])
            ->latest()
            ->paginate(20);

        return view('admin.document_requirement_rules.index', compact('rules'));
    }

    public function create()
    {
        $documentTypes = DocumentType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $gradeLevels = GradeLevel::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.document_requirement_rules.create', compact(
            'documentTypes',
            'gradeLevels'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type_id' => ['required', 'exists:document_types,id'],
            'grade_level_id' => ['nullable', 'exists:grade_levels,id'],
            'student_type' => ['nullable', 'string', 'max:50'],
            'is_required' => ['nullable', 'boolean'],
            'require_if_no_existing_copy' => ['nullable', 'boolean'],
            'remarks' => ['nullable', 'string'],
        ]);

        $validated['is_required'] = $request->boolean('is_required');
        $validated['require_if_no_existing_copy'] = $request->boolean('require_if_no_existing_copy');

        DocumentRequirementRule::create($validated);

        return redirect()
            ->route('admin.document-requirement-rules.index')
            ->with('success', 'Document requirement rule created successfully.');
    }

    public function edit(DocumentRequirementRule $documentRequirementRule)
    {
        $documentTypes = DocumentType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $gradeLevels = GradeLevel::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.document_requirement_rules.edit', compact(
            'documentRequirementRule',
            'documentTypes',
            'gradeLevels'
        ));
    }

    public function update(Request $request, DocumentRequirementRule $documentRequirementRule)
    {
        $validated = $request->validate([
            'document_type_id' => ['required', 'exists:document_types,id'],
            'grade_level_id' => ['nullable', 'exists:grade_levels,id'],
            'student_type' => ['nullable', 'string', 'max:50'],
            'is_required' => ['nullable', 'boolean'],
            'require_if_no_existing_copy' => ['nullable', 'boolean'],
            'remarks' => ['nullable', 'string'],
        ]);

        $validated['is_required'] = $request->boolean('is_required');
        $validated['require_if_no_existing_copy'] = $request->boolean('require_if_no_existing_copy');

        $documentRequirementRule->update($validated);

        return redirect()
            ->route('admin.document-requirement-rules.index')
            ->with('success', 'Document requirement rule updated successfully.');
    }

    public function destroy(DocumentRequirementRule $documentRequirementRule)
    {
        $documentRequirementRule->delete();

        return redirect()
            ->route('admin.document-requirement-rules.index')
            ->with('success', 'Document requirement rule deleted successfully.');
    }
}