@extends('layouts.app')

@section('title', 'Presensi Kelas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/attendance/index.css') }}">

<div class="attendance-page">


{{-- Header --}}
<div class="attendance-header">
<div class="attendance-decoration attendance-decoration-one"></div>
<div class="attendance-decoration attendance-decoration-two"></div>

<div class="attendance-header-content">

    <div>
        <span class="attendance-label">KEGIATAN GURU</span>

        <h1>Presensi Kelas</h1>

        <p>
            Jadwal mengajar Anda hari
            <strong>{{ $todayName }}</strong>

            @if ($activeYear)
                &middot;
                Tahun ajaran
                <strong>{{ $activeYear->name }}</strong>
            @endif
        </p>
    </div>

    <a href="{{ route('guru.dashboard') }}"
       class="attendance-dashboard-button">
        ← Dashboard
    </a>

</div>

</div>



@if ($schedules->isEmpty())

    {{-- Empty State --}}
    <div class="attendance-empty-card">

        <div class="attendance-empty-icon">
            ✓
        </div>

        <h2>Tidak Ada Jadwal</h2>

        <p>
            Tidak ada jadwal mengajar untuk Anda hari ini.
        </p>

    </div>

@else

    {{-- Schedule Card --}}
    <div class="attendance-card">

        <div class="attendance-card-header">

            <div class="attendance-card-icon">
                ✓
            </div>

            <div>
                <h2>Jadwal Presensi Hari Ini</h2>
                <p>
                    Kelola sesi presensi berdasarkan jadwal mengajar Anda.
                </p>
            </div>

        </div>


        <div class="attendance-table-wrapper">

            <table class="attendance-table">

                <thead>
                    <tr>
                        <th class="col-time">Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th class="col-status">Status Sesi</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($schedules as $schedule)

                        <tr>

                            <td>
                                <div class="attendance-time">
                                    {{ $schedule->start_time->format('H:i') }}
                                    <span>–</span>
                                    {{ $schedule->end_time->format('H:i') }}
                                </div>
                            </td>

                            <td>
                                <div class="attendance-subject">
                                    {{ $schedule->teachingAssignment->subject->name }}
                                </div>
                            </td>

                            <td>
                                <span class="attendance-class">
                                    {{ $schedule->teachingAssignment->classroom->name }}
                                </span>
                            </td>

                            <td>

                                @if (! $schedule->todaySession)

                                    <span class="attendance-status status-pending">
                                        <span class="status-dot"></span>
                                        Belum Dibuka
                                    </span>

                                @elseif ($schedule->todaySession->isOpen())

                                    <span class="attendance-status status-active">
                                        <span class="status-dot"></span>
                                        Sedang Berlangsung
                                    </span>

                                @else

                                    <span class="attendance-status status-finished">
                                        <span class="status-dot"></span>
                                        Selesai
                                    </span>

                                @endif

                            </td>

                            <td class="attendance-action-cell">

                                <a href="{{ route('guru.attendance.session', $schedule) }}"
                                   class="attendance-action-button">

                                    @if (! $schedule->todaySession)
                                        Buka Sesi
                                    @else
                                        Kelola
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


</div>

@endsection
