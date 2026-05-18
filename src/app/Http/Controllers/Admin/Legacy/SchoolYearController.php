<?php

namespace App\Http\Controllers\Admin\Legacy;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    public function index()
    {
        $schoolYears = SchoolYear::latest()->paginate(15);
        return view('admin.school_years.index', compact('schoolYears'));
    }

    public function create()
    {
        return view('admin.school_years.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:50', 'unique:school_years,name'],
            'starts_on' => ['nullable', 'date'],
            'ends_on'   => ['nullable', 'date', 'after_or_equal:starts_on'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('is_active')) {
            SchoolYear::query()->update(['is_active' => false]);
        }

        $validated['is_active'] = $request->boolean('is_active');

        SchoolYear::create($validated);

        return redirect()
            ->route('admin.school_years.index')
            ->with('success', 'School year created successfully.');
    }

    public function edit(SchoolYear $schoolYear)
    {
        return view('admin.school_years.edit', compact('schoolYear'));
    }

    public function update(Request $request, SchoolYear $schoolYear)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:50', 'unique:school_years,name,' . $schoolYear->id],
            'starts_on' => ['nullable', 'date'],
            'ends_on'   => ['nullable', 'date', 'after_or_equal:starts_on'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('is_active')) {
            SchoolYear::where('id', '!=', $schoolYear->id)->update(['is_active' => false]);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $schoolYear->update($validated);

        return redirect()
            ->route('admin.school_years.index')
            ->with('success', 'School year updated successfully.');
    }

    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();

        return redirect()
            ->route('admin.school_years.index')
            ->with('success', 'School year deleted successfully.');
    }
}