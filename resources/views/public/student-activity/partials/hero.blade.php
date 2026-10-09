<section class="student-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="student-breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">
                        <i class="bi bi-house-door-fill me-1"></i> Beranda
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Kesiswaan
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
                    Wadah resmi kepemimpinan siswa, wadah pengembangan bakat & minat,
                    organisasi OSIS, puluhan ekstrakurikuler unggulan, serta rekam capaian prestasi membanggakan
                    {{ $settings['school_name'] ?? config('app.name') }}.
                </p>

                <div class="student-hero-actions">
                    <a href="#osis" class="student-primary-btn">
                        <i class="bi bi-people-fill"></i> Organisasi OSIS
                    </a>
                    <a href="#ekstrakurikuler" class="student-secondary-btn">
                        <i class="bi bi-grid-3x3-gap-fill"></i> Ekstrakurikuler
                    </a>
                    <a href="#prestasi" class="student-secondary-btn">
                        <i class="bi bi-trophy-fill"></i> Prestasi Siswa
                    </a>
                </div>
            </div>

            <div class="student-hero-visual">
                <div class="hero-image-frame">
                    <div class="student-life-hero-image">
                        <img
                            src="https://assets.pikiran-rakyat.com/crop/0x0%3A0x0/1200x675/photo/2024/05/22/3466153989.jpg"
                            alt="Kegiatan siswa berprestasi"
                        >
                    </div>

                    <div class="hero-image-label">
                        <span><i class="bi bi-stars"></i></span>
                        <div>
                            <strong>Aktif & Berkarakter</strong>
                            <small>Ekosistem pembinaan siswa holistik</small>
                        </div>
                    </div>
                </div>

                <div class="hero-floating-card">
                    <strong>{{ $extracurriculars->count() }}+</strong>
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
                    <strong>Organisasi OSIS</strong>
                    <small>{{ $osisMembers->count() }} Anggota Pengurus Aktif</small>
                </div>
            </div>

            <div class="hero-info-item">
                <span class="hero-info-icon yellow">
                    <i class="bi bi-trophy-fill"></i>
                </span>
                <div>
                    <strong>Prestasi Membanggakan</strong>
                    <small>{{ $achievements->count() }} Rekam Kejuaraan</small>
                </div>
            </div>

            <div class="hero-info-item">
                <span class="hero-info-icon green">
                    <i class="bi bi-mortarboard-fill"></i>
                </span>
                <div>
                    <strong>Program Beasiswa</strong>
                    <small>Dukungan Prestasi & KIP</small>
                </div>
            </div>
        </div>
    </div>
</section>
