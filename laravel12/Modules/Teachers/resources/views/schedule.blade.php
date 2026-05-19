@extends('layouts.app')

@section('title', 'My Schedule')
@section('page_title', 'My Schedule')

@section('content')
    <div class="card">
        <div class="card-body">
            <h3>My Teaching Schedule</h3>

            @if($scheduleList->isEmpty())
                <p>No schedule found for your active teaching loads.</p>
            @else
                <table class="table">
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
                        @foreach($scheduleList as $item)
                            <tr>
                                <td>{{ $item['day_label'] }}</td>
                                <td>{{ $item['time_start_hm'] }} - {{ $item['time_end_hm'] }}</td>
                                <td>{{ $item['subject_label'] }}</td>
                                <td>{{ $item['section_name'] }}</td>
                                <td>{{ $item['room'] }}</td>
                                <td>{{ $item['school_year_name'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection