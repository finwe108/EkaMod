@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

{{-- Welcome --}}
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

{{-- Role-specific stats --}}
@if(!empty($stats))
    <div class="stat-grid">
        @if(isset($stats['students']))
            <div class="stat-card">
                <div class="stat-label">Students</div>
                <div class="stat-value">{{ number_format($stats['students']) }}</div>
            </div>
        @endif

        @if(isset($stats['teachers']))
            <div class="stat-card">
                <div class="stat-label">Teachers</div>
                <div class="stat-value">{{ number_format($stats['teachers']) }}</div>
            </div>
        @endif

        @if(isset($stats['employees']))
            <div class="stat-card">
                <div class="stat-label">Employees</div>
                <div class="stat-value">{{ number_format($stats['employees']) }}</div>
            </div>
        @endif

        @if(isset($stats['sections']))
            <div class="stat-card">
                <div class="stat-label">Sections</div>
                <div class="stat-value">{{ number_format($stats['sections']) }}</div>
            </div>
        @endif

        @if(isset($stats['school_years']))
            <div class="stat-card">
                <div class="stat-label">School Years</div>
                <div class="stat-value">{{ number_format($stats['school_years']) }}</div>
            </div>
        @endif

        @if(isset($stats['collected']))
            <div class="stat-card">
                <div class="stat-label">Collected</div>
                <div class="stat-value">₱{{ number_format($stats['collected'], 2) }}</div>
            </div>
        @endif

        @if(isset($stats['attendance_rate']))
            <div class="stat-card">
                <div class="stat-label">Attendance Rate</div>
                <div class="stat-value">{{ number_format($stats['attendance_rate'], 1) }}%</div>
            </div>
        @endif

        @if(isset($stats['teacher_loads']))
            <div class="stat-card">
                <div class="stat-label">My Active Loads</div>
                <div class="stat-value">{{ number_format($stats['teacher_loads']) }}</div>
            </div>
        @endif

        @if(isset($stats['assigned_classes']))
            <div class="stat-card">
                <div class="stat-label">Assigned Classes</div>
                <div class="stat-value">{{ number_format($stats['assigned_classes']) }}</div>
            </div>
        @endif

        @if(isset($stats['documents']))
            <div class="stat-card">
                <div class="stat-label">Uploaded Documents</div>
                <div class="stat-value">{{ number_format($stats['documents']) }}</div>
            </div>
        @endif

        @if(isset($stats['verified_documents']))
            <div class="stat-card">
                <div class="stat-label">Verified Documents</div>
                <div class="stat-value">{{ number_format($stats['verified_documents']) }}</div>
            </div>
        @endif
    </div>
@endif

{{-- Announcements --}}
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
                    {{ \Illuminate\Support\Str::limit($announcement->content ?? $announcement->body ?? '', 120) }}
                </div>
            </div>
        @empty
            <div class="empty">No announcements available.</div>
        @endforelse
    </div>
</div>

{{-- Admin section --}}
@if($adminData['show'])
    <div class="card" style="margin-top:18px;">
        <div class="card-header">
            <div>
                <div class="card-title">Administration</div>
                <div class="card-subtitle">System management tools</div>
            </div>
        </div>

        <div class="card-body">
            <div class="quick-actions" style="margin-bottom:16px;">
                @if(Route::has('admin.employees.index'))
                    <a href="{{ route('admin.employees.index') }}" class="qa-btn">
                        <div class="qa-icon">👥</div>
                        <div class="qa-label">Employees</div>
                    </a>
                @endif

                @if(Route::has('admin.students.index'))
                    <a href="{{ route('admin.students.index') }}" class="qa-btn">
                        <div class="qa-icon">🎓</div>
                        <div class="qa-label">Students</div>
                    </a>
                @endif

                @if(Route::has('admin.sections.index'))
                    <a href="{{ route('admin.sections.index') }}" class="qa-btn">
                        <div class="qa-icon">🏫</div>
                        <div class="qa-label">Sections</div>
                    </a>
                @endif
            </div>

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
                            <tr class="clickable-row" data-href="{{ route('admin.employees.show', $emp) }}">
                                <td>{{ $emp->employee_id }}</td>
                                <td>{{ $emp->full_name }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $emp->employee_type)) }}</td>
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

{{-- Registrar section --}}
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
                @if(Route::has('admin.students.index'))
                    <a href="{{ route('admin.students.index') }}" class="qa-btn">
                        <div class="qa-icon">🎓</div>
                        <div class="qa-label">Students</div>
                    </a>
                @endif

                @if(Route::has('admin.sections.index'))
                    <a href="{{ route('admin.sections.index') }}" class="qa-btn">
                        <div class="qa-icon">📚</div>
                        <div class="qa-label">Sections</div>
                    </a>
                @endif

                @if(Route::has('admin.admission_applications.index'))
                    <a href="{{ route('admin.admission_applications.index') }}" class="qa-btn">
                        <div class="qa-icon">📋</div>
                        <div class="qa-label">Admissions</div>
                    </a>
                @endif
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
                            <tr data-href="{{ route('admin.students.show', $student) }}" class="clickable-row">
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

{{-- Teacher section --}}
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
                @if(Route::has('teacher.classes'))
                    <a href="{{ route('teacher.classes') }}" class="qa-btn" style="text-decoration:none; color:inherit;">
                        <div class="qa-icon">📘</div>
                        <div class="qa-label">Classes</div>
                    </a>
                @endif

                @if(Route::has('teacher.grades'))
                    <a href="{{ route('teacher.grades') }}" class="qa-btn" style="text-decoration:none; color:inherit;">
                        <div class="qa-icon">📝</div>
                        <div class="qa-label">Grades</div>
                    </a>
                @endif

                @if(Route::has('teacher.attendance'))
                    <a href="{{ route('teacher.attendance') }}" class="qa-btn" style="text-decoration:none; color:inherit;">
                        <div class="qa-icon">✅</div>
                        <div class="qa-label">Attendance</div>
                    </a>
                @endif

                @if(Route::has('teacher.schedule'))
                    <a href="{{ route('teacher.schedule') }}" class="qa-btn" style="text-decoration:none; color:inherit;">
                        <div class="qa-icon">📅</div>
                        <div class="qa-label">Schedule</div>
                    </a>
                @endif
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

{{-- Student section --}}
@if(($studentData['show'] ?? false))
    <div class="card" style="margin-top:18px;">
        <div class="card-header">
            <div>
                <div class="card-title">Student</div>
                <div class="card-subtitle">Student portal</div>
            </div>
        </div>

        <div class="card-body">
            <div class="quick-actions" style="margin-bottom:16px;">
                @if(Route::has('student.profile.show'))
                    <a href="{{ route('student.profile.show') }}" class="qa-btn" style="text-decoration:none; color:inherit;">
                        <div class="qa-icon">👤</div>
                        <div class="qa-label">My Profile</div>
                    </a>
                @endif

                @if(Route::has('student.documents.index'))
                    <a href="{{ route('student.documents.index') }}" class="qa-btn" style="text-decoration:none; color:inherit;">
                        <div class="qa-icon">📄</div>
                        <div class="qa-label">Documents</div>
                    </a>
                @endif

                @if(Route::has('student.account.edit'))
                    <a href="{{ route('student.account.edit') }}" class="qa-btn" style="text-decoration:none; color:inherit;">
                        <div class="qa-icon">⚙️</div>
                        <div class="qa-label">Account</div>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif

@endsection