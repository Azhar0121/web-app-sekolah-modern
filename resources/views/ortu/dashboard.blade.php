@extends('layouts.app')

@section('title', 'Portal Orang Tua / Wali')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/ortu/dashboard.css') }}">

@include('layouts.partials.announcements-banner')

<div class="ortu-dashboard">


{{-- =====================================================
    HERO
====================================================== --}}
<section class="ortu-hero">

    <div class="ortu-hero-content">

        <div class="ortu-hero-label">
            <i class="bi bi-shield-check"></i>
            <span>PORTAL ORANG TUA / WALI</span>
        </div>

        <h1>
            Selamat datang,
            <span>{{ auth()->user()->name }}</span>
        </h1>

        <p>
            Pantau perkembangan akademik, kehadiran, tagihan,
            dan aktivitas anak Anda dari satu tempat.
        </p>

        <div class="ortu-hero-meta">

            <div class="ortu-meta-item">
                <i class="bi bi-people-fill"></i>

                <span>
                    <strong>{{ $children->count() }}</strong>
                    anak terdaftar
                </span>
            </div>

            @if ($totalPendingLeave > 0)
                <div class="ortu-meta-item">
                    <i class="bi bi-hourglass-split"></i>

                    <span>
                        <strong>{{ $totalPendingLeave }}</strong>
                        pengajuan izin menunggu
                    </span>
                </div>
            @endif

            <div class="ortu-meta-item">
                <i class="bi bi-calendar3"></i>

                <span>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>

        </div>

    </div>

    <div class="ortu-hero-decoration">
        <i class="bi bi-house-heart-fill"></i>
    </div>

</section>


{{-- =====================================================
    AKSES CEPAT
====================================================== --}}
<section class="ortu-section ortu-quick-section">

    <div class="ortu-section-header">

        <div class="ortu-section-title">

            <div class="ortu-section-title-icon">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>

            <div>
                <span class="ortu-section-eyebrow">
                    AKSES CEPAT
                </span>

                <h2>
                    Akses layanan utama
                </h2>

                <p>
                    Pilih layanan yang ingin Anda akses
                </p>
            </div>

        </div>

        <span class="ortu-section-count">
            {{ $children->isNotEmpty() ? 'Layanan tersedia' : '3 layanan tersedia' }}
        </span>

    </div>


    <div class="ortu-quick-actions">

        {{-- Ajukan Izin --}}
        <a
            href="{{ route('ortu.leave-requests.index') }}"
            class="ortu-quick-card"
        >
            <div class="ortu-quick-icon">
                <i class="bi bi-envelope-paper-fill"></i>
            </div>

            <div class="ortu-quick-content">
                <strong>Ajukan Izin</strong>

                <small>
                    Kirim surat izin anak
                </small>
            </div>

            <i class="bi bi-arrow-up-right ortu-card-arrow"></i>
        </a>


        {{-- Konsultasi Guru --}}
        <a
            href="{{ route('ortu.communication.index') }}"
            class="ortu-quick-card"
        >
            <div class="ortu-quick-icon">
                <i class="bi bi-chat-dots-fill"></i>
            </div>

            <div class="ortu-quick-content">
                <strong>Konsultasi Guru</strong>

                <small>
                    Pesan terarah dengan guru
                </small>
            </div>

            <i class="bi bi-arrow-up-right ortu-card-arrow"></i>
        </a>


        {{-- Riwayat Izin --}}
        <a
            href="{{ route('ortu.leave-requests.index') }}"
            class="ortu-quick-card"
        >
            <div class="ortu-quick-icon">
                <i class="bi bi-calendar-check-fill"></i>
            </div>

            <div class="ortu-quick-content">
                <strong>Riwayat Izin</strong>

                <small>
                    Lihat pengajuan izin
                </small>
            </div>

            <i class="bi bi-arrow-up-right ortu-card-arrow"></i>
        </a>


        @if ($children->isNotEmpty())

            {{-- Jadwal --}}
            <a
                href="{{ route('ortu.schedule.index', $children->first()) }}"
                class="ortu-quick-card"
            >
                <div class="ortu-quick-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div class="ortu-quick-content">
                    <strong>Jadwal</strong>

                    <small>
                        Jadwal pelajaran anak
                    </small>
                </div>

                <i class="bi bi-arrow-up-right ortu-card-arrow"></i>
            </a>


            {{-- Presensi --}}
            <a
                href="{{ route('ortu.attendance.index', $children->first()) }}"
                class="ortu-quick-card"
            >
                <div class="ortu-quick-icon">
                    <i class="bi bi-person-check-fill"></i>
                </div>

                <div class="ortu-quick-content">
                    <strong>Presensi</strong>

                    <small>
                        Riwayat kehadiran
                    </small>
                </div>

                <i class="bi bi-arrow-up-right ortu-card-arrow"></i>
            </a>


            {{-- Nilai --}}
            <a
                href="{{ route('ortu.grades.index', $children->first()) }}"
                class="ortu-quick-card"
            >
                <div class="ortu-quick-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>

                <div class="ortu-quick-content">
                    <strong>Nilai</strong>

                    <small>
                        Rapor akademik
                    </small>
                </div>

                <i class="bi bi-arrow-up-right ortu-card-arrow"></i>
            </a>

        @endif

    </div>

</section>


{{-- =====================================================
    DATA ANAK
====================================================== --}}
<section class="ortu-section ortu-student-section">

    <div class="ortu-section-header">

        <div class="ortu-section-title">

            <div class="ortu-section-title-icon">
                <i class="bi bi-person-vcard-fill"></i>
            </div>

            <div>
                <span class="ortu-section-eyebrow">
                    INFORMASI SISWA
                </span>

                <h2>
                    Data Anak
                </h2>

                <p>
                    Informasi dan layanan siswa yang terhubung
                </p>
            </div>

        </div>

        <span class="ortu-section-count">
            {{ $children->count() }}
            {{ $children->count() === 1 ? 'siswa' : 'siswa' }}
        </span>

    </div>


    @if ($children->isEmpty())

        <div class="ortu-empty">

            <div class="ortu-empty-icon">
                <i class="bi bi-person-plus-fill"></i>
            </div>

            <h3>
                Belum ada data anak
            </h3>

            <p>
                Akun Anda belum tertaut ke data siswa.
                Hubungi Tata Usaha sekolah untuk penghubungan akun.
            </p>

        </div>

    @else

        <div class="ortu-children">

            @foreach ($children as $child)

                <article class="child-card">

                    {{-- =================================================
                        CHILD HEADER
                    ================================================== --}}
                    <div class="child-card-header">

                        <div class="child-profile">

                            <div class="child-avatar overflow-hidden">
                                @if ($child->photo_url)
                                    <img src="{{ $child->photo_url }}" alt="{{ $child->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    {{ strtoupper(substr($child->name, 0, 1)) }}
                                @endif
                            </div>

                            <div class="child-info">

                                <h3>
                                    {{ $child->name }}
                                </h3>

                                <span>
                                    <i class="bi bi-building"></i>
                                    {{ $child->classroomForDisplay?->name ?? 'Kelas belum ditentukan' }}
                                </span>

                            </div>

                        </div>


                        <div class="child-badges">

                            @if ($child->unpaidBillingCount > 0)

                                <span class="status-badge status-warning">
                                    <i class="bi bi-exclamation-circle"></i>

                                    {{ $child->unpaidBillingCount }}
                                    tagihan belum lunas
                                </span>

                            @else

                                <span class="status-badge status-success">
                                    <i class="bi bi-check-circle"></i>

                                    Tagihan lunas
                                </span>

                            @endif


                            @if ($child->pendingLeaveCount > 0)

                                <span class="status-badge status-neutral">

                                    {{ $child->pendingLeaveCount }}
                                    izin menunggu

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                        CHILD ACTIONS
                    ================================================== --}}
                    <div class="child-card-body">

                        <div class="child-action-grid">

                            {{-- Jadwal --}}
                            <a
                                href="{{ route('ortu.schedule.index', $child) }}"
                                class="child-action-btn"
                            >
                                <span class="child-action-icon">
                                    <i class="bi bi-calendar3"></i>
                                </span>

                                <span class="child-action-content">
                                    <strong>Jadwal Pelajaran</strong>
                                    <small>Lihat jadwal siswa</small>
                                </span>

                                <i class="bi bi-chevron-right child-action-arrow"></i>
                            </a>


                            {{-- Presensi --}}
                            <a
                                href="{{ route('ortu.attendance.index', $child) }}"
                                class="child-action-btn"
                            >
                                <span class="child-action-icon">
                                    <i class="bi bi-calendar-check-fill"></i>
                                </span>

                                <span class="child-action-content">
                                    <strong>Riwayat Presensi</strong>
                                    <small>Lihat kehadiran siswa</small>
                                </span>

                                <i class="bi bi-chevron-right child-action-arrow"></i>
                            </a>


                            {{-- Nilai --}}
                            <a
                                href="{{ route('ortu.grades.index', $child) }}"
                                class="child-action-btn"
                            >
                                <span class="child-action-icon">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </span>

                                <span class="child-action-content">
                                    <strong>Nilai Rapor</strong>
                                    <small>Lihat perkembangan nilai</small>
                                </span>

                                <i class="bi bi-chevron-right child-action-arrow"></i>
                            </a>


                            {{-- Tagihan --}}
                            <a
                                href="{{ route('ortu.billing.index', $child) }}"
                                class="child-action-btn"
                            >
                                <span class="child-action-icon">
                                    <i class="bi bi-receipt"></i>
                                </span>

                                <span class="child-action-content">
                                    <strong>Status Tagihan</strong>

                                    <small>
                                        @if ($child->unpaidBillingCount > 0)
                                            {{ $child->unpaidBillingCount }}
                                            tagihan perlu diperiksa
                                        @else
                                            Semua tagihan telah lunas
                                        @endif
                                    </small>
                                </span>

                                <i class="bi bi-chevron-right child-action-arrow"></i>
                            </a>


                            {{-- Rapor --}}
                            <a
                                href="{{ route('ortu.report-cards.index', $child) }}"
                                class="child-action-btn"
                            >
                                <span class="child-action-icon">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </span>

                                <span class="child-action-content">
                                    <strong>Rapor Digital</strong>
                                    <small>Lihat dokumen rapor</small>
                                </span>

                                <i class="bi bi-chevron-right child-action-arrow"></i>
                            </a>


                            {{-- Konsultasi --}}
                            <a
                                href="{{ route('ortu.communication.index') }}"
                                class="child-action-btn"
                            >
                                <span class="child-action-icon">
                                    <i class="bi bi-chat-dots-fill"></i>
                                </span>

                                <span class="child-action-content">
                                    <strong>Konsultasi Guru</strong>
                                    <small>Hubungi guru siswa</small>
                                </span>

                                <i class="bi bi-chevron-right child-action-arrow"></i>
                            </a>


                            {{-- Izin --}}
                            <a
                                href="{{ route('ortu.leave-requests.create') }}"
                                class="child-action-btn"
                            >
                                <span class="child-action-icon">
                                    <i class="bi bi-envelope-paper"></i>
                                </span>

                                <span class="child-action-content">
                                    <strong>Ajukan Izin</strong>
                                    <small>Kirim pengajuan izin</small>
                                </span>

                                <i class="bi bi-chevron-right child-action-arrow"></i>
                            </a>

                        </div>

                    </div>

                </article>

            @endforeach

        </div>

    @endif

</section>


</div>

@endsection
