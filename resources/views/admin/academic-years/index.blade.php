@extends('layouts.admin')

@section('title', 'Tahun Ajaran & Semester')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Tahun Ajaran & Semester</h4>
    <a href="{{ route('admin.academic-years.create') }}" class="btn btn-primary">+ Tambah Tahun Ajaran</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tahun Ajaran</th>
                    <th>Periode</th>
                    <th class="text-center">Jumlah Semester</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($academicYears as $year)
                    <tr>
                        <td class="fw-semibold">{{ $year->name }}</td>
                        <td class="text-muted small">
                            {{ $year->start_date->format('d M Y') }} &ndash; {{ $year->end_date->format('d M Y') }}
                        </td>
                        <td class="text-center">{{ $year->semesters_count }}</td>
                        <td class="text-center">
                            @if ($year->is_active)
                                <span class="badge text-bg-success">Aktif</span>
                            @else
                                <span class="badge text-bg-light text-muted">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.academic-years.edit', $year) }}" class="btn btn-sm btn-outline-secondary">
                                Kelola
                            </a>
                            <form method="POST" action="{{ route('admin.academic-years.destroy', $year) }}" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus tahun ajaran {{ $year->name }}? Semua semester di dalamnya ikut terhapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" @disabled($year->is_active)>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada tahun ajaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<p class="text-muted small mt-3">
    Hanya boleh ada 1 tahun ajaran dan 1 semester yang aktif dalam sistem pada satu waktu.
    Semester aktif inilah yang nanti dipakai sebagai acuan modul nilai, presensi, dan materi pembelajaran.
</p>
@endsection
