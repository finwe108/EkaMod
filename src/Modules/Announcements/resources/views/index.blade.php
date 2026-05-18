@extends('layouts.app')

@section('title', 'Announcements | MMCI')
@section('page_title', 'Announcements')

@section('topbar_actions')
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">+ Post Announcement</a>
@endsection

@section('content')
    @if(session('success'))
        <div class="card" style="margin-bottom:16px; border-color: rgba(45,212,160,.35);">
            <div class="card-body">{{ session('success') }}</div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">School Announcements</div>
                <div class="card-subtitle">Manage posted notices and updates</div>
            </div>
        </div>

        <div>
            @forelse($announcements as $announcement)
                <div class="announce-item">
                    <div style="display:flex;justify-content:space-between;gap:16px;align-items:flex-start;">
                        <div style="flex:1;">
                            <div class="announce-title">{{ $announcement->title }}</div>
                            <div class="announce-body">{{ $announcement->body }}</div>
                            <div class="announce-meta">
                                {{ ucfirst($announcement->category ?? 'General') }} · {{ ucfirst($announcement->status) }}
                                @if($announcement->posted_at)
                                    · {{ $announcement->posted_at->format('M d, Y h:i A') }}
                                @endif
                            </div>
                        </div>

                        <div style="display:flex;gap:8px;flex-shrink:0;">
                            <a href="{{ route('admin.announcements.edit', $announcement) }}"
                               class="btn btn-ghost"
                               style="font-size:11px;padding:4px 10px">Edit</a>

                            <form method="POST"
                                  action="{{ route('admin.announcements.destroy', $announcement) }}"
                                  onsubmit="return confirm('Delete this announcement?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-ghost"
                                        style="font-size:11px;padding:4px 10px">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty">No announcements found.</div>
            @endforelse
        </div>
    </div>

    <div style="margin-top:16px;">
        {{ $announcements->links() }}
    </div>
@endsection