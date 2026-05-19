<?php

namespace Modules\Announcements\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Modules\Announcements\Actions\UpdateAnnouncementAction;
use Modules\Announcements\Actions\CreateAnnouncementAction;
use Modules\Announcements\Actions\DeleteAnnouncementAction;
use Modules\Announcements\Requests\StoreAnnouncementRequest;
use Modules\Announcements\Requests\UpdateAnnouncementRequest;
use Modules\Announcements\Services\AnnouncementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Handles administrative announcement management operations.
 *
 * This controller is part of the Announcements module and preserves the
 * existing CRUD behavior from the original monolith controller.
 *
 * Existing route names, URLs, middleware, views, model usage, validation rules,
 * and redirect behavior must remain backward-compatible during this migration.
 *
 * Module: Announcements
 * Layer: HTTP Controller
 */
class AnnouncementController extends Controller
{
    /**
     * Display a paginated listing of announcements.
     *
     * Announcements are ordered by latest record first to preserve the original
     * admin listing behavior.
     *
     * @return View
     */
    public function index(): View
    {
        $announcements = Announcement::latest()->paginate(config('announcements.pagination', 15));

        return view('announcements::index', compact('announcements'));
    }

    /**
     * Show the announcement creation form.
     *
     * Uses the existing admin Blade view to avoid changing the current UI
     * during the modular migration.
     *
     * @return View
     */
    public function create(): View
    {
        return view('announcements::create');
    }

    /**
     * Store a newly created announcement.
     *
     * Validates the request using the same rules as the original controller,
     * then creates the announcement using the existing Announcement model.
     *
     * @param StoreAnnouncementRequest $request
     * @return RedirectResponse
     */
    public function store(
        StoreAnnouncementRequest $request,
        AnnouncementService $announcementService
    ): RedirectResponse {
        $validated = $request->validated();

        /*
        * posted_by currently references teachers.id, not users.id.
        * Keep nullable for admin-created announcements.
        */
        $validated['posted_by'] = null;

        $announcementService->create(
            $validated,
            $request->file('image')
        );

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement posted successfully.');
    }

    /**
     * Show the announcement edit form.
     *
     * Uses Laravel route model binding for the existing Announcement model.
     *
     * @param Announcement $announcement
     * @return View
     */
    public function edit(Announcement $announcement): View
    {
        return view('announcements::edit', compact('announcement'));
    }

    /**
     * Update an existing announcement.
     *
     * Applies the same validation rules used during creation to keep
     * announcement data consistent across create and update operations.
     *
     * @param UpdateAnnouncementRequest $request
     * @param Announcement $announcement
     * @return RedirectResponse
     */
    public function update(
        UpdateAnnouncementRequest $request,
        Announcement $announcement,
        AnnouncementService $announcementService
    ): RedirectResponse {
        $announcementService->update(
            $announcement,
            $request->validated(),
            $request->file('image')
        );

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Delete an existing announcement.
     *
     * Deleting is kept as a direct model delete to preserve the current
     * behavior. Do not change this to soft deletes unless the database schema
     * and business rule are updated separately.
     *
     * @param Announcement $announcement
     * @return RedirectResponse
     */
    public function destroy(Announcement $announcement, AnnouncementService $announcementService): RedirectResponse
    {
        $announcementService->delete($announcement);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}