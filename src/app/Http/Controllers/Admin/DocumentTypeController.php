<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $documentTypes = DocumentType::orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.document_types.index', compact('documentTypes'));
    }

    public function create()
    {
        return view('admin.document_types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:document_types,name'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        DocumentType::create($validated);

        return redirect()
            ->route('admin.document-types.index')
            ->with('success', 'Document type created successfully.');
    }

    public function show(DocumentType $documentType)
    {
        return redirect()->route('admin.document-types.edit', $documentType);
    }

    public function edit(DocumentType $documentType)
    {
        return view('admin.document_types.edit', compact('documentType'));
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:document_types,name,' . $documentType->id],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $documentType->update($validated);

        return redirect()
            ->route('admin.document-types.index')
            ->with('success', 'Document type updated successfully.');
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return redirect()
            ->route('admin.document-types.index')
            ->with('success', 'Document type deleted successfully.');
    }
}