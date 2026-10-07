@extends('layouts.admin')

@section('title', 'Presensi Kelas')

@section('content')
<link rel="stylesheet" href="{{ asset('css/guru/attendance/index.css') }}">

<div class="guru-attendance-page">

    {{-- HERO --}}
    <div class="attendance-page-header">

        <div class="attendance-header-content">

            <div class="attendance-header-icon">
                <i class="bi bi-calendar-check-fill"></i>
            </div>

            <div>
                <div class="attendance-header-label">
                    KEGIATAN GURU
                </div>

                <h1>
                    Presensi Kelas
                </h1>

                <p>
                    Jadwal hari <strong>{{ $todayName }}</strong>

                    @if($activeYear)
                        <span class="attendance-separator">•</span>
                        TA <strong>{{ $activeYear->name }}</strong>
                    @endif
                </p>
            </div>

        </div>

        <a href="{{ route('guru.dashboard') }}" class="attendance-dashboard-button">
            <i class="bi bi-arrow-left"></i>
            <span>Dashboard</span>
        </a>

        <div class="attendance-hero-decoration one"></div>
        <div class="attendance-hero-decoration two"></div>

    </div>


    @if ($schedules->isEmpty())

        {{-- EMPTY STATE --}}
        <div class="attendance-empty-card">

            <div class="attendance-empty-icon">
                <i class="bi bi-calendar-x"></i>
            </div>

            <h2>
                Tidak Ada Jadwal Hari Ini
            </h2>

            <p>
                Tidak ada jadwal mengajar untuk Anda hari ini.
            </p>

        </div>

    @else

        {{-- SCHEDULE CARD --}}
        <div class="attendance-schedule-card">

            {{-- CARD HEADER --}}
            <div class="attendance-card-header">

                <div class="attendance-card-title">

                    <div class="attendance-section-icon">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>

                    <div>
                        <div class="attendance-card-eyebrow">
                            PRESENSI HARIAN
                        </div>

                        <h2>
                            Jadwal Presensi Hari Ini
                        </h2>

                        <p>
                            Kelola sesi presensi berdasarkan jadwal mengajar Anda.
                        </p>
                    </div>

                </div>

                <div class="attendance-total">
                    <strong>{{ $schedules->count() }}</strong>
                    <span>Jadwal</span>
                </div>

            </div>


            {{-- TABLE --}}
            <div class="attendance-table-wrapper">

                <table class="attendance-table">

                    <thead>
                        <tr>
                            <th class="attendance-time-column">
                                Jam
                            </th>

                            <th>
                                Mata Pelajaran
                            </th>

                            <th>
                                Kelas
                            </th>

                            <th class="attendance-status-column">
                                Status Sesi
                            </th>

                            <th class="attendance-action-column">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($schedules as $schedule)

                            <tr>

                                {{-- JAM --}}
                                <td class="attendance-time-cell">

                                    <div class="attendance-time">
                                        <strong>
                                            {{ $schedule->start_time->format('H:i') }}
                                        </strong>

                                        <span>
                                            –
                                        </span>

                                        <small>
                                            {{ $schedule->end_time->format('H:i') }}
                                        </small>
                                    </div>

                                </td>


                                {{-- MATA PELAJARAN --}}
                                <td>

                                    <div class="attendance-subject">

                                        <div class="attendance-subject-icon">
                                            <i class="bi bi-book-fill"></i>
                                        </div>

                                        <div class="attendance-subject-info">
                                            <strong>
                                                {{ $schedule->teachingAssignment->subject->name }}
                                            </strong>

                                            <span>
                                                Jadwal mengajar
                                            </span>
                                        </div>

                                    </div>

                                </td>


                                {{-- KELAS --}}
                                <td>

                                    <span class="attendance-class">
                                        <i class="bi bi-door-open-fill"></i>
                                        {{ $schedule->teachingAssignment->classroom->name }}
                                    </span>

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if (! $schedule->todaySession)

                                        <span class="attendance-status pending">
                                            <i class="bi bi-clock"></i>
                                            Belum Dibuka
                                        </span>

                                    @elseif ($schedule->todaySession->isOpen())

                                        <span class="attendance-status active">
                                            <span class="attendance-status-dot"></span>
                                            Berlangsung
                                        </span>

                                    @else

                                        <span class="attendance-status completed">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Selesai
                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td class="attendance-action-cell">

                                    <a
                                        href="{{ route('guru.attendance.session', $schedule) }}"
                                        class="attendance-action-button {{ $schedule->todaySession?->isOpen() ? 'active' : '' }}"
                                    >

                                        @if (! $schedule->todaySession)

                                            <i class="bi bi-play-fill"></i>
                                            <span>Buka Sesi</span>

                                        @else

                                            <i class="bi bi-pencil-fill"></i>
                                            <span>Kelola</span>

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