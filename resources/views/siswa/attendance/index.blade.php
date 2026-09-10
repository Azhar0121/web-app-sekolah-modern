@extends('layouts.app')

@section('title', 'Riwayat Presensi')

@section('content')

{{-- Bootstrap Icons --}}
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- CSS halaman --}}
<link rel="stylesheet"
      href="{{ asset('css/siswa/attendance/index.css') }}">

<div class="student-attendance-page">

    {{-- =====================================================
        HEADER RIWAYAT PRESENSI
    ====================================================== --}}

    <div class="attendance-header mb-4">

        <div class="attendance-header-decoration decoration-one"></div>
        <div class="attendance-header-decoration decoration-two"></div>

        <div class="attendance-header-dot dot-one"></div>
        <div class="attendance-header-dot dot-two"></div>

        <div class="attendance-header-content">

            <div class="attendance-header-icon">
                <i class="bi bi-calendar-check-fill"></i>
            </div>

            <div>
                <h4>RIWAYAT PRESENSI</h4>

                <p class="mb-0">
                    Rekap kehadiran Anda dari seluruh sesi yang sudah tercatat.
                </p>
            </div>

        </div>

    </div>


    {{-- =====================================================
        REKAP PRESENSI
    ====================================================== --}}

    <div class="row g-3 mb-4 attendance-recap">

        {{-- HADIR --}}
        <div class="col-6 col-md-3">

            <div class="recap-card recap-hadir">

                <div class="recap-shape"></div>

                <div class="recap-icon">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div class="recap-content">

                    <div class="recap-number">
                        {{ $recap['hadir'] }}
                    </div>

                    <div class="recap-label">
                        Hadir
                    </div>

                </div>

                <div class="recap-mini-icon">
                    <i class="bi bi-person-check-fill"></i>
                </div>

            </div>

        </div>


        {{-- IZIN --}}
        <div class="col-6 col-md-3">

            <div class="recap-card recap-izin">

                <div class="recap-shape"></div>

                <div class="recap-icon">
                    <i class="bi bi-envelope-check-fill"></i>
                </div>

                <div class="recap-content">

                    <div class="recap-number">
                        {{ $recap['izin'] }}
                    </div>

                    <div class="recap-label">
                        Izin
                    </div>

                </div>

                <div class="recap-mini-icon">
                    <i class="bi bi-envelope-paper-fill"></i>
                </div>

            </div>

        </div>


        {{-- SAKIT --}}
        <div class="col-6 col-md-3">

            <div class="recap-card recap-sakit">

                <div class="recap-shape"></div>

                <div class="recap-icon">
                    <i class="bi bi-bandaid-fill"></i>
                </div>

                <div class="recap-content">

                    <div class="recap-number">
                        {{ $recap['sakit'] }}
                    </div>

                    <div class="recap-label">
                        Sakit
                    </div>

                </div>

                <div class="recap-mini-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>

            </div>

        </div>


        {{-- ALPHA --}}
        <div class="col-6 col-md-3">

            <div class="recap-card recap-alpha">

                <div class="recap-shape"></div>

                <div class="recap-icon">
                    <i class="bi bi-x-lg"></i>
                </div>

                <div class="recap-content">

                    <div class="recap-number">
                        {{ $recap['alpha'] }}
                    </div>

                    <div class="recap-label">
                        Alpha
                    </div>

                </div>

                <div class="recap-mini-icon">
                    <i class="bi bi-person-x-fill"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        RIWAYAT PER TANGGAL
    ====================================================== --}}

    @forelse ($attendances as $date => $items)

        <div class="attendance-date-card mb-3">

            {{-- Dekorasi --}}
            <div class="date-decoration date-decoration-one"></div>
            <div class="date-decoration date-decoration-two"></div>


            {{-- =================================================
                HEADER TANGGAL
            ================================================== --}}

            <div class="attendance-date-header">

                <div class="attendance-date-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div class="attendance-date-info">

                    <span class="date-label">
                        TANGGAL PRESENSI
                    </span>

                    <span class="date-value">
                        {{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                    </span>

                </div>

                <div class="date-small-decoration">
                    <i class="bi bi-calendar-check"></i>
                </div>

            </div>


            {{-- =================================================
                TABEL PRESENSI
            ================================================== --}}

            <div class="table-responsive">

                <table class="table table-hover align-middle attendance-table mb-0">

                    {{-- HEADER TABEL --}}
                    <thead>
                        <tr>
                            <th>
                                Mata Pelajaran
                            </th>

                            <th style="width: 130px;">
                                Status
                            </th>

                            <th style="width: 120px;">
                                Waktu Scan
                            </th>
                        </tr>
                    </thead>


                    {{-- ISI TABEL --}}
                    <tbody>

                        @foreach ($items as $attendance)

                            <tr>

                                {{-- MATA PELAJARAN --}}
                                <td>

                                    <div class="subject-cell">

                                        <div class="subject-icon">
                                            <i class="bi bi-book-fill"></i>
                                        </div>

                                        <div class="subject-name">
                                            {{ $attendance->session->schedule->teachingAssignment->subject->name }}
                                        </div>

                                    </div>

                                </td>


                                {{-- STATUS --}}
                                <td style="width: 130px;">

                                    <span class="badge attendance-status {{ $attendance->statusBadgeClass() }}">
                                        {{ $attendance->statusLabel() }}
                                    </span>

                                </td>


                                {{-- WAKTU SCAN --}}
                                <td style="width: 120px;">

                                    <div class="scan-time">

                                        <i class="bi bi-clock-fill"></i>

                                        {{ $attendance->scanned_at?->format('H:i') ?? '-' }}

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @empty

        {{-- =================================================
            EMPTY STATE
        ================================================== --}}

        <div class="attendance-empty">

            <div class="empty-decoration empty-decoration-one"></div>
            <div class="empty-decoration empty-decoration-two"></div>

            <div class="empty-icon">
                <i class="bi bi-calendar-x"></i>
            </div>

            <h5>
                Belum Ada Catatan Presensi
            </h5>

            <p>
                Belum ada riwayat kehadiran yang tercatat.
            </p>

        </div>

    @endforelse


    {{-- =====================================================
        KEMBALI KE DASHBOARD
        DITARUH DI BAGIAN PALING BAWAH
    ====================================================== --}}

    <div class="attendance-back-bottom">

        <a href="{{ route('siswa.dashboard') }}" class="back-dashboard">

            <i class="bi bi-arrow-left"></i>

            <span>
                Kembali
            </span>

        </a>

    </div>

</div>

@endsection

