@extends('layouts.app')

@section('title', 'Riwayat Presensi')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/siswa/attendance/index.css') }}">

<div class="student-attendance-page">

    <div class="student-attendance-wrapper">

        {{-- =====================================================
            HERO
        ====================================================== --}}
        <div class="student-attendance-hero">

            <div class="student-attendance-hero-content">

                <div class="student-attendance-hero-label">
                    <span class="student-attendance-hero-dot"></span>
                    PRESENSI SISWA
                </div>

                <h1>Riwayat Presensi</h1>

                <p>
                    Lihat rekap dan riwayat kehadiran kamu selama kegiatan belajar.
                </p>

            </div>

            <a href="{{ route('siswa.dashboard') }}"
               class="student-attendance-hero-back">

                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>

            </a>

        </div>


        {{-- =====================================================
            SCAN PRESENSI
        ====================================================== --}}
        {{-- SCAN PRESENSI --}}
        <a href="{{ route('siswa.attendance.scan') }}"
        class="student-attendance-scan">

            <div class="student-attendance-scan-icon">
                <i class="bi bi-qr-code-scan"></i>
            </div>

            <div class="student-attendance-scan-content">

                <strong>Scan Presensi Sekarang</strong>

                <span>
                    Gunakan QR Code untuk mencatat kehadiran
                </span>

            </div>

            <div class="student-attendance-scan-action">

                <span>Mulai Scan</span>

                <i class="bi bi-arrow-right"></i>

            </div>

        </a>


        {{-- =====================================================
            REKAP KEHADIRAN
        ====================================================== --}}
        <div class="student-attendance-summary-card">

            <div class="student-attendance-section-heading">

                <div>

                    <span>RINGKASAN</span>

                    <h2>Rekap Kehadiran</h2>

                </div>

            </div>


            <div class="student-attendance-stats">

                {{-- HADIR --}}
                <div class="student-attendance-stat present">

                    <div class="student-attendance-stat-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <div class="student-attendance-stat-info">

                        <span>HADIR</span>

                        <strong>{{ $recap['hadir'] }}</strong>

                        <small>Kehadiran</small>

                    </div>

                </div>


                {{-- IZIN --}}
                <div class="student-attendance-stat permit">

                    <div class="student-attendance-stat-icon">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>

                    <div class="student-attendance-stat-info">

                        <span>IZIN</span>

                        <strong>{{ $recap['izin'] }}</strong>

                        <small>Dengan izin</small>

                    </div>

                </div>


                {{-- SAKIT --}}
                <div class="student-attendance-stat sick">

                    <div class="student-attendance-stat-icon">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>

                    <div class="student-attendance-stat-info">

                        <span>SAKIT</span>

                        <strong>{{ $recap['sakit'] }}</strong>

                        <small>Keterangan sakit</small>

                    </div>

                </div>


                {{-- ALPHA --}}
                <div class="student-attendance-stat alpha">

                    <div class="student-attendance-stat-icon">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>

                    <div class="student-attendance-stat-info">

                        <span>ALPHA</span>

                        <strong>{{ $recap['alpha'] }}</strong>

                        <small>Tanpa keterangan</small>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            RIWAYAT KEHADIRAN
        ====================================================== --}}
        <div class="student-attendance-history-card">

            <div class="student-attendance-history-heading">

                <div class="student-attendance-history-heading-icon">
                    <i class="bi bi-calendar2-check-fill"></i>
                </div>

                <div class="student-attendance-history-heading-content">

                    <span>AKTIVITAS PRESENSI</span>

                    <h2>Riwayat Kehadiran</h2>

                    <p>
                        Daftar kehadiran berdasarkan tanggal dan mata pelajaran.
                    </p>

                </div>

            </div>


            {{-- =================================================
                RIWAYAT PER TANGGAL
            ================================================== --}}
            @forelse ($attendances as $date => $items)

                <div class="student-attendance-history">

                    {{-- DATE HEADER --}}
                    <div class="student-attendance-date">

                        <div class="student-attendance-date-icon">
                            <i class="bi bi-calendar3"></i>
                        </div>

                        <div class="student-attendance-date-info">

                            <span>TANGGAL PRESENSI</span>

                            <strong>
                                {{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                            </strong>

                        </div>

                    </div>


                    {{-- TABLE --}}
                    <div class="table-responsive">

                        <table class="student-attendance-table">

                            <thead>

                                <tr>

                                    <th>
                                        Mata Pelajaran
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Waktu Scan
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($items as $attendance)

                                    <tr>

                                        {{-- MATA PELAJARAN --}}
                                        <td>

                                            <div class="student-attendance-subject">

                                                <div class="student-attendance-subject-icon">
                                                    <i class="bi bi-book-fill"></i>
                                                </div>

                                                <div class="student-attendance-subject-info">

                                                    <strong>
                                                        {{ $attendance->session->schedule->teachingAssignment->subject->name }}
                                                    </strong>

                                                    <span>
                                                        Mata Pelajaran
                                                    </span>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- STATUS --}}
                                        <td>

                                            <span class="student-attendance-status {{ $attendance->statusBadgeClass() }}">
                                                {{ $attendance->statusLabel() }}
                                            </span>

                                        </td>


                                        {{-- WAKTU SCAN --}}
                                        <td>

                                            <span class="student-attendance-time">

                                                <i class="bi bi-clock"></i>

                                                {{ $attendance->scanned_at?->format('H:i') ?? '—' }}

                                            </span>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @empty

                {{-- EMPTY STATE --}}
                <div class="student-attendance-empty">

                    <div class="student-attendance-empty-icon">
                        <i class="bi bi-calendar-x"></i>
                    </div>

                    <h3>Belum Ada Catatan Presensi</h3>

                    <p>
                        Belum ada riwayat kehadiran yang tercatat.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>


{{-- =========================================================
    MOBILE FAB
========================================================= --}}
<a href="{{ route('siswa.attendance.scan') }}"
   class="student-attendance-fab">

    <i class="bi bi-qr-code-scan"></i>

    <span>Scan</span>

</a>

@endsection