@extends('layouts.app')
@section('title', 'Riwayat Presensi')
@section('content')

{{-- Tombol Scan Presensi — Posisi Pertama & Paling Menonjol --}}
<a href="{{ route('siswa.attendance.scan') }}"
   class="btn btn-success fw-bold w-100 py-3 mb-4 d-flex align-items-center justify-content-center gap-2 fs-6">
    <i class="bi bi-qr-code-scan fs-5"></i>
    Scan Presensi Sekarang
    <i class="bi bi-arrow-right-circle-fill ms-auto"></i>
</a>

{{-- Rekap Statistik --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-top: 3px solid #198754 !important;">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-success">{{ $recap['hadir'] }}</div>
                <div class="small text-muted fw-semibold text-uppercase" style="font-size:.7rem; letter-spacing:.05em;">Hadir</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-top: 3px solid #0dcaf0 !important;">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-info">{{ $recap['izin'] }}</div>
                <div class="small text-muted fw-semibold text-uppercase" style="font-size:.7rem; letter-spacing:.05em;">Izin</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-top: 3px solid #ffc107 !important;">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-warning">{{ $recap['sakit'] }}</div>
                <div class="small text-muted fw-semibold text-uppercase" style="font-size:.7rem; letter-spacing:.05em;">Sakit</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-top: 3px solid #dc3545 !important;">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-danger">{{ $recap['alpha'] }}</div>
                <div class="small text-muted fw-semibold text-uppercase" style="font-size:.7rem; letter-spacing:.05em;">Alpha</div>
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Per Tanggal --}}
@forelse ($attendances as $date => $items)
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-light border-bottom py-2 px-3 d-flex align-items-center gap-2">
            <i class="bi bi-calendar3 text-primary small"></i>
            <span class="fw-semibold small">{{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d F Y') }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 small">Mata Pelajaran</th>
                        <th class="small" style="width:130px;">Status</th>
                        <th class="small" style="width:110px;">Waktu Scan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $attendance)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-1 d-flex flex-shrink-0">
                                    <i class="bi bi-book-fill small"></i>
                                </div>
                                <span class="fw-semibold small">
                                    {{ $attendance->session->schedule->teachingAssignment->subject->name }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $attendance->statusBadgeClass() }}">
                                {{ $attendance->statusLabel() }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            <i class="bi bi-clock me-1"></i>
                            {{ $attendance->scanned_at?->format('H:i') ?? '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-calendar-x display-4 text-muted mb-3 d-block"></i>
            <h6 class="fw-bold">Belum Ada Catatan Presensi</h6>
            <p class="text-muted small mb-0">Belum ada riwayat kehadiran yang tercatat.</p>
        </div>
    </div>
@endforelse

<div class="mt-3 pt-2">
    <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
</div>

{{-- FAB Scan — Mobile only --}}
<a href="{{ route('siswa.attendance.scan') }}"
   class="d-md-none position-fixed bottom-0 end-0 m-4 btn btn-success rounded-pill px-4 py-3 shadow fw-bold d-flex align-items-center gap-2"
   style="z-index:1050; animation: fabPop .4s cubic-bezier(.34,1.56,.64,1) forwards;">
    <i class="bi bi-qr-code-scan fs-5"></i> Scan
</a>

<style>
@keyframes fabPop {
    from { transform: scale(.5) translateY(20px); opacity: 0; }
    to   { transform: scale(1) translateY(0);     opacity: 1; }
}
</style>

@endsection