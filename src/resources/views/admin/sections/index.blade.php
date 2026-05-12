@extends('layouts.app')

@section('title', 'Sections | MMCI')
@section('page_title', 'Sections')

@section('topbar_actions')
    <a href="{{ route('admin.sections.create') }}" class="btn btn-primary">+ Add Section</a>
@endsection

@section('content')
    <div class="card" style="margin-bottom: 18px;">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.sections.index') }}">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="school_year_id">School Year</label>
                        <select name="school_year_id" id="school_year_id" class="form-input" onchange="this.form.submit()">
                            @foreach($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->id }}"
                                    {{ (string) $schoolYearId === (string) $schoolYear->id ? 'selected' : '' }}>
                                    {{ $schoolYear->name }}{{ optional($activeSchoolYear)->id === $schoolYear->id ? ' (Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Section List</div>
                <div class="card-subtitle">Showing sections for the selected school year</div>
            </div>
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Grade Level</th>
                        <th>School Year</th>
                        <th colspan="3" style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $section)
                        <tr>
                            <td>{{ $section->name }} <span class="badge badge-red">{{ $section->enrollments_count ?? 0 }}</span></td>
                            <td>{{ $section->gradeLevel?->name }}</td>
                            <td>{{ $section->schoolYear?->name }}</td>
                            <td>
                                <a href="{{ route('admin.sections.show', ['section' => $section->id, 'school_year_id' => $schoolYearId]) }}" class="btn btn-sm btn-primary">
                                    View Class List
                                </a>
                            </td>
                            <td class="actions">
                               <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-sm btn-primary">
                                   Edit
                               </a>
                            </td>
                            <td>
                               @if($section->enrollments_count == 0)
                                   <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this empty section?');">
                                       @csrf
                                       @method('DELETE') 
                                       <button type="submit" class="btn btn-sm btn-ghost">
                                           Delete
                                       </button>
                                   </form>
                               @else
                                   <button type="button" class="btn btn-sm btn-secondary" disabled title="Cannot delete section with enrolled students">
                                       Delete
                                   </button>
                               @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No sections found for this school year.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $sections->links() }}
        </div>
    </div>
@endsection