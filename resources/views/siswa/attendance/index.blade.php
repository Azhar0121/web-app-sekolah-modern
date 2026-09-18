@extends('layouts.app')

@section('title', 'Riwayat Presensi')

@section('content')

{{-- Bootstrap Icons --}}
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- Plus Jakarta Sans --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
      rel="stylesheet">

{{-- CSS halaman --}}
<link rel="stylesheet"
      href="{{ asset('css/siswa/attendance/index.css') }}">

<div class="student-attendance-page">

    {{-- =====================================================
         HEADER
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

            <div class="attendance-header-text">

                <h4>RIWAYAT PRESENSI</h4>

                <p>
                    Rekap kehadiran Anda dari seluruh sesi yang sudah tercatat.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
         REKAP PRESENSI
    ====================================================== --}}
    <div class="attendance-recap">

        {{-- HADIR --}}
        <div class="recap-card recap-hadir">

            <div class="recap-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <div class="recap-number">
                {{ $recap['hadir'] }}
            </div>

            <div class="recap-label">
                Hadir
            </div>

        </div>


        {{-- IZIN --}}
        <div class="recap-card recap-izin">

            <div class="recap-icon">
                <i class="bi bi-envelope-paper"></i>
            </div>

            <div class="recap-number">
                {{ $recap['izin'] }}
            </div>

            <div class="recap-label">
                Izin
            </div>

        </div>


        {{-- SAKIT --}}
        <div class="recap-card recap-sakit">

            <div class="recap-icon">
                <i class="bi bi-heart-pulse"></i>
            </div>

            <div class="recap-number">
                {{ $recap['sakit'] }}
            </div>

            <div class="recap-label">
                Sakit
            </div>

        </div>


        {{-- ALPHA --}}
        <div class="recap-card recap-alpha">

            <div class="recap-icon">
                <i class="bi bi-x-lg"></i>
            </div>

            <div class="recap-number">
                {{ $recap['alpha'] }}
            </div>

            <div class="recap-label">
                Alpha
            </div>

        </div>

    </div>


    {{-- =====================================================
         RIWAYAT PRESENSI PER TANGGAL
    ====================================================== --}}
    @forelse ($attendances as $date => $items)

        <div class="attendance-date-card">

            <div class="date-decoration date-decoration-one"></div>
            <div class="date-decoration date-decoration-two"></div>


            {{-- HEADER TANGGAL --}}
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


            {{-- TABEL --}}
            <div class="table-responsive">

                <table class="table align-middle attendance-table mb-0">

                    <thead>

                        <tr>

                            <th>
                                <i class="bi bi-book-fill"></i>
                                <span>Mata Pelajaran</span>
                            </th>

                            <th style="width: 145px;">
                                <i class="bi bi-check2-circle"></i>
                                <span>Status</span>
                            </th>

                            <th style="width: 130px;">
                                <i class="bi bi-clock-fill"></i>
                                <span>Waktu Scan</span>
                            </th>

                        </tr>

                    </thead>


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
                                <td style="width: 145px;">

                                    <span class="badge attendance-status {{ $attendance->statusBadgeClass() }}">
                                        {{ $attendance->statusLabel() }}
                                    </span>

                                </td>


                                {{-- WAKTU SCAN --}}
                                <td style="width: 130px;">

                                    <div class="scan-time">

                                        <i class="bi bi-clock-fill"></i>

                                        <span>
                                            {{ $attendance->scanned_at?->format('H:i') ?? '-' }}
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @empty

        {{-- EMPTY STATE --}}
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
         KEMBALI
    ====================================================== --}}
    <div class="attendance-back-bottom">

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