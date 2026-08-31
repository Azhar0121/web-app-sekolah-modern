@extends('layouts.admin')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Jadwal Pelajaran</h4>
    <a href="{{ route('admin.schedules.create', ['academic_year_id' => $selectedYearId, 'classroom_id' => $selectedClassroomId]) }}"
       class="btn btn-primary">+ Tambah Jadwal</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.schedules.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted">Tahun Ajaran</label>
                <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" @selected($selectedYearId === $year->id)>
                            {{ $year->name }} @if ($year->is_active) (Aktif) @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted">Kelas</label>
                <select name="classroom_id" class="form-select" onchange="this.form.submit()">
                    @foreach ($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" @selected($selectedClassroomId === $classroom->id)>
                            {{ $classroom->name }}
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
                    <th style="width: 110px;">Hari</th>
                    <th style="width: 140px;">Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Ruangan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $schedule)
                    <tr>
                        <td class="fw-semibold">{{ $schedule->day_of_week }}</td>
                        <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                        <td>
                            <span class="badge text-bg-secondary">{{ $schedule->teachingAssignment->subject->code }}</span>
                            {{ $schedule->teachingAssignment->subject->name }}
                        </td>
                        <td>{{ $schedule->teachingAssignment->teacher->name }}</td>
                        <td>{{ $schedule->room ?: '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus jadwal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Belum ada jadwal untuk kelas & tahun ajaran ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<p class="text-muted small mt-3">
    Jadwal hanya bisa dibuat dari mata pelajaran yang sudah punya
    <a href="{{ route('admin.teaching-assignments.index', ['academic_year_id' => $selectedYearId]) }}">penugasan mengajar</a>
    untuk kelas ini. Sistem otomatis menolak jadwal yang bentrok jam dengan guru atau kelas yang sama.
</p>
@endsection
