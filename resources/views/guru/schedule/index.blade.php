@extends('layouts.app')

@section('title', 'Jadwal Mengajar Saya')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-2">Jadwal Mengajar Saya</h4>
        <p class="text-muted mb-0">
            @if ($activeYear)
                Tahun ajaran aktif: <strong>{{ $activeYear->name }}</strong>.
            @else
                Belum ada tahun ajaran aktif — hubungi Super Admin.
            @endif
        </p>
    </div>
</div>

@php
    $today = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][now()->dayOfWeek];
@endphp

@foreach (\App\Models\Schedule::DAY_ORDER as $day)
    @continue(($schedules[$day] ?? collect())->isEmpty())

    <div class="card border-0 shadow-sm mb-3 {{ $day === $today ? 'border-start border-4 border-primary' : '' }}">
        <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
            {{ $day }}
            @if ($day === $today)
                <span class="badge text-bg-primary">Hari Ini</span>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 140px;">Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Ruangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules[$day] as $schedule)
                        <tr>
                            <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                            <td>{{ $schedule->teachingAssignment->subject->name }}</td>
                            <td class="fw-semibold">{{ $schedule->teachingAssignment->classroom->name }}</td>
                            <td>{{ $schedule->room ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach

@if ($schedules->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center text-muted py-5">
            Belum ada jadwal mengajar yang diatur untuk Anda pada tahun ajaran ini.
        </div>
    </div>
@endif
@endsection
