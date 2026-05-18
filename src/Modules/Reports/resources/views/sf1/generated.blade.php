@extends('layouts.app')

@section('title', 'Generated SF1 Files | MMCI')
@section('page_title', 'Generated SF1 Files')

@section('content')
@if(session('success'))
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body">{{ session('success') }}</div>
    </div>
@endif

<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <div>
            <div class="card-title">SF1 Generated Files</div>
            <div class="card-subtitle">Files are generated one section at a time to avoid timeout.</div>
        </div>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.sf1.generated') }}">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">School Year</label>
                    <select name="school_year_id" class="form-input">
                        <option value="">All School Years</option>
                        @foreach($schoolYears as $schoolYear)
                            <option value="{{ $schoolYear->id }}" @selected((string)$schoolYearId === (string)$schoolYear->id)>
                                {{ $schoolYear->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="display:flex;align-items:end;gap:10px;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.reports.sf1.filter') }}" class="btn btn-ghost">Create More</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>School Year</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Generated At</th>
                    <th style="width:180px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr data-report-row="{{ $report->id }}">
                        <td>{{ $report->schoolYear?->name }}</td>
                        <td>{{ $report->gradeLevel?->name }}</td>
                        <td>{{ $report->section?->name }}</td>

                        <td class="report-status">
                            @if($report->needs_regeneration)
                                <span class="badge badge-amber">Needs Regeneration</span>
                            @elseif($report->status === 'completed')
                                <span class="badge badge-green">Completed</span>
                            @elseif($report->status === 'processing')
                                <span class="badge badge-blue">Processing</span>
                            @elseif($report->status === 'failed')
                                <span class="badge badge-red">Failed</span>
                            @else
                                <span class="badge badge-amber">{{ ucfirst($report->status) }}</span>
                            @endif
                        </td>

                        <td>
                            <div style="height:10px;background:var(--bg3);border-radius:999px;overflow:hidden;min-width:120px;">
                                <div class="report-progress-bar"
                                     style="height:100%;width:{{ $report->progress }}%;background:var(--green);"></div>
                            </div>
                            <div class="report-progress-text" style="font-size:11px;color:var(--muted);margin-top:4px;">
                                {{ $report->progress }}%
                            </div>
                        </td>

                        <td class="report-generated-at">
                            {{ optional($report->generated_at)->format('M d, Y h:i A') ?? '—' }}
                        </td>

                        <td class="report-action">
                            @if($report->status === 'completed' && !$report->needs_regeneration)
                                <a href="{{ route('admin.reports.sf1.generated.download', $report) }}" class="btn btn-primary btn-sm">
                                    Download
                                </a>
                            @elseif($report->needs_regeneration)
                                <button type="button"
                                        class="btn btn-warning btn-sm generate-one"
                                        data-id="{{ $report->id }}">
                                    Regenerate
                                </button>
                            @elseif($report->status === 'failed')
                                <button type="button" class="btn btn-primary btn-sm generate-one" data-id="{{ $report->id }}">
                                    Retry
                                </button>
                            @else
                                <button type="button" class="btn btn-primary btn-sm generate-one" data-id="{{ $report->id }}">
                                    Generate
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty">No SF1 files generated yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($reports->whereIn('status', ['pending', 'failed'])->count() > 0)
    <div style="margin-top:16px;">
        <button type="button" class="btn btn-primary" id="generateAllBtn">
            Generate Pending Files
        </button>
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = '{{ csrf_token() }}';

    async function generateReport(id) {
        const row = document.querySelector(`[data-report-row="${id}"]`);
        if (!row) return;

        const statusEl = row.querySelector('.report-status');
        const barEl = row.querySelector('.report-progress-bar');
        const textEl = row.querySelector('.report-progress-text');
        const actionEl = row.querySelector('.report-action');

        statusEl.textContent = 'Processing';
        barEl.style.width = '10%';
        textEl.textContent = '10%';
        actionEl.innerHTML = '<span style="font-size:12px;color:var(--muted);">Generating...</span>';

        try {
            const response = await fetch(`/admin/reports/sf1/generated/${id}/generate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (!response.ok || !data.ok) {
                throw new Error(data.message || 'Generation failed.');
            }

            statusEl.textContent = 'Completed';
            barEl.style.width = '100%';
            textEl.textContent = '100%';

            actionEl.innerHTML = `
                <a href="/admin/reports/sf1/generated/${id}/download" class="btn btn-primary btn-sm">
                    Download
                </a>
            `;

        } catch (error) {
            statusEl.textContent = 'Failed';
            barEl.style.width = '0%';
            textEl.textContent = '0%';

            actionEl.innerHTML = `
                <button type="button" class="btn btn-primary btn-sm generate-one" data-id="${id}">
                    Retry
                </button>
            `;
        }
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('generate-one')) {
            generateReport(e.target.dataset.id);
        }
    });

    const generateAllBtn = document.getElementById('generateAllBtn');

    if (generateAllBtn) {
        generateAllBtn.addEventListener('click', async function () {
            generateAllBtn.disabled = true;
            generateAllBtn.textContent = 'Generating...';

            const buttons = Array.from(document.querySelectorAll('.generate-one'));

            for (const button of buttons) {
                await generateReport(button.dataset.id);
            }

            generateAllBtn.textContent = 'Done';
        });
    }
});
</script>
@endpush