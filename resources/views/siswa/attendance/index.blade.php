@extends('layouts.app')

@section('title', 'Riwayat Presensi')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-1">Riwayat Presensi</h4>
        <p class="text-muted mb-0">Rekap kehadiran Anda dari seluruh sesi yang sudah tercatat.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-4 fw-bold text-success">{{ $recap['hadir'] }}</div>
            <div class="text-muted small">Hadir</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-4 fw-bold text-info">{{ $recap['izin'] }}</div>
            <div class="text-muted small">Izin</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-4 fw-bold text-warning">{{ $recap['sakit'] }}</div>
            <div class="text-muted small">Sakit</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-4 fw-bold text-danger">{{ $recap['alpha'] }}</div>
            <div class="text-muted small">Alpha</div>
        </div>
    </div>
</div>

@forelse ($attendances as $date => $items)
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white fw-semibold">
            {{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d F Y') }}
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th style="width: 130px;">Status</th>
                        <th style="width: 120px;">Waktu Scan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $attendance)
                        <tr>
                            <td>{{ $attendance->session->schedule->teachingAssignment->subject->name }}</td>
                            <td><span class="badge {{ $attendance->statusBadgeClass() }}">{{ $attendance->statusLabel() }}</span></td>
                            <td>{{ $attendance->scanned_at?->format('H:i') ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center text-muted py-5">
            Belum ada catatan presensi.
        </div>
    </div>
@endforelse
@endsection
