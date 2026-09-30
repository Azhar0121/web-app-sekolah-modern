@extends('layouts.app')

@section('title', 'Kartu Pelajar Digital')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/siswa/qr/show.css') }}">

<div class="student-qr-page">

    <div class="student-qr-wrapper">

        {{-- =====================================================
            MAIN CARD
        ====================================================== --}}
        <div class="student-qr-card">

            {{-- =================================================
                HERO / HEADER
            ================================================== --}}
            <div class="student-qr-header">

                <div class="student-qr-header-pattern"></div>

                <div class="student-qr-header-content">

                    <div class="student-qr-header-top">

                        <div class="student-qr-school">
                            <div class="student-qr-school-icon">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>

                            <div class="student-qr-school-info">
                                <strong>Sekolah Modern</strong>
                                <span>Kartu Pelajar Digital</span>
                            </div>
                        </div>

                        <div class="student-qr-header-actions">

                            <span class="student-qr-badge">
                                <i class="bi bi-person-vcard"></i>
                                ID Card
                            </span>

                        </div>

                    </div>

                    <div class="student-qr-hero-main">

                        <div class="student-qr-hero-label">
                            <span></span>
                            KARTU PELAJAR
                        </div>

                        <div class="student-qr-name-row">

                            <h1>{{ $student->name }}</h1>

                        </div>

                        <div class="student-qr-meta">

                            <span>
                                <i class="bi bi-building"></i>
                                {{ $classroom?->name ?? 'Kelas belum ditentukan' }}
                            </span>

                            @if($activeYear)
                                <span class="student-qr-meta-divider">•</span>

                                <span>
                                    {{ $activeYear->name }}
                                </span>
                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                BODY
            ================================================== --}}
            <div class="student-qr-body">

                {{-- =================================================
                    PROFILE
                ================================================== --}}
                <div class="student-qr-profile">

                    <div class="student-qr-avatar">
                        {{ mb_strtoupper(mb_substr($student->name, 0, 1)) }}
                    </div>

                    <div class="student-qr-profile-info">

                        <span class="student-qr-profile-label">
                            IDENTITAS SISWA
                        </span>

                        <strong>
                            {{ $student->name }}
                        </strong>

                        <span class="student-qr-email">
                            <i class="bi bi-envelope"></i>
                            {{ $student->email }}
                        </span>

                    </div>

                    <div class="student-qr-active-badge">
                        <span></span>
                        Aktif
                    </div>

                </div>


                {{-- =================================================
                    DETAIL SISWA
                ================================================== --}}
                <div class="student-qr-details">

                    <div class="student-qr-detail">

                        <div class="student-qr-detail-icon">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>

                        <div class="student-qr-detail-content">
                            <span>Nomor Induk Siswa</span>

                            <strong>
                                {{ $student->studentProfile?->student_id_number ?? 'Belum diisi' }}
                            </strong>
                        </div>

                    </div>


                    <div class="student-qr-detail">

                        <div class="student-qr-detail-icon">
                            <i class="bi bi-building"></i>
                        </div>

                        <div class="student-qr-detail-content">
                            <span>Kelas / Rombel</span>

                            <strong>
                                {{ $classroom?->name ?? '—' }}
                            </strong>
                        </div>

                    </div>


                    <div class="student-qr-detail">

                        <div class="student-qr-detail-icon">
                            <i class="bi bi-calendar3"></i>
                        </div>

                        <div class="student-qr-detail-content">
                            <span>Tahun Ajaran</span>

                            <strong>
                                {{ $activeYear?->name ?? '—' }}
                            </strong>
                        </div>

                    </div>


                    <div class="student-qr-detail">

                        <div class="student-qr-detail-icon green">
                            <i class="bi bi-person-check-fill"></i>
                        </div>

                        <div class="student-qr-detail-content">
                            <span>Status</span>

                            <strong class="status-active">
                                Siswa Aktif
                            </strong>
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    REKAP KEHADIRAN
                ================================================== --}}
                <div class="student-qr-attendance">

                    <div class="student-qr-attendance-header">

                        <div class="student-qr-attendance-title">

                            <div class="student-qr-attendance-icon">
                                <i class="bi bi-calendar2-check-fill"></i>
                            </div>

                            <div>
                                <span>REKAP KEHADIRAN</span>
                                <strong>Kehadiran Saya</strong>
                            </div>

                        </div>

                    </div>


                    <div class="student-qr-attendance-grid">

                        <div class="student-qr-attendance-item present">

                            <div class="student-qr-attendance-item-top">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>HADIR</span>
                            </div>

                            <strong>
                                {{ $attendanceSummary['hadir'] }}
                            </strong>

                            <small>
                                Kehadiran
                            </small>

                        </div>


                        <div class="student-qr-attendance-item alpha">

                            <div class="student-qr-attendance-item-top">
                                <i class="bi bi-x-circle-fill"></i>
                                <span>ALPHA</span>
                            </div>

                            <strong>
                                {{ $attendanceSummary['alpha'] }}
                            </strong>

                            <small>
                                Tanpa keterangan
                            </small>

                        </div>


                        <div class="student-qr-attendance-item permit">

                            <div class="student-qr-attendance-item-top">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span>IZIN</span>
                            </div>

                            <strong>
                                {{ $attendanceSummary['izin'] }}
                            </strong>

                            <small>
                                Dengan izin
                            </small>

                        </div>


                        <div class="student-qr-attendance-item sick">

                            <div class="student-qr-attendance-item-top">
                                <i class="bi bi-heart-pulse-fill"></i>
                                <span>SAKIT</span>
                            </div>

                            <strong>
                                {{ $attendanceSummary['sakit'] }}
                            </strong>

                            <small>
                                Keterangan sakit
                            </small>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACTION BUTTONS
                ================================================== --}}
                <div class="student-qr-actions">

                    <a
                        href="{{ route('siswa.attendance.scan') }}"
                        class="student-qr-button primary"
                    >
                        <i class="bi bi-qr-code-scan"></i>
                        <span>Scan Presensi</span>
                    </a>

                    <a
                        href="{{ route('siswa.attendance.index') }}"
                        class="student-qr-button secondary"
                    >
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat Presensi</span>
                    </a>

                </div>

            </div>


            {{-- =================================================
                FOOTER
            ================================================== --}}
            <div class="student-qr-footer">

                <div class="student-qr-footer-status">
                    <span class="student-qr-status-dot"></span>
                    <strong>Kartu Aktif</strong>
                </div>

                <a href="{{ route('siswa.dashboard') }}" class="student-qr-back">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali</span>
                </a>

            </div>

        </div>

    </div>

</div>

@endsection