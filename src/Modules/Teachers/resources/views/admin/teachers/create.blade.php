@extends('layouts.app')

@section('title', 'Add Faculty Profile | MMCI')
@section('page_title', 'Add Faculty Profile')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">New Teacher Profile</div>
                <div class="card-subtitle">Link a teaching employee to a teacher profile</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.teachers.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Teaching Employee</label>
                    <select name="employee_id_ref" class="form-input" required>
                        <option value="">Select teaching employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id_ref') == $employee->id)>
                                {{ $employee->employee_id }} — {{ $employee->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id_ref')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                @include('teachers::admin.teachers.partials.teacher-fields')

                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Teacher Profile</button>
                </div>
            </form>
        </div>
    </div>
@endsection