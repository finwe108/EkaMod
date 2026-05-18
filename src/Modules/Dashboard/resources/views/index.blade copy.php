@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

{{-- ✅ Welcome --}}
<div class="card" style="margin-bottom:18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Welcome back</div>
            <div class="card-subtitle">
                {{ $user->name }} · {{ $user->displayRoles() ?: 'User' }}
            </div>
        </div>
    </div>
</div>

{{-- ✅ STATS --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Students</div>
        <div class="stat-value">{{ $stats['students'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Teachers</div>
        <div class="stat-value">{{ $stats['teachers'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Employees</div>
        <div class="stat-value">{{ $stats['employees'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Sections</div>
        <div class="stat-value">{{ $stats['sections'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">School Years</div>
        <div class="stat-value">{{ $stats['school_years'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Collected</div>
        <div class="stat-value">₱{{ number_format($stats['collected'], 2) }}</div>
    </div>
</div>

{{-- ✅ ANNOUNCEMENTS --}}
<div class="card" style="margin-top:18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Announcements</div>
            <div class="card-subtitle">Latest updates</div>
        </div>
    </div>

    <div class="card-body">
        @forelse($recentAnnouncements as $announcement)
            <div class="announce-item">
                <div class="announce-title">{{ $announcement->title }}</div>
                <div class="announce-body">
                    {{ \Illuminate\Support\Str::limit($announcement->body, 120) }}
                </div>
            </div>
        @empty
            <div class="empty">No announcements available.</div>
        @endforelse
    </div>
</div>

{{-- ========================= --}}
{{-- ADMIN SECTION --}}
{{-- ========================= --}}
@if($adminData['show'])
<div class="card" style="margin-top:18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Administration</div>
            <div class="card-subtitle">System management tools</div>
        </div>
    </div>

    <div class="card-body">

        {{-- Quick Actions --}}
        <div class="quick-actions" style="margin-bottom:16px;">
            <a href="{{ route('admin.employees.index') }}" class="qa-btn">
                <div class="qa-icon">👥</div>
                <div class="qa-label">Employees</div>
            </a>

            <a href="{{ route('admin.students.index') }}" class="qa-btn">
                <div class="qa-icon">🎓</div>
                <div class="qa-label">Students</div>
            </a>

            <a href="{{ route('admin.sections.index') }}" class="qa-btn">
                <div class="qa-icon">🏫</div>
                <div class="qa-label">Sections</div>
            </a>

        </div>

        {{-- Recent Employees --}}
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adminData['recentEmployees'] as $emp)
                        <tr
                            class="clickable-row"
                            data-href="{{ route('admin.employees.show', $emp) }}"
                        >
                            <td>{{ $emp->employee_id }}</td>
                            <td>{{ $emp->full_name }}</td>
                            <td>{{ ucfirst(str_replace('_',' ',$emp->employee_type)) }}</td>
                            <td>{{ ucfirst($emp->employment_status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endif

{{-- ========================= --}}
{{-- REGISTRAR SECTION --}}
{{-- ========================= --}}
@if($registrarData['show'])
<div class="card" style="margin-top:18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Registrar</div>
            <div class="card-subtitle">Student management</div>
        </div>
    </div>

    <div class="card-body">

        <div class="quick-actions" style="margin-bottom:16px;">
            <a href="{{ route('admin.students.index') }}" class="qa-btn">
                <div class="qa-icon">🎓</div>
                <div class="qa-label">Students</div>
            </a>

            <a href="{{ route('admin.sections.index') }}" class="qa-btn">
                <div class="qa-icon">📚</div>
                <div class="qa-label">Sections</div>
            </a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrarData['recentStudents'] as $student)
                        <tr
                            data-href="{{ route('admin.students.show', $student) }}"
                            class="clickable-row"
                        >
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->full_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endif

{{-- ========================= --}}
{{-- TEACHER SECTION --}}
{{-- ========================= --}}
@if($teacherData['show'])
<div class="card" style="margin-top:18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Teacher</div>
            <div class="card-subtitle">Teaching profile</div>
        </div>
    </div>

    <div class="card-body">

        <div class="quick-actions" style="margin-bottom:16px;">
            <a href="{{ route('teacher.classes') }}" class="qa-btn" style="text-decoration:none; color:inherit;">
                <div class="qa-icon">📘</div>
                <div class="qa-label">Classes</div>
            </a>

            <a href="{{ route('teacher.grades') }}" class="qa-btn" style="text-decoration:none; color:inherit;">
                <div class="qa-icon">📝</div>
                <div class="qa-label">Grades</div>
            </a>

            <a href="{{ route('teacher.attendance') }}" class="qa-btn" style="text-decoration:none; color:inherit;">
                <div class="qa-icon">✅</div>
                <div class="qa-label">Attendance</div>
            </a>
        </div>

        @if($teacherData['teacherRecord'])
            <div>
                <p><strong>Teacher No:</strong> {{ $teacherData['teacherRecord']->teacher_no }}</p>
                <p><strong>Specialization:</strong> {{ $teacherData['teacherRecord']->specialization }}</p>
                <p><strong>Subject:</strong> {{ $teacherData['teacherRecord']->subject_specialty }}</p>
                <p><strong>Rank:</strong> {{ $teacherData['teacherRecord']->rank_title }}</p>
            </div>
        @else
            <div class="empty">No teacher profile linked.</div>
        @endif

    </div>
</div>
@endif

@endsection