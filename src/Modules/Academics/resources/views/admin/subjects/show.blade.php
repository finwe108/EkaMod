@extends('layouts.app')

@section('title', 'View Subject')
@section('page_title', 'Subject Details')

@section('content')
<div class="card" style="margin-bottom:18px;">
    <div class="card-header">
        <div>
            <div class="card-title">{{ $subject->name }}</div>
            <div class="card-subtitle">{{ $subject->code }}</div>
        </div>
    </div>

    <div class="card-body">
        <p><strong>Grade Level:</strong> {{ $subject->gradeLevel?->name ?? '—' }}</p>
        <p><strong>Description:</strong> {{ $subject->description ?: '—' }}</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Assignments</div>
            <div class="card-subtitle">Teachers and sections currently assigned to this subject</div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Teacher</th>
                        <th>Section</th>
                        <th>School Year</th>
                        <th>Schedule</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subject->teacherLoads as $load)
                        <tr>
                            <td>{{ $load->teacher?->employee?->full_name ?? '—' }}</td>
                            <td>{{ $load->section?->name ?? '—' }}</td>
                            <td>{{ $load->schoolYear?->name ?? '—' }}</td>
                            <td>{{ $load->schedule_days ?: '—' }} {{ $load->schedule_time ?: '' }}</td>
                            <td>{{ $load->room ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No assignments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection