@extends('public_site::layouts.landing')

@section('title', 'News & Announcements | ' . $schoolName)

@section('content')
<section class="section">
    <div class="container">
        <div class="section__header">
            <span class="eyebrow">News & Announcements</span>
            <h1>School Updates</h1>
            <p>Read the latest announcements, advisories, and school updates.</p>
        </div>

        <div class="news-layout">
            <div>
                @if($month)
                    <div class="card" style="margin-bottom: 18px;">
                        <div class="card-body">
                            Showing announcements for <strong>{{ $month }}</strong>.
                            <a href="{{ route('public.news.index') }}">View all</a>
                        </div>
                    </div>
                @endif

                <div class="news-list">
                    @forelse($announcements as $announcement)
                        <article class="news-card">
                            <span>
                                {{ optional($announcement->created_at)->format('M d, Y') }}
                                / Announcement
                            </span>

                            <h2>{{ $announcement->title }}</h2>

                            <p>
                                {{ \Illuminate\Support\Str::limit($announcement->content, 180) }}
                            </p>

                            <a href="{{ route('public.news.show', $announcement) }}">
                                Read full announcement
                            </a>
                        </article>
                    @empty
                        <article class="news-card">
                            <span>Updates</span>
                            <h2>No announcements found</h2>
                            <p>Please check back soon for school updates.</p>
                        </article>
                    @endforelse
                </div>

                <div style="margin-top: 20px;">
                    {{ $announcements->links() }}
                </div>
            </div>

            <aside class="news-sidebar">
                <div class="news-card">
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