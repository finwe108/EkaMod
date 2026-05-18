<?php

namespace Modules\Notifications\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Handles authenticated user notifications.
 *
 * This controller preserves the existing notification list, mark-as-read,
 * and mark-all-as-read behavior from the original monolith.
 *
 * Module: Notifications
 * Layer: HTTP Controller
 */
class NotificationController extends Controller
{
    /**
     * Display paginated notifications for the authenticated user.
     *
     * @return View
     */
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications::index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function markAsRead(string $id): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return back();
    }

    /**
     * Mark all unread notifications as read.
     *
     * @return RedirectResponse
     */
    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()
            ->unreadNotifications
            ->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}