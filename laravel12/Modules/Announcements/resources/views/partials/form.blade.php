@php
    $announcement = $announcement ?? null;
@endphp

<div class="form-group">
    <label class="form-label">Title</label>
    <input type="text"
           name="title"
           class="form-input"
           value="{{ old('title', $announcement->title ?? '') }}"
           required>

    @error('title')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">Content</label>
    <textarea name="content"
              class="form-input"
              rows="6"
              required>{{ old('content', $announcement->content ?? '') }}</textarea>

    @error('content')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label class="form-label" for="image">Announcement Image</label>

    @if(!empty($announcement?->image_path))
        <div style="margin-bottom:10px;">
            <img src="{{ asset($announcement->image_path) }}"
                 alt="Announcement Image"
                 style="width:180px; max-height:120px; object-fit:cover; border-radius:12px; background:var(--bg3);">
        </div>
    @endif

    <input type="file"
           name="image"
           id="image"
           class="form-input"
           accept="image/png,image/jpeg,image/jpg,image/webp">

    <small class="text-muted">
        Optional. Recommended: JPG, PNG, or WEBP. Max size: 2MB.
    </small>

    @error('image')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Category</label>
        <input type="text"
               name="category"
               class="form-input"
               value="{{ old('category', $announcement->category ?? '') }}"
               placeholder="Academic">

        @error('category')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @php
                $selectedStatus = old('status', $announcement->status ?? 'published');
            @endphp

            <option value="draft" @selected($selectedStatus === 'draft')>Draft</option>
            <option value="published" @selected($selectedStatus === 'published')>Published</option>
            <option value="archived" @selected($selectedStatus === 'archived')>Archived</option>
        </select>

        @error('status')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label">Posted At</label>
    <input type="datetime-local"
           name="posted_at"
           class="form-input"
           value="{{ old('posted_at', $announcement?->posted_at ? $announcement->posted_at->format('Y-m-d\TH:i') : '') }}">

    <small class="text-muted">
        Leave blank to use the default posting date.
    </small>

    @error('posted_at')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>