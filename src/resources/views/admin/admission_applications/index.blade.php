@extends('layouts.app')

@section('title', 'Admission Applications')
@section('page_title', 'Admission Applications')

@section('content')
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <div>
        <h1 style="margin:0;">Admission Applications</h1>
        <p style="margin:.25rem 0 0;">Review submitted admission forms.</p>
    </div>
</div>

@if(session('success'))
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<div class="card" style="margin-bottom:1rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.admission_applications.index') }}" style="display:flex;gap:.75rem;flex-wrap:wrap;">
            <select name="status" class="form-input">
                <option value="">All statuses</option>
                @foreach(['submitted', 'under_review', 'accepted', 'rejected', 'cancelled'] as $s)
                    <option value="{{ $s }}" @selected($status === $s)>
                        {{ ucfirst(str_replace('_', ' ', $s)) }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-primary" type="submit">Filter</button>
            <a href="{{ route('admin.admission_applications.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Application No.</th>
                        <th>Name</th>
                        <th>Grade Level</th>
                        <th>Student Type</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th style="width:120px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td>{{ $application->application_number }}</td>
                            <td>{{ $application->full_name }}</td>
                            <td>{{ $application->gradeLevel?->name ?? '—' }}</td>
                            <td>{{ ucfirst($application->student_type) }}</td>
                            <td>
                                <span class="badge">
                                    {{ ucfirst(str_replace('_', ' ', $application->application_status)) }}
                                </span>
                            </td>
                            <td>{{ $application->submitted_at?->format('M d, Y h:i A') ?? $application->created_at?->format('M d, Y h:i A') }}</td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.admission_applications.show', $application) }}">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No admission applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding:1rem;">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection