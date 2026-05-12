<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('gradeLevel')
            ->latest()
            ->paginate(20);

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();

        return view('admin.subjects.create', compact('gradeLevels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:subjects,code',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'grade_level_id' => 'nullable|exists:grade_levels,id',
        ]);

        Subject::create($validated);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show($id)
    {
        $subject = Subject::with(['gradeLevel', 'teacherLoads.teacher.employee', 'teacherLoads.section', 'teacherLoads.schoolYear'])
            ->findOrFail($id);

        return view('admin.subjects.show', compact('subject'));
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $gradeLevels = GradeLevel::orderBy('name')->get();

        return view('admin.subjects.edit', compact('subject', 'gradeLevels'));
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'grade_level_id' => 'nullable|exists:grade_levels,id',
        ]);

        $subject->update($validated);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);

        $subject->delete();

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}