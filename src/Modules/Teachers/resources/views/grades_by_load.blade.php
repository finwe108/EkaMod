@extends('layouts.app')

@section('title', 'Grade Entry')
@section('page_title', 'Grade Entry')

@php
    $gradingPeriodLabel = ($gradingSystem ?? 'term') === 'quarter' ? 'Quarter' : 'Term';
    $maxPeriods = ($gradingSystem ?? 'term') === 'quarter' ? 4 : 3;

    $componentLabels = [
        'written_work' => 'Written Work',
        'performance_task' => 'Performance Task',
        'behavior' => 'Behavior',
        'long_test' => 'Long Test',
        'quarterly_exam' => 'Quarterly Exam',
        'quarterly_assessment' => 'Quarterly Assessment',
    ];

    $availableComponents = collect($gradingComponents ?? [])
        ->map(function ($component) {
            return is_object($component) ? $component->component : $component['component'];
        })
        ->filter()
        ->values();

    $displayComponents = $availableComponents->isNotEmpty()
        ? $availableComponents
        : collect(['written_work', 'performance_task', 'behavior', 'long_test', 'quarterly_exam']);
@endphp

@section('content')
<div class="card" style="margin-bottom: 18px;">
    <div class="card-header">
        <div>
            <div class="card-title">
                {{ $teacherLoad->subject?->name ?? 'Subject' }}
                @if($teacherLoad->subject?->code)
                    <span class="mono" style="color: var(--muted);">({{ $teacherLoad->subject->code }})</span>
                @endif
            </div>
            <div class="card-subtitle">
                {{ $teacherLoad->section?->name ?? 'Section' }}
                @if($teacherLoad->schoolYear?->name)
                    · {{ $teacherLoad->schoolYear->name }}
                @endif
                @if($teacherLoad->schedule_days || $teacherLoad->schedule_time)
                    · {{ $teacherLoad->schedule_days ?: '' }} {{ $teacherLoad->schedule_time ?: '' }}
                @endif
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="form-row" style="margin-bottom: 16px;">
            <div class="form-group">
                <label class="form-label" for="period_selector">{{ $gradingPeriodLabel }}</label>
                <form method="GET" action="{{ route('teacher.loads.grades.index', $teacherLoad->id) }}">
                    <input type="hidden" name="grading_system" value="{{ $gradingSystem ?? 'term' }}">
                    <select
                        name="{{ ($gradingSystem ?? 'term') === 'quarter' ? 'quarter_no' : 'term_no' }}"
                        id="period_selector"
                        class="form-input"
                        onchange="this.form.submit()"
                    >
                        @for($i = 1; $i <= $maxPeriods; $i++)
                            <option value="{{ $i }}" {{ (int) ($periodNo ?? 1) === $i ? 'selected' : '' }}>
                                {{ $gradingPeriodLabel }} {{ $i }}
                            </option>
                        @endfor
                    </select>
                </form>
            </div>

            <div class="form-group">
                <label class="form-label">Grading Mode</label>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a
                        href="{{ route('teacher.loads.grades.index', ['teacherLoad' => $teacherLoad->id, 'grading_system' => 'term', 'term_no' => 1]) }}"
                        class="chip {{ ($gradingSystem ?? 'term') === 'term' ? 'active' : '' }}"
                        style="text-decoration:none;"
                    >
                        3 Terms
                    </a>

                    <a
                        href="{{ route('teacher.loads.grades.index', ['teacherLoad' => $teacherLoad->id, 'grading_system' => 'quarter', 'quarter_no' => 1]) }}"
                        class="chip {{ ($gradingSystem ?? 'term') === 'quarter' ? 'active' : '' }}"
                        style="text-decoration:none;"
                    >
                        4 Quarters
                    </a>
                </div>
            </div>
        </div>

        @if($gradingProfile)
            <div class="card" style="margin-bottom: 18px;">
                <div class="card-header">
                    <div>
                        <div class="card-title">Grading Profile</div>
                        <div class="card-subtitle">
                            {{ $gradingProfile->name ?? 'Configured profile' }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        @foreach($gradingComponents as $component)
                            @php
                                $componentName = is_object($component) ? $component->component : $component['component'];
                                $componentWeight = is_object($component) ? $component->weight : $component['weight'];
                            @endphp
                            <span class="chip active">
                                {{ $componentLabels[$componentName] ?? ucwords(str_replace('_', ' ', $componentName)) }}
                                · {{ number_format((float) $componentWeight, 0) }}%
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="card" style="margin-bottom:18px;">
                <div class="card-body">{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="card" style="margin-bottom: 18px; border-color: rgba(240,96,96,.35);">
                <div class="card-body">
                    <ul style="margin: 0; padding-left: 18px; color: var(--red); line-height: 1.7;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.loads.grades.store', $teacherLoad->id) }}">
            @csrf

            <input type="hidden" name="grading_system" value="{{ $gradingSystem ?? 'term' }}">

            @if(($gradingSystem ?? 'term') === 'quarter')
                <input type="hidden" name="quarter_no" value="{{ $periodNo ?? 1 }}">
            @else
                <input type="hidden" name="term_no" value="{{ $periodNo ?? 1 }}">
            @endif

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>

                            @foreach($displayComponents as $componentKey)
                                <th>{{ $componentLabels[$componentKey] ?? ucwords(str_replace('_', ' ', $componentKey)) }}</th>
                            @endforeach

                            <th>Initial Grade</th>
                            <th>Final Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                            @php
                                $grade = $existingGrades->get($student->id);
                                $studentName = $student->full_name
                                    ?? trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                            @endphp
                            <tr>
                                <td>
                                    <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                    <div>{{ $studentName }}</div>
                                    @if(!empty($student->student_id))
                                        <small class="text-muted mono">{{ $student->student_id }}</small>
                                    @endif
                                </td>

                                @foreach($displayComponents as $componentKey)
                                    <td>
                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            name="grades[{{ $index }}][{{ $componentKey }}]"
                                            class="form-input"
                                            value="{{ old("grades.$index.$componentKey", $grade?->{$componentKey}) }}"
                                        >
                                    </td>
                                @endforeach

                                <td>
                                    {{ $grade?->initial_grade !== null ? number_format((float) $grade->initial_grade, 2) : '—' }}
                                </td>
                                <td>
                                    {{ $grade?->final_grade !== null ? number_format((float) $grade->final_grade, 2) : '—' }}
                                </td>
                                <td>
                                    {{ $grade?->remarks ?: '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + $displayComponents->count() }}">
                                    No enrolled students found for this load.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->count())
                <div style="display:flex; gap:10px; margin-top:16px;">
                    <a href="{{ route('teacher.grades') }}" class="btn btn-ghost">Back to Loads</a>
                    <button type="submit" class="btn btn-primary">
                        Save {{ $gradingPeriodLabel }} {{ $periodNo ?? 1 }} Grades
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection