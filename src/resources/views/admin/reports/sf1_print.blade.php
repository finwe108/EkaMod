@extends('layouts.app')

@section('title', 'Printable SF1 | MMCI')
@section('page_title', 'Printable SF1')

@section('content')
<div class="no-print" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; gap:1rem; flex-wrap:wrap;">
    <div>
        <h1 style="margin:0;">School Form 1</h1>
        <p style="margin:.25rem 0 0; color:#666;">Printable school register.</p>
    </div>

    <div style="display:flex; gap:.75rem; flex-wrap:wrap;">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
        <a href="{{ route('admin.reports.sf1.filter', request()->query()) }}" class="btn btn-secondary">Back to Filter</a>
    </div>
</div>

<div class="sf1-paper">
    <div class="sf1-header">
        <div class="sf1-title">School Form 1 (SF1)</div>
        <div class="sf1-subtitle">School Register</div>

        <div class="sf1-meta-grid">
            <div><strong>School Year:</strong> {{ $schoolYear->name }}</div>
            <div><strong>Grade Level:</strong> {{ $gradeLevel?->name ?? ($section?->gradeLevel?->name ?? 'All') }}</div>
            <div><strong>Section:</strong> {{ $section?->name ?? 'All Sections' }}</div>
            <div><strong>Total Learners:</strong> {{ $enrollments->count() }}</div>
            <div><strong>Male:</strong> {{ $maleCount }}</div>
            <div><strong>Female:</strong> {{ $femaleCount }}</div>
        </div>
    </div>

    <div class="table-wrap">
        <table class="sf1-table">
            <thead>
                <tr>
                    <th style="width:50px;">No.</th>
                    <th style="width:120px;">LRN</th>
                    <th>Name</th>
                    <th style="width:60px;">Sex</th>
                    <th style="width:110px;">Birth Date</th>
                    <th style="width:60px;">Age</th>
                    <th>Address</th>
                    <th>Father</th>
                    <th>Mother</th>
                    <th>Guardian</th>
                    <th style="width:110px;">Contact No.</th>
                    <th style="width:60px;">IP</th>
                    <th style="width:120px;">Ethnic Group</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $index => $enrollment)
                    @php
                        $student = $enrollment->student;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student?->lrn ?: '—' }}</td>
                        <td>{{ $student?->formal_name ?: '—' }}</td>
                        <td>{{ $student?->sex ? strtoupper(substr($student->sex, 0, 1)) : '—' }}</td>
                        <td>{{ optional($student?->birth_date)->format('m/d/Y') ?: '—' }}</td>
                        <td>{{ $student?->age ?? '—' }}</td>
                        <td>
                            {{ $student?->address ?: collect([
                                $student?->house_street,
                                $student?->barangay,
                                $student?->municipality_city,
                                $student?->province
                            ])->filter()->implode(', ') ?: '—' }}
                        </td>
                        <td>{{ $student?->father_name ?: '—' }}</td>
                        <td>{{ $student?->mother_name ?: '—' }}</td>
                        <td>{{ $student?->guardian_name ?: '—' }}</td>
                        <td>{{ $student?->parent_guardian_contact ?: ($student?->guardian_contact ?: '—') }}</td>
                        <td>{{ $student?->is_ip ? 'Yes' : 'No' }}</td>
                        <td>{{ $student?->ethnic_group ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" style="text-align:center;">No enrollment records found for the selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="sf1-footer">
        <div><strong>Prepared by:</strong> ____________________________</div>
        <div><strong>Date Printed:</strong> {{ now()->format('F d, Y h:i A') }}</div>
    </div>
</div>
@endsection

@push('styles')
<style>
.sf1-paper {
    background: #fff;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
}

.sf1-header {
    margin-bottom: 1rem;
}

.sf1-title {
    font-size: 1.4rem;
    font-weight: 700;
    text-align: center;
}

.sf1-subtitle {
    text-align: center;
    margin-top: .2rem;
    margin-bottom: 1rem;
    color: #555;
}

.sf1-meta-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: .5rem 1rem;
    font-size: .95rem;
}

.sf1-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.sf1-table th,
.sf1-table td {
    border: 1px solid #222;
    padding: 6px;
    vertical-align: top;
    text-align: left;
}

.sf1-table thead th {
    background: #f5f5f5;
}

.sf1-footer {
    margin-top: 1.25rem;
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

@media (max-width: 992px) {
    .sf1-meta-grid {
        grid-template-columns: 1fr;
    }
}

@media print {
    .no-print,
    .sidebar,
    .topbar,
    .page-header,
    .btn {
        display: none !important;
    }

    body {
        background: #fff !important;
    }

    .main-content,
    .content,
    .container,
    .sf1-paper {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    .sf1-table {
        font-size: 10px;
    }

    .sf1-table th,
    .sf1-table td {
        padding: 4px;
    }

    @page {
        size: landscape;
        margin: 10mm;
    }
}
</style>
@endpush