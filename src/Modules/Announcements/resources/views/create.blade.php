@extends('layouts.app')

@section('title', 'Post Announcement | MMCI')
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
            <form method="POST" action="{{ route('admin.announcements.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text"
                           name="title"
                           class="form-input"
                           value="{{ old('title') }}"
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label">Content</label>
                    <textarea name="content"
                              class="form-input"
                              rows="6"
                              required>{{ old('content') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <input type="text"
                               name="category"
                               class="form-input"
                               value="{{ old('category') }}"
                               placeholder="Academic">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                            <option value="published" @selected(old('status') === 'published')>Published</option>
                            <option value="archived" @selected(old('status') === 'archived')>Archived</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Posted At</label>
                    <input type="datetime-local"
                           name="posted_at"
                           class="form-input"
                           value="{{ old('posted_at') }}">
                </div>

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Announcement</button>
                </div>
            </form>
        </div>
    </div>
@endsection