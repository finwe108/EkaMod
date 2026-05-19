<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use Illuminate\Http\Request;

class GradeLevelController extends Controller
{
    public function index()
    {
        $gradeLevels = GradeLevel::orderBy('sort_order')->paginate(15);
        return view('admin.grade_levels.index', compact('gradeLevels'));
    }

    public function create()
    {
        return view('admin.grade_levels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100', 'unique:grade_levels,name'],
            'sort_order' => ['nullable', 'integer'],
            'department' => ['nullable', 'string', 'max:100'],
        ]);

        GradeLevel::create($validated);

        return redirect()
            ->route('admin.grade-levels.index')
            ->with('success', 'Grade level created successfully.');
    }

    public function edit(GradeLevel $gradeLevel)
    {
        return view('admin.grade_levels.edit', compact('gradeLevel'));
    }

    public function update(Request $request, GradeLevel $gradeLevel)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100', 'unique:grade_levels,name,' . $gradeLevel->id],
            'sort_order' => ['nullable', 'integer'],
            'department' => ['nullable', 'string', 'max:100'],
        ]);

        $gradeLevel->update($validated);

        return redirect()
            ->route('admin.grade-levels.index')
            ->with('success', 'Grade level updated successfully.');
    }

    public function destroy(GradeLevel $gradeLevel)
    {
        $gradeLevel->delete();

        return redirect()
            ->route('admin.grade-levels.index')
            ->with('success', 'Grade level deleted successfully.');
    }
}