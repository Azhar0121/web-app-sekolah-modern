@extends('layouts.public')

@section('title', 'Profil Sekolah — ' . config('app.name'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/public/profile.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- HERO PROFIL SEKOLAH --}}
<section class="profile-hero">
    <div class="profile-hero-decoration profile-hero-decoration-one"></div>
    <div class="profile-hero-decoration profile-hero-decoration-two"></div>

    <div class="container profile-hero-container">
        <nav aria-label="breadcrumb" class="profile-breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">
                        <i class="bi bi-house-door-fill"></i> Beranda
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Profil Sekolah
                </li>
            </ol>
        </nav>

        <div class="profile-hero-grid">
            <div class="profile-hero-content">
                <span class="profile-hero-label">
                    <i class="bi bi-mortarboard-fill"></i>
                    PROFIL SEKOLAH
                </span>

                <h1>
                    Mengenal Lebih Dekat
                    <span>{{ $settings['school_name'] ?? config('app.name') }}</span>
                </h1>

                <div class="profile-hero-line"></div>

                <p>
                    Mengenal perjalanan, visi-misi, nilai-nilai utama, jajaran pengajar,
                    serta fasilitas yang mendukung lingkungan belajar berkualitas,
                    berkarakter, dan siap menghadapi masa depan.
                </p>

                <div class="profile-hero-highlights">
                    <div class="profile-hero-highlight">
                        <span class="profile-highlight-icon">
                            <i class="bi bi-book-half"></i>
                        </span>

                        <span>
                            <strong>Pendidikan Berkualitas</strong>
                            <small>Belajar dan berkembang</small>
                        </span>
                    </div>

                    <div class="profile-hero-highlight">
                        <span class="profile-highlight-icon profile-highlight-gold">
                            <i class="bi bi-award-fill"></i>
                        </span>

                        <span>
                            <strong>Karakter Unggul</strong>
                            <small>Integritas dan prestasi</small>
                        </span>
                    </div>
                </div>

                <a href="#sejarah-visi" class="profile-hero-cta">
                    Kenali Sekolah Kami
                    <i class="bi bi-arrow-down"></i>
                </a>
            </div>

            <div class="profile-hero-visual" aria-label="Foto gedung sekolah">
                <div class="profile-hero-image">
                    <img
                        src="https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=85"
                        alt="Gedung dan lingkungan sekolah"
                        loading="eager"
                    >
                </div>
            </div>

        </div>
    </div>

    <div class="profile-hero-bottom">
        <div class="container">
            <div class="profile-hero-points">
                <span><i class="bi bi-check-circle-fill"></i> Berkarakter</span>
                <span><i class="bi bi-check-circle-fill"></i> Inovatif</span>
                <span><i class="bi bi-check-circle-fill"></i> Kolaboratif</span>
            </div>
        </div>
    </div>
</section>

<div class="profile-page-background">
    <div class="profile-background-orb profile-background-orb-one"></div>
    <div class="profile-background-orb profile-background-orb-two"></div>

    <div class="container profile-container">

        {{-- SECTION 1: SEJARAH & VISI MISI --}}
        <section id="sejarah-visi" class="profile-section profile-history-section">
            <div class="profile-section-heading">
                <div class="profile-heading-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div>
                    <span class="profile-eyebrow">REKAM JEJAK KEMAJUAN</span>
                    <h2>Sejarah &amp; Visi Misi Sekolah</h2>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-6">
                    <div class="history-panel">
                        <span class="history-label">PERJALANAN SEKOLAH</span>

                        <h3>Sejarah Berdirinya Sekolah</h3>

                        <p class="history-intro">
                            Didirikan sebagai institusi pendidikan modern,
                            <strong>{{ $settings['school_name'] ?? config('app.name') }}</strong>
                            bertekad menghadirkan pembelajaran berkualitas yang memadukan
                            pendidikan karakter dengan penguasaan teknologi digital.
                        </p>

                        <div class="history-timeline">
                            <div class="history-timeline-item">
                                <div class="history-timeline-marker">
                                    <i class="bi bi-calendar-check"></i>
                                </div>

                                <div>
                                    <strong>Tahun Pendirian</strong>
                                    <p>
                                        Resmi beroperasi mengabdi di dunia pendidikan
                                        dengan fasilitas pembelajaran yang terus dikembangkan.
                                    </p>
                                </div>
                            </div>

                            <div class="history-timeline-item">
                                <div class="history-timeline-marker yellow">
                                    <i class="bi bi-award"></i>
                                </div>

                                <div>
                                    <strong>Transformasi Digital &amp; Akreditasi A</strong>
                                    <p>
                                        Mengembangkan manajemen sekolah berbasis teknologi
                                        dan meningkatkan mutu layanan pendidikan.
                                    </p>
                                </div>
                            </div>

                            <div class="history-timeline-item">
                                <div class="history-timeline-marker cyan">
                                    <i class="bi bi-cpu"></i>
                                </div>

                                <div>
                                    <strong>Ekosistem Smart School Modern</strong>
                                    <p>
                                        Mengembangkan SIM sekolah terintegrasi, presensi QR Code,
                                        portal orang tua, dan layanan akademik digital.
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
                                <span>PANDUAN KAMI</span>
                                <h3>Visi &amp; Misi Sekolah</h3>
                            </div>
                        </div>

                        <div class="vision-box">
                            <div class="vision-box-label">
                                <i class="bi bi-eye-fill"></i>
                                VISI SEKOLAH
                            </div>

                            <p>
                                “Menjadi Lembaga Pendidikan Unggul yang Berkarakter Mulia,
                                Menguasai Sains &amp; Teknologi Modern, serta Berwawasan Global.”
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
                                    Menyelenggarakan pembelajaran berkualitas berstandar nasional
                                    dan internasional berbasis teknologi informasi.
                                </span>
                            </li>

                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>
                                    Membina karakter peserta didik yang bertakwa, berintegritas,
                                    berjiwa kepemimpinan, dan santun.
                                </span>
                            </li>

                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>
                                    Menyediakan sarana laboratorium, media pembelajaran digital,
                                    dan lingkungan belajar yang kondusif.
                                </span>
                            </li>

                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>
                                    Mengembangkan bakat seni, olahraga, non-akademik,
                                    dan semangat kewirausahaan digital.
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION 2: NILAI & IDENTITAS --}}
        <section id="nilai-logo" class="profile-section">
            <div class="profile-section-heading">
                <div class="profile-heading-icon heading-green">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <span class="profile-eyebrow">NILAI UTAMA &amp; IDENTITAS</span>
                    <h2>Nilai Karakter &amp; Filosofi Logo</h2>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="value-card value-green">
                        <div class="value-number">01</div>

                        <div class="value-icon">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </div>

                        <span class="value-label">KARAKTER</span>
                        <h3>Integritas &amp; Moralitas</h3>

                        <p>
                            Menanamkan kejujuran, kedisiplinan, serta etika mulia
                            dalam kehidupan akademik dan bermasyarakat.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-card value-blue">
                        <div class="value-number">02</div>

                        <div class="value-icon">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>

                        <span class="value-label">INOVASI</span>
                        <h3>Inovasi &amp; Teknologi</h3>

                        <p>
                            Mendorong daya kritis, kreativitas, dan adaptasi terhadap
                            perkembangan sains serta teknologi masa depan.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-card value-yellow">
                        <div class="value-number">03</div>

                        <div class="value-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <span class="value-label">KOLABORASI</span>
                        <h3>Kolaborasi Global</h3>

                        <p>
                            Membentuk pribadi yang inklusif dan komunikatif, serta
                            mampu berkolaborasi pada skala nasional maupun global.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION 3: PENGAJAR --}}
        <section id="pengajar" class="profile-section">
            <div class="profile-section-header">
                <div class="profile-section-heading">
                    <div class="profile-heading-icon heading-purple">
                        <i class="bi bi-person-workspace"></i>
                    </div>

                    <div>
                        <span class="profile-eyebrow">TENAGA PENDIDIK</span>
                        <h2>Jajaran Pengajar &amp; Staf Ahli</h2>
                    </div>
                </div>

                <span class="profile-count-badge">
                    <i class="bi bi-people-fill"></i>
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

                                <div class="teacher-avatar overflow-hidden">
                                    @if ($teacher->photo_url)
                                        <img
                                            src="{{ $teacher->photo_url }}"
                                            alt="{{ $teacher->name }}"
                                            loading="lazy"
                                        >
                                    @else
                                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                    @endif
                                </div>

                                <h3 title="{{ $teacher->name }}" class="teacher-name">
                                    {{ $teacher->name }}
                                </h3>

                                @php
                                    $subjectsTaught = $teacher->teachingAssignments
                                        ->pluck('subject.name')
                                        ->unique()
                                        ->filter()
                                        ->implode(', ');
                                @endphp

                                <div class="teacher-email" title="{{ $teacher->email }}">
                                    <i class="bi bi-envelope"></i>
                                    <span>{{ $teacher->email }}</span>
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

                <div class="text-center mt-4">
                    <a href="{{ route('teachers.show') }}" class="profile-outline-button">
                        <i class="bi bi-people-fill"></i>
                        Lihat Seluruh Direktori Pengajar &amp; Staf Sekolah
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @else
                <div class="teacher-empty">
                    <div class="teacher-empty-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <h3>Data Pengajar Terdaftar</h3>

                    <p>
                        Daftar staf pendidik profesional sekolah akan muncul
                        di bagian ini secara otomatis.
                    </p>

                    <div class="mt-3">
                        <a href="{{ route('teachers.show') }}" class="profile-outline-button">
                            Buka Direktori Pengajar
                        </a>
                    </div>
                </div>
            @endif
        </section>

        {{-- SECTION 4: STRUKTUR ORGANISASI --}}
        <section id="struktur" class="profile-section">
            <div class="profile-section-heading">
                <div class="profile-heading-icon heading-cyan">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>

                <div>
                    <span class="profile-eyebrow">BAGAN KEPEMIMPINAN</span>
                    <h2>Struktur Organisasi Sekolah</h2>
                </div>
            </div>

            <div class="organization-panel">
                <div class="organization-principal">
                    <span>
                        <i class="bi bi-person-badge-fill"></i>
                        KEPALA SEKOLAH
                    </span>

                    <strong>Drs. H. Ahmad Dahlan, M.Pd.</strong>
                </div>

                <div class="organization-line"></div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="organization-card">
                            <span>WAKA KURIKULUM</span>
                            <strong>Dra. Hj. Siti Aminah, M.Si.</strong>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="organization-card">
                            <span>WAKA KESISWAAN</span>
                            <strong>Bambang Susilo, S.Pd.</strong>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="organization-card">
                            <span>WAKA SARANA PRASARANA</span>
                            <strong>Ir. Eko Prasetyo, M.T.</strong>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION 5: AKREDITASI --}}
        <section id="akreditasi" class="profile-section">
            <div class="accreditation-panel">
                <div class="accreditation-grade">A</div>

                <div class="accreditation-content">
                    <span class="accreditation-label">
                        <i class="bi bi-patch-check-fill"></i>
                        AKREDITASI UNGGUL
                    </span>

                    <h2>
                        <i class="bi bi-patch-check-fill"></i>
                        Terakreditasi “A” (Unggul) BAN-S/M
                    </h2>

                    <p>
                        {{ $settings['school_name'] ?? config('app.name') }}
                        berkomitmen menjaga mutu pendidikan melalui pengembangan kurikulum,
                        fasilitas pembelajaran, serta peningkatan kompetensi tenaga pendidik.
                    </p>
                </div>
            </div>
        </section>

        {{-- SECTION FASILITAS --}}
        @include('public.profile.partials.facilities-section')

    </div>
</div>
@endsection