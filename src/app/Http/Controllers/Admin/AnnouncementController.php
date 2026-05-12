<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'body'      => ['required', 'string'],
            'category'  => ['nullable', 'string', 'max:50'],
            'status'    => ['required', 'string', 'max:50'],
            'posted_at' => ['nullable', 'date'],
        ]);

        Announcement::create($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement posted successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'body'      => ['required', 'string'],
            'category'  => ['nullable', 'string', 'max:50'],
            'status'    => ['required', 'string', 'max:50'],
            'posted_at' => ['nullable', 'date'],
        ]);

        $announcement->update($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}