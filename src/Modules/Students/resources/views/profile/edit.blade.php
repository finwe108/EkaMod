@extends('layouts.app')

@section('title', 'Edit Profile')
@section('page_title', 'Edit Profile')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Edit Profile</div>
            <div class="card-subtitle">You can update your contact and address details only.</div>
        </div>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="card" style="margin-bottom:16px; border-color: rgba(45,212,160,.35);">
                <div class="card-body">{{ session('success') }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ===================== --}}
            {{-- BASIC (READ ONLY) --}}
            {{-- ===================== --}}
            <div class="two-col">
                <div>

            <div class="form-group">
                <label class="form-label">Student Photo</label>

                <div style="display:flex; gap:16px; align-items:center; flex-wrap:wrap;">
                    <img
                        id="currentPhoto"
                        src="{{ $student->photo_path ? asset($student->photo_path) : 'https://via.placeholder.com/120x120.png?text=Photo' }}"
                        alt="Student Photo"
                        style="width:120px;height:120px;object-fit:cover;border-radius:12px;border:1px solid var(--border);"
                    >

                    <div style="flex:1; min-width:220px;">
                        <input type="file" id="photoInput" class="form-input" accept="image/*">

                        {{-- This hidden input will contain cropped image --}}
                        <input type="hidden" name="cropped_photo" id="croppedPhoto">

                        <div style="font-size:11px;color:var(--muted);margin-top:6px;">
                            Select a photo, crop it, then save changes.
                        </div>
                    </div>
                </div>
            </div>

            <div id="cropperBox" style="display:none; margin-top:16px;">
                <div style="max-width:360px;">
                    <img id="cropperImage" style="max-width:100%; border-radius:12px;">
                </div>

                <div style="display:flex; gap:10px; margin-top:12px;">
                    <button type="button" class="btn btn-primary" id="cropBtn">Crop Photo</button>
                    <button type="button" class="btn btn-ghost" id="cancelCropBtn">Cancel</button>
                </div>
            </div>

            <div class="divider"></div>


                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-input" value="{{ $student->full_name }}" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Student ID</label>
                        <input type="text" class="form-input" value="{{ $student->student_id }}" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">LRN</label>
                        <input type="text" class="form-input" value="{{ $student->lrn ?? 'N/A' }}" disabled>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label class="form-label">Sex</label>
                        <input type="text" class="form-input" value="{{ $student->sex ?? 'N/A' }}" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Birth Date</label>
                        <input type="text" class="form-input"
                               value="{{ optional($student->birth_date)->format('M d, Y') ?? 'N/A' }}" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Birth Place</label>
                        <input type="text" class="form-input" value="{{ $student->birth_place ?? 'N/A' }}" disabled>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            {{-- ===================== --}}
            {{-- CONTACT (EDITABLE) --}}
            {{-- ===================== --}}
            <h3 class="section-title">Contact Information</h3>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email"
                       name="email"
                       class="form-input"
                       value="{{ old('email', $student->email) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text"
                       name="contact_number"
                       class="form-input"
                       value="{{ old('contact_number', $student->contact_number) }}">
            </div>

            <div class="divider"></div>

            {{-- ===================== --}}
            {{-- ADDRESS (EDITABLE) --}}
            {{-- ===================== --}}
            <h3 class="section-title">Address</h3>

            <div class="form-group">
                <label class="form-label">Full Address</label>
                <textarea name="address"
                          class="form-input"
                          rows="2">{{ old('address', $student->address) }}</textarea>
            </div>

            <div class="two-col">
                <div class="form-group">
                    <label class="form-label">House / Street</label>
                    <input type="text"
                           name="house_street"
                           class="form-input"
                           value="{{ old('house_street', $student->house_street) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Barangay</label>
                    <input type="text"
                           name="barangay"
                           class="form-input"
                           value="{{ old('barangay', $student->barangay) }}">
                </div>
            </div>

            <div class="two-col">
                <div class="form-group">
                    <label class="form-label">Municipality / City</label>
                    <input type="text"
                           name="municipality_city"
                           class="form-input"
                           value="{{ old('municipality_city', $student->municipality_city) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Province</label>
                    <input type="text"
                           name="province"
                           class="form-input"
                           value="{{ old('province', $student->province) }}">
                </div>
            </div>

            <div class="divider"></div>

            {{-- ===================== --}}
            {{-- GUARDIAN (EDITABLE) --}}
            {{-- ===================== --}}
            <h3 class="section-title">Guardian Contact</h3>

            <div class="form-group">
                <label class="form-label">Guardian Contact</label>
                <input type="text"
                       name="guardian_contact"
                       class="form-input"
                       value="{{ old('guardian_contact', $student->guardian_contact) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Parent / Guardian Contact</label>
                <input type="text"
                       name="parent_guardian_contact"
                       class="form-input"
                       value="{{ old('parent_guardian_contact', $student->parent_guardian_contact) }}">
            </div>

            {{-- ===================== --}}
            {{-- ACTIONS --}}
            {{-- ===================== --}}
            <div style="display:flex; gap:10px; margin-top:20px;">
                <a href="{{ route('student.profile.show') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const photoInput = document.getElementById('photoInput');
    const cropperBox = document.getElementById('cropperBox');
    const cropperImage = document.getElementById('cropperImage');
    const cropBtn = document.getElementById('cropBtn');
    const cancelCropBtn = document.getElementById('cancelCropBtn');
    const croppedPhoto = document.getElementById('croppedPhoto');
    const currentPhoto = document.getElementById('currentPhoto');

    let cropper = null;

    photoInput.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (!file) return;

        const reader = new FileReader();

        reader.onload = function (e) {
            cropperImage.src = e.target.result;
            cropperBox.style.display = 'block';

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(cropperImage, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                responsive: true,
                background: false,
            });
        };

        reader.readAsDataURL(file);
    });

    cropBtn.addEventListener('click', function () {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 500,
            height: 500,
            imageSmoothingQuality: 'high',
        });

        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);

        croppedPhoto.value = dataUrl;
        currentPhoto.src = dataUrl;

        cropper.destroy();
        cropper = null;
        cropperBox.style.display = 'none';
    });

    cancelCropBtn.addEventListener('click', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

        photoInput.value = '';
        croppedPhoto.value = '';
        cropperBox.style.display = 'none';
    });
});
</script>
@endpush