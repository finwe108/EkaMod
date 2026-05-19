@extends('layouts.app')

@section('title', 'Teacher Schedule')
@section('page_title', 'Teacher Schedule')

@section('content')
@php
    $teacherName = $teacher->employee?->full_name
        ?? trim(($teacher->employee?->first_name ?? '') . ' ' . ($teacher->employee?->last_name ?? ''))
        ?: 'Teacher';

    $dayShort = [
        1 => 'Mon',
        2 => 'Tue',
        3 => 'Wed',
        4 => 'Thu',
        5 => 'Fri',
        6 => 'Sat',
    ];
@endphp

<div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-end; gap:16px; margin-bottom:1rem; flex-wrap:wrap;">
    <div>
        <h1 style="margin:0;">{{ $teacherName }}</h1>
        <p style="margin:.25rem 0 0;">Weekly teaching schedule</p>
    </div>

    <form method="GET" action="{{ route('admin.teachers.schedule', $teacher) }}" style="display:flex; gap:10px; align-items:end; flex-wrap:wrap;">
        <div>
            <label for="school_year_id">School Year</label>
            <select name="school_year_id" id="school_year_id" class="form-input">
                @foreach($schoolYears as $schoolYear)
                    <option value="{{ $schoolYear->id }}" {{ (string)$schoolYearId === (string)$schoolYear->id ? 'selected' : '' }}>
                        {{ $schoolYear->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">View</button>
        </div>
    </form>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <h3 style="margin-top:0; margin-bottom:12px;">Weekly Matrix</h3>

        <div class="table-wrap">
            <table style="min-width:1100px;">
                <thead>
                    <tr>
                        <th style="width:120px;">Time</th>
                        @foreach($dayLabels as $dayNumber => $dayName)
                            <th>{{ $dayName }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($timeSlots as $timeSlot)
                        <tr>
                            <td style="font-weight:600;">
                                {{ \Carbon\Carbon::createFromFormat('H:i', $timeSlot)->format('g:i A') }}
                            </td>

                            @foreach($dayLabels as $dayNumber => $dayName)
                                <td>
                                    @forelse($matrix[$timeSlot][$dayNumber] as $item)
                                        <div style="border:1px solid #e5e7eb; border-radius:10px; padding:10px; margin-bottom:8px; background:#f9fafb;">
                                            <div style="font-weight:700;">{{ $item['subject_label'] }}</div>

                                            @if($item['subject_label'] === 'MAPEH' && $item['subject_details'])
                                                <div style="font-size:12px; color:#666;">
                                                    {{ $item['subject_details'] }}
                                                </div>
                                            @endif

                                            <div style="font-size:13px; margin-top:4px;">
                                                {{ $item['section_name'] }}
                                            </div>

                                            <div style="font-size:12px; color:#666;">
                                                {{ \Carbon\Carbon::parse($item['time_start'])->format('g:i A') }}
                                                -
                                                {{ \Carbon\Carbon::parse($item['time_end'])->format('g:i A') }}
                                            </div>

                                            <div style="font-size:12px; color:#666;">
                                                Room: {{ $item['room'] }}
                                            </div>
                                        </div>
                                    @empty
                                        <span style="color:#aaa;">—</span>
                                    @endforelse
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No schedule found for this teacher in the selected school year.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h3 style="margin-top:0; margin-bottom:12px;">Detailed Schedule List</h3>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Subject</th>
                        <th>Section</th>
                        <th>Room</th>
                        <th>School Year</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scheduleList as $item)
                        <tr>
                            <td>{{ $item['day_label'] }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($item['time_start'])->format('g:i A') }}
                                -
                                {{ \Carbon\Carbon::parse($item['time_end'])->format('g:i A') }}
                            </td>
                            <td>
                                {{ $item['subject_label'] }}
                                @if($item['subject_label'] === 'MAPEH' && $item['subject_details'])
                                    <div style="font-size:12px; color:#666;">{{ $item['subject_details'] }}</div>
                                @endif
                            </td>
                            <td>{{ $item['section_name'] }}</td>
                            <td>{{ $item['room'] }}</td>
                            <td>{{ $item['school_year_name'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No schedule found for this teacher in the selected school year.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection