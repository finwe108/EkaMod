@extends('layouts.app')

@section('title', 'School Settings | MMCI')
@section('page_title', 'School Settings')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; gap:1rem; flex-wrap:wrap;">
    <div>
        <h1 style="margin:0;">School Settings</h1>
        <p style="margin:.25rem 0 0; color:#666;">Manage school details used in SF1 and other reports.</p>
    </div>
</div>

@if(session('success'))
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<form method="POST" action="{{ route('admin.school-settings.update') }}">
    @csrf
    @method('PUT')

    <div class="card">
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
                    <label for="school_name">School Name</label>
                    <input
                        type="text"
                        name="school_name"
                        id="school_name"
                        class="form-input"
                        value="{{ old('school_name', $schoolSetting->school_name) }}"
                    >
                    @error('school_name') <div class="text-danger">{{ $message }}</div> @enderror
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

.form-group {
    display: flex;
    flex-direction: column;
    gap: .35rem;
}

.form-input {
    width: 100%;
}

.text-danger {
    color: #c62828;
    font-size: .9rem;
}

@media (max-width: 992px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush