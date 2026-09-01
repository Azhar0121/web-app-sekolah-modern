@extends('layouts.app')

@section('title', 'Dashboard Guru / Wali Kelas')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-2">Dashboard Guru / Wali Kelas</h4>
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>.
            @if ($activeYear)
                Tahun ajaran aktif: <strong>{{ $activeYear->name }}</strong>.
            @else
                Belum ada tahun ajaran aktif — hubungi Super Admin.
            @endif
        </p>
        <a href="{{ route('guru.schedule.index') }}" class="btn btn-sm btn-outline-primary mt-3">
            📅 Lihat Jadwal Mengajar Saya
        </a>
        <a href="{{ route('guru.attendance.index') }}" class="btn btn-sm btn-outline-primary mt-3">
            📷 Presensi Kelas
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Kelas & Mata Pelajaran yang Saya Ampu</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assignments as $assignment)
                    <tr>
                        <td class="fw-semibold">{{ $assignment->classroom->name }}</td>
                        <td>{{ $assignment->subject->name }}</td>
                        <td class="text-end">
                            <a href="{{ route('guru.teaching-assignments.materials.index', $assignment) }}" class="btn btn-sm btn-outline-primary">
                                Materi
                            </a>
                            <a href="{{ route('guru.teaching-assignments.tasks.index', $assignment) }}" class="btn btn-sm btn-outline-primary">
                                Tugas
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            Anda belum ditugaskan mengajar kelas/mapel apa pun pada tahun ajaran ini.
                            Hubungi Super Admin untuk pengaturan penugasan mengajar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
