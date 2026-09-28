@extends('layouts.app')
@section('title', 'Kartu Pelajar Digital')
@section('content')

<div class="row justify-content-center py-3">
    <div class="col-md-7 col-lg-5">

        {{-- Kartu Pelajar --}}
        <div class="card border-0 shadow overflow-hidden mb-4">

            {{-- Stripe gradient atas --}}
            <div style="height:6px; background:linear-gradient(90deg,#0d6efd,#6f42c1,#198754);"></div>

            {{-- Header Sekolah --}}
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-2 p-2 d-flex" style="background:linear-gradient(135deg,#0d6efd,#6f42c1);">
                        <i class="bi bi-mortarboard-fill text-white"></i>
                    </div>
                    <div class="lh-sm">
                        <strong class="d-block small">Sekolah Modern</strong>
                        <span class="text-muted" style="font-size:.7rem;">Kartu Pelajar Digital</span>
                    </div>
                </div>
                <span class="badge bg-primary-subtle text-primary fw-semibold">ID Card</span>
            </div>

            {{-- Body: Identitas Siswa --}}
            <div class="card-body p-4">

                {{-- Avatar + Nama --}}
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                         style="width:60px;height:60px;font-size:1.6rem;background:linear-gradient(135deg,#0d6efd,#198754);">
                        {{ mb_strtoupper(mb_substr($student->name, 0, 1)) }}
                    </div>
                    <div class="min-width-0">
                        <h6 class="fw-bold mb-0">{{ $student->name }}</h6>
                        <span class="badge bg-primary-subtle text-primary fw-semibold small">
                            {{ $classroom?->name ?? 'Kelas belum ditentukan' }}
                        </span>
                        @if($activeYear)
                        <span class="text-muted ms-1" style="font-size:.75rem;">• {{ $activeYear->name }}</span>
                        @endif
                        <div class="text-muted small mt-1">{{ $student->email }}</div>
                    </div>
                </div>

                {{-- Detail Baris --}}
                <div class="d-flex flex-column gap-2 mb-4">
                    <div class="d-flex align-items-center gap-3 bg-light rounded-2 p-2">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-2 d-flex flex-shrink-0">
                            <i class="bi bi-person-badge small"></i>
                        </div>
                        <div class="lh-sm">
                            <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; letter-spacing:.06em;">Nomor Induk Siswa</div>
                            <strong class="small">{{ $student->studentProfile?->student_id_number ?? 'Belum diisi' }}</strong>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 bg-light rounded-2 p-2">
                        <div class="bg-success bg-opacity-10 text-success rounded-2 p-2 d-flex flex-shrink-0">
                            <i class="bi bi-building small"></i>
                        </div>
                        <div class="lh-sm">
                            <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; letter-spacing:.06em;">Kelas / Rombel</div>
                            <strong class="small">{{ $classroom?->name ?? '—' }}</strong>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 bg-light rounded-2 p-2">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-2 p-2 d-flex flex-shrink-0">
                            <i class="bi bi-calendar3 small"></i>
                        </div>
                        <div class="lh-sm">
                            <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; letter-spacing:.06em;">Tahun Ajaran</div>
                            <strong class="small">{{ $activeYear?->name ?? '—' }}</strong>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 bg-light rounded-2 p-2">
                        <div class="bg-info bg-opacity-10 text-info rounded-2 p-2 d-flex flex-shrink-0">
                            <i class="bi bi-person-check small"></i>
                        </div>
                        <div class="lh-sm">
                            <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; letter-spacing:.06em;">Status</div>
                            <strong class="small text-success">Siswa Aktif</strong>
                        </div>
                    </div>
                </div>

                {{-- Rekap Kehadiran --}}
                <div class="bg-light rounded-3 p-3 mb-4">
                    <div class="text-muted fw-semibold text-uppercase mb-2" style="font-size:.65rem; letter-spacing:.08em;">
                        Rekap Kehadiran Saya
                    </div>
                    <div class="row g-2 text-center">
                        <div class="col-3">
                            <div class="fw-bold text-success fs-5">{{ $attendanceSummary['hadir'] }}</div>
                            <div class="text-muted" style="font-size:.65rem;">Hadir</div>
                        </div>
                        <div class="col-3">
                            <div class="fw-bold text-danger fs-5">{{ $attendanceSummary['alpha'] }}</div>
                            <div class="text-muted" style="font-size:.65rem;">Alpha</div>
                        </div>
                        <div class="col-3">
                            <div class="fw-bold text-warning fs-5">{{ $attendanceSummary['izin'] }}</div>
                            <div class="text-muted" style="font-size:.65rem;">Izin</div>
                        </div>
                        <div class="col-3">
                            <div class="fw-bold text-info fs-5">{{ $attendanceSummary['sakit'] }}</div>
                            <div class="text-muted" style="font-size:.65rem;">Sakit</div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-grid gap-2">
                    <a href="{{ route('siswa.attendance.scan') }}" class="btn btn-success fw-bold py-2">
                        <i class="bi bi-qr-code-scan me-2"></i>Scan Presensi
                    </a>
                    <a href="{{ route('siswa.attendance.index') }}" class="btn btn-outline-secondary py-2">
                        <i class="bi bi-clock-history me-2"></i>Riwayat Presensi
                    </a>
                </div>

            </div>

            {{-- Footer --}}
            <div class="card-footer bg-white border-top py-2 px-4 d-flex justify-content-between align-items-center">
                <span class="text-success small fw-semibold d-flex align-items-center gap-1">
                    <span style="display:inline-block;width:7px;height:7px;background:#198754;border-radius:50%;animation:blink 1.5s ease infinite;"></span>
                    Aktif
                </span>
                <span class="text-muted small">{{ now()->format('Y') }} / Sekolah Modern</span>
            </div>

        </div>

    </div>
</div>

<style>
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
</style>

@endsection