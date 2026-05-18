<div class="card" style="margin-bottom: 18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Contact Information</div>
            <div class="card-subtitle">Personal email, phone number, and address</div>
        </div>
    </div>

    <div class="card-body">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="email">Personal Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-input"
                    value="{{ old('email', $employee->email ?? '') }}"
                >
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Phone</label>
                <input
                    type="text"
                    name="phone"
                    id="phone"
                    class="form-input"
                    value="{{ old('phone', $employee->phone ?? '') }}"
                >
                @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="address">Address</label>
            <textarea
                name="address"
                id="address"
                rows="3"
                class="form-input"
            >{{ old('address', $employee->address ?? '') }}</textarea>
            @error('address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>