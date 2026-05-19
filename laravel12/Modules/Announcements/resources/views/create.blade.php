@extends('layouts.app')

@section('title', 'Post Announcement | ' . $shortName)
@section('page_title', 'Post Announcement')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">New Announcement</div>
                <div class="card-subtitle">Post a school notice or update</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ route('admin.announcements.store') }}"
                  enctype="multipart/form-data">
                @csrf

                @include('announcements::partials.form')

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Announcement</button>
                </div>
            </form>
        </div>
    </div>
@endsection