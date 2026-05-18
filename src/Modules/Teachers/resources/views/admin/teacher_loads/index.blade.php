@extends('layouts.app')

@section('title', 'Teacher Loads')
@section('page_title', 'Teacher Loads')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <div>
        <h1 style="margin:0;">Teacher Loads</h1>
        <p style="margin:.25rem 0 0;">Assign subjects to teachers and sections.</p>
    </div>
    <a href="{{ route('admin.teacher_loads.create') }}" class="btn btn-primary">+ Assign Load</a>
</div>

@if(session('success'))
    <div class="card" style="margin-bottom:18px;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<div class="card" style="margin-bottom:18px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.teacher_loads.index') }}" style="display:grid; grid-template-columns: 1.5fr 1fr auto auto; gap:12px; align-items:end;">
            <div>
                <label for="teacher_id">Teacher</label>
                <select name="teacher_id" id="teacher_id" class="form-input">
                    <option value="">Select Teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ (string) $teacherId === (string) $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->employee?->full_name ?? 'Unnamed Teacher' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="school_year_id">School Year</label>
                <select name="school_year_id" id="school_year_id" class="form-input">
                    @foreach($schoolYears as $schoolYear)
                        <option value="{{ $schoolYear->id }}" {{ (string) $schoolYearId === (string) $schoolYear->id ? 'selected' : '' }}>
                            {{ $schoolYear->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>

            <div>
                <a href="{{ route('admin.teacher_loads.index') }}" class="btn btn-ghost">Reset</a>
            </div>
        </form>
    </div>
</div>

@if(!$teacherId)
    <div class="card">
        <div class="card-body">
            Select a teacher to view assigned loads.
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Section</th>
                            <th>School Year</th>
                            <th>Terms</th>
                            <th>Schedule</th>
                            <th>Room</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teacherLoads as $load)
                            <tr>
                                <td>{{ $load->teacher?->employee?->full_name ?? '—' }}</td>

                                <td>
                                    @php
                                        $subjectNames = $load->loadSubjects->pluck('subject.name')->filter()->values();
                                        $mapehParts = collect(['Music', 'Arts', 'PE', 'Health']);
                                        $isMapeh = $mapehParts->diff($subjectNames)->isEmpty() && $subjectNames->count() === 4;
                                    @endphp

                                    @if($subjectNames->isEmpty())
                                        —
                                    @elseif($isMapeh)
                                        MAPEH
                                        <div style="font-size:12px; color:#666;">{{ $subjectNames->implode(' / ') }}</div>
                                    @else
                                        {{ $subjectNames->implode(' / ') }}
                                    @endif
                                </td>

                                <td>{{ $load->section?->name ?? '—' }}</td>
                                <td>{{ $load->schoolYear?->name ?? '—' }}</td>
                                <td>{{ $load->termLabel() }}</td>

                                <td>
                                    @forelse($load->schedules as $schedule)
                                        <div>
                                            @php
                                                $days = [
                                                    1 => 'Mon',
                                                    2 => 'Tue',
                                                    3 => 'Wed',
                                                    4 => 'Thu',
                                                    5 => 'Fri',
                                                    6 => 'Sat',
                                                ];
                                            @endphp

                                            {{ $days[$schedule->day_of_week] ?? $schedule->day_of_week }}
                                            {{ \Carbon\Carbon::parse($schedule->time_start)->format('g:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($schedule->time_end)->format('g:i A') }}
                                        </div>
                                    @empty
                                        —
                                    @endforelse
                                </td>

                                <td>
                                    @php
                                        $rooms = $load->schedules->pluck('room')->filter()->unique()->values();
                                    @endphp

                                    {{ $rooms->isNotEmpty() ? $rooms->implode(' / ') : '—' }}
                                </td>

                                <td>
                                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                        <a href="{{ route('admin.teacher_loads.edit', $load->id) }}" class="btn btn-ghost">Edit</a>

                                        <form action="{{ route('admin.teacher_loads.destroy', $load->id) }}" method="POST" onsubmit="return confirm('Delete this assignment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost">Delete</button>
                                        </form>

                                        <a href="{{ route('admin.teachers.schedule', $load->teacher_id) }}" class="btn btn-ghost">View Schedule</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">No teacher loads found for the selected teacher.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding:16px;">
                {{ $teacherLoads->links() }}
            </div>
        </div>
    </div>
@endif
@endsection
