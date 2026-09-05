@extends('layouts.app')

@section('title', 'Jadwal Mengajar Saya')

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/schedule/index.css') }}">

<div class="guru-schedule-page">


{{-- =========================
     PAGE HEADER / HERO
     ========================= --}}
<section class="schedule-hero">

    <div class="schedule-hero-content">

        <span class="schedule-eyebrow">
            PORTAL GURU
        </span>

        <h1>
            Jadwal Mengajar Saya
        </h1>

        <p>
            Lihat jadwal mengajar Anda berdasarkan hari,
            mata pelajaran, kelas, dan ruangan.
        </p>

        @if ($activeYear)
            <div class="schedule-year">
                <span class="schedule-year-icon">A</span>

                <div>
                    <small>TAHUN AJARAN AKTIF</small>
                    <strong>{{ $activeYear->name }}</strong>
                </div>
            </div>
        @else
            <div class="schedule-warning">
                <strong>Belum ada tahun ajaran aktif</strong>
                <span>Hubungi Super Admin untuk pengaturan tahun ajaran.</span>
            </div>
        @endif

    </div>


    {{-- =========================
         HERO RIGHT SIDE
         ========================= --}}
    <div class="schedule-hero-side">

        <a href="{{ route('guru.dashboard') }}" class="schedule-back-button">
            <span class="schedule-back-icon">←</span>
            <span>Dashboard</span>
        </a>

        <div class="schedule-hero-decoration">
            <span class="schedule-circle schedule-circle-one"></span>
            <span class="schedule-circle schedule-circle-two"></span>
            <span class="schedule-circle schedule-circle-three"></span>
            <span class="schedule-dot schedule-dot-one"></span>
            <span class="schedule-dot schedule-dot-two"></span>
            <span class="schedule-dot schedule-dot-three"></span>
        </div>

    </div>

</section>


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


{{-- =========================
     SCHEDULE LIST
     ========================= --}}
<div class="schedule-list">

    @foreach (\App\Models\Schedule::DAY_ORDER as $day)

        @continue(($schedules[$day] ?? collect())->isEmpty())

        <section class="schedule-day-card {{ $day === $today ? 'is-today' : '' }}">

            {{-- =========================
                 DAY HEADER
                 ========================= --}}
            <div class="schedule-day-header">

                <div class="schedule-day-title">

                    <div class="schedule-day-icon">
                        <span></span>
                    </div>

                    <div>
                        <span class="schedule-day-label">
                            HARI
                        </span>

                        <h2>
                            {{ $day }}
                        </h2>
                    </div>

                </div>

                @if ($day === $today)
                    <div class="schedule-today-badge">
                        <span></span>
                        Hari Ini
                    </div>
                @endif

            </div>


            {{-- =========================
                 SCHEDULE TABLE
                 ========================= --}}
            <div class="schedule-table-wrapper">

                <table class="schedule-table">

                    <thead>
                        <tr>
                            <th class="schedule-time-column">
                                Jam
                            </th>

                            <th class="schedule-subject-column">
                                Mata Pelajaran
                            </th>

                            <th class="schedule-class-column">
                                Kelas
                            </th>

                            <th class="schedule-room-column">
                                Ruangan
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($schedules[$day] as $schedule)

                            <tr>

                                {{-- =========================
                                     TIME
                                     ========================= --}}
                                <td class="schedule-time">

                                    <div class="schedule-time-main">
                                        <strong>
                                            {{ $schedule->start_time->format('H:i') }}
                                        </strong>

                                        <span>
                                            -
                                        </span>

                                        <strong>
                                            {{ $schedule->end_time->format('H:i') }}
                                        </strong>
                                    </div>

                                </td>


                                {{-- =========================
                                     SUBJECT
                                     ========================= --}}
                                <td class="schedule-subject">

                                    <div class="schedule-subject-mark"></div>

                                    <div class="schedule-subject-content">

                                        <strong>
                                            {{ $schedule->teachingAssignment->subject->name }}
                                        </strong>

                                        <small>
                                            Mata pelajaran
                                        </small>

                                    </div>

                                </td>


                                {{-- =========================
                                     CLASS
                                     ========================= --}}
                                <td class="schedule-class">

                                    <div class="schedule-class-badge">
                                        {{ strtoupper(substr($schedule->teachingAssignment->classroom->name, 0, 1)) }}
                                    </div>

                                    <div class="schedule-class-content">
                                        <strong>
                                            {{ $schedule->teachingAssignment->classroom->name }}
                                        </strong>

                                        <small>
                                            Kelas
                                        </small>
                                    </div>

                                </td>


                                {{-- =========================
                                     ROOM
                                     ========================= --}}
                                <td class="schedule-room">

                                    @if ($schedule->room)

                                        <span class="room-badge">
                                            <span class="room-icon">⌂</span>
                                            {{ $schedule->room }}
                                        </span>

                                    @else

                                        <span class="room-empty">
                                            Belum ditentukan
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </section>

    @endforeach


    {{-- =========================
         EMPTY STATE
         ========================= --}}
    @if ($schedules->isEmpty())

        <section class="schedule-empty-card">

            <div class="schedule-empty-visual">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <h2>
                Belum Ada Jadwal Mengajar
            </h2>

            <p>
                Belum ada jadwal mengajar yang diatur untuk Anda
                pada tahun ajaran ini.
            </p>

            <small>
                Hubungi Super Admin untuk pengaturan jadwal.
            </small>

        </section>

    @endif

</div>


</div>

@endsection
