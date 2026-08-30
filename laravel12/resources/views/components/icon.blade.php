@props([
    'name' => 'default',
    'size' => 20,
    'strokeWidth' => 1.5,
])

@php
    $icons = [
        'default' => '
            <circle cx="12" cy="12" r="9" />
        ',

        'dashboard' => '
            <rect x="3" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="3" width="7" height="7" rx="1" />
            <rect x="3" y="14" width="7" height="7" rx="1" />
            <rect x="14" y="14" width="7" height="7" rx="1" />
        ',

        'bell' => '
            <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9a6 6 0 0 0-12 0v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.077 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        ',

        'users' => '
            <path d="M15 19a6 6 0 0 0-12 0" />
            <circle cx="9" cy="7" r="4" />
            <path d="M22 19a6 6 0 0 0-4-5.65" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        ',

        'graduation-cap' => '
            <path d="M3 10l9-5 9 5-9 5-9-5Z" />
            <path d="M7 12.5V16c0 1.5 2.5 3 5 3s5-1.5 5-3v-3.5" />
            <path d="M21 10v6" />
        ',

        'academic-cap' => '
            <path d="M3 10l9-5 9 5-9 5-9-5Z" />
            <path d="M7 12.5V16c0 1.5 2.5 3 5 3s5-1.5 5-3v-3.5" />
            <path d="M21 10v6" />
        ',

        'document-text' => '
            <path d="M14.25 2.25H6a1.5 1.5 0 0 0-1.5 1.5v16.5A1.5 1.5 0 0 0 6 21.75h12a1.5 1.5 0 0 0 1.5-1.5V7.5l-5.25-5.25Z" />
            <path d="M14.25 2.25V7.5h5.25" />
            <path d="M8.25 12h7.5" />
            <path d="M8.25 15.75h7.5" />
            <path d="M8.25 19.5h4.5" />
        ',

        'document' => '
            <path d="M14.25 2.25H6a1.5 1.5 0 0 0-1.5 1.5v16.5A1.5 1.5 0 0 0 6 21.75h12a1.5 1.5 0 0 0 1.5-1.5V7.5l-5.25-5.25Z" />
            <path d="M14.25 2.25V7.5h5.25" />
        ',

        'clipboard-document' => '
            <rect x="6" y="4" width="12" height="17" rx="2" />
            <path d="M9 4V3a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1" />
            <path d="M9 9h6M9 13h6M9 17h4" />
        ',

        'clipboard-document-list' => '
            <rect x="6" y="4" width="12" height="17" rx="2" />
            <path d="M9 4V3a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1" />
            <path d="M9 9h1M13 9h2M9 13h1M13 13h2M9 17h1M13 17h2" />
        ',

        'receipt' => '
            <path d="M4 3h16v18l-3-2-3 2-3-2-3 2-4-2V3Z" />
            <path d="M8 8h8M8 12h8M8 16h4" />
        ',

        'check-circle' => '
            <circle cx="12" cy="12" r="9" />
            <path d="m8 12 2.5 2.5L16 9" />
        ',

        'calendar' => '
            <rect x="3" y="4.5" width="18" height="16" rx="2" />
            <path d="M16 2.5v4M8 2.5v4M3 9.5h18" />
        ',

        'calendar-days' => '
            <rect x="3" y="4.5" width="18" height="16" rx="2" />
            <path d="M16 2.5v4M8 2.5v4M3 9.5h18" />
            <path d="M8 13h.01M12 13h.01M16 13h.01M8 17h.01M12 17h.01M16 17h.01" />
        ',

        'building-office' => '
            <path d="M4 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17" />
            <path d="M16 9h3a1 1 0 0 1 1 1v11" />
            <path d="M8 7h4M8 11h4M8 15h4M8 19h4M3 21h18" />
        ',

        'book-open' => '
            <path d="M2.75 5.75A2.75 2.75 0 0 1 5.5 3h5.25a1.25 1.25 0 0 1 1.25 1.25V21a3 3 0 0 0-3-3H5.5a2.75 2.75 0 0 0-2.75 2.75V5.75Z" />
            <path d="M21.25 5.75A2.75 2.75 0 0 0 18.5 3h-5.25A1.25 1.25 0 0 0 12 4.25V21a3 3 0 0 1 3-3h3.5a2.75 2.75 0 0 1 2.75 2.75V5.75Z" />
        ',

        'pencil-square' => '
            <path d="M4 20h4l11-11-4-4L4 16v4Z" />
            <path d="m14 5 4 4" />
            <path d="M14 20h6" />
        ',

        'lock-closed' => '
            <rect x="4" y="10" width="16" height="11" rx="2" />
            <path d="M8 10V7a4 4 0 0 1 8 0v3" />
        ',

        'cog-6-tooth' => '
            <path d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.592c.55 0 1.02.398 1.11.94l.184 1.105a7.5 7.5 0 0 1 1.7.983l1.03-.41c.506-.2 1.08.01 1.35.49l1.296 2.245c.275.477.16 1.085-.277 1.38l-.847.577a7.5 7.5 0 0 1 0 1.964l.847.577c.437.295.552.903.277 1.38l-1.296 2.245c-.27.48-.844.69-1.35.49l-1.03-.41a7.5 7.5 0 0 1-1.7.983l-.184 1.105c-.09.542-.56.94-1.11.94h-2.592c-.55 0-1.02-.398-1.11-.94l-.184-1.105a7.5 7.5 0 0 1-1.7-.983l-1.03.41c-.506.2-1.08-.01-1.35-.49l-1.296-2.245c-.275-.477-.16-1.085.277-1.38l.847-.577a7.5 7.5 0 0 1 0-1.964l-.847-.577c-.437-.295-.552-.903-.277-1.38L5.33 6.108c.27-.48.844-.69 1.35-.49l1.03.41a7.5 7.5 0 0 1 1.7-.983l.184-1.105Z" />
            <circle cx="12" cy="12" r="3" />
        ',

        'default'
    ];

    $paths = $icons[$name] ?? $icons['default'];
@endphp

<svg
    {{ $attributes->merge([
        'width' => $size,
        'height' => $size,
        'viewBox' => '0 0 24 24',
        'fill' => 'none',
        'stroke' => 'currentColor',
        'stroke-width' => $strokeWidth,
        'stroke-linecap' => 'round',
        'stroke-linejoin' => 'round',
        'aria-hidden' => 'true',
    ]) }}>
    {!! $paths !!}
</svg>

