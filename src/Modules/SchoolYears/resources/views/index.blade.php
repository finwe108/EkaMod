@extends('layouts.app')

@section('title', 'School Years | MMCI')
@section('page_title', 'School Years')

@section('topbar_actions')
    <a href="{{ route('admin.school_years.create') }}" class="btn btn-primary">+ Add School Year</a>
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
                <div class="card-title">School Years</div>
                <div class="card-subtitle">Manage academic periods</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schoolYears as $schoolYear)
                        <tr>
                            <td>{{ $schoolYear->name }}</td>
                            <td>{{ optional($schoolYear->starts_on)->format('M d, Y') }}</td>
                            <td>{{ optional($schoolYear->ends_on)->format('M d, Y') }}</td>
                            <td>
                                @if($schoolYear->is_active)
                                    <span class="badge badge-green">Active</span>
                                @else
                                    <span class="badge badge-amber">Inactive</span>
                                @endif
                            </td>
                            <td style="display:flex; gap:8px;">
                                <a href="{{ route('admin.school_years.edit', $schoolYear) }}"
                                   class="btn btn-ghost"
                                   style="font-size:11px;padding:4px 10px">Edit</a>

                                <form method="POST"
                                      action="{{ route('admin.school_years.destroy', $schoolYear) }}"
                                      onsubmit="return confirm('Delete this school year?')">
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
                            <td colspan="5" class="empty">No school years found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:16px;">
        {{ $schoolYears->links() }}
    </div>
@endsection