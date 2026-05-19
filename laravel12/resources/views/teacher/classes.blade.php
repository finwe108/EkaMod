@extends('layouts.app')

@section('title', 'My Classes')
@section('page_title', 'My Classes')

@section('content')
<div class="card" style="margin-bottom: 18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Teacher Classes</div>
            <div class="card-subtitle">Assigned classes, subjects, and teaching load</div>
        </div>
    </div>

    <div class="card-body">
        @if($teacher)
            <div class="form-row" style="margin-bottom: 18px;">
                <div>
                    <div><strong>Teacher No:</strong> {{ $teacher->teacher_no ?: '-' }}</div>
                    <div><strong>Specialization:</strong> {{ $teacher->specialization ?: '-' }}</div>
                </div>
                <div>
                    <div><strong>Subject Specialty:</strong> {{ $teacher->subject_specialty ?: '-' }}</div>
                    <div><strong>Rank / Title:</strong> {{ $teacher->rank_title ?: '-' }}</div>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Section</th>
                            <th>School Year</th>
                            <th>Terms</th>
                            <th>Schedule</th>
                            <th>Room</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teacherLoads as $load)
                            <tr>
                                <td>
                                    @php
                                        $subjectNames = $load->loadSubjects->pluck('subject.name')->filter()->values();
                                    @endphp

                                    @if($subjectNames->isNotEmpty())
                                        <div>{{ $subjectNames->implode(' / ') }}</div>
                                    @else
                                        <div>{{ $load->subject?->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $load->subject?->code ?? '' }}</small>
                                    @endif
                                </td>
                                <td>{{ $load->section?->name ?? '-' }}</td>
                                <td>{{ $load->schoolYear?->name ?? '-' }}</td>
                                <td>{{ $load->termLabel() }}</td>
                                <td>
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

                                    @forelse($load->schedules as $schedule)
                                        <div>
                                            {{ $days[$schedule->day_of_week] ?? $schedule->day_of_week }}
                                            {{ \Carbon\Carbon::parse($schedule->time_start)->format('g:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($schedule->time_end)->format('g:i A') }}
                                        </div>
                                    @empty
                                        {{ $load->schedule_time ?: '-' }}
                                    @endforelse
                                </td>
                                <td>
                                    @php
                                        $rooms = $load->schedules->pluck('room')->filter()->unique()->values();
                                    @endphp

                                    {{ $rooms->isNotEmpty() ? $rooms->implode(' / ') : ($load->room ?: '-') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No teaching loads assigned yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty">
                No teacher profile linked to this account yet.
            </div>
        @endif
    </div>
</div>
@endsection
