@extends('layouts.app')

@section('title', 'Edit Announcement | MMCI')
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
            <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text"
                           name="title"
                           class="form-input"
                           value="{{ old('title', $announcement->title) }}"
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label">Content</label>
                    <textarea name="content"
                              class="form-input"
                              rows="6"
                              required>{{ old('content', $announcement->content) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <input type="text"
                               name="category"
                               class="form-input"
                               value="{{ old('category', $announcement->category) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" @selected(old('status', $announcement->status) === 'draft')>Draft</option>
                            <option value="published" @selected(old('status', $announcement->status) === 'published')>Published</option>
                            <option value="archived" @selected(old('status', $announcement->status) === 'archived')>Archived</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Posted At</label>
                    <input type="datetime-local"
                           name="posted_at"
                           class="form-input"
                           value="{{ old('posted_at', optional($announcement->posted_at)->format('Y-m-d\TH:i')) }}">
                </div>

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Announcement</button>
                </div>
            </form>
        </div>
    </div>
@endsection