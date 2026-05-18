<?php

namespace App\Http\Controllers\Admin\Legacy;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::latest()->paginate(15);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_no'    => ['nullable', 'string', 'max:100', 'unique:teachers,employee_no'],
            'first_name'     => ['required', 'string', 'max:255'],
            'middle_name'    => ['nullable', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'email'          => ['nullable', 'email', 'max:255', 'unique:teachers,email'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'department'     => ['nullable', 'string', 'max:100'],
            'status'         => ['required', 'string', 'max:50'],
        ]);

        Teacher::create($validated);

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'employee_no'    => ['nullable', 'string', 'max:100', 'unique:teachers,employee_no,' . $teacher->id],
            'first_name'     => ['required', 'string', 'max:255'],
            'middle_name'    => ['nullable', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'email'          => ['nullable', 'email', 'max:255', 'unique:teachers,email,' . $teacher->id],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'department'     => ['nullable', 'string', 'max:100'],
            'status'         => ['required', 'string', 'max:50'],
        ]);

        $teacher->update($validated);

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}