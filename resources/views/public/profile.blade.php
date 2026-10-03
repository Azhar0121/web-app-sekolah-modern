@extends('layouts.public')

@section('title', 'Profil Sekolah — ' . config('app.name'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/public/profile.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- =========================================================
HERO
========================================================= --}}

<section class="profile-hero">


<div class="container">

    <nav aria-label="breadcrumb" class="profile-breadcrumb">
        <ol class="breadcrumb mb-0">

            <li class="breadcrumb-item">
                <a href="{{ url('/') }}">
                    <i class="bi bi-house-door-fill"></i>
                    Beranda
                </a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">
                Profil Sekolah
            </li>

        </ol>
    </nav>


    <div class="profile-hero-content">

        <span class="profile-hero-label">
            PROFIL SEKOLAH
        </span>

        <h1>
            Profil {{ $settings['school_name'] ?? config('app.name') }}
        </h1>

        <div class="profile-hero-line"></div>

        <p>
            Mengenal lebih dekat sejarah, visi-misi, nilai-nilai utama,
            profil jajaran pengajar, serta sarana fasilitas sekolah modern.
        </p>

    </div>

</div>


</section>

<div class="container profile-container">


{{-- =========================================================
     SECTION 1 — SEJARAH & VISI MISI
     ========================================================= --}}
<section id="sejarah-visi" class="profile-section profile-history-section">

    <div class="profile-section-heading">

        <div class="profile-heading-icon">
            <i class="bi bi-hourglass-split"></i>
        </div>

        <div>
            <span class="profile-eyebrow">
                REKAM JEJAK KEMAJUAN
            </span>

            <h2>
                Sejarah & Visi Misi Sekolah
            </h2>
        </div>

    </div>


    <div class="row g-4 align-items-stretch">

        <div class="col-lg-6">

            <div class="history-panel">

                <div class="history-panel-number">
                    
                </div>

                <span class="history-label">
                    PERJALANAN SEKOLAH
                </span>

                <h3>
                    Sejarah Berdirinya Sekolah
                </h3>

                <p class="history-intro">
                    Didirikan sebagai institusi pendidikan modern,
                    <strong>{{ $settings['school_name'] ?? config('app.name') }}</strong>
                    bertekad menghadirkan pembelajaran berkualitas tinggi
                    yang memadukan pendidikan karakter berintegritas tinggi
                    dengan penguasaan teknologi digital terkini.
                </p>


                <div class="history-timeline">

                    <div class="history-timeline-item">

                        <div class="history-timeline-marker">
                            <i class="bi bi-calendar-check"></i>
                        </div>

                        <div>
                            <strong>
                                Tahun Pendirian
                            </strong>

                            <p>
                                Resmi beroperasi mengabdi di dunia pendidikan
                                dengan fasilitas pembelajaran digital terpadu.
                            </p>
                        </div>

                    </div>


                    <div class="history-timeline-item">

                        <div class="history-timeline-marker yellow">
                            <i class="bi bi-award"></i>
                        </div>

                        <div>
                            <strong>
                                Transformasi Digital & Akreditasi A
                            </strong>

                            <p>
                                Meraih Akreditasi "A" (Unggul) dari BAN-S/M
                                serta menerapkan Manajemen Sekolah Berbasis Teknologi.
                            </p>
                        </div>

                    </div>


                    <div class="history-timeline-item">

                        <div class="history-timeline-marker cyan">
                            <i class="bi bi-cpu"></i>
                        </div>

                        <div>
                            <strong>
                                Ekosistem Smart School Modern
                            </strong>

                            <p>
                                Meluncurkan SIM Sekolah Terintegrasi
                                (CBT, Absensi QR Code, Portal Orang Tua
                                & Kepala Sekolah).
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="vision-panel">

                <div class="vision-panel-top">

                    <div class="vision-icon">
                        <i class="bi bi-compass-fill"></i>
                    </div>

                    <div>
                        <span>
                            PANDUAN KAMI
                        </span>

                        <h3>
                            Visi & Misi Sekolah
                        </h3>
                    </div>

                </div>


                <div class="vision-box">

                    <div class="vision-box-label">
                        <i class="bi bi-eye-fill"></i>
                        VISI SEKOLAH
                    </div>

                    <p>
                        "Menjadi Lembaga Pendidikan Unggul yang Berkarakter
                        Mulia, Menguasai Sains & Teknologi Modern,
                        serta Berwawasan Global."
                    </p>

                </div>


                <div class="mission-heading">
                    <i class="bi bi-list-check"></i>
                    MISI SEKOLAH
                </div>


                <ul class="mission-list">

                    <li>
                        <i class="bi bi-check-circle-fill"></i>

                        <span>
                            Menyelenggarakan proses pembelajaran berkualitas
                            berstandar nasional dan internasional berbasis
                            teknologi informasi.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-circle-fill"></i>

                        <span>
                            Membina karakter peserta didik yang bertakwa,
                            berintegritas tinggi, berjiwa kepemimpinan,
                            dan santun.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-circle-fill"></i>

                        <span>
                            Menyediakan sarana laboratorium, media pembelajaran
                            digital, dan lingkungan belajar yang ramah
                            dan kondusif.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-circle-fill"></i>

                        <span>
                            Mengembangkan bakat non-akademik, seni, olahraga,
                            serta semangat kewirausahaan digital peserta didik.
                        </span>
                    </li>

                </ul>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     SECTION 2 — NILAI & LOGO
     ========================================================= --}}
<section id="nilai-logo" class="profile-section">

    <div class="profile-section-heading">

        <div class="profile-heading-icon">
            <i class="bi bi-shield-check"></i>
        </div>

        <div>
            <span class="profile-eyebrow">
                NILAI UTAMA & IDENTITAS
            </span>

            <h2>
                Nilai Karakter & Filosofi Logo
            </h2>
        </div>

    </div>


    <div class="row g-4">


        <div class="col-md-4">

            <div class="value-card value-green">

                <div class="value-number">
                    
                </div>

                <div class="value-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>

                <span class="value-label">
                    KARAKTER
                </span>

                <h3>
                    Integritas & Moralitas
                </h3>

                <p>
                    Menanamkan kejujuran, kedisiplinan, serta etika mulia
                    dalam setiap aspek kehidupan akademik dan bermasyarakat.
                </p>

            </div>

        </div>


        <div class="col-md-4">

            <div class="value-card value-blue">

                <div class="value-number">
                    02
                </div>

                <div class="value-icon">
                    <i class="bi bi-lightbulb-fill"></i>
                </div>

                <span class="value-label">
                    INOVASI
                </span>

                <h3>
                    Inovasi & Teknologi
                </h3>

                <p>
                    Mendorong daya kritis, kreativitas, dan adaptasi
                    terhadap perkembangan sains serta teknologi masa depan.
                </p>

            </div>

        </div>


        <div class="col-md-4">

            <div class="value-card value-yellow">

                <div class="value-number">
                    03
                </div>

                <div class="value-icon">
                    <i class="bi bi-people-fill"></i>
                </div>

                <span class="value-label">
                    KOLABORASI
                </span>

                <h3>
                    Kolaborasi Global
                </h3>

                <p>
                    Membentuk kepribadian yang inklusif, komunikatif,
                    sanggup berkolaborasi dalam skala nasional maupun global.
                </p>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     SECTION 3 — PENGAJAR
     ========================================================= --}}
<section id="pengajar" class="profile-section">

    <div class="profile-section-header">

        <div class="profile-section-heading">

            <div class="profile-heading-icon">
                <i class="bi bi-person-workspace"></i>
            </div>

            <div>
                <span class="profile-eyebrow">
                    TENAGA PENDIDIK
                </span>

                <h2>
                    Jajaran Pengajar & Staf Ahli
                </h2>
            </div>

        </div>

        <span class="profile-count-badge">
            {{ $teachers->count() }} Guru Terdaftar
        </span>

    </div>


    @if ($teachers->isNotEmpty())

        <div class="row g-4">

            @foreach ($teachers as $teacher)

                <div class="col-6 col-md-4 col-lg-3">

                    <div class="teacher-card">

                        <div class="teacher-card-top">

                            <span class="teacher-index">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>

                        </div>


                        <div class="teacher-avatar">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>


                        <h3
                            title="{{ $teacher->name }}"
                            class="teacher-name"
                        >
                            {{ $teacher->name }}
                        </h3>


                        @php
                            $subjectsTaught = $teacher->teachingAssignments
                                ->pluck('subject.name')
                                ->unique()
                                ->filter()
                                ->implode(', ');
                        @endphp


                        <div
                            class="teacher-email"
                            title="{{ $teacher->email }}"
                        >
                            <i class="bi bi-envelope"></i>
                            {{ $teacher->email }}
                        </div>


                        <div
                            class="teacher-subject"
                            title="{{ $subjectsTaught ?: 'Tenaga Pendidik / Guru' }}"
                        >
                            {{ $subjectsTaught ?: 'Tenaga Pendidik / Guru' }}
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="teacher-empty">

            <div class="teacher-empty-icon">
                <i class="bi bi-people"></i>
            </div>

            <h3>
                Data Pengajar Terdaftar
            </h3>

            <p>
                Daftar staf pendidik profesional sekolah akan muncul
                di bagian ini secara otomatis.
            </p>

        </div>

    @endif

</section>


{{-- =========================================================
     SECTION 4 — STRUKTUR ORGANISASI
     ========================================================= --}}
<section id="struktur" class="profile-section">

    <div class="profile-section-heading">

        <div class="profile-heading-icon">
            <i class="bi bi-diagram-3-fill"></i>
        </div>

        <div>
            <span class="profile-eyebrow">
                BAGAN KEPEMIMPINAN
            </span>

            <h2>
                Struktur Organisasi Sekolah
            </h2>
        </div>

    </div>


    <div class="organization-panel">

        <div class="organization-principal">

            <span>
                KEPALA SEKOLAH
            </span>

            <strong>
                Drs. H. Ahmad Dahlan, M.Pd.
            </strong>

        </div>


        <div class="organization-line"></div>


        <div class="row g-3">

            <div class="col-md-4">

                <div class="organization-card">

                    <span>
                        WAKA KURIKULUM
                    </span>

                    <strong>
                        Dra. Hj. Siti Aminah, M.Si.
                    </strong>

                </div>

            </div>


            <div class="col-md-4">

                <div class="organization-card">

                    <span>
                        WAKA KESISWAAN
                    </span>

                    <strong>
                        Bambang Susilo, S.Pd.
                    </strong>

                </div>

            </div>


            <div class="col-md-4">

                <div class="organization-card">

                    <span>
                        WAKA SARANA PRASARANA
                    </span>

                    <strong>
                        Ir. Eko Prasetyo, M.T.
                    </strong>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     SECTION 5 — AKREDITASI
     ========================================================= --}}
<section id="akreditasi" class="profile-section">

    <div class="accreditation-panel">

        <div class="accreditation-grade">
            A
        </div>

        <div class="accreditation-content">

            <span class="accreditation-label">
                AKREDITASI UNGGUL
            </span>

            <h2>
                <i class="bi bi-patch-check-fill"></i>
                Terakreditasi "A" (Unggul) BAN-S/M
            </h2>

            <p>
                {{ $settings['school_name'] ?? config('app.name') }}
                secara resmi terakreditasi A dengan nilai kualifikasi
                sangat baik oleh Badan Akreditasi Nasional Sekolah/Madrasah
                (BAN-S/M). Menjamin standar kurikulum, fasilitas laboratorium,
                serta kualifikasi pengajar yang prima.
            </p>

        </div>

    </div>

</section>


{{-- =========================================================
     SECTION 6 — FASILITAS
     ========================================================= --}}
<section id="fasilitas" class="profile-section profile-section-last">

    <div class="profile-section-header">

        <div class="profile-section-heading">

            <div class="profile-heading-icon">
                <i class="bi bi-building-gear"></i>
            </div>

            <div>
                <span class="profile-eyebrow">
                    SARANA & PRASARANA
                </span>

                <h2>
                    Fasilitas Kampus & Laboratorium
                </h2>
            </div>

        </div>

        <span class="profile-count-badge profile-count-gray">
            {{ $facilities->count() }} Ruangan Aktif
        </span>

    </div>


    @if ($facilities->isNotEmpty())

        <div class="row g-3">

            @foreach ($facilities as $facility)

                <div class="col-md-4 col-lg-3">

                    <div class="facility-card">

                        <div class="facility-icon">
                            <i class="bi bi-door-open-fill"></i>
                        </div>

                        <div class="facility-content">

                            <h3>
                                {{ $facility->name }}
                            </h3>

                            <span>
                                Kapasitas:
                                {{ $facility->capacity ?? 36 }}
                                Siswa
                            </span>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="row g-3">

            <div class="col-md-4">

                <div class="facility-card">

                    <div class="facility-icon">
                        <i class="bi bi-pc-display"></i>
                    </div>

                    <div class="facility-content">

                        <h3>
                            Laboratorium Komputer CBT
                        </h3>

                        <span>
                            AC & Internet High Speed
                        </span>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="facility-card facility-green">

                    <div class="facility-icon">
                        <i class="bi bi-book-half"></i>
                    </div>

                    <div class="facility-content">

                        <h3>
                            Perpustakaan Digital
                        </h3>

                        <span>
                            Koleksi E-Book & Ruang Baca
                        </span>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="facility-card facility-yellow">

                    <div class="facility-icon">
                        <i class="bi bi-dribbble"></i>
                    </div>

                    <div class="facility-content">

                        <h3>
                            Lapangan Olahraga Outdoor
                        </h3>

                        <span>
                            Basket, Futsal & Voli
                        </span>

                    </div>

                </div>

            </div>

        </div>

    @endif

</section>


</div>

@endsection
