@extends('layouts.admin')

@section('title', 'Kelola Presensi — ' . $schedule->teachingAssignment->subject->name)

@section('content')
<link rel="stylesheet" href="{{ asset('css/guru/attendance/session.css') }}">

<div class="guru-attendance-session">

    {{-- HERO --}}
    <div class="session-page-header">

        <div class="session-header-content">

            <div class="session-header-icon">
                <i class="bi bi-calendar-check-fill"></i>
            </div>

            <div class="session-header-info">

                <div class="session-header-label">
                    PRESENSI KELAS
                </div>

                <h1>
                    {{ $schedule->teachingAssignment->subject->name }}
                </h1>

                <div class="session-header-meta">

                    <span>
                        <i class="bi bi-people-fill"></i>
                        {{ $schedule->teachingAssignment->classroom->name }}
                    </span>

                    <span class="session-meta-separator">•</span>

                    <span>
                        <i class="bi bi-calendar3"></i>
                        {{ $attendanceSession->date->translatedFormat('l, d F Y') }}
                    </span>

                    <span class="session-meta-separator">•</span>

                    <span>
                        <i class="bi bi-clock"></i>
                        {{ $schedule->start_time->format('H:i') }}
                        –
                        {{ $schedule->end_time->format('H:i') }}
                    </span>

                </div>

            </div>

        </div>


        {{-- STATUS --}}
        @if ($attendanceSession->isOpen())

            <div class="session-status active">

                <span class="session-status-dot"></span>

                <span>Sesi Berlangsung</span>

            </div>

        @else

            <div class="session-status closed">

                <i class="bi bi-stop-circle-fill"></i>

                <span>Sesi Selesai</span>

            </div>

        @endif


        <div class="session-hero-decoration one"></div>
        <div class="session-hero-decoration two"></div>

    </div>


    {{-- =====================================================
        SESI MASIH TERBUKA
    ====================================================== --}}

    @if ($attendanceSession->isOpen())

        <div class="session-open-grid">

            {{-- QR PANEL --}}
            <div class="session-qr-card">

                <div class="session-card-header">

                    <div class="session-card-title">

                        <div class="session-section-icon">
                            <i class="bi bi-qr-code"></i>
                        </div>

                        <div>

                            <div class="session-card-eyebrow">
                                PRESENSI DIGITAL
                            </div>

                            <h2>
                                QR Presensi Kelas
                            </h2>

                            <p>
                                Tampilkan QR kepada siswa untuk melakukan scan.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="session-qr-body">

                    <div class="session-qr-visual">

                        <div class="session-qr-icon">
                            <i class="bi bi-qr-code"></i>
                        </div>

                    </div>


                    <h3>
                        Scan QR untuk Presensi
                    </h3>

                    <p class="session-qr-description">
                        Tampilkan <strong>QR besar</strong> yang dapat
                        diproyeksikan ke papan tulis. Siswa dapat memindai
                        QR menggunakan HP masing-masing.
                    </p>


                    <a
                        href="{{ route('guru.attendance.show-qr', $attendanceSession) }}"
                        class="session-show-qr-button"
                    >
                        <i class="bi bi-fullscreen"></i>
                        <span>Tampilkan QR Presensi</span>
                    </a>


                    <div class="session-qr-note">

                        <i class="bi bi-shield-check"></i>

                        <span>
                            QR berlaku 5 menit dan dapat diperbarui.
                        </span>

                    </div>

                </div>

            </div>


            {{-- DAFTAR SISWA --}}
            <div class="session-roster-card">

                <div class="session-roster-heading">

                    <div class="session-roster-heading-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div>

                        <div class="session-card-eyebrow">
                            KEHADIRAN SISWA
                        </div>

                        <h2>
                            Daftar Siswa
                        </h2>

                        <p>
                            Pantau status kehadiran siswa pada sesi ini.
                        </p>

                    </div>

                </div>


                <div class="session-roster-content">
                    @include('guru.attendance.partials.roster')
                </div>

            </div>

        </div>


        {{-- TUTUP SESI --}}
        <div class="session-close-area">

            <div class="session-close-info">

                <div class="session-close-icon">
                    <i class="bi bi-info-circle"></i>
                </div>

                <div>

                    <strong>
                        Sesi presensi masih berlangsung
                    </strong>

                    <span>
                        Tutup sesi setelah proses presensi selesai.
                    </span>

                </div>

            </div>


            <form
                method="POST"
                action="{{ route('guru.attendance.close', $attendanceSession) }}"
                onsubmit="return confirm('Tutup sesi presensi? Siswa yang belum tercatat akan otomatis ditandai Alpha.');"
            >
                @csrf

                <button
                    type="submit"
                    class="session-close-button"
                >
                    <i class="bi bi-stop-circle"></i>
                    <span>Tutup Sesi Presensi</span>
                </button>

            </form>

        </div>


    {{-- =====================================================
        SESI SUDAH DITUTUP
    ====================================================== --}}

    @else

        {{-- INFO CLOSED --}}
        <div class="session-closed-alert">

            <div class="session-closed-alert-icon">
                <i class="bi bi-info-circle-fill"></i>
            </div>

            <div class="session-closed-alert-content">

                <strong>
                    Sesi presensi telah ditutup.
                </strong>

                <p>
                    Anda masih dapat mengedit status kehadiran siswa
                    secara manual pada tabel di bawah ini.
                    Klik <strong>Buka Kembali Sesi</strong> untuk
                    mengaktifkan QR kembali.
                </p>

            </div>

        </div>


        {{-- ROSTER --}}
        <div class="session-roster-card session-roster-closed">

            <div class="session-roster-heading">

                <div class="session-roster-heading-icon">
                    <i class="bi bi-clipboard-check-fill"></i>
                </div>

                <div>

                    <div class="session-card-eyebrow">
                        REKAP KEHADIRAN
                    </div>

                    <h2>
                        Daftar Siswa
                    </h2>

                    <p>
                        Periksa dan edit status kehadiran siswa secara manual.
                    </p>

                </div>

            </div>


            <div class="session-roster-content">
                @include('guru.attendance.partials.roster')
            </div>

        </div>


        {{-- BUKA KEMBALI --}}
        <div class="session-reopen-area">

            <form
                method="POST"
                action="{{ route('guru.attendance.reopen', $attendanceSession) }}"
                onsubmit="return confirm('Buka kembali sesi presensi ini?');"
            >
                @csrf

                <button
                    type="submit"
                    class="session-reopen-button"
                >
                    <i class="bi bi-play-circle"></i>
                    <span>Buka Kembali Sesi</span>
                </button>

            </form>

        </div>

    @endif


    {{-- BACK --}}
    <div class="session-back-area">

        <a
            href="{{ route('guru.attendance.index') }}"
            class="session-back-button"
        >
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Presensi Kelas</span>
        </a>

    </div>

</div>
@endsection