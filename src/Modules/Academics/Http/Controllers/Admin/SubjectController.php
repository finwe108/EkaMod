<?php

namespace Modules\Academics\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Academics\Requests\Admin\StoreSubjectRequest;
use Modules\Academics\Requests\Admin\UpdateSubjectRequest;
use Modules\Academics\Services\Admin\SubjectService;

/**
 * Handles administrative subject management.
 *
 * This controller preserves existing subject CRUD behavior while moving
 * ownership into the Academics module.
 *
 * Module: Academics
 * Layer: HTTP Controller
 */
class SubjectController extends Controller
{
    /**
     * Display subjects.
     *
     * @return View
     */
    public function index(): View
    {
        $subjects = Subject::with('gradeLevel')
            ->latest()
            ->paginate(20);

        return view('academics::admin.subjects.index', compact('subjects'));
    }

    /**
     * Show subject creation form.
     *
     * @return View
     */
    public function create(): View
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();

        return view('academics::admin.subjects.create', compact('gradeLevels'));
    }

    /**
     * Store a subject.
     *
     * @param StoreSubjectRequest $request
     * @param SubjectService $subjectService
     * @return RedirectResponse
     */
    public function store(
        StoreSubjectRequest $request,
        SubjectService $subjectService
    ): RedirectResponse {
        $subjectService->create($request->validated());

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Display a subject.
     *
     * @param Subject $subject
     * @return View
     */
    public function show(Subject $subject): View
    {
        $subject->load([
            'gradeLevel',
            'teacherLoads.teacher.employee',
            'teacherLoads.section',
            'teacherLoads.schoolYear',
        ]);

        return view('academics::admin.subjects.show', compact('subject'));
    }

    /**
     * Show subject edit form.
     *
     * @param Subject $subject
     * @return View
     */
    public function edit(Subject $subject): View
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();

        return view('academics::admin.subjects.edit', compact(
            'subject',
            'gradeLevels'
        ));
    }

    /**
     * Update a subject.
     *
     * @param UpdateSubjectRequest $request
     * @param Subject $subject
     * @param SubjectService $subjectService
     * @return RedirectResponse
     */
    public function update(
        UpdateSubjectRequest $request,
        Subject $subject,
        SubjectService $subjectService
    ): RedirectResponse {
        $subjectService->update($subject, $request->validated());

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Delete a subject.
     *
     * @param Subject $subject
     * @param SubjectService $subjectService
     * @return RedirectResponse
     */
    public function destroy(
        Subject $subject,
        SubjectService $subjectService
    ): RedirectResponse {
        $subjectService->delete($subject);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}