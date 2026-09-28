@extends('layouts.admin')
@section('title', 'Presensi Kelas')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary fw-semibold mb-1">KEGIATAN GURU</span>
        <h4 class="mb-0 fw-bold">Presensi Kelas</h4>
        <p class="text-muted small mb-0">
            Jadwal hari <strong>{{ $todayName }}</strong>
            @if($activeYear) &middot; TA <strong>{{ $activeYear->name }}</strong> @endif
        </p>
    </div>
    <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Dashboard
    </a>
</div>

@if ($schedules->isEmpty())

    <div class="card border-0 shadow-sm text-center py-5">
        <div class="card-body">
            <div class="mb-3">
                <span class="display-4 text-muted"><i class="bi bi-calendar-x"></i></span>
            </div>
            <h5 class="fw-bold">Tidak Ada Jadwal Hari Ini</h5>
            <p class="text-muted">Tidak ada jadwal mengajar untuk Anda hari ini.</p>
        </div>
    </div>

@else

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
            <div class="bg-success bg-opacity-10 text-success rounded-2 p-2 d-flex">
                <i class="bi bi-calendar-check-fill fs-5"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Jadwal Presensi Hari Ini</h6>
                <small class="text-muted">Kelola sesi presensi berdasarkan jadwal mengajar Anda.</small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width:120px;">Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th style="width:160px;">Status Sesi</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-semibold text-dark">{{ $schedule->start_time->format('H:i') }}</span>
                            <span class="text-muted mx-1">–</span>
                            <span class="text-muted">{{ $schedule->end_time->format('H:i') }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-1 d-flex">
                                    <i class="bi bi-book-fill small"></i>
                                </div>
                                <span class="fw-semibold">{{ $schedule->teachingAssignment->subject->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary fw-semibold">
                                {{ $schedule->teachingAssignment->classroom->name }}
                            </span>
                        </td>
                        <td>
                            @if (! $schedule->todaySession)
                                <span class="badge bg-warning-subtle text-warning fw-semibold">
                                    <i class="bi bi-clock me-1"></i> Belum Dibuka
                                </span>
                            @elseif ($schedule->todaySession->isOpen())
                                <span class="badge bg-success-subtle text-success fw-semibold">
                                    <span class="me-1" style="display:inline-block;width:7px;height:7px;background:currentColor;border-radius:50%;animation:blink 1.2s ease infinite;"></span>
                                    Berlangsung
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary fw-semibold">
                                    <i class="bi bi-check-circle me-1"></i> Selesai
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('guru.attendance.session', $schedule) }}"
                               class="btn btn-sm {{ $schedule->todaySession?->isOpen() ? 'btn-success' : 'btn-primary' }} fw-semibold">
                                @if (! $schedule->todaySession)
                                    <i class="bi bi-play-fill me-1"></i> Buka Sesi
                                @else
                                    <i class="bi bi-pencil-fill me-1"></i> Kelola
                                @endif
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endif

<style>
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
</style>

@endsection
