@extends('layouts.app')

@section('title', 'Students | MMCI')
@section('page_title', 'Students')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; gap:1rem; flex-wrap:wrap;">
    <div>
        <h1 style="margin:0;">Students</h1>
        <p style="margin:.25rem 0 0; color:#666;">Student master records and profiles.</p>
    </div>

    <a href="{{ route('admin.students.create') }}" class="btn btn-primary">+ Add Student</a>
</div>

@if(session('success'))
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<div class="card" style="margin-bottom:1rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.students.index') }}" style="display:flex; gap:.75rem; flex-wrap:wrap;">
            <input
                type="text"
                name="search"
                class="form-input"
                placeholder="Search by student ID, LRN, or name..."
                value="{{ request('search') }}"
                style="max-width:420px;"
            >
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>LRN</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Status</th>
                        <th style="width:180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->lrn ?: '—' }}</td>
                            <td>
                                <a href="{{ route('admin.students.show', $student) }}" style="text-decoration:inherit; color:white; font-weight:500;">
                                    {{ $student->formal_name }}
                                </a>
                            </td>
                            <td>{{ $student->sex ? ucfirst($student->sex) : '—' }}</td>
                            <td>{{ ucfirst($student->status) }}</td>
                            <td>
                                <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-primary">View</a>
                                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-primary">Edit</a>

                                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Delete this student record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding:1rem;">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection