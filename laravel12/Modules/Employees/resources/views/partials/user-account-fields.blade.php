<div class="card" style="margin-bottom: 18px;">
    <div class="card-header">
        <div>
            <div class="card-title">System User Account</div>
            <div class="card-subtitle">Login credentials and role assignment for system access</div>
        </div>
    </div>

    <div class="card-body">
        @if(!$isEdit)
            <div class="form-group">
                <label style="display:flex; align-items:center; gap:8px;">
                    <input
                        type="checkbox"
                        name="create_user_account"
                        id="create_user_account"
                        value="1"
                        {{ old('create_user_account') ? 'checked' : '' }}
                    >
                    <span>Create system user account</span>
                </label>
            </div>
        @endif

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-input"
                    value="{{ old('username', $employee->user->username ?? '') }}"
                >
                @error('username')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="user_email">Login Email</label>
                <input
                    type="email"
                    name="user_email"
                    id="user_email"
                    class="form-input"
                    value="{{ old('user_email', $employee->user->email ?? '') }}"
                >
                @error('user_email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="password">{{ $isEdit ? 'New Password' : 'Password' }}</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-input"
                >
                @if($isEdit)
                    <small class="text-muted">Leave blank to keep the current password.</small>
                @endif
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Roles</label>
            <div style="display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:8px;">
                @foreach($roles as $role)
                    <label style="display:flex; align-items:center; gap:8px; font-size:12px;">
                        <input
                            type="checkbox"
                            name="roles[]"
                            value="{{ $role->id }}"
                            {{ in_array($role->id, old('roles', isset($employee) && $employee->user ? $employee->user->roles->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                        >
                        <span>{{ $role->display_name }}</span>
                    </label>
                @endforeach
            </div>
            @error('roles')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            @error('roles.*')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>