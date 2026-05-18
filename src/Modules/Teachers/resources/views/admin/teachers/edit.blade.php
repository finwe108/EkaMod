@extends('layouts.app')

@section('title', 'Edit Faculty Profile | MMCI')
@section('page_title', 'Edit Faculty Profile')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Edit Teacher Profile</div>
                <div class="card-subtitle">
                    {{ $teacher->employee?->full_name ?? 'Teacher' }}
                </div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}">
                @csrf
                @method('PUT')

                @include('teachers::admin.teachers.partials.teacher-fields')

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Teacher Profile</button>
                </div>
            </form>
        </div>
    </div>
@endsection