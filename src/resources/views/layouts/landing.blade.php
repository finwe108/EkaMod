<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Dynamic title --}}
    <title>@yield('title', config('school.name'))</title>

    {{-- SEO --}}
    <meta name="description" content="@yield('meta_description', config('school.name') . ' official website')">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}?v={{ time() }}">

    {{-- Optional preload --}}
    <link rel="preload" href="{{ asset('assets/images/campus-hero-philippines.png') }}" as="image">
</head>

<body class="@yield('body_class')">

    {{-- TOPBAR --}}
    @include('public_site.partials.header')

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('public_site.partials.footer')
    @stack('scripts')

</body>
</html>