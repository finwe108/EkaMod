@extends('public_site::layouts.landing')

@section('title', $announcement->title . ' | ' . $schoolName)

@section('content')
<section class="section">
    <div class="container">
        <div class="news-layout">
            <article class="news-card">
                <span>
                    {{ optional($announcement->created_at)->format('M d, Y') }}
                    / Announcement
                </span>

                <h1>{{ $announcement->title }}</h1>

                <div style="margin-top: 18px; line-height: 1.8;">
                    {!! nl2br(e($announcement->content)) !!}
                </div>

                <div style="margin-top: 24px;">
                    <a href="{{ route('public.news.index') }}">
                        ← Back to News & Announcements
                    </a>
                </div>
            </article>

            <aside class="news-sidebar">
                <div class="news-card">
                    <span>Recent</span>
                    <h3>Recent Announcements</h3>

                    @forelse($recentAnnouncements as $recent)
                        <a href="{{ route('public.news.show', $recent) }}"
                           style="display:block; margin-top:10px;">
                            {{ $recent->title }}
                        </a>
                    @empty
                        <p>No recent announcements.</p>
                    @endforelse
                </div>

                <div class="news-card" style="margin-top:18px;">
                    <span>Archive</span>
                    <h3>Previous Announcements</h3>

                    @forelse($archives as $archive)
                        <a href="{{ route('public.news.index', ['month' => $archive->month_key]) }}"
                           style="display:flex; justify-content:space-between; margin-top:10px;">
                            <span>{{ $archive->month_label }}</span>
                            <span>{{ $archive->total }}</span>
                        </a>
                    @empty
                        <p>No archive available.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection