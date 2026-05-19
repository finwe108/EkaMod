<?php

namespace Modules\SchoolYears\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\SchoolYears\Requests\StoreSchoolYearRequest;
use Modules\SchoolYears\Requests\UpdateSchoolYearRequest;
use Modules\SchoolYears\Services\SchoolYearService;

/**
 * Handles administrative school year management.
 *
 * Module: SchoolYears
 * Layer: HTTP Controller
 */
class SchoolYearController extends Controller
{
    /**
     * Display school years.
     *
     * @return View
     */
    public function index(): View
    {
        $schoolYears = SchoolYear::latest()->paginate(15);

        return view('school_years::index', compact('schoolYears'));
    }

    /**
     * Show the school year creation form.
     *
     * @return View
     */
    public function create(): View
    {
        return view('school_years::create');
    }

    /**
     * Store a new school year.
     *
     * @param StoreSchoolYearRequest $request
     * @param SchoolYearService $schoolYearService
     * @return RedirectResponse
     */
    public function store(
        StoreSchoolYearRequest $request,
        SchoolYearService $schoolYearService
    ): RedirectResponse {
        $schoolYearService->create($request->validated());

        return redirect()
            ->route('admin.school_years.index')
            ->with('success', 'School year created successfully.');
    }

    /**
     * Show the school year edit form.
     *
     * @param SchoolYear $schoolYear
     * @return View
     */
    public function edit(SchoolYear $schoolYear): View
    {
        return view('school_years::edit', compact('schoolYear'));
    }

    /**
     * Update a school year.
     *
     * @param UpdateSchoolYearRequest $request
     * @param SchoolYear $schoolYear
     * @param SchoolYearService $schoolYearService
     * @return RedirectResponse
     */
    public function update(
        UpdateSchoolYearRequest $request,
        SchoolYear $schoolYear,
        SchoolYearService $schoolYearService
    ): RedirectResponse {
        $schoolYearService->update($schoolYear, $request->validated());

        return redirect()
            ->route('admin.school_years.index')
            ->with('success', 'School year updated successfully.');
    }

    /**
     * Delete a school year.
     *
     * @param SchoolYear $schoolYear
     * @param SchoolYearService $schoolYearService
     * @return RedirectResponse
     */
    public function destroy(
        SchoolYear $schoolYear,
        SchoolYearService $schoolYearService
    ): RedirectResponse {
        $schoolYearService->delete($schoolYear);

        return redirect()
            ->route('admin.school_years.index')
            ->with('success', 'School year deleted successfully.');
    }
}