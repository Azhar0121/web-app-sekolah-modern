@extends('layouts.admin')

@section('title', 'Daftar Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Daftar Siswa</h4>
        <p class="text-muted mb-0">Pilih kelas untuk melihat daftar siswa yang Anda ampu.</p>
    </div>
    @if ($activeYear)
        <span class="badge text-bg-primary fs-6">{{ $activeYear->name }}</span>
    @endif
</div>

@if ($classrooms->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center text-muted py-5">
            <div class="fs-1 mb-3">📭</div>
            <h6 class="fw-semibold">Tidak ada data siswa</h6>
            <p class="small mb-0">Anda belum ditugaskan mengajar kelas manapun pada tahun ajaran ini.</p>
        </div>
    </div>
@else
    {{-- Tab Pilih Kelas --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3 px-4">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="text-muted small fw-semibold me-1">Pilih Kelas:</span>
                @foreach ($classrooms as $classroom)
                    <a href="{{ route('guru.student-profile.index', ['classroom_id' => $classroom->id]) }}"
                       class="btn btn-sm {{ $selectedClassroom?->id === $classroom->id ? 'btn-primary' : 'btn-outline-secondary' }}">
                        {{ $classroom->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tabel Siswa Kelas Terpilih --}}
    @if ($selectedClassroom)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex align-items-center gap-3 py-3">
                <div>
                    <span class="fw-bold">Kelas {{ $selectedClassroom->name }}</span>
                    <span class="text-muted small ms-2">{{ $students->count() }} siswa</span>
                </div>
            </div>

            @if ($students->isEmpty())
                <div class="card-body text-center text-muted py-5">
                    <div class="mb-2">📋</div>
                    Belum ada siswa yang terdaftar di kelas ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Jenis Kelamin</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $i => $student)
                                <tr>
                                    <td class="text-muted small">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                                 style="width: 36px; height: 36px; font-size: 0.85rem; flex-shrink: 0;">
                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $student->name }}</div>
                                                <div class="text-muted small">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $student->studentProfile?->nisn ?? '-' }}</td>
                                    <td>
                                        @if ($student->studentProfile?->gender)
                                            <span class="badge {{ $student->studentProfile->gender === 'L' ? 'text-bg-info' : 'text-bg-warning' }}">
                                                {{ $student->studentProfile->genderLabel() }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('guru.student-profile.show', $student) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Lihat Biodata →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
@endif
@endsection
