@extends('layouts.app')

@section('title', 'Riwayat Presensi — ' . $student->name)

@section('content')
<link rel="stylesheet" href="{{ asset('css/ortu/attendance/index.css') }}">

<div class="ortu-attendance-history">

    {{-- HERO --}}
    <section class="ah-hero">
        <div class="ah-hero-content">
            <div class="ah-eyebrow">
                <i class="bi bi-calendar2-check"></i>
                PORTAL ORANG TUA / WALI
            </div>

            <h1>Riwayat Presensi</h1>

            <p>
                Pantau rekap kehadiran
                <strong>{{ $student->name }}</strong>
                dari seluruh sesi pembelajaran yang sudah tercatat.
            </p>

            <div class="ah-hero-meta">
                <span>
                    <i class="bi bi-person-check"></i>
                    Data kehadiran siswa
                </span>
                <span>
                    <i class="bi bi-journal-check"></i>
                    Rekap seluruh sesi
                </span>
            </div>
        </div>

        <div class="ah-hero-decoration">
            <i class="bi bi-calendar2-week"></i>
        </div>
    </section>

    {{-- RINGKASAN PRESENSI --}}
    <section class="ah-summary-section">
        <div class="ah-section-heading">
            <div>
                <span class="ah-section-label">RINGKASAN KEHADIRAN</span>
                <h2>Statistik Presensi</h2>
                <p>Jumlah catatan berdasarkan status kehadiran siswa.</p>
            </div>
            <div class="ah-heading-icon">
                <i class="bi bi-bar-chart-line"></i>
            </div>
        </div>

        <div class="ah-summary-grid">
            <article class="ah-summary-card ah-hadir">
                <div class="ah-summary-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="ah-summary-info">
                    <span class="ah-summary-label">Hadir</span>
                    <strong>{{ $recap['hadir'] }}</strong>
                    <small>Sesi kehadiran</small>
                </div>
                <div class="ah-summary-watermark">
                    <i class="bi bi-check-circle"></i>
                </div>
            </article>

            <article class="ah-summary-card ah-izin">
                <div class="ah-summary-icon">
                    <i class="bi bi-envelope-check-fill"></i>
                </div>
                <div class="ah-summary-info">
                    <span class="ah-summary-label">Izin</span>
                    <strong>{{ $recap['izin'] }}</strong>
                    <small>Sesi izin</small>
                </div>
                <div class="ah-summary-watermark">
                    <i class="bi bi-envelope-check"></i>
                </div>
            </article>

            <article class="ah-summary-card ah-sakit">
                <div class="ah-summary-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div class="ah-summary-info">
                    <span class="ah-summary-label">Sakit</span>
                    <strong>{{ $recap['sakit'] }}</strong>
                    <small>Sesi sakit</small>
                </div>
                <div class="ah-summary-watermark">
                    <i class="bi bi-heart-pulse"></i>
                </div>
            </article>

            <article class="ah-summary-card ah-alpha">
                <div class="ah-summary-icon">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="ah-summary-info">
                    <span class="ah-summary-label">Alpha</span>
                    <strong>{{ $recap['alpha'] }}</strong>
                    <small>Tanpa keterangan</small>
                </div>
                <div class="ah-summary-watermark">
                    <i class="bi bi-x-circle"></i>
                </div>
            </article>
        </div>
    </section>

    {{-- DAFTAR RIWAYAT --}}
    <section class="ah-history-section">
        <div class="ah-section-heading ah-history-heading">
            <div>
                <span class="ah-section-label">CATATAN PEMBELAJARAN</span>
                <h2>Detail Riwayat Kehadiran</h2>
                <p>Presensi dikelompokkan berdasarkan tanggal pembelajaran.</p>
            </div>

            <div class="ah-heading-icon">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>

        @forelse ($attendances as $date => $items)
            <article class="ah-date-card">

                <div class="ah-date-header">
                    <div class="ah-date-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>

                    <div class="ah-date-info">
                        <span class="ah-date-label">TANGGAL PRESENSI</span>
                        <h3>
                            {{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                        </h3>
                    </div>

                    <div class="ah-session-count">
                        <i class="bi bi-journal-check"></i>
                        {{ $items->count() }} sesi
                    </div>
                </div>

                <div class="table-responsive ah-table-responsive">
                    <table class="table ah-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ah-number-column">NO.</th>
                                <th>MATA PELAJARAN</th>
                                <th>STATUS KEHADIRAN</th>
                                <th>WAKTU SCAN</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($items as $attendance)
                                <tr>
                                    <td class="ah-number-column">
                                        <span class="ah-row-number">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="ah-subject">
                                            <span class="ah-subject-icon">
                                                <i class="bi bi-book"></i>
                                            </span>
                                            <span class="ah-subject-name">
                                                {{ $attendance->session->schedule->teachingAssignment->subject->name }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge ah-status-badge {{ $attendance->statusBadgeClass() }}">
                                            {{ $attendance->statusLabel() }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="ah-scan-time">
                                            <i class="bi bi-clock"></i>
                                            {{ $attendance->scanned_at?->format('H:i') ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
        @empty
            <div class="ah-empty-state">
                <div class="ah-empty-icon">
                    <i class="bi bi-calendar2-x"></i>
                </div>

                <h3>Belum Ada Catatan Presensi</h3>

                <p>
                    Belum ada catatan presensi untuk
                    <strong>{{ $student->name }}</strong>.
                    Riwayat kehadiran akan ditampilkan di sini setelah tersedia.
                </p>
            </div>
        @endforelse
    </section>

    {{-- KEMBALI --}}
    <div class="ah-footer">
        <a href="{{ route('ortu.dashboard') }}" class="ah-back-button">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>

        <span class="ah-footer-note">
            <i class="bi bi-shield-check"></i>
            Informasi kehadiran siswa
        </span>
    </div>

</div>
@endsection
