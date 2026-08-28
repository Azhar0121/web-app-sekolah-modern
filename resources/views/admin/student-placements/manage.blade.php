@extends('layouts.admin')

@section('title', 'Kelola Siswa Kelas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Kelola Siswa: {{ $classroom->name }}</h4>
    <a href="{{ route('admin.student-placements.index', ['academic_year_id' => $selectedYearId]) }}" class="btn btn-outline-secondary btn-sm">
        &larr; Kembali ke Daftar Kelas
    </a>
</div>

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="GET" action="{{ route('admin.student-placements.manage', $classroom) }}" class="mb-3" style="max-width: 300px;">
    <label class="form-label small text-muted">Tahun Ajaran</label>
    <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
        @foreach ($academicYears as $year)
            <option value="{{ $year->id }}" @selected($selectedYearId === $year->id)>
                {{ $year->name }} @if ($year->is_active) (Aktif) @endif
            </option>
        @endforeach
    </select>
</form>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Siswa Terdaftar ({{ $enrollments->count() }})</div>
            <ul class="list-group list-group-flush">
                @forelse ($enrollments as $enrollment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $enrollment->student->name }}
                        <form method="POST" action="{{ route('admin.student-placements.destroy', $enrollment) }}"
                              onsubmit="return confirm('Keluarkan {{ $enrollment->student->name }} dari kelas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Keluarkan</button>
                        </form>
                    </li>
                @empty
                    <li class="list-group-item text-muted text-center py-4">Belum ada siswa di kelas ini.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Tambah Siswa</div>
            <div class="card-body">
                @if ($availableStudents->isEmpty())
                    <p class="text-muted small mb-0">
                        Tidak ada siswa yang tersedia (semua siswa sudah terdaftar di suatu kelas
                        untuk tahun ajaran ini).
                    </p>
                @else
                    <form method="POST" action="{{ route('admin.student-placements.store', $classroom) }}" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
                        <select name="student_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($availableStudents as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary text-nowrap">+ Tambah</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
