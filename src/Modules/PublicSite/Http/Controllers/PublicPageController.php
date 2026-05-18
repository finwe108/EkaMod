<?php

namespace Modules\PublicSite\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Handles public website pages.
 *
 * Module: PublicSite
 * Layer: HTTP Controller
 */
class PublicPageController extends Controller
{
    /**
     * Display the public home page.
     *
     * @return View
     */
    public function home(): View
    {
        $announcements = Announcement::latest()
            ->take(3)
            ->get();        
        return view('public_site::home', compact('announcements'));
    }

    /**
     * Display the about page.
     *
     * @return View
     */
    public function about(): View
    {
        return view('public_site::about');
    }

    /**
     * Display the privacy page.
     *
     * @return View
     */
    public function privacy(): View
    {
        return view('public_site::privacy');
    }

    /**
     * Redirect admission landing URL to the public application form.
     *
     * @return RedirectResponse
     */
    public function admission(): RedirectResponse
    {
        return redirect()->route('public.admission.create');
    }

    /**
     * Display the TESDA page when enabled.
     *
     * @return View
     */
    public function tesda(): View
    {
        if (!config('school.tesda_enabled')) {
            abort(404);
        }

        return view('public_site::tesda');
    }
}