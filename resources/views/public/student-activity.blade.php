@extends('layouts.public')

@section('title', 'Kesiswaan & Alumni — ' . config('app.name'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/public/student.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="student-page">

    {{-- =========================================================
         HERO
    ========================================================== --}}
    <section class="student-hero">

        <div class="container">

            <nav aria-label="breadcrumb" class="student-breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">
                            <i class="bi bi-house-door-fill me-1"></i>
                            Beranda
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Kesiswaan & Alumni
                    </li>
                </ol>
            </nav>


            <div class="student-hero-grid">

                <div class="student-hero-copy">

                    <span class="student-eyebrow">
                        KESISWAAN & KEHIDUPAN SEKOLAH
                    </span>

                    <h1>
                        Tumbuh,
                        <span>Berkarya,</span>
                        dan Berprestasi.
                    </h1>

                    <p>
                        Wadah kegiatan organisasi siswa, pengembangan bakat,
                        ekstrakurikuler, prestasi, beasiswa, hingga jejaring
                        alumni {{ $settings['school_name'] ?? config('app.name') }}.
                    </p>

                    <div class="student-hero-actions">

                        <a href="#ekstrakurikuler" class="student-primary-btn">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                            Lihat Ekstrakurikuler
                        </a>

                        <a href="#prestasi" class="student-secondary-btn">
                            Lihat Prestasi
                            <i class="bi bi-arrow-down"></i>
                        </a>

                    </div>

                </div>


                <div class="student-hero-visual">

                    <div class="hero-image-frame">

                        <div class="student-life-hero-image">
                            <img
                                src="https://assets.pikiran-rakyat.com/crop/0x0%3A0x0/1200x675/photo/2024/05/22/3466153989.jpg"
                                alt="Kegiatan ekstrakurikuler siswa"
                            >
                        </div>

                        <div class="hero-image-label">
                            <span>
                                <i class="bi bi-stars"></i>
                            </span>

                            <div>
                                <strong>Aktif & Kreatif</strong>
                                <small>Kegiatan siswa di luar kelas</small>
                            </div>
                        </div>

                    </div>

                    <div class="hero-floating-card">
                        <strong>10+</strong>
                        <span>Ekstrakurikuler</span>
                    </div>

                </div>

            </div>


            <div class="student-hero-bottom">

                <div class="hero-info-item">
                    <span class="hero-info-icon blue">
                        <i class="bi bi-people-fill"></i>
                    </span>

                    <div>
                        <strong>Organisasi Siswa</strong>
                        <small>OSIS & MPK</small>
                    </div>
                </div>


                <div class="hero-info-item">
                    <span class="hero-info-icon yellow">
                        <i class="bi bi-trophy-fill"></i>
                    </span>

                    <div>
                        <strong>Prestasi Siswa</strong>
                        <small>Akademik & non-akademik</small>
                    </div>
                </div>


                <div class="hero-info-item">
                    <span class="hero-info-icon green">
                        <i class="bi bi-mortarboard-fill"></i>
                    </span>

                    <div>
                        <strong>Program Beasiswa</strong>
                        <small>Dukungan pendidikan</small>
                    </div>
                </div>


                <div class="hero-info-item">
                    <span class="hero-info-icon navy">
                        <i class="bi bi-diagram-3-fill"></i>
                    </span>

                    <div>
                        <strong>Jejaring Alumni</strong>
                        <small>Tracer study & alumni</small>
                    </div>
                </div>

            </div>

        </div>
    </section>



    <main class="container student-content">


        {{-- =========================================================
             OSIS
        ========================================================== --}}
        <section id="osis" class="student-section">

            <div class="student-section-heading">

                <div class="section-heading-icon blue">
                    <i class="bi bi-person-lines-fill"></i>
                </div>

                <div>
                    <span>KEPEMIMPINAN SISWA</span>
                    <h2>OSIS & Majelis Perwakilan Kelas</h2>
                </div>

            </div>


            <div class="osis-layout">

                <div class="osis-image">

                    <img
                        src="https://cdn.medcom.id/dynamic/content/2025/05/14/1758912/BxaCzTX4oF.jpg?w=1024"
                        alt="Aktivitas siswa di sekolah"
                    >

                    <div class="image-caption">
                        <strong>Ruang Belajar & Berorganisasi</strong>
                        <span>
                            Membentuk siswa yang aktif, bertanggung jawab,
                            dan mampu bekerja sama.
                        </span>
                    </div>

                </div>


                <div class="osis-content">

                    <span class="content-kicker">
                        ORGANISASI SISWA
                    </span>

                    <h3>
                        Ruang tumbuh untuk kepemimpinan,
                        kreativitas, dan kontribusi.
                    </h3>

                    <p>
                        Organisasi Siswa Intra Sekolah (OSIS) dan MPK merupakan
                        wadah utama kepemimpinan siswa dalam menggerakkan kegiatan
                        akademis, keagamaan, sosial, dan seni budaya sekolah.
                    </p>


                    <div class="osis-vision">

                        <div class="vision-icon">
                            <i class="bi bi-quote"></i>
                        </div>

                        <div>
                            <span>VISI KEPENGURUSAN OSIS 2026/2027</span>

                            <p>
                                "Mewujudkan OSIS yang Inklusif, Kreatif,
                                Berprestasi, Berkarakter Pancasila, serta
                                Tanggap Terhadap Perkembangan Teknologi Digital."
                            </p>
                        </div>

                    </div>


                    <div class="osis-points">

                        <div>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Kepemimpinan siswa</span>
                        </div>

                        <div>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Kegiatan sosial dan budaya</span>
                        </div>

                        <div>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Pengembangan kreativitas</span>
                        </div>

                    </div>

                </div>

            </div>


            <div class="program-area">

                <div class="program-heading">
                    <div>
                        <span>PROGRAM UNGGULAN</span>
                        <h3>Gerak & Karya Siswa</h3>
                    </div>

                    <i class="bi bi-stars"></i>
                </div>


                <div class="program-grid">

                    <div class="program-item">
                        <div class="program-item-icon red">
                            <i class="bi bi-palette"></i>
                        </div>

                        <div>
                            <strong>Pentas Seni & Classmeet</strong>
                            <small>
                                Ajang ekspresi kreativitas, tari, musik,
                                dan kompetisi antar kelas.
                            </small>
                        </div>
                    </div>


                    <div class="program-item">
                        <div class="program-item-icon green">
                            <i class="bi bi-heart-pulse"></i>
                        </div>

                        <div>
                            <strong>Bakti Sosial & Peduli Sesama</strong>
                            <small>
                                Penggalangan dana bantuan bencana,
                                santunan, dan aksi bersih lingkungan.
                            </small>
                        </div>
                    </div>


                    <div class="program-item">
                        <div class="program-item-icon blue">
                            <i class="bi bi-cpu"></i>
                        </div>

                        <div>
                            <strong>Tech Fest & Hackathon</strong>
                            <small>
                                Kompetisi aplikasi web, desain grafis,
                                dan robotika sederhana.
                            </small>
                        </div>
                    </div>


                    <div class="program-item">
                        <div class="program-item-icon yellow">
                            <i class="bi bi-journals"></i>
                        </div>

                        <div>
                            <strong>Buletin & Jurnalistik Digital</strong>
                            <small>
                                Publikasi majalah sekolah, mading digital,
                                dan liputan video.
                            </small>
                        </div>
                    </div>

                </div>

            </div>

        </section>



        {{-- =========================================================
             EKSTRAKURIKULER
        ========================================================== --}}
        <section id="ekstrakurikuler" class="student-section">

            <div class="student-section-heading">

                <div class="section-heading-icon blue">
                    <i class="bi bi-activity"></i>
                </div>

                <div>
                    <span>PENGEMBANGAN BAKAT & MINAT</span>
                    <h2>Ekstrakurikuler Aktif</h2>
                </div>

                <div class="section-heading-count">
                    <strong>10+</strong>
                    <span>Kegiatan</span>
                </div>

            </div>


            <div class="extracurricular-feature">

                <div class="extracurricular-image">

                    <img
                        src="https://s3.schoolmedia.id/01-cms-website/smanegeri52jkt.sch.id/editor/xt1Z8Lfzq8FQRzBPXeCGCIskJ0YPJ86vtkRChBDo.jpeg"
                        alt="Kegiatan ekstrakurikuler siswa"
                    >

                    <div class="extracurricular-image-overlay">
                        <span>KEGIATAN SISWA</span>
                        <strong>Belajar tidak berhenti di dalam kelas.</strong>
                    </div>

                </div>


                <div class="extracurricular-text">

                    <span class="content-kicker">
                        BAKAT & MINAT
                    </span>

                    <h3>
                        Temukan bidang yang
                        <span>paling kamu sukai.</span>
                    </h3>

                    <p>
                        Beragam kegiatan tersedia untuk membantu siswa
                        mengembangkan kemampuan, kreativitas, kerja sama,
                        disiplin, dan rasa percaya diri.
                    </p>

                    <div class="activity-mini-list">

                        <span>
                            <i class="bi bi-check2"></i>
                            Olahraga
                        </span>

                        <span>
                            <i class="bi bi-check2"></i>
                            Seni & Musik
                        </span>

                        <span>
                            <i class="bi bi-check2"></i>
                            Teknologi
                        </span>

                        <span>
                            <i class="bi bi-check2"></i>
                            Kepemimpinan
                        </span>

                    </div>

                </div>

            </div>


            <div class="activity-grid">

                <div class="activity-card yellow">
                    <div class="activity-icon">
                        <i class="bi bi-compass-fill"></i>
                    </div>

                    <div>
                        <h3>Pramuka Wajib</h3>
                        <p>Kepanduan & Kedisiplinan</p>
                    </div>
                </div>


                <div class="activity-card red">
                    <div class="activity-icon">
                        <i class="bi bi-hospital-fill"></i>
                    </div>

                    <div>
                        <h3>PMR Wira</h3>
                        <p>Palang Merah & Pertolongan Pertama</p>
                    </div>
                </div>


                <div class="activity-card navy">
                    <div class="activity-icon">
                        <i class="bi bi-flag-fill"></i>
                    </div>

                    <div>
                        <h3>Paskibra Sekolah</h3>
                        <p>PBB & Pengibar Bendera</p>
                    </div>
                </div>


                <div class="activity-card blue">
                    <div class="activity-icon">
                        <i class="bi bi-code-slash"></i>
                    </div>

                    <div>
                        <h3>Coding & Tech Club</h3>
                        <p>Web Dev & AI Hacking</p>
                    </div>
                </div>


                <div class="activity-card yellow">
                    <div class="activity-icon">
                        <i class="bi bi-dribbble"></i>
                    </div>

                    <div>
                        <h3>Bola Basket</h3>
                        <p>Tim Basket Putra/Putri</p>
                    </div>
                </div>


                <div class="activity-card green">
                    <div class="activity-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>

                    <div>
                        <h3>Futsal & Sepakbola</h3>
                        <p>Kompetisi Liga Pelajar</p>
                    </div>
                </div>


                <div class="activity-card cyan">
                    <div class="activity-icon">
                        <i class="bi bi-music-note-beamed"></i>
                    </div>

                    <div>
                        <h3>Paduan Suara & Band</h3>
                        <p>Olah Vokal & Alat Musik</p>
                    </div>
                </div>


                <div class="activity-card purple">
                    <div class="activity-icon">
                        <i class="bi bi-chat-quote-fill"></i>
                    </div>

                    <div>
                        <h3>English Debate & Speech</h3>
                        <p>Public Speaking & Debate</p>
                    </div>
                </div>

            </div>

        </section>



        {{-- =========================================================
             PRESTASI
        ========================================================== --}}
        <section id="prestasi" class="student-section">

            <div class="student-section-heading">

                <div class="section-heading-icon yellow">
                    <i class="bi bi-award-fill"></i>
                </div>

                <div>
                    <span>REKAM CAPAIAN</span>
                    <h2>Prestasi Siswa</h2>
                </div>

            </div>


            <div class="achievement-layout">

                <div class="achievement-feature">

                    <div class="achievement-feature-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>

                    <span>JUARA 1 NASIONAL</span>

                    <h3>
                        LKS SMK Bidang Web Technologies
                    </h3>

                    <p>
                        Diselenggarakan oleh Kemendikbudristek RI
                        Tahun 2025.
                    </p>

                    <div class="achievement-person">
                        <small>DIRAIH OLEH</small>
                        <strong>
                            Muhammad Farhan
                            <span>Kelas XII RPL 1</span>
                        </strong>
                    </div>

                </div>


                <div class="achievement-list">

                    <div class="achievement-row blue">

                        <div class="achievement-row-icon">
                            <i class="bi bi-award-fill"></i>
                        </div>

                        <div>
                            <span>MEDALI EMAS</span>
                            <h3>OSN Informatika</h3>
                            <p>
                                Tingkat Provinsi Daerah Istimewa Yogyakarta 2025.
                            </p>
                            <small>
                                Siti Nurhaliza — Kelas XI MIPA 2
                            </small>
                        </div>

                    </div>


                    <div class="achievement-row green">

                        <div class="achievement-row-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>

                        <div>
                            <span>JUARA UMUM</span>
                            <h3>Festival Seni & FLS2N Kabupaten</h3>
                            <p>
                                Kategori Cipta Lagu & Paduan Suara Pelajar 2025.
                            </p>
                            <small>
                                Tim Paduan Suara Voice of Modern
                            </small>
                        </div>

                    </div>

                </div>

            </div>

        </section>



        {{-- =========================================================
             BEASISWA
        ========================================================== --}}
        <section id="beasiswa" class="student-section">

            <div class="student-section-heading">

                <div class="section-heading-icon blue">
                    <i class="bi bi-wallet2"></i>
                </div>

                <div>
                    <span>DUKUNGAN PENDIDIKAN</span>
                    <h2>Program Beasiswa Sekolah</h2>
                </div>

            </div>


            <div class="scholarship-grid">

                <div class="scholarship-card blue">

                    <span class="scholarship-number">01</span>

                    <div class="scholarship-icon">
                        <i class="bi bi-star-fill"></i>
                    </div>

                    <h3>Beasiswa Prestasi Akademik</h3>

                    <p>
                        Bebas biaya SPP 100% bagi siswa peraih peringkat 1
                        paralel sekolah selama 2 semester berturut-turut.
                    </p>

                </div>


                <div class="scholarship-card green">

                    <span class="scholarship-number">02</span>

                    <div class="scholarship-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <h3>Beasiswa KIP & Kesejahteraan</h3>

                    <p>
                        Bantuan perlengkapan sekolah dan subsidi SPP bagi
                        pemegang Kartu Indonesia Pintar (KIP) atau kurang mampu.
                    </p>

                </div>


                <div class="scholarship-card yellow">

                    <span class="scholarship-number">03</span>

                    <div class="scholarship-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>

                    <h3>Beasiswa Bakat Olahraga & Seni</h3>

                    <p>
                        Penghargaan pembebasan biaya bagi peraih medali
                        kejuaraan olahraga/seni minimal tingkat kabupaten/provinsi.
                    </p>

                </div>

            </div>

        </section>



        {{-- =========================================================
             ALUMNI
        ========================================================== --}}
        <section id="alumni" class="student-section alumni-section">

            <div class="alumni-layout">

                <div class="alumni-copy">

                    <span class="alumni-label">
                        JEJARING LULUSAN
                    </span>

                    <h2>
                        Tetap terhubung
                        <span>setelah lulus.</span>
                    </h2>

                    <p>
                        Kami bangga atas capaian ribuan alumni
                        {{ $settings['school_name'] ?? config('app.name') }}
                        yang kini berkiprah di Perguruan Tinggi Negeri unggulan,
                        instansi pemerintah, perusahaan multinasional,
                        serta dunia wirausaha digital.
                    </p>


                    <div class="alumni-stats">

                        <div>
                            <strong>65%</strong>
                            <span>Lolos PTN / Kedinasan</span>
                        </div>

                        <div>
                            <strong>25%</strong>
                            <span>Bekerja di Industri</span>
                        </div>

                        <div>
                            <strong>10%</strong>
                            <span>Wirausaha / Startup</span>
                        </div>

                    </div>

                </div>


                <div class="alumni-form-card">

                    <div class="form-card-heading">

                        <div class="form-card-icon">
                            <i class="bi bi-person-check-fill"></i>
                        </div>

                        <div>
                            <span>TRACER STUDY</span>
                            <h3>Data Alumni</h3>
                        </div>

                    </div>


                    <p>
                        Apakah Anda alumni sekolah ini? Daftarkan data
                        karir/studi Anda untuk memperluas jejaring alumni.
                    </p>


                    <form onsubmit="alert('Terima kasih telah mengisi pendataan alumni!'); return false;">

                        <div class="form-field">
                            <input
                                type="text"
                                placeholder="Nama Lengkap Alumni"
                                required
                            >
                        </div>

                        <div class="form-field">
                            <input
                                type="text"
                                placeholder="Tahun Lulus (Contoh: 2023)"
                                required
                            >
                        </div>

                        <div class="form-field">
                            <input
                                type="text"
                                placeholder="Status Saat Ini (Kuliah / Bekerja di...)"
                                required
                            >
                        </div>

                        <button type="submit" class="alumni-submit">
                            <i class="bi bi-send-fill"></i>
                            Kirim Data Alumni
                        </button>

                    </form>

                </div>

            </div>

        </section>

    </main>

</div>

@endsection