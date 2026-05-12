@extends('layouts.app')

@section('title', 'Grade Levels | EduCore')
@section('page_title', 'Grade Levels')

@section('topbar_actions')
    <a href="{{ route('admin.grade-levels.create') }}" class="btn btn-primary">+ Add Grade Level</a>
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
                <div class="card-title">Grade Levels</div>
                <div class="card-subtitle">Organize your academic levels</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Sort Order</th>
                        <th>Department</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gradeLevels as $gradeLevel)
                        <tr>
                            <td>{{ $gradeLevel->name }}</td>
                            <td>{{ $gradeLevel->sort_order }}</td>
                            <td>{{ $gradeLevel->department }}</td>
                            <td style="display:flex; gap:8px;">
                                <a href="{{ route('admin.grade-levels.edit', $gradeLevel) }}"
                                   class="btn btn-ghost"
                                   style="font-size:11px;padding:4px 10px">Edit</a>

                                <form method="POST"
                                      action="{{ route('admin.grade-levels.destroy', $gradeLevel) }}"
                                      onsubmit="return confirm('Delete this grade level?')">
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
                            <td colspan="4" class="empty">No grade levels found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:16px;">
        {{ $gradeLevels->links() }}
    </div>
@endsection