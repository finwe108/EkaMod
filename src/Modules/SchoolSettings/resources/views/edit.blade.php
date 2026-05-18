@extends('layouts.app')

@section('title', 'School Settings | MMCI')
@section('page_title', 'School Settings')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; gap:1rem; flex-wrap:wrap;">
    <div>
        <h1 style="margin:0;">School Settings</h1>
        <p style="margin:.25rem 0 0; color:var(--muted);">
            Manage school identity, contact details, logo, and report information.
        </p>
    </div>
</div>

@if(session('success'))
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<form method="POST" action="{{ route('admin.school-settings.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Branding</div>
                <div class="card-subtitle">School logo, name, short name, and tagline</div>
            </div>
        </div>

        <div class="card-body">
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label" for="logo">School Logo</label>

                    @if(!empty($schoolSetting->logo_path))
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset($schoolSetting->logo_path) }}"
                                 alt="School Logo"
                                 style="width: 90px; height: 90px; object-fit: contain; border-radius: 12px; background: var(--bg3); padding: 8px;">
                        </div>
                    @endif

                    <input type="file"
                           name="logo"
                           id="logo"
                           class="form-input"
                           accept="image/png,image/jpeg,image/jpg,image/webp">

                    <small class="text-muted">
                        Recommended: square PNG or WEBP, at least 300x300px.
                    </small>

                    @error('logo')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="school_name">School Name</label>
                    <input
                        type="text"
                        name="school_name"
                        id="school_name"
                        class="form-input"
                        value="{{ old('school_name', $schoolSetting->school_name) }}"
                        placeholder="Official school name"
                    >
                    @error('school_name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="short_name">Short Name</label>
                    <input
                        type="text"
                        name="short_name"
                        id="short_name"
                        class="form-input"
                        value="{{ old('short_name', $schoolSetting->short_name ?? '') }}"
                        placeholder="School short name"
                    >
                    @error('short_name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="tagline">Tagline</label>
                    <input
                        type="text"
                        name="tagline"
                        id="tagline"
                        class="form-input"
                        value="{{ old('tagline', $schoolSetting->tagline ?? '') }}"
                        placeholder="School tagline"
                    >
                    @error('tagline') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:1rem;">
        <div class="card-header">
            <div>
                <div class="card-title">School Identification</div>
                <div class="card-subtitle">Information used in official reports</div>
            </div>
        </div>

        <div class="card-body">
            <div class="settings-grid">
                <div class="form-group">
                    <label for="school_id">School ID</label>
                    <input
                        type="text"
                        name="school_id"
                        id="school_id"
                        class="form-input"
                        value="{{ old('school_id', $schoolSetting->school_id) }}"
                    >
                    @error('school_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="region">Region</label>
                    <input
                        type="text"
                        name="region"
                        id="region"
                        class="form-input"
                        value="{{ old('region', $schoolSetting->region) }}"
                    >
                    @error('region') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="division">Division</label>
                    <input
                        type="text"
                        name="division"
                        id="division"
                        class="form-input"
                        value="{{ old('division', $schoolSetting->division) }}"
                    >
                    @error('division') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="district">District</label>
                    <input
                        type="text"
                        name="district"
                        id="district"
                        class="form-input"
                        value="{{ old('district', $schoolSetting->district) }}"
                    >
                    @error('district') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="school_head_name">School Head Name</label>
                    <input
                        type="text"
                        name="school_head_name"
                        id="school_head_name"
                        class="form-input"
                        value="{{ old('school_head_name', $schoolSetting->school_head_name) }}"
                    >
                    @error('school_head_name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:1rem;">
        <div class="card-header">
            <div>
                <div class="card-title">Contact Information</div>
                <div class="card-subtitle">Used on public pages, headers, and school communications</div>
            </div>
        </div>

        <div class="card-body">
            <div class="settings-grid">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input
                        type="text"
                        name="phone"
                        id="phone"
                        class="form-input"
                        value="{{ old('phone', $schoolSetting->phone ?? '') }}"
                        placeholder="school contact number"
                    >
                    @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-input"
                        value="{{ old('email', $schoolSetting->email ?? '') }}"
                        placeholder="school email"
                    >
                    @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group settings-wide">
                    <label for="address">Address</label>
                    <input
                        type="text"
                        name="address"
                        id="address"
                        class="form-input"
                        value="{{ old('address', $schoolSetting->address ?? '') }}"
                        placeholder="school address"
                    >
                    @error('address') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top:1rem; display:flex; gap:.75rem;">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
.settings-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.settings-wide {
    grid-column: 1 / -1;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: .35rem;
}

.form-input {
    width: 100%;
}

.text-danger {
    color: var(--red);
    font-size: .9rem;
}

@media (max-width: 992px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }

    .settings-wide {
        grid-column: auto;
    }
}
</style>
@endpush