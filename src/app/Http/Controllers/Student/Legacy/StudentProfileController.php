<?php

namespace App\Http\Controllers\Student\Legacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    public function show()
    {
        $student = auth()->user()->student;

        abort_if(!$student, 404, 'Student profile not found.');

        $student->load([
            'enrollments.schoolYear',
            'enrollments.gradeLevel',
            'enrollments.section',
        ]);

        return view('student.profile.show', compact('student'));
    }

    public function edit()
    {
        $student = auth()->user()->student;

        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = auth()->user()->student;

        $data = $request->validate([
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:20',
        ]);

        $student->update($data);

        return back()->with('success', 'Profile updated.');
    }
}