@extends('layouts.app')

@section('title', 'Dashboard Guru / Wali Kelas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/dashboard.css') }}">

<div class="guru-dashboard">

{{-- =========================
     HERO / WELCOME
     ========================= --}}
<section class="guru-hero">

    <div class="guru-hero-main">
        <div class="guru-hero-content">

            <span class="guru-hero-label">
                PORTAL GURU & WALI KELAS
            </span>

            <h1>
                Selamat datang,
                <span>{{ auth()->user()->name }}</span>
            </h1>

            <p>
                Kelola aktivitas mengajar, presensi, materi,
                dan tugas kelas Anda dari satu tempat.
            </p>

            @if ($activeYear)
                <div class="guru-year-info">
                    <div class="guru-year-icon">
                        A
                    </div>

                    <div>
                        <small>TAHUN AJARAN AKTIF</small>
                        <strong>{{ $activeYear->name }}</strong>
                    </div>
                </div>
            @else
                <div class="guru-year-warning">
                    <strong>Belum ada tahun ajaran aktif</strong>
                    <span>Hubungi Super Admin untuk pengaturan tahun ajaran.</span>
                </div>
            @endif

        </div>

        <div class="guru-hero-illustration">
            <div class="guru-orbit guru-orbit-one"></div>
            <div class="guru-orbit guru-orbit-two"></div>

            <div class="guru-dot guru-dot-one"></div>
            <div class="guru-dot guru-dot-two"></div>
            <div class="guru-dot guru-dot-three"></div>
            <div class="guru-dot guru-dot-four"></div>

            <div class="guru-teacher-shape">
                <div class="guru-circle-decoration">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="guru-quick-actions">

        <a href="{{ route('guru.schedule.index') }}" class="guru-quick-card">
            <div class="guru-quick-icon schedule-icon">
                <span>▣</span>
            </div>

            <div class="guru-quick-text">
                <small>AKADEMIK</small>
                <strong>Jadwal Mengajar</strong>
                <span>Lihat jadwal Anda</span>
            </div>

            <div class="guru-quick-arrow">
                →
            </div>
        </a>

        <a href="{{ route('guru.attendance.index') }}" class="guru-quick-card">
            <div class="guru-quick-icon attendance-icon">
                <span>✓</span>
            </div>

            <div class="guru-quick-text">
                <small>KEHADIRAN</small>
                <strong>Presensi Kelas</strong>
                <span>Kelola presensi siswa</span>
            </div>

            <div class="guru-quick-arrow">
                →
            </div>
        </a>

    </div>

</section>


{{-- =========================
     ASSIGNMENT SECTION
     ========================= --}}
<section class="guru-assignment-section">

    <div class="guru-section-heading">

        <div>
            <span class="guru-section-label">
                AKTIVITAS MENGAJAR
            </span>

            <h2>
                Kelas & Mata Pelajaran
            </h2>

            <p>
                Daftar kelas dan mata pelajaran yang Anda ampu.
            </p>
        </div>

        <div class="guru-assignment-count">
            <strong>{{ $assignments->count() }}</strong>
            <span>Penugasan</span>
        </div>

    </div>


    <div class="guru-assignment-card">

        <div class="guru-table-header">
            <div>Kelas</div>
            <div>Mata Pelajaran</div>
            <div class="guru-table-action-title">Aksi</div>
        </div>

        <div class="guru-assignment-list">

            @forelse ($assignments as $assignment)

                <div class="guru-assignment-row">

                    <div class="guru-class-cell">
                        <div class="guru-class-avatar">
                            {{ strtoupper(substr($assignment->classroom->name, 0, 1)) }}
                        </div>

                        <div>
                            <strong>
                                {{ $assignment->classroom->name }}
                            </strong>

                            <small>
                                Kelas yang diampu
                            </small>
                        </div>
                    </div>


                    <div class="guru-subject-cell">
                        <span class="guru-subject-dot"></span>

                        <span>
                            {{ $assignment->subject->name }}
                        </span>
                    </div>


                    <div class="guru-row-actions">

                        <a href="{{ route('guru.teaching-assignments.materials.index', $assignment) }}"
                           class="guru-material-btn">
                            <span>Materi</span>
                            <b>→</b>
                        </a>

                        <a href="{{ route('guru.teaching-assignments.tasks.index', $assignment) }}"
                           class="guru-task-btn">
                            <span>Tugas</span>
                            <b>→</b>
                        </a>

                    </div>

                </div>

            @empty

                <div class="guru-empty-state">

                    <div class="guru-empty-icon">
                        !
                    </div>

                    <h3>
                        Belum Ada Penugasan Mengajar
                    </h3>

                    <p>
                        Anda belum ditugaskan mengajar kelas/mapel apa pun
                        pada tahun ajaran ini.
                    </p>

                    <span>
                        Hubungi Super Admin untuk pengaturan penugasan mengajar.
                    </span>

                </div>

            @endforelse

        </div>

    </div>

</section>

</div>

@endsection
