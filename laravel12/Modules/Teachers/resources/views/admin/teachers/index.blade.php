@extends('layouts.app')

@section('title', 'Faculty | MMCI')
@section('page_title', 'Faculty')

@section('topbar_actions')
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">+ Add Faculty Profile</a>
@endsection

@section('content')
    @if(session('success'))
        <div class="card" style="margin-bottom:16px; border-color: rgba(45,212,160,.35);">
            <div class="card-body">{{ session('success') }}</div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Faculty Directory</div>
                <div class="card-subtitle">Teaching employees with teacher profiles</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Teacher No.</th>
                        <th>Specialization</th>
                        <th>Subject Specialty</th>
                        <th>Rank</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td class="mono">{{ $teacher->employee?->employee_id ?? '—' }}</td>
                            <td>{{ $teacher->employee?->full_name ?? '—' }}</td>
                            <td class="mono">{{ $teacher->teacher_no ?? '—' }}</td>
                            <td>{{ $teacher->specialization ?? '—' }}</td>
                            <td>{{ $teacher->subject_specialty ?? '—' }}</td>
                            <td>{{ $teacher->rank_title ?? '—' }}</td>
                            <td style="display:flex; gap:8px;">
                                <a href="{{ route('admin.teachers.show', $teacher) }}"
                                   class="btn btn-ghost"
                                   style="font-size:11px;padding:4px 10px">View</a>

                                <a href="{{ route('admin.teachers.edit', $teacher) }}"
                                   class="btn btn-ghost"
                                   style="font-size:11px;padding:4px 10px">Edit</a>

                                <form method="POST"
                                      action="{{ route('admin.teachers.destroy', $teacher) }}"
                                      onsubmit="return confirm('Delete this teacher profile? Employee record will remain.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-ghost"
                                            style="font-size:11px;padding:4px 10px">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty">No teacher profiles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:16px;">
        {{ $teachers->links() }}
    </div>
@endsection