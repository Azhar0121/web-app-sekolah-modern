@extends('layouts.app')

@section('title', 'Presensi Kelas')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-2">Presensi Kelas</h4>
        <p class="text-muted mb-0">
            Jadwal mengajar Anda hari <strong>{{ $todayName }}</strong>
            @if ($activeYear)
                &middot; Tahun ajaran <strong>{{ $activeYear->name }}</strong>
            @endif
        </p>
    </div>
</div>

@if ($schedules->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center text-muted py-5">
            Tidak ada jadwal mengajar untuk Anda hari ini.
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 140px;">Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th style="width: 160px;">Status Sesi</th>
                        <th class="text-end" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                            <td>{{ $schedule->teachingAssignment->subject->name }}</td>
                            <td class="fw-semibold">{{ $schedule->teachingAssignment->classroom->name }}</td>
                            <td>
                                @if (! $schedule->todaySession)
                                    <span class="badge text-bg-secondary">Belum Dibuka</span>
                                @elseif ($schedule->todaySession->isOpen())
                                    <span class="badge text-bg-success">Sedang Berlangsung</span>
                                @else
                                    <span class="badge text-bg-dark">Selesai</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('guru.attendance.session', $schedule) }}" class="btn btn-sm btn-primary">
                                    @if (! $schedule->todaySession) Buka Sesi @else Kelola @endif
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
