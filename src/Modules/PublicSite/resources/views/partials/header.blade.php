<div class="topbar">
    <div class="container topbar__inner">
        <div class="topbar__contact">
            <a href="tel:{{ $schoolPhone }}" data-icon="phone">
                {{ $schoolPhone }}
            </a>

            <a href="mailto:{{ $schoolEmail }}" data-icon="mail">
                {{ $schoolEmail }}
            </a>
        </div>

        <div class="topbar__actions" aria-label="Quick links">
            <a href="{{ route('public.admission.create') }}">Online Enrollment</a>

            @guest
                <a href="{{ route('login') }}">Guest / Sign In</a>
            @endguest

            @auth
                <div class="user-dropdown">
                    <button type="button" class="account-toggle">
                        👤 {{ auth()->user()->name }}
                    </button>

                    <div class="account-menu">
                        <a href="{{ route('dashboard') }}">Dashboard</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>

<header class="site-header" data-header>
    <div class="container header__inner">

        <a class="brand" href="{{ route('public.home') }}#home" aria-label="{{ $schoolName }} home">
            <span class="brand__mark">
                <img src="{{ asset('assets/images/mmci-logo.png') }}" alt="" width="48" height="48">
            </span>

            <span>
                <strong>{{ $schoolName }}</strong>
                <small>{{ $tagline }}</small>
            </span>
        </a>

        <button class="icon-button nav-toggle" type="button" aria-label="Open navigation" aria-expanded="false" data-nav-toggle>
            <span></span><span></span><span></span>
        </button>

        <nav class="main-nav" data-nav>
            @foreach(config('school.nav_items', []) as $item)
                @continue(($item['key'] ?? null) === 'tesda' && !config('school.tesda_enabled'))

                @php
                    $hasChildren = !empty($item['children']);
                    $href = route($item['href']);

                    if (!empty($item['fragment'])) {
                        $href .= '#' . $item['fragment'];
                    }
                @endphp

                <div class="nav-item {{ $hasChildren ? 'has-dropdown' : '' }}">
                    <a href="{{ $href }}">{{ $item['label'] }}</a>

                    @if($hasChildren)
                        <button
                            class="dropdown-toggle"
                            type="button"
                            aria-label="Toggle {{ $item['label'] }} menu">
                        </button>

                        <div class="dropdown">
                            @foreach($item['children'] as $child)
                                @php
                                    $childHref = route($child['href']);

                                    if (!empty($child['fragment'])) {
                                        $childHref .= '#' . $child['fragment'];
                                    }
                                @endphp

                                <a href="{{ $childHref }}">{{ $child['label'] }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>
    </div>
</header>