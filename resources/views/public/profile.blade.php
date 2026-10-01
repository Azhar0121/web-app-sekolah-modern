@extends('layouts.public')

@section('title', 'Profil Sekolah — ' . config('app.name'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/public/profile.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- ── HERO HEADER ──────────────────────────────────────────────── --}}
<section class="bg-dark text-white py-5 shadow-sm" style="background: linear-gradient(135deg, #071b35 0%, #0f2747 50%, #1e3a8a 100%);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 small">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-warning text-decoration-none"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">Profil Sekolah</li>
            </ol>
        </nav>
        <h1 class="display-6 fw-bold mb-2">Profil {{ $settings['school_name'] ?? config('app.name') }}</h1>
        <p class="lead text-light opacity-75 max-w-2xl mb-0" style="font-size: 1.05rem;">
            Mengenal lebih dekat sejarah, visi-misi, nilai-nilai utama, profil jajaran pengajar, serta sarana fasilitas sekolah modern.
        </p>
    </div>
</section>


<div class="container py-5">

    {{-- ── SECTION 1: SEJARAH & VISI MISI ───────────────────────── --}}
    <section id="sejarah-visi" class="mb-5 pt-2">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; flex-shrink:0;">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <span class="text-warning fw-bold small text-uppercase">REKAM JEJAK KEMAJUAN</span>
                        <h3 class="fw-bold text-dark mb-0">Sejarah Berdirinya Sekolah</h3>
                    </div>
                </div>

                <p class="text-secondary leading-relaxed">
                    Didirikan sebagai institusi pendidikan modern, <strong>{{ $settings['school_name'] ?? config('app.name') }}</strong> bertekad menghadirkan pembelajaran berkualitas tinggi yang memadukan pendidikan karakter berintegritas tinggi dengan penguasaan teknologi digital terkini.
                </p>

                <div class="ps-3 border-start border-3 border-primary ms-2 my-4">
                    <div class="mb-3">
                        <strong class="text-dark d-block">
                            <i class="bi bi-calendar-check text-primary me-2"></i>
                            Tahun Pendirian
                        </strong>
                        <small class="text-muted">
                            Resmi beroperasi mengabdi di dunia pendidikan dengan fasilitas pembelajaran digital terpadu.
                        </small>
                    </div>

                    <div class="mb-3">
                        <strong class="text-dark d-block">
                            <i class="bi bi-award text-warning me-2"></i>
                            Transformasi Digital & Akreditasi A
                        </strong>
                        <small class="text-muted">
                            Meraih Akreditasi "A" (Unggul) dari BAN-S/M serta menerapkan Manajemen Sekolah Berbasis Teknologi.
                        </small>
                    </div>

                    <div>
                        <strong class="text-dark d-block">
                            <i class="bi bi-cpu text-info me-2"></i>
                            Ekosistem Smart School Modern
                        </strong>
                        <small class="text-muted">
                            Meluncurkan SIM Sekolah Terintegrasi (CBT, Absensi QR Code, Portal Orang Tua & Kepala Sekolah).
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100 border-top border-4 border-primary">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3" style="width:46px; height:46px; flex-shrink:0;">
                            <i class="bi bi-compass-fill fs-4"></i>
                        </div>

                        <div>
                            <span class="text-primary fw-bold small text-uppercase">PANDUAN KAMI</span>
                            <h4 class="fw-bold text-dark mb-0">Visi & Misi Sekolah</h4>
                        </div>
                    </div>

                    <div class="p-3 bg-primary-subtle rounded-3 mb-4 border border-primary-subtle">
                        <h6 class="fw-bold text-primary mb-1">
                            <i class="bi bi-eye-fill me-2"></i>
                            VISI SEKOLAH
                        </h6>

                        <p class="mb-0 text-dark fw-semibold" style="font-size:0.95rem;">
                            "Menjadi Lembaga Pendidikan Unggul yang Berkarakter Mulia, Menguasai Sains & Teknologi Modern, serta Berwawasan Global."
                        </p>
                    </div>

                    <h6 class="fw-bold text-dark mb-2">
                        <i class="bi bi-list-check text-success me-2"></i>
                        MISI SEKOLAH:
                    </h6>

                    <ul class="list-unstyled text-secondary small mb-0">
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                            <span>
                                Menyelenggarakan proses pembelajaran berkualitas berstandar nasional dan internasional berbasis teknologi informasi.
                            </span>
                        </li>

                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                            <span>
                                Membina karakter peserta didik yang bertakwa, berintegritas tinggi, berjiwa kepemimpinan, dan santun.
                            </span>
                        </li>

                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                            <span>
                                Menyediakan sarana laboratorium, media pembelajaran digital, dan lingkungan belajar yang ramah dan kondusif.
                            </span>
                        </li>

                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                            <span>
                                Mengembangkan bakat non-akademik, seni, olahraga, serta semangat kewirausahaan digital peserta didik.
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    {{-- ── SECTION 2: NILAI-NILAI UTAMA & FILOSOFI LOGO ────────────── --}}
    <section id="nilai-logo" class="mb-5 pt-3">

        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; flex-shrink:0;">
                <i class="bi bi-shield-check fs-4"></i>
            </div>

            <div>
                <span class="text-warning fw-bold small text-uppercase">NILAI UTAMA & IDENTITAS</span>
                <h3 class="fw-bold text-dark mb-0">Nilai Karakter & Filosofi Logo</h3>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-3 mb-3" style="width:44px; height:44px;">
                        <i class="bi bi-heart-pulse-fill fs-5"></i>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">
                        1. Integritas & Moralitas
                    </h5>

                    <p class="text-secondary small mb-0">
                        Menanamkan kejujuran, kedisiplinan, serta etika mulia dalam setiap aspek kehidupan akademik dan bermasyarakat.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 mb-3" style="width:44px; height:44px;">
                        <i class="bi bi-lightbulb-fill fs-5"></i>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">
                        2. Inovasi & Teknologi
                    </h5>

                    <p class="text-secondary small mb-0">
                        Mendorong daya kritis, kreativitas, dan adaptasi terhadap perkembangan sains serta teknologi masa depan.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
                    <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-3 mb-3" style="width:44px; height:44px;">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">
                        3. Kolaborasi Global
                    </h5>

                    <p class="text-secondary small mb-0">
                        Membentuk kepribadian yang inklusif, komunikatif, sanggup berkolaborasi dalam skala nasional maupun global.
                    </p>
                </div>
            </div>

        </div>
    </section>


    {{-- ── SECTION 3: JAJARAN PENGAJAR & STAF ──────────────────────── --}}
    <section id="pengajar" class="mb-5 pt-3">

        <div class="d-flex align-items-center justify-content-between mb-4">

            <div class="d-flex align-items-center gap-3">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; flex-shrink:0;">
                    <i class="bi bi-person-workspace fs-4"></i>
                </div>

                <div>
                    <span class="text-warning fw-bold small text-uppercase">TENAGA PENDIDIK</span>
                    <h3 class="fw-bold text-dark mb-0">Jajaran Pengajar & Staf Ahli</h3>
                </div>
            </div>

            <span class="badge text-bg-primary fs-7">
                {{ $teachers->count() }} Guru Terdaftar
            </span>
        </div>


        @if ($teachers->isNotEmpty())

        <div class="row g-4">

            @foreach ($teachers as $teacher)

            <div class="col-6 col-md-4 col-lg-3">

                <div class="card text-center border-0 shadow-sm rounded-3 p-3 h-100">

                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold fs-3 shadow-sm" style="width: 72px; height: 72px;">
                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                    </div>

                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $teacher->name }}">
                        {{ $teacher->name }}
                    </h6>

                    @php
                        $subjectsTaught = $teacher->teachingAssignments->pluck('subject.name')->unique()->filter()->implode(', ');
                    @endphp

                    <small class="text-primary fw-semibold d-block mb-1 text-truncate" title="{{ $teacher->email }}">
                        {{ $teacher->email }}
                    </small>

                    <span class="badge bg-light text-secondary border border-secondary-subtle mx-auto text-truncate max-w-100" style="font-size:0.75rem;" title="{{ $subjectsTaught ?: 'Tenaga Pendidik / Guru' }}">
                        {{ $subjectsTaught ?: 'Tenaga Pendidik / Guru' }}
                    </span>

                </div>

            </div>

            @endforeach

        </div>

        @else

        <div class="card border-0 shadow-sm rounded-3 p-5 text-center">

            <div class="d-inline-flex align-items-center justify-content-center bg-light text-secondary rounded-circle mx-auto mb-3" style="width:60px; height:60px;">
                <i class="bi bi-people fs-2"></i>
            </div>

            <h5 class="fw-bold text-dark">
                Data Pengajar Terdaftar
            </h5>

            <p class="text-muted small mb-0">
                Daftar staf pendidik profesional sekolah akan muncul di bagian ini secara otomatis.
            </p>

        </div>

        @endif

    </section>


    {{-- ── SECTION 4: STRUKTUR ORGANISASI ──────────────────────────── --}}
    <section id="struktur" class="mb-5 pt-3">

        <div class="d-flex align-items-center gap-3 mb-4">

            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; flex-shrink:0;">
                <i class="bi bi-diagram-3-fill fs-4"></i>
            </div>

            <div>
                <span class="text-warning fw-bold small text-uppercase">BOGAN KEPEMIMPINAN</span>
                <h3 class="fw-bold text-dark mb-0">Struktur Organisasi Sekolah</h3>
            </div>

        </div>


        <div class="card border-0 shadow-sm rounded-3 p-4">

            <div class="row g-3 text-center">

                <div class="col-12">

                    <div class="p-3 bg-dark text-white rounded-3 max-w-sm mx-auto shadow-sm">

                        <small class="text-warning fw-bold d-block">
                            KEPALA SEKOLAH
                        </small>

                        <strong class="fs-6">
                            Drs. H. Ahmad Dahlan, M.Pd.
                        </strong>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="p-3 bg-light rounded-3 border">

                        <small class="text-primary fw-bold d-block">
                            WAKA KURIKULUM
                        </small>

                        <span class="fw-semibold text-dark small">
                            Dra. Hj. Siti Aminah, M.Si.
                        </span>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="p-3 bg-light rounded-3 border">

                        <small class="text-primary fw-bold d-block">
                            WAKA KESISWAAN
                        </small>

                        <span class="fw-semibold text-dark small">
                            Bambang Susilo, S.Pd.
                        </span>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="p-3 bg-light rounded-3 border">

                        <small class="text-primary fw-bold d-block">
                            WAKA SARANA PRASARANA
                        </small>

                        <span class="fw-semibold text-dark small">
                            Ir. Eko Prasetyo, M.T.
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ── SECTION 5: AKREDITASI ────────────────────────────────────── --}}
    <section id="akreditasi" class="mb-5 pt-3">

        <div class="card bg-warning-subtle border border-warning rounded-4 p-4 text-center shadow-sm">

            <div class="row align-items-center">

                <div class="col-md-3">

                    <span class="display-1 fw-black text-warning-emphasis d-block leading-none">
                        A
                    </span>

                    <span class="badge text-bg-warning px-3 py-1 fw-bold">
                        AKREDITASI UNGGUL
                    </span>

                </div>

                <div class="col-md-9 text-md-start mt-3 mt-md-0">

                    <h4 class="fw-bold text-dark mb-1">
                        <i class="bi bi-patch-check-fill text-warning me-2"></i>
                        Terakreditasi "A" (Unggul) BAN-S/M
                    </h4>

                    <p class="text-secondary small mb-0">
                        {{ $settings['school_name'] ?? config('app.name') }} secara resmi terakreditasi A dengan nilai kualifikasi sangat baik oleh Badan Akreditasi Nasional Sekolah/Madrasah (BAN-S/M). Menjamin standar kurikulum, fasilitas laboratorium, serta kualifikasi pengajar yang prima.
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- ── SECTION 6: DENAH & FASILITAS SEKOLAH ────────────────────── --}}
    <section id="fasilitas" class="mb-4 pt-3">

        <div class="d-flex align-items-center justify-content-between mb-4">

            <div class="d-flex align-items-center gap-3">

                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; flex-shrink:0;">
                    <i class="bi bi-building-gear fs-4"></i>
                </div>

                <div>
                    <span class="text-warning fw-bold small text-uppercase">SARANA & PRASARANA</span>
                    <h3 class="fw-bold text-dark mb-0">
                        Fasilitas Kampus & Laboratorium
                    </h3>
                </div>

            </div>

            <span class="badge text-bg-secondary fs-7">
                {{ $facilities->count() }} Ruangan Aktif
            </span>

        </div>


        @if ($facilities->isNotEmpty())

        <div class="row g-3">

            @foreach ($facilities as $facility)

            <div class="col-md-4 col-lg-3">

                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">

                    <div class="d-flex align-items-center gap-3">

                        <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-3" style="width:40px; height:40px; flex-shrink:0;">
                            <i class="bi bi-door-open-fill fs-5"></i>
                        </div>

                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">
                                {{ $facility->name }}
                            </h6>

                            <small class="text-muted d-block fs-xs">
                                Kapasitas: {{ $facility->capacity ?? 36 }} Siswa
                            </small>
                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

        @else

        <div class="row g-3">

            <div class="col-md-4">

                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">

                    <div class="d-flex align-items-center gap-3">

                        <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-3" style="width:40px; height:40px; flex-shrink:0;">
                            <i class="bi bi-pc-display fs-5"></i>
                        </div>

                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">
                                Laboratorium Komputer CBT
                            </h6>

                            <small class="text-muted d-block fs-xs">
                                AC & Internet High Speed
                            </small>
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">

                    <div class="d-flex align-items-center gap-3">

                        <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-3" style="width:40px; height:40px; flex-shrink:0;">
                            <i class="bi bi-book-half fs-5"></i>
                        </div>

                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">
                                Perpustakaan Digital
                            </h6>

                            <small class="text-muted d-block fs-xs">
                                Koleksi E-Book & Ruang Baca
                            </small>
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">

                    <div class="d-flex align-items-center gap-3">

                        <div class="d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning-emphasis rounded-3" style="width:40px; height:40px; flex-shrink:0;">
                            <i class="bi bi-dribbble fs-5"></i>
                        </div>

                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">
                                Lapangan Olahraga Outdoor
                            </h6>

                            <small class="text-muted d-block fs-xs">
                                Basket, Futsal & Voli
                            </small>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        @endif

    </section>

</div>

@endsection