@extends('layouts.admin')

@section('title', 'Penugasan Mengajar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Penugasan Mengajar</h4>
    <a href="{{ route('admin.teaching-assignments.create', ['academic_year_id' => $selectedYearId]) }}" class="btn btn-primary">+ Tambah Penugasan</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.teaching-assignments.index') }}" class="row g-2 align-items-end">
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
                    <th>Mata Pelajaran</th>
                    <th>Guru Pengampu</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assignments as $assignment)
                    <tr>
                        <td class="fw-semibold">{{ $assignment->classroom->name }}</td>
                        <td>
                            <span class="badge text-bg-secondary">{{ $assignment->subject->code }}</span>
                            {{ $assignment->subject->name }}
                        </td>
                        <td>{{ $assignment->teacher->name }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.teaching-assignments.edit', $assignment) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.teaching-assignments.destroy', $assignment) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus penugasan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Belum ada penugasan mengajar untuk tahun ajaran ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<p class="text-muted small mt-3">
    Penugasan mengajar menentukan guru mana yang bisa mengunggah materi/tugas untuk
    kelas &amp; mata pelajaran tertentu di Portal Guru.
</p>
@endsection
