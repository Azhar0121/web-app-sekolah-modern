@extends('layouts.admin')

@section('title', 'Dashboard Tata Usaha')

@section('content')
@include('layouts.partials.announcements-banner')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-1">Dashboard Tata Usaha</h4>
        <p class="text-muted mb-0">Selamat datang, <strong>{{ auth()->user()->name }}</strong>.</p>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/tu/dashboard.css') }}">

<div class="tu-dashboard">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="tu-header">

        <div class="tu-header-content">

            <span class="tu-eyebrow">
                PORTAL TATA USAHA
            </span>

            <h1>
                Dashboard Tata Usaha
            </h1>

            <p>
                Selamat datang,
                <strong>{{ auth()->user()->name }}</strong>.
                Kelola administrasi sekolah melalui dashboard ini.
            </p>

        </div>

        <div class="tu-header-icon">
            <x-icon name="layout-dashboard" :size="28" />
        </div>

    </div>


    {{-- =====================================================
        PPDB
    ====================================================== --}}
    <section class="tu-section">

        <div class="tu-section-heading">

            <div>
                <span class="tu-section-label">
                    PPDB
                </span>

                <h2>
                    Ringkasan Pendaftaran
                </h2>
            </div>

            <a
                href="{{ route('admin.ppdb.index') }}"
                class="tu-section-button"
            >
                <span>Kelola PPDB</span>
                <span class="tu-button-arrow">→</span>
            </a>

        </div>


        <div class="tu-stat-grid">

            {{-- CARD 1 --}}
            <div class="tu-stat-card">

                <div class="tu-stat-icon">
                    <x-icon name="file-text" :size="21" />
                </div>

                <div class="tu-stat-content">

                    <span class="tu-stat-label">
                        Menunggu Verifikasi
                    </span>

                    <strong>
                        {{ $stats['submitted'] }}
                    </strong>

                    <small>
                        pendaftar
                    </small>

                </div>

                <div class="tu-stat-decoration"></div>

            </div>


            {{-- CARD 2 --}}
            <div class="tu-stat-card">

                <div class="tu-stat-icon">
                    <x-icon name="user-check" :size="21" />
                </div>

                <div class="tu-stat-content">

                    <span class="tu-stat-label">
                        Terverifikasi
                    </span>

                    <strong>
                        {{ $stats['verified'] }}
                    </strong>

                    <small>
                        pendaftar
                    </small>

                </div>

                <div class="tu-stat-decoration"></div>

            </div>


            {{-- CARD 3 --}}
            <div class="tu-stat-card">

                <div class="tu-stat-icon">
                    <x-icon name="calendar" :size="21" />
                </div>

                <div class="tu-stat-content">

                    <span class="tu-stat-label">
                        Menunggu Daftar Ulang
                    </span>

                    <strong>
                        {{ $stats['accepted'] }}
                    </strong>

                    <small>
                        pendaftar
                    </small>

                </div>

                <div class="tu-stat-decoration"></div>

            </div>


            {{-- CARD 4 --}}
            <div class="tu-stat-card">

                <div class="tu-stat-icon">
                    <x-icon name="user-group" :size="21" />
                </div>

                <div class="tu-stat-content">

                    <span class="tu-stat-label">
                        Daftar Ulang Selesai
                    </span>

                    <strong>
                        {{ $stats['registered_ulang'] }}
                    </strong>

                    <small>
                        pendaftar
                    </small>

                </div>

                <div class="tu-stat-decoration"></div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        PPDB TABLES
    ====================================================== --}}
    <section class="tu-section">

        <div class="tu-two-column">

            {{-- MENUNGGU KONFIRMASI --}}
            <div class="tu-panel">

                <div class="tu-panel-header">

                    <div>

                        <span class="tu-panel-label">
                            PPDB
                        </span>

                        <h3>
                            Menunggu Konfirmasi Daftar Ulang
                        </h3>

                    </div>

                    <a
                        href="{{ route('admin.ppdb.index', ['status' => 'accepted']) }}"
                        class="tu-panel-button"
                    >
                        <span>Lihat semua</span>
                        <span>→</span>
                    </a>

                </div>


                <div class="tu-table-wrapper">

                    <table class="tu-table">

                        <thead>
                            <tr>
                                <th>Nama Pendaftar</th>
                                <th>Batas Daftar Ulang</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($awaitingReRegistration as $registration)

                                <tr>

                                    <td>

                                        <div class="tu-person">

                                            <strong>
                                                {{ $registration->full_name }}
                                            </strong>

                                            <span>
                                                {{ $registration->registration_number }}
                                            </span>

                                        </div>

                                    </td>


                                    <td>

                                        @if ($registration->isReRegistrationOverdue())

                                            <span class="tu-badge tu-badge-danger">
                                                Lewat batas
                                            </span>

                                        @endif

                                        <span class="tu-date">
                                            {{ $registration->reRegistrationDeadlineLabel() ?? '-' }}
                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a
                                            href="{{ route('admin.ppdb.show', $registration) }}"
                                            class="tu-btn tu-btn-primary tu-btn-sm"
                                        >
                                            Konfirmasi
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3">

                                        <div class="tu-empty">

                                            <div class="tu-empty-icon">
                                                <x-icon
                                                    name="check-circle"
                                                    :size="22"
                                                />
                                            </div>

                                            <strong>
                                                Tidak ada pendaftaran
                                            </strong>

                                            <span>
                                                Tidak ada pendaftar yang menunggu konfirmasi.
                                            </span>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PENEMPATAN --}}
            <div class="tu-panel tu-panel-blue">

                <div class="tu-panel-header">

                    <div>

                        <span class="tu-panel-label">
                            PENEMPATAN
                        </span>

                        <h3>
                            Riwayat Penempatan Kelas
                        </h3>

                    </div>

                </div>


                <div class="tu-table-wrapper">

                    <table class="tu-table">

                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($recentEnrollments as $registration)

                                <tr>

                                    <td>

                                        <div class="tu-person">

                                            <strong>
                                                {{ $registration->full_name }}
                                            </strong>

                                            <span>
                                                {{ $registration->user->email }}
                                            </span>

                                        </div>

                                    </td>


                                    <td>

                                        @if ($registration->placedClassroom)

                                            <span class="tu-badge tu-badge-success">
                                                {{ $registration->placedClassroom->name }}
                                            </span>

                                        @else

                                            <span class="tu-badge tu-badge-warning">
                                                Belum ditempatkan
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="2">

                                        <div class="tu-empty">

                                            <div class="tu-empty-icon">
                                                <x-icon
                                                    name="users"
                                                    :size="22"
                                                />
                                            </div>

                                            <strong>
                                                Belum ada penempatan
                                            </strong>

                                            <span>
                                                Data penempatan kelas belum tersedia.
                                            </span>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        PERSURATAN DIGITAL
    ====================================================== --}}
    <section class="tu-section tu-correspondence-section">

        <div class="tu-correspondence-heading">

            <div>

                <span class="tu-correspondence-label">
                    ADMINISTRASI
                </span>

                <h2>
                    Persuratan Digital
                </h2>

                <p>
                    Kelola surat masuk dan surat keluar sekolah secara terpusat.
                </p>

            </div>

            <a
                href="{{ route('admin.correspondences.index') }}"
                class="tu-correspondence-button"
            >
                <span>Kelola semua surat</span>
                <span class="tu-button-arrow">→</span>
            </a>

        </div>


        <div class="tu-correspondence-grid">

            {{-- SURAT MASUK --}}
            <a
                href="{{ route('admin.correspondences.index', ['type' => 'masuk']) }}"
                class="tu-correspondence-card"
            >

                <div class="tu-correspondence-top">

                    <div class="tu-correspondence-icon">
                        <x-icon
                            name="file-text"
                            :size="22"
                        />
                    </div>

                    <span class="tu-card-number">
                        01
                    </span>

                </div>

                <div class="tu-correspondence-content">

                    <span class="tu-correspondence-title">
                        Surat Masuk
                    </span>

                    <strong>
                        {{ $correspondenceStats['masuk'] }}
                    </strong>

                    <small>
                        Total surat masuk
                    </small>

                </div>

                <span class="tu-card-arrow">
                    →
                </span>

            </a>


            {{-- SURAT BELUM DIPROSES --}}
            <a
                href="{{ route('admin.correspondences.index', ['type' => 'masuk']) }}"
                class="tu-correspondence-card"
            >

                <div class="tu-correspondence-top">

                    <div class="tu-correspondence-icon tu-icon-warning">
                        <x-icon
                            name="clipboard-list"
                            :size="22"
                        />
                    </div>

                    <span class="tu-card-number">
                        02
                    </span>

                </div>

                <div class="tu-correspondence-content">

                    <span class="tu-correspondence-title">
                        Surat Masuk Belum Diproses
                    </span>

                    <strong>
                        {{ $correspondenceStats['masuk_baru'] }}
                    </strong>

                    <small>
                        Perlu ditindaklanjuti
                    </small>

                </div>

                <span class="tu-card-arrow">
                    →
                </span>

            </a>


            {{-- SURAT KELUAR --}}
            <a
                href="{{ route('admin.correspondences.index', ['type' => 'keluar']) }}"
                class="tu-correspondence-card"
            >

                <div class="tu-correspondence-top">

                    <div class="tu-correspondence-icon tu-icon-send">
                        <x-icon
                            name="send"
                            :size="22"
                        />
                    </div>

                    <span class="tu-card-number">
                        03
                    </span>

                </div>

                <div class="tu-correspondence-content">

                    <span class="tu-correspondence-title">
                        Surat Keluar
                    </span>

                    <strong>
                        {{ $correspondenceStats['keluar'] }}
                    </strong>

                    <small>
                        Total surat keluar
                    </small>

                </div>

                <span class="tu-card-arrow">
                    →
                </span>

            </a>

        </div>

    </section>


    {{-- =====================================================
        AKSI ADMINISTRASI
    ====================================================== --}}
    <section class="tu-actions">

        <div class="tu-actions-header">

            <div>

                <span class="tu-section-label">
                    AKSES CEPAT
                </span>

                <h2>
                    Aksi Administrasi
                </h2>

            </div>

        </div>


        <div class="tu-action-grid">

            {{-- KELOLA PPDB --}}
            <a
                href="{{ route('admin.ppdb.index') }}"
                class="tu-action-card"
            >

                <div class="tu-action-icon">
                    <x-icon
                        name="users"
                        :size="21"
                    />
                </div>

                <div class="tu-action-content">

                    <strong>
                        Kelola PPDB
                    </strong>

                    <span>
                        Kelola seluruh data pendaftaran
                    </span>

                </div>

                <span class="tu-action-arrow">
                    →
                </span>

            </a>


            {{-- SURAT MASUK --}}
            <a
                href="{{ route('admin.correspondences.create', ['type' => 'masuk']) }}"
                class="tu-action-card"
            >

                <div class="tu-action-icon">
                    <x-icon
                        name="inbox"
                        :size="21"
                    />
                </div>

                <div class="tu-action-content">

                    <strong>
                        Catat Surat Masuk
                    </strong>

                    <span>
                        Tambahkan surat masuk baru
                    </span>

                </div>

                <span class="tu-action-arrow">
                    →
                </span>

            </a>


            {{-- SURAT KELUAR --}}
            <a
                href="{{ route('admin.correspondences.create', ['type' => 'keluar']) }}"
                class="tu-action-card"
            >

                <div class="tu-action-icon">
                    <x-icon
                        name="send"
                        :size="21"
                    />
                </div>

                <div class="tu-action-content">

                    <strong>
                        Buat Surat Keluar
                    </strong>

                    <span>
                        Catat surat keluar sekolah
                    </span>

                </div>

                <span class="tu-action-arrow">
                    →
                </span>

            </a>

        </div>

    </section>

</div>

@endsection