@extends('layouts.app')

@section('title', 'Attendance')
@section('page_title', 'Attendance')

@section('content')
<div class="card" style="margin-bottom: 18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Attendance</div>
            <div class="card-subtitle">Daily attendance tracking and summaries</div>
        </div>
    </div>

    <div class="card-body">
        @if($teacher)
            <div style="margin-bottom: 14px;">
                <div><strong>Teacher:</strong> {{ $user->name }}</div>
                <div><strong>Adviser:</strong> {{ $teacher->is_adviser ? 'Yes' : 'No' }}</div>
            </div>
        @endif

        <div class="empty">
            Attendance module placeholder.<br>
            Later, this page can show section attendance sheets and monthly summaries.
        </div>
    </div>
</div>
@endsection