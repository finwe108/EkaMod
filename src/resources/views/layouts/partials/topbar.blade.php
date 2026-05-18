<header class="topbar no-print">
    <button type="button" class="burger-btn" id="burgerBtn" aria-label="Open menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div>
        <div class="page-title" id="pageTitle">
            @yield('page_title', 'Dashboard')
        </div>
    </div>

    <div class="topbar-right">
        <input class="search-bar" placeholder="🔍 Search anything…" type="text">

        <button class="btn btn-ghost" type="button">Export</button>

        @yield('topbar_actions')

        @php
            $user = auth()->user();

            $unreadNotifications = $user?->unreadNotifications()->latest()->take(5)->get();
            $unreadCount = $user?->unreadNotifications()->count() ?? 0;

            $missingDocumentCount = 0;
            $pendingDocumentVerificationCount = 0;

            if ($user && $user->roles->contains('name', 'student') && $user->student) {
                $student = $user->student;

                $currentEnrollment = $student->currentEnrollment()->first()
                    ?: $student->latestEnrollment()->first();

                $gradeLevelId = $currentEnrollment?->grade_level_id;
                $studentType = $currentEnrollment?->student_type;

                $requiredDocumentTypeIds = \App\Models\DocumentRequirementRule::query()
                    ->where('is_required', true)
                    ->whereHas('documentType', function ($query) {
                        $query->where('is_active', true);
                    })
                    ->where(function ($query) use ($gradeLevelId) {
                        $query->whereNull('grade_level_id')
                            ->orWhere('grade_level_id', $gradeLevelId);
                    })
                    ->where(function ($query) use ($studentType) {
                        $query->whereNull('student_type')
                            ->orWhere('student_type', $studentType);
                    })
                    ->pluck('document_type_id')
                    ->unique();

                $verifiedDocumentTypeIds = $student->documents()
                    ->whereIn('document_type_id', $requiredDocumentTypeIds)
                    ->where('is_verified', true)
                    ->pluck('document_type_id');

                $missingDocumentCount = $requiredDocumentTypeIds
                    ->diff($verifiedDocumentTypeIds)
                    ->count();
            }

            if ($user && ! $user->roles->contains('name', 'student')) {
                $pendingDocumentVerificationCount = \App\Models\StudentDocument::query()
                    ->where('status', 'submitted')
                    ->where('is_verified', false)
                    ->count();
            }

            $topbarNotificationCount = $unreadCount
                + $missingDocumentCount
                + $pendingDocumentVerificationCount;

            /*
             * Route the bell to the source of the count.
             *
             * Student missing documents should open the student document page.
             * Admin pending document verification should open verification page.
             * Otherwise, open the normal notification list.
             */
            if ($missingDocumentCount > 0 && Route::has('student.documents.index')) {
                $notificationUrl = route('student.documents.index');
            } elseif ($pendingDocumentVerificationCount > 0 && Route::has('admin.student-documents.index')) {
                $notificationUrl = route('admin.student-documents.index');
            } else {
                $notificationUrl = route('notifications.index');
            }
        @endphp

        <div class="notification-wrapper">
            <a href="{{ $notificationUrl }}"
               class="notif-btn"
               title="Unread: {{ $unreadCount }}, Missing docs: {{ $missingDocumentCount }}, Pending verification: {{ $pendingDocumentVerificationCount }}">
                🔔

                @if($topbarNotificationCount > 0)
                    <span class="notif-count">{{ $topbarNotificationCount }}</span>
                @endif
            </a>
        </div>

        <button type="button" id="theme-toggle" class="btn btn-ghost" title="Toggle theme">
            <span id="theme-toggle-icon">🌙</span>
            <span id="theme-toggle-text">Dark</span>
        </button>

        @auth
            <div class="topbar-user-dropdown" id="topbarUserDropdown">
                <button type="button" class="topbar-user-toggle" id="topbarUserToggle" aria-expanded="false">
                    <div class="topbar-user-text">
                        <div class="topbar-user-name">{{ auth()->user()->name }}</div>
                        <div class="topbar-user-role">{{ auth()->user()->displayRoles() ?: 'User' }}</div>
                    </div>
                    <span class="topbar-user-caret">▾</span>
                </button>

                <div class="topbar-user-menu" id="topbarUserMenu">
                    <div class="topbar-user-menu-header">
                        <div class="topbar-user-menu-name">{{ auth()->user()->name }}</div>
                        <div class="topbar-user-menu-role">{{ auth()->user()->displayRoles() ?: 'User' }}</div>
                    </div>

                    <a href="{{ route('dashboard') }}" class="topbar-user-menu-item">Dashboard</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="topbar-user-menu-item topbar-user-menu-danger">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</header>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.getElementById('topbarUserDropdown');
    const toggle = document.getElementById('topbarUserToggle');

    if (!dropdown || !toggle) return;

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();

        const isOpen = dropdown.classList.toggle('open');

        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
});
</script>
@endpush