<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MMCI')</title>

    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');

            if (savedTheme === 'light' || savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', savedTheme);
                return;
            }

            const prefersDark = window.matchMedia &&
                window.matchMedia('(prefers-color-scheme: dark)').matches;

            document.documentElement.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v={{ config('app.asset_version') }}">
    @stack('styles')
</head>
<body>
    @auth
        @include('layouts.partials.sidebar')
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @endauth

    <main class="main">
        @auth
            @include('layouts.partials.topbar')
        @endauth

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="margin-bottom: 1rem;">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="{{ asset('assets/js/app.js') }}?v={{ config('app.asset_version') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}?v={{ config('app.asset_version') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.clickable-row').forEach(row => {
                row.addEventListener('click', (e) => {
                    const url = row.dataset.href;
                    if (!url) return;

                    if (e.ctrlKey || e.metaKey) {
                        window.open(url, '_blank');
                    } else {
                        window.location = url;
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>