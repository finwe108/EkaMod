<?php

namespace Modules\Teachers\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Teachers\Requests\Admin\StoreTeacherRequest;
use Modules\Teachers\Requests\Admin\UpdateTeacherRequest;
use Modules\Teachers\Services\Admin\TeacherService;

/**
 * Handles administrative teacher profile management.
 *
 * A teacher is not a standalone person record. A teacher is an employee
 * with employee_type=teaching and a related teacher profile row.
 *
 * Module: Teachers
 * Layer: HTTP Controller
 */
class TeacherController extends Controller
{
    /**
     * Display teacher profiles.
     *
     * @return View
     */
    public function index(): View
    {
        $teachers = Teacher::with('employee')
            ->latest()
            ->paginate(20);

        return view('teachers::admin.teachers.index', compact('teachers'));
    }

    /**
     * Show teacher profile creation form.
     *
     * @return View
     */
    public function create(): View
    {
        $employees = Employee::query()
            ->where('employee_type', 'teaching')
            ->whereDoesntHave('teacher')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('teachers::admin.teachers.create', compact('employees'));
    }

    /**
     * Store a teacher profile.
     *
     * @param StoreTeacherRequest $request
     * @param TeacherService $teacherService
     * @return RedirectResponse
     */
    public function store(
        StoreTeacherRequest $request,
        TeacherService $teacherService
    ): RedirectResponse {
        $teacherService->create($request->validated());

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher profile created successfully.');
    }

    /**
     * Show a teacher profile.
     *
     * @param Teacher $teacher
     * @return View
     */
    public function show(Teacher $teacher): View
    {
        $teacher->load(['employee', 'teacherLoads.subject', 'teacherLoads.section', 'teacherLoads.schoolYear']);

        return view('teachers::admin.teachers.show', compact('teacher'));
    }

    /**
     * Show teacher profile edit form.
     *
     * @param Teacher $teacher
     * @return View
     */
    public function edit(Teacher $teacher): View
    {
        $teacher->load('employee');

        return view('teachers::admin.teachers.edit', compact('teacher'));
    }

    /**
     * Update a teacher profile.
     *
     * @param UpdateTeacherRequest $request
     * @param Teacher $teacher
     * @param TeacherService $teacherService
     * @return RedirectResponse
     */
    public function update(
        UpdateTeacherRequest $request,
        Teacher $teacher,
        TeacherService $teacherService
    ): RedirectResponse {
        $teacherService->update($teacher, $request->validated());

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher profile updated successfully.');
    }

    /**
     * Delete a teacher profile.
     *
     * This does not delete the underlying employee record.
     *
     * @param Teacher $teacher
     * @param TeacherService $teacherService
     * @return RedirectResponse
     */
    public function destroy(
        Teacher $teacher,
        TeacherService $teacherService
    ): RedirectResponse {
        $teacherService->delete($teacher);

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher profile deleted successfully.');
    }
}