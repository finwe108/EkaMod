@extends('layouts.app')

@section('title', 'Faculty | MMCI')
@section('page_title', 'Faculty')

@section('topbar_actions')
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">+ Add Faculty</a>
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
                <div class="card-subtitle">All registered teachers</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Employee No.</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->full_name }}</td>
                            <td class="mono">{{ $teacher->employee_no }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->department }}</td>
                            <td><span class="badge badge-green">{{ ucfirst($teacher->status) }}</span></td>
                            <td style="display:flex; gap:8px;">
                                <a href="{{ route('admin.teachers.edit', $teacher) }}"
                                   class="btn btn-ghost"
                                   style="font-size:11px;padding:4px 10px">Edit</a>

                                <form method="POST"
                                      action="{{ route('admin.teachers.destroy', $teacher) }}"
                                      onsubmit="return confirm('Delete this teacher?')">
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
                            <td colspan="6" class="empty">No teachers found.</td>
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