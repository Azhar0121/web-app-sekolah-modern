@extends('layouts.admin')

@section('title', 'Penempatan Siswa')

@section('content')
<h4 class="fw-bold mb-4">Penempatan Siswa ke Kelas</h4>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.student-placements.index') }}" class="row g-2 align-items-end">
            <div class="col-md-8">
                <label class="form-label small text-muted">Tahun Ajaran</label>
                <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" @selected($selectedYearId === $year->id)>
                            {{ $year->name }} @if ($year->is_active) (Aktif) @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kelas</th>
                    <th>Tingkat</th>
                    <th class="text-center">Jumlah Siswa</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($classrooms as $classroom)
                    <tr>
                        <td class="fw-semibold">{{ $classroom->name }}</td>
                        <td>{{ $classroom->grade_level }}</td>
                        <td class="text-center">
                            {{ $classroom->students_count }}
                            @if ($classroom->capacity) / {{ $classroom->capacity }} @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.student-placements.manage', ['classroom' => $classroom, 'academic_year_id' => $selectedYearId]) }}"
                               class="btn btn-sm btn-outline-secondary">
                                Kelola Siswa
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada kelas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
