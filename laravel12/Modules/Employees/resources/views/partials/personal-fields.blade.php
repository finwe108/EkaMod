<div class="card" style="margin-bottom: 18px;">
    <div class="card-header">
        <div>
            <div class="card-title">Employee Information</div>
            <div class="card-subtitle">Basic personal profile details</div>
        </div>
    </div>

    <div class="card-body">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="first_name">First Name</label>
                <input
                    type="text"
                    name="first_name"
                    id="first_name"
                    class="form-input"
                    value="{{ old('first_name', $employee->first_name ?? '') }}"
                    required
                >
                @error('first_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="middle_name">Middle Name</label>
                <input
                    type="text"
                    name="middle_name"
                    id="middle_name"
                    class="form-input"
                    value="{{ old('middle_name', $employee->middle_name ?? '') }}"
                >
                @error('middle_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="last_name">Last Name</label>
                <input
                    type="text"
                    name="last_name"
                    id="last_name"
                    class="form-input"
                    value="{{ old('last_name', $employee->last_name ?? '') }}"
                    required
                >
                @error('last_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="suffix">Suffix</label>
                <input
                    type="text"
                    name="suffix"
                    id="suffix"
                    class="form-input"
                    value="{{ old('suffix', $employee->suffix ?? '') }}"
                >
                @error('suffix')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="gender">Gender</label>
                <select name="gender" id="gender" class="form-input">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ old('gender', $employee->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $employee->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                </select>
                @error('gender')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="birthdate">Birthdate</label>
                <input
                    type="date"
                    name="birthdate"
                    id="birthdate"
                    class="form-input"
                    value="{{ old('birthdate', isset($employee->birthdate) ? optional($employee->birthdate)->format('Y-m-d') : '') }}"
                >
                @error('birthdate')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="civil_status">Civil Status</label>
                <input
                    type="text"
                    name="civil_status"
                    id="civil_status"
                    class="form-input"
                    value="{{ old('civil_status', $employee->civil_status ?? '') }}"
                >
                @error('civil_status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
</div>