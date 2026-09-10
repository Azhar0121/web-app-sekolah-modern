@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet"
      href="{{ asset('css/siswa/schedule/index.css') }}">

<div class="student-schedule-page">

    {{-- =====================================================
        HEADER JADWAL
    ====================================================== --}}

    <div class="schedule-header mb-4">

    <div class="schedule-decoration schedule-decoration-one"></div>
    <div class="schedule-decoration schedule-decoration-two"></div>

    <div class="schedule-dot schedule-dot-one"></div>
    <div class="schedule-dot schedule-dot-two"></div>

    <div class="schedule-floating-icon schedule-floating-one">
        <i class="bi bi-calendar-week"></i>
    </div>

    <div class="schedule-floating-icon schedule-floating-two">
        <i class="bi bi-clock-fill"></i>
    </div>

        <div class="schedule-header-content">

            <div class="schedule-header-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="schedule-header-text">

                <span class="schedule-eyebrow">
                    AKADEMIK SISWA
                </span>

                <h4>JADWAL PELAJARAN</h4>

                <p class="mb-0">
                    @if ($activeYear && $classroom)
                        Kelas <strong>{{ $classroom->name }}</strong>
                        <span class="schedule-separator">•</span>
                        Tahun ajaran <strong>{{ $activeYear->name }}</strong>
                    @elseif (! $classroom)
                        Anda belum terdaftar di kelas manapun pada tahun ajaran ini.
                    @else
                        Belum ada tahun ajaran aktif — hubungi Tata Usaha.
                    @endif
                </p>

            </div>

        </div>

    </div>


    @php
        $today = [
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        ][now()->dayOfWeek];
    @endphp


    {{-- =====================================================
        DAFTAR JADWAL
    ====================================================== --}}

    @foreach (\App\Models\Schedule::DAY_ORDER as $day)

        @continue(($schedules[$day] ?? collect())->isEmpty())

        <div class="schedule-day-card {{ $day === $today ? 'is-today' : '' }}">

            {{-- HEADER HARI --}}

            <div class="schedule-day-header">

                <div class="day-title-wrapper">

                    <div class="day-icon">
                        <i class="bi bi-calendar-week"></i>
                    </div>

                    <div>

                        <span class="day-eyebrow">
                            JADWAL HARIAN
                        </span>

                        <h5>{{ $day }}</h5>

                    </div>

                </div>

                @if ($day === $today)

                    <span class="today-badge">
                        <i class="bi bi-check-circle-fill"></i>
                        Hari Ini
                    </span>

                @endif

            </div>


            {{-- TABEL --}}

            <div class="table-responsive">

                <table class="table table-hover align-middle schedule-table mb-0">

                    <thead>

                        <tr>

                            <th style="width: 150px;">
                                <i class="bi bi-clock"></i>
                                Jam
                            </th>

                            <th>
                                <i class="bi bi-book"></i>
                                Mata Pelajaran
                            </th>

                            <th>
                                <i class="bi bi-person"></i>
                                Guru
                            </th>

                            <th>
                                <i class="bi bi-door-open"></i>
                                Ruangan
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($schedules[$day] as $schedule)

                            <tr>

                                <td>

                                    <div class="time-cell">

                                        <div class="time-icon">
                                            <i class="bi bi-clock-fill"></i>
                                        </div>

                                        <span>
                                            {{ $schedule->start_time->format('H:i') }}
                                            -
                                            {{ $schedule->end_time->format('H:i') }}
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <div class="subject-cell">

                                        <div class="subject-icon">
                                            <i class="bi bi-book-fill"></i>
                                        </div>

                                        <span>
                                            {{ $schedule->teachingAssignment->subject->name }}
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <div class="teacher-cell">

                                        <div class="teacher-icon">
                                            <i class="bi bi-person-fill"></i>
                                        </div>

                                        <span>
                                            {{ $schedule->teachingAssignment->teacher->name }}
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    @if ($schedule->room)

                                        <span class="room-badge">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            {{ $schedule->room }}
                                        </span>

                                    @else

                                        <span class="room-empty">
                                            -
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endforeach


    {{-- =====================================================
        EMPTY STATE
    ====================================================== --}}

    @if ($schedules->isEmpty())

        <div class="schedule-empty">

            <div class="empty-icon">
                <i class="bi bi-calendar-x"></i>
            </div>

            <h5>Belum Ada Jadwal</h5>

            <p>
                Belum ada jadwal pelajaran yang diatur untuk kelas Anda
                pada tahun ajaran ini.
            </p>

        </div>

    @endif


    {{-- =====================================================
        KEMBALI KE DASHBOARD
    ====================================================== --}}

    <div class="schedule-back-bottom">

        <a href="{{ route('siswa.dashboard') }}"
           class="back-dashboard">

            <i class="bi bi-arrow-left"></i>

            <span>
                Kembali
            </span>

        </a>

    </div>

</div>

@endsection