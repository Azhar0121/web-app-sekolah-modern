@extends('layouts.admin')
@section('title', 'Kelola Presensi — ' . $schedule->teachingAssignment->subject->name)
@section('content')

{{-- Header --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-semibold mb-1">PRESENSI KELAS</span>
                <h5 class="mb-1 fw-bold">{{ $schedule->teachingAssignment->subject->name }}</h5>
                <div class="d-flex flex-wrap gap-2 text-muted small">
                    <span><i class="bi bi-people me-1"></i>{{ $schedule->teachingAssignment->classroom->name }}</span>
                    <span>•</span>
                    <span><i class="bi bi-calendar3 me-1"></i>{{ $attendanceSession->date->translatedFormat('l, d F Y') }}</span>
                    <span>•</span>
                    <span><i class="bi bi-clock me-1"></i>{{ $schedule->start_time->format('H:i') }} – {{ $schedule->end_time->format('H:i') }}</span>
                </div>
            </div>
            <div>
                @if ($attendanceSession->isOpen())
                    <span class="badge bg-success fs-6 fw-semibold">
                        <span class="me-1" style="display:inline-block;width:8px;height:8px;background:#fff;border-radius:50%;animation:blink 1.2s ease infinite;"></span>
                        Sesi Berlangsung
                    </span>
                @else
                    <span class="badge bg-secondary fs-6 fw-semibold">
                        <i class="bi bi-stop-circle me-1"></i> Sesi Selesai
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

@if ($attendanceSession->isOpen())

    <div class="row g-4 mb-4">

        {{-- Panel QR --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-2 d-flex">
                        <i class="bi bi-qr-code fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">QR Presensi Kelas</h6>
                        <small class="text-muted">Tampilkan QR ke siswa untuk scan mandiri</small>
                    </div>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mx-auto mb-4 rounded-4 d-flex align-items-center justify-content-center"
                         style="width:90px;height:90px;background:linear-gradient(135deg,#6366f1,#10b981);">
                        <i class="bi bi-qr-code text-white" style="font-size:2.5rem;"></i>
                    </div>
                    <p class="text-muted small mb-4">
                        Tampilkan <strong>QR besar</strong> yang dapat diproyeksikan ke papan tulis.<br>
                        Siswa scan QR dari HP masing-masing.
                    </p>
                    <a href="{{ route('guru.attendance.show-qr', $attendanceSession) }}"
                       class="btn btn-primary fw-bold px-4 py-2 mb-3">
                        <i class="bi bi-fullscreen me-2"></i>Tampilkan QR Presensi
                    </a>
                    <div class="text-muted small">
                        <i class="bi bi-shield-check text-success me-1"></i>
                        QR berlaku 5 menit, dapat diperbarui
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Siswa --}}
        <div class="col-lg-7">
            @include('guru.attendance.partials.roster')
        </div>

    </div>

    {{-- Tutup Sesi --}}
    <div class="d-flex justify-content-end">
        <form method="POST"
              action="{{ route('guru.attendance.close', $attendanceSession) }}"
              onsubmit="return confirm('Tutup sesi presensi? Siswa yang belum tercatat akan otomatis ditandai Alpha.');">
            @csrf
            <button type="submit" class="btn btn-danger fw-bold px-4 py-2">
                <i class="bi bi-stop-circle me-2"></i>Tutup Sesi Presensi
            </button>
        </form>
    </div>

@else

    <div class="alert alert-info d-flex align-items-start gap-3 mb-4" role="alert">
        <i class="bi bi-info-circle-fill fs-4 flex-shrink-0 mt-1"></i>
        <div>
            <strong class="d-block">Sesi presensi telah ditutup.</strong>
            <span class="small">Anda masih dapat mengedit status kehadiran siswa secara manual pada tabel di bawah ini.
            Klik <strong>Buka Kembali Sesi</strong> untuk mengaktifkan QR kembali.</span>
        </div>
    </div>

    <div class="mb-4">
        @include('guru.attendance.partials.roster')
    </div>

    <form method="POST"
          action="{{ route('guru.attendance.reopen', $attendanceSession) }}"
          onsubmit="return confirm('Buka kembali sesi presensi ini?');">
        @csrf
        <button type="submit" class="btn btn-success fw-bold px-4 py-2">
            <i class="bi bi-play-circle me-2"></i>Buka Kembali Sesi
        </button>
    </form>

@endif

<div class="mt-4 pt-2 border-top">
    <a href="{{ route('guru.attendance.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Presensi Kelas
    </a>
</div>

<style>
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
</style>

@endsection
