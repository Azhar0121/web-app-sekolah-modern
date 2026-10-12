@extends('layouts.app')

@section('title', 'Jadwal Pelajaran - ' . $student->name)

@section('content')
<link rel="stylesheet" href="{{ asset('css/ortu/schedule/index.css') }}">

@php
    $parentChildren = auth()->user()->children()->get();

    $days = [
        'Minggu', 'Senin', 'Selasa', 'Rabu',
        'Kamis', 'Jumat', 'Sabtu'
    ];

    $today = $days[now()->dayOfWeek];
@endphp

<div class="ortu-schedule-page">
    <div class="os-container">

        {{-- HERO --}}
        <section class="os-hero">
            <div class="os-hero-decoration os-decoration-one"></div>
            <div class="os-hero-decoration os-decoration-two"></div>

            <div class="os-hero-content">
                <div class="os-eyebrow">
                    <i class="bi bi-calendar-week"></i>
                    <span>PORTAL ORANG TUA / WALI</span>
                </div>

                <h1>Jadwal Pelajaran</h1>

                <p class="os-hero-description">
                    Pantau jadwal belajar, mata pelajaran, dan guru pengampu
                    anak Anda dengan mudah.
                </p>

                <div class="os-student-info">
                    <div class="os-student-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <div class="os-student-details">
                        <span class="os-student-label">Jadwal milik</span>
                        <strong>{{ $student->name }}</strong>

                        <span class="os-student-class">
                            @if ($activeYear && $classroom)
                                <i class="bi bi-mortarboard"></i>
                                {{ $classroom->name }}
                                <span class="os-info-dot"></span>
                                Tahun Ajaran {{ $activeYear->name }}
                            @elseif (!$activeYear)
                                Belum ada tahun ajaran aktif
                            @else
                                Belum terdaftar di kelas aktif
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </section>

        {{-- PILIH ANAK --}}
        @if ($parentChildren->count() > 1)
            <section class="os-child-selector">
                <div class="os-selector-heading">
                    <div class="os-section-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <div>
                        <h2>Pilih Anak</h2>
                        <p>Pilih anak untuk melihat jadwal pelajarannya.</p>
                    </div>
                </div>

                <div class="os-child-list">
                    @foreach ($parentChildren as $c)
                        <a
                            href="{{ route('ortu.schedule.index', $c) }}"
                            class="os-child-option {{ $c->id === $student->id ? 'is-active' : '' }}"
                            @if ($c->id === $student->id) aria-current="page" @endif
                        >
                            <span class="os-child-avatar">
                                <i class="bi bi-person"></i>
                            </span>

                            <span class="os-child-name">{{ $c->name }}</span>

                            @if ($c->id === $student->id)
                                <i class="bi bi-check-circle-fill os-child-check"></i>
                            @else
                                <i class="bi bi-chevron-right os-child-arrow"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- RINGKASAN --}}
        <section class="os-summary">
            <div class="os-summary-card">
                <div class="os-summary-icon os-icon-blue">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div>
                    <span class="os-summary-label">Hari Ini</span>
                    <strong>{{ $today }}</strong>
                    <small>Jadwal pelajaran</small>
                </div>
            </div>

            <div class="os-summary-card">
                <div class="os-summary-icon os-icon-purple">
                    <i class="bi bi-book"></i>
                </div>

                <div>
                    <span class="os-summary-label">Kelas</span>
                    <strong>{{ $classroom->name ?? 'Belum tersedia' }}</strong>
                    <small>Kelas saat ini</small>
                </div>
            </div>

            <div class="os-summary-card">
                <div class="os-summary-icon os-icon-teal">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>
                    <span class="os-summary-label">Informasi</span>
                    <strong>Jadwal Mingguan</strong>
                    <small>Sesuai pengaturan sekolah</small>
                </div>
            </div>
        </section>

        {{-- JADWAL PER HARI --}}
        <div class="os-content-heading">
            <div>
                <span class="os-section-kicker">AKADEMIK</span>
                <h2>Rincian Jadwal Pelajaran</h2>
                <p>Daftar mata pelajaran berdasarkan hari belajar.</p>
            </div>
        </div>

        @if ($schedules->isNotEmpty())
            <div class="os-schedule-list">
                @foreach (\App\Models\Schedule::DAY_ORDER as $day)
                    @continue(($schedules[$day] ?? collect())->isEmpty())

                    <section class="os-day-card {{ $day === $today ? 'is-today' : '' }}">
                        <div class="os-day-header">
                            <div class="os-day-heading">
                                <div class="os-day-icon">
                                    <i class="bi bi-calendar-day"></i>
                                </div>

                                <div>
                                    <h3>{{ $day }}</h3>
                                    <p>
                                        {{ $schedules[$day]->count() }}
                                        mata pelajaran terjadwal
                                    </p>
                                </div>
                            </div>

                            @if ($day === $today)
                                <span class="os-today-badge">
                                    <span class="os-today-dot"></span>
                                    Hari Ini
                                </span>
                            @endif
                        </div>

                        <div class="os-table-wrapper">
                            <table class="os-table">
                                <thead>
                                    <tr>
                                        <th class="os-time-column">Waktu</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru Pengampu</th>
                                        <th>Ruangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($schedules[$day] as $schedule)
                                        <tr>
                                            <td>
                                                <div class="os-time">
                                                    <i class="bi bi-clock"></i>
                                                    <span>
                                                        {{ $schedule->start_time->format('H:i') }}
                                                        <span class="os-time-separator">—</span>
                                                        {{ $schedule->end_time->format('H:i') }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="os-subject">
                                                    <span class="os-subject-icon">
                                                        <i class="bi bi-journal-bookmark"></i>
                                                    </span>

                                                    <span>
                                                        {{ $schedule->teachingAssignment?->subject?->name ?? 'Mata pelajaran belum tersedia' }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="os-teacher">
                                                    <i class="bi bi-person-badge"></i>
                                                    <span>
                                                        {{ $schedule->teachingAssignment?->teacher?->name ?? 'Belum ditentukan' }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td>
                                                @if ($schedule->room)
                                                    <span class="os-room">
                                                        <i class="bi bi-geo-alt"></i>
                                                        {{ $schedule->room }}
                                                    </span>
                                                @else
                                                    <span class="os-room-empty">Belum ditentukan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endforeach
            </div>
        @else
            <section class="os-empty-state">
                <div class="os-empty-icon">
                    <i class="bi bi-calendar2-x"></i>
                </div>

                <h3>Jadwal Belum Tersedia</h3>

                <p>
                    Jadwal pelajaran untuk
                    <strong>{{ $student->name }}</strong>
                    @if ($classroom)
                        di kelas <strong>{{ $classroom->name }}</strong>
                    @endif
                    belum tersedia. Silakan hubungi pihak sekolah untuk
                    informasi lebih lanjut.
                </p>

                <a href="{{ route('ortu.dashboard') }}" class="os-empty-button">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Dasbor
                </a>
            </section>
        @endif

        {{-- INFORMASI --}}
        <div class="os-footer-note">
            <i class="bi bi-info-circle"></i>
            <span>
                Jadwal ditampilkan sesuai data yang diatur oleh pihak sekolah.
                Hubungi sekolah jika terdapat informasi yang perlu diperbarui.
            </span>
        </div>

        {{-- TOMBOL KEMBALI --}}
        <div class="os-bottom-actions">
            <a href="{{ route('ortu.dashboard') }}" class="os-back-button">
                <span class="os-back-icon">
                    <i class="bi bi-arrow-left"></i>
                </span>

                <span class="os-back-label">
                    <strong>Kembali</strong>
                    <small>Portal Orang Tua / Wali</small>
                </span>
            </a>
        </div>

    </div>
</div>
@endsection
