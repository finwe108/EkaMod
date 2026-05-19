@extends('layouts.app')

@section('title', 'Subjects')
@section('page_title', 'Subjects')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <div>
        <h1 style="margin:0;">Subjects</h1>
        <p style="margin:.25rem 0 0;">Manage subject catalog and view teaching assignments.</p>
    </div>
    <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">+ Add Subject</a>
</div>

@if(session('success'))
    <div class="card" style="margin-bottom:18px;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Grade Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td>{{ $subject->code }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ $subject->gradeLevel?->name ?? '—' }}</td>
                            <td>
                                <div style="display:flex; gap:8px;">
                                    <a href="{{ route('admin.subjects.show', $subject->id) }}" class="btn btn-ghost">View</a>
                                    <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-ghost">Edit</a>
                                    <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Delete this subject?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No subjects found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding:16px;">
            {{ $subjects->links() }}
        </div>
    </div>
</div>
@endsection