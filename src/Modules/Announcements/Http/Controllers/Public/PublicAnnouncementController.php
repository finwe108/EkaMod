<?php

namespace Modules\Announcements\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Handles public announcement/news pages.
 *
 * This controller displays published school announcements to public visitors.
 *
 * Module: Announcements
 * Layer: HTTP Controller
 */
class PublicAnnouncementController extends Controller
{
    /**
     * Display public announcements grouped with archive links.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $month = $request->query('month');

        $announcements = Announcement::query()
            ->when($month, function ($query) use ($month) {
                $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$month]);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
         * Archive list grouped by month.
         * Example: May 2026, April 2026, etc.
         */
        $archives = Announcement::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key")
            ->selectRaw("DATE_FORMAT(created_at, '%M %Y') as month_label")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('month_key', 'month_label')
            ->orderByDesc('month_key')
            ->get();

        return view('announcements::public.index', compact(
            'announcements',
            'archives',
            'month'
        ));
    }

    /**
     * Display a full announcement.
     *
     * @param Announcement $announcement
     * @return View
     */
    public function show(Announcement $announcement): View
    {
        $recentAnnouncements = Announcement::query()
            ->whereKeyNot($announcement->id)
            ->latest()
            ->take(5)
            ->get();

        $archives = Announcement::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key")
            ->selectRaw("DATE_FORMAT(created_at, '%M %Y') as month_label")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('month_key', 'month_label')
            ->orderByDesc('month_key')
            ->get();

        return view('announcements::public.show', compact(
            'announcement',
            'recentAnnouncements',
            'archives'
        ));
    }
}