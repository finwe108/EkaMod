@extends('layouts.app')

@section('title', 'Edit Announcement| ' . $shortName)
@section('page_title', 'Edit Announcement')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Edit Announcement</div>
                <div class="card-subtitle">Update the announcement details</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ route('admin.announcements.update', $announcement) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('announcements::partials.form', ['announcement' => $announcement])

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Announcement</button>
                </div>
            </form>
        </div>
    </div>
@endsection