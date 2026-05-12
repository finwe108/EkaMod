@php
    $user = auth()->user();
    $roles = $user ? $user->roles->pluck('name')->toArray() : [];

    $isAdminLike = in_array('super_admin', $roles) || in_array('admin', $roles) || in_array('hr', $roles);
    $isRegistrar = in_array('registrar', $roles);
    $isTeacher = in_array('teacher', $roles);
    $isStudent = in_array('student', $roles);

    $initials = strtoupper(substr($user->name ?? 'U', 0, 1));
@endphp

<aside class="sidebar">
    <div class="logo">
        <div class="brand__mark">
            <a href="{{ route('public.home') }}">
                <img src="{{ asset('assets/images/mmci-logo.png') }}" width="48" height="48" alt="">
            </a>
        </div>
        <div>
            <div class="logo-text">MMCI</div>
            <div class="logo-sub">Enterprise SIS</div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-label">Overview</div>

            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="icon">⊞</span> Dashboard
            </a>
        </div>

        @if($isAdminLike)
            <div class="nav-section">
                <div class="nav-label">Administration</div>

                <a href="{{ route('admin.employees.index') }}" class="nav-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                    <span class="icon">👥</span> Employees
                </a>

                <a href="{{ route('admin.school_years.index') }}" class="nav-item {{ request()->routeIs('admin.school_years.*') ? 'active' : '' }}">
                    <span class="icon">📅</span> School Years
                </a>

                <a href="{{ route('admin.announcements.index') }}" class="nav-item {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                    <span class="icon">📢</span> Announcements
                </a>

                <a href="{{ route('admin.finance.index') }}" class="nav-item {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
                    <span class="icon">💰</span> Finance
                </a>

                <a href="{{ route('admin.school-settings.edit') }}" class="nav-item {{ request()->routeIs('admin.school-settings.*') ? 'active' : '' }}">
                    <span class="icon">🏫</span> School Settings
                </a>
            </div>
        @endif

        @if($isAdminLike || $isRegistrar)
            <div class="nav-section">
                <div class="nav-label">Registrar</div>

                <a href="{{ route('admin.students.index') }}" class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    <span class="icon">🎓</span> Students
                </a>

                <a href="{{ route('admin.subjects.index') }}" class="nav-item {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                    <span class="icon">📚</span> Subjects
                </a>

                <a href="{{ route('admin.teacher_loads.index') }}" class="nav-item {{ request()->routeIs('admin.teacher_loads.*') ? 'active' : '' }}">
                    <span class="icon">📘</span> Teacher Loads
                </a>

                <a href="{{ route('admin.sections.index') }}" class="nav-item {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
                    <span class="icon">🏫</span> Sections
                </a>

                <a href="{{ route('admin.admission_applications.index') }}" class="nav-item {{ request()->routeIs('admin.admission_applications.*') ? 'active' : '' }}">
                    <span class="icon">📋</span> Admission Applications
                </a>

                <a href="{{ route('admin.document-types.index') }}"
                class="nav-item {{ request()->routeIs('admin.document-types.*') ? 'active' : '' }}">
                    <span class="icon">📄</span> Document Types
                </a>

                <a href="{{ route('admin.document-requirement-rules.index') }}"
                class="nav-item {{ request()->routeIs('admin.document-requirement-rules.*') ? 'active' : '' }}">
                    <span class="icon">📋</span> Document Rules
                </a>

                <a href="{{ route('admin.student-documents.index') }}"
                class="nav-item {{ request()->routeIs('admin.student-documents.*') ? 'active' : '' }}">
                    <span class="icon">✅</span> Verify Documents
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-label">Reports</div>

                <a href="{{ route('admin.reports.sf1.filter') }}" class="nav-item {{ request()->routeIs('admin.reports.sf1.*') ? 'active' : '' }}">
                    <span class="icon">🧾</span> SF1 Report
                </a>
            </div>
        @endif

        @if($isTeacher)
            <div class="nav-section">
                <div class="nav-label">Teaching</div>

                <a href="{{ route('teacher.classes') }}" class="nav-item {{ request()->routeIs('teacher.classes') ? 'active' : '' }}">
                    <span class="icon">📘</span> Classes
                </a>

                <a href="{{ route('teacher.grades') }}" class="nav-item {{ request()->routeIs('teacher.grades') ? 'active' : '' }}">
                    <span class="icon">📝</span> Grades
                </a>

                <a href="{{ route('teacher.attendance') }}" class="nav-item {{ request()->routeIs('teacher.attendance') ? 'active' : '' }}">
                    <span class="icon">✅</span> Attendance
                </a>
            </div>
        @endif

        @if($isStudent)
            <div class="nav-section">
                <div class="nav-label">Student</div>

                <a href="{{ route('student.profile.show') }}" class="nav-item {{ request()->routeIs('student.profile.*') ? 'active' : '' }}">
                    <span class="icon">👤</span> My Profile
                </a>

                <a href="#" class="nav-item">
                    <span class="icon">📝</span> My Grades
                </a>

                <a href="#" class="nav-item">
                    <span class="icon">✅</span> My Attendance
                </a>

                @php
                    $requiredDocsSidebarCount = 0;

                    if(auth()->check()
                        && auth()->user()->roles->contains('name', 'student')
                        && auth()->user()->student) {

                        $student = auth()->user()->student;

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

                        $requiredDocsSidebarCount = $requiredDocumentTypeIds
                            ->diff($verifiedDocumentTypeIds)
                            ->count();
                    }
                @endphp

                <a href="{{ route('student.documents.index') }}"
                class="nav-item {{ request()->routeIs('student.documents.*') ? 'active' : '' }}">

                    <span class="icon">📄</span>

                    <span style="flex:1;">
                        Required Documents
                    </span>

                    @if($requiredDocsSidebarCount > 0)
                        <span class="sidebar-count">
                            {{ $requiredDocsSidebarCount }}
                        </span>
                    @endif
                </a>

            </div>
            <div class="nav-section">
                <div class="nav-label">Account</div>

                <a href="{{ route('student.account.edit') }}"
                class="nav-item {{ request()->routeIs('student.account.*') ? 'active' : '' }}">
                    <span class="icon">⚙️</span> Account Settings
                </a>

                <a href="{{ route('student.password.edit') }}"
                class="nav-item {{ request()->routeIs('student.password.*') ? 'active' : '' }}">
                    <span class="icon">🔐</span> Change Password
                </a>
                
            </div>
        @endif
    </div>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="avatar">{{ auth()->user()->initials() }}</div>
            <div>
                <div class="user-name">{{ $user->name ?? 'User' }}</div>
                <div class="user-role">{{ $user?->displayRoles() ?: 'User' }}</div>
            </div>
        </div>
    </div>
</aside>