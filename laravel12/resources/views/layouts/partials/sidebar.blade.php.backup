@php
    $user = auth()->user();
@endphp

<aside class="sidebar">
    <div class="logo">
        <div class="brand__mark">
            <a href="{{ route('public.home') }}">
                <img
                    src="{{ $schoolLogo ?? asset('assets/images/EkaModLogo.png') }}"
                    width="48"
                    height="48"
                    alt="{{ $schoolName ?? 'School Logo' }}"
                >
            </a>
        </div>
        <div>
            <div class="logo-text">{{ $shortName ?? config('app.short_name') }}</div>
            <div class="logo-sub">Enterprise System</div>
        </div>
    </div>

    <div class="sidebar-nav">
        @foreach($sidebarSections ?? [] as $section)
            <div class="nav-section">
                <div class="nav-label">{{ $section['label'] }}</div>

                @foreach($section['items'] as $item)
                    <a href="{{ $item['url'] }}"
                       class="nav-item {{ $item['is_active'] ? 'active' : '' }}">
                        <span class="icon">{{ $item['icon'] ?? '•' }}</span>

                        <span style="flex:1;">
                            {{ $item['label'] }}
                        </span>

                        @if(($item['badge_count'] ?? 0) > 0)
                            <span class="sidebar-count">
                                {{ $item['badge_count'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="avatar">{{ $user?->initials() ?? 'U' }}</div>
            <div>
                <div class="user-name">{{ $user->name ?? 'User' }}</div>
                <div class="user-role">{{ $user?->displayRoles() ?: 'User' }}</div>
            </div>
        </div>
    </div>
</aside>