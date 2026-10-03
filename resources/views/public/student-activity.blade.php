@extends('layouts.public')

@section('title', 'Kesiswaan & Alumni — ' . config('app.name'))

@section('content')

{{-- ── HERO HEADER ──────────────────────────────────────────────── --}}
<section class="bg-dark text-white py-5 shadow-sm" style="background: linear-gradient(135deg, #071b35 0%, #0f2747 50%, #1e3a8a 100%);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 small">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-warning text-decoration-none"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">Kesiswaan & Alumni</li>
            </ol>
        </nav>
        <h1 class="display-6 fw-bold mb-2">Kesiswaan, Ekstrakurikuler & Jejaring Alumni</h1>
        <p class="lead text-light opacity-75 max-w-2xl mb-0" style="font-size: 1.05rem;">
            Wadah kegiatan organisasi siswa, pengemban bakat minat melalui ekstrakurikuler, apresiasi prestasi, info beasiswa, serta jejaring alumni {{ $settings['school_name'] ?? config('app.name') }}.
        </p>
    </div>
</section>

<div class="container py-5">

    {{-- ── SECTION 1: ORGANISASI SISWA (OSIS & MPK) ───────────────── --}}
    <section id="osis" class="mb-5 pt-2">
        <div class="row g-4 align-items-center mb-4">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; shrink-0;">
                        <i class="bi bi-person-lines-fill fs-4"></i>
                    </div>
                    <div>
                        <span class="text-warning fw-bold small text-uppercase">KEPODANG ORGANISASI</span>
                        <h3 class="fw-bold text-dark mb-0">OSIS & Majelis Perwakilan Kelas (MPK)</h3>
                    </div>
                </div>
                <p class="text-secondary leading-relaxed mb-4">
                    Organisasi Siswa Intra Sekolah (OSIS) dan MPK merupakan wadah utama kepemimpinan siswa dalam menggerakkan kegiatan akademis, keagamaan, sosial, dan seni budaya sekolah.
                </p>

                <div class="card border-0 bg-primary-subtle text-dark rounded-3 p-3 mb-3 border border-primary-subtle">
                    <h6 class="fw-bold text-primary mb-1"><i class="bi bi-quote me-1"></i>Visi Kepengurusan OSIS Periode 2026/2027</h6>
                    <p class="mb-0 small fst-italic">
                        "Mewujudkan OSIS yang Inklusif, Kreatif, Berprestasi, Berkarakter Pancasila, serta Tanggap Terhadap Perkembangan Teknologi Digital."
                    </p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-top border-4 border-primary shadow-sm rounded-3 p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-trophy-fill text-warning me-2"></i>Program Kerja Unggulan OSIS</h5>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <strong class="text-dark d-block small mb-1"><i class="bi bi-palette text-danger me-1"></i>Pentas Seni & Classmeet</strong>
                                <small class="text-muted fs-xs">Ajang ekspresi kreativitas, tari, musik, dan kompetisi antar kelas.</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <strong class="text-dark d-block small mb-1"><i class="bi bi-heart-pulse text-success me-1"></i>Bakti Sosial & Peduli Sesama</strong>
                                <small class="text-muted fs-xs">Penggalangan dana bantuan bencana, santunan, dan aksi bersih lingkungan.</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <strong class="text-dark d-block small mb-1"><i class="bi bi-cpu text-info me-1"></i>Tech Fest & Hackathon</strong>
                                <small class="text-muted fs-xs">Kompetisi pembuatan aplikasi web, desain grafis, dan robotika sederhana.</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <strong class="text-dark d-block small mb-1"><i class="bi bi-journals text-warning me-1"></i>Buletin & Jurnalistik Digital</strong>
                                <small class="text-muted fs-xs">Publikasi majalah dinding digital, majalah sekolah, dan liputan video.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SECTION 2: EKSTRAKURIKULER ──────────────────────────────── --}}
    <section id="ekstrakurikuler" class="mb-5 pt-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; shrink-0;">
                    <i class="bi bi-activity fs-4"></i>
                </div>
                <div>
                    <span class="text-warning fw-bold small text-uppercase">PENGEMBANGAN BAKAT</span>
                    <h3 class="fw-bold text-dark mb-0">Daftar Ekstrakurikuler Aktif</h3>
                </div>
            </div>
            <span class="badge text-bg-primary fs-7">10+ Ekstrakurikuler</span>
        </div>

        <div class="row g-3">
            {{-- Pramuka & Kepanduan --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning-emphasis rounded-3" style="width:40px; height:40px; shrink-0;">
                            <i class="bi bi-compass-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">Pramuka Wajib</h6>
                            <small class="text-muted fs-xs">Kepanduan & Kedisiplinan</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PMR --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-3" style="width:40px; height:40px; shrink-0;">
                            <i class="bi bi-hospital-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">PMR Wira</h6>
                            <small class="text-muted fs-xs">Palang Merah & Pertolongan Pertama</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Paskibra --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-dark bg-opacity-10 text-dark rounded-3" style="width:40px; height:40px; shrink-0;">
                            <i class="bi bi-flag-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">Paskibra Sekolah</h6>
                            <small class="text-muted fs-xs">PBB & Pengibar Bendera</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Coding & Robotika --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:40px; height:40px; shrink-0;">
                            <i class="bi bi-code-slash fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">Coding & Tech Club</h6>
                            <small class="text-muted fs-xs">Web Dev & AI Hacking</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Basket --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning-emphasis rounded-3" style="width:40px; height:40px; shrink-0;">
                            <i class="bi bi-dribbble fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">Bola Basket</h6>
                            <small class="text-muted fs-xs">Tim Basket Putra/Putri</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Futsal --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-3" style="width:40px; height:40px; shrink-0;">
                            <i class="bi bi-trophy-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">Futsal & Sepakbola</h6>
                            <small class="text-muted fs-xs">Kompetisi Liga Pelajar</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Paduan Suara & Musik --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-3" style="width:40px; height:40px; shrink-0;">
                            <i class="bi bi-music-note-beamed fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">Paduan Suara & Band</h6>
                            <small class="text-muted fs-xs">Olah Vokal & Alat Musik</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- English Debate --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-secondary bg-opacity-10 text-secondary rounded-3" style="width:40px; height:40px; shrink-0;">
                            <i class="bi bi-chat-quote-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 small">English Debate & Speech</h6>
                            <small class="text-muted fs-xs">Public Speaking & Debate</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SECTION 3: GALERI PRESTASI SISWA ────────────────────────── --}}
    <section id="prestasi" class="mb-5 pt-3">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; shrink-0;">
                <i class="bi bi-award-fill fs-4"></i>
            </div>
            <div>
                <span class="text-warning fw-bold small text-uppercase">REKAP PRESTASI</span>
                <h3 class="fw-bold text-dark mb-0">Capaian & Prestasi Siswa</h3>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-top border-4 border-warning shadow-sm rounded-3 p-4 h-100 text-center">
                    <span class="badge text-bg-warning px-3 py-1 fw-bold mx-auto mb-2">JUARA 1 NASIONAL</span>
                    <h5 class="fw-bold text-dark mb-1">LKS SMK Bidang Web Technologies</h5>
                    <p class="text-secondary small mb-2">Diselenggarakan oleh Kemendikbudristek RI Tahun 2025.</p>
                    <small class="text-muted d-block border-top pt-2">Diraih oleh: <strong>Muhammad Farhan (Kelas XII RPL 1)</strong></small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-top border-4 border-primary shadow-sm rounded-3 p-4 h-100 text-center">
                    <span class="badge text-bg-primary px-3 py-1 fw-bold mx-auto mb-2">MEDALI EMAS</span>
                    <h5 class="fw-bold text-dark mb-1">Olimpiade Sains Nasional (OSN) Informatika</h5>
                    <p class="text-secondary small mb-2">Tingkat Provinsi Daerah Istimewa Yogyakarta 2025.</p>
                    <small class="text-muted d-block border-top pt-2">Diraih oleh: <strong>Siti Nurhaliza (Kelas XI MIPA 2)</strong></small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-top border-4 border-success shadow-sm rounded-3 p-4 h-100 text-center">
                    <span class="badge text-bg-success px-3 py-1 fw-bold mx-auto mb-2">JUARA UMUM</span>
                    <h5 class="fw-bold text-dark mb-1">Festival Seni & FLS2N Kabupaten</h5>
                    <p class="text-secondary small mb-2">Kategori Cipta Lagu & Paduan Suara Pelajar 2025.</p>
                    <small class="text-muted d-block border-top pt-2">Diraih oleh: <strong>Tim Paduan Suara Voice of Modern</strong></small>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SECTION 4: INFORMASI BEASISWA ────────────────────────────── --}}
    <section id="beasiswa" class="mb-5 pt-3">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; shrink-0;">
                <i class="bi bi-wallet2 fs-4"></i>
            </div>
            <div>
                <span class="text-warning fw-bold small text-uppercase">BANTUAN PENDIDIKAN</span>
                <h3 class="fw-bold text-dark mb-0">Program Beasiswa Sekolah</h3>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width:40px; height:40px; shrink-0;">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Beasiswa Prestasi Akademik</h6>
                    </div>
                    <p class="text-secondary small mb-0">Bebas biaya SPP 100% bagi siswa peraih peringkat 1 paralel sekolah selama 2 semester berturut-turut.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle" style="width:40px; height:40px; shrink-0;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Beasiswa KIP & Kesejahteraan</h6>
                    </div>
                    <p class="text-secondary small mb-0">Bantuan perlengkapan sekolah dan subsidi SPP bagi pemegang Kartu Indonesia Pintar (KIP) atau kurang mampu.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning text-dark rounded-circle" style="width:40px; height:40px; shrink-0;">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Beasiswa Bakat Olahraga & Seni</h6>
                    </div>
                    <p class="text-secondary small mb-0">Penghargaan pembebasan biaya bagi peraih medali kejuaraan olahraga/seni minimal tingkat kabupaten/provinsi.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SECTION 5: JEJARING ALUMNI & TRACER STUDY ────────────────── --}}
    <section id="alumni" class="mb-4 pt-3">
        <div class="card bg-dark text-white rounded-4 p-4 p-md-5 shadow-sm" style="background: linear-gradient(135deg, #071b35 0%, #0f2747 100%);">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge text-bg-warning px-3 py-1 fw-bold mb-2">JEJARING LULUSAN</span>
                    <h3 class="fw-bold mb-2">Tracer Study & Alumni Network</h3>
                    <p class="text-light opacity-75 small leading-relaxed mb-4">
                        Kami bangga atas capaian ribuan alumni {{ $settings['school_name'] ?? config('app.name') }} yang kini berkiprah di Perguruan Tinggi Negeri unggulan, instansi pemerintah, perusahaan multinasional, serta dunia wirausaha digital.
                    </p>

                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="p-3 bg-white bg-opacity-10 rounded-3">
                                <strong class="display-6 fw-bold text-warning d-block">65%</strong>
                                <small class="text-white-50 fs-xs">Lolos PTN / Kedinasan</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-white bg-opacity-10 rounded-3">
                                <strong class="display-6 fw-bold text-info d-block">25%</strong>
                                <small class="text-white-50 fs-xs">Bekerja di Industri</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-white bg-opacity-10 rounded-3">
                                <strong class="display-6 fw-bold text-success d-block">10%</strong>
                                <small class="text-white-50 fs-xs">Wirausaha / Startup</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 text-dark rounded-3 p-4">
                        <h6 class="fw-bold mb-2"><i class="bi bi-person-check-fill text-primary me-2"></i>Formulir Data Alumni (Tracer Study)</h6>
                        <p class="text-muted fs-xs mb-3">Apakah Anda alumni sekolah ini? Daftarkan data karir/studi Anda untuk memperluas jejaring alumni.</p>
                        <form onsubmit="alert('Terima kasih telah mengisi pendataan alumni!'); return false;">
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm" placeholder="Nama Lengkap Alumni" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm" placeholder="Tahun Lulus (Contoh: 2023)" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control form-control-sm" placeholder="Status Saat Ini (Kuliah / Bekerja di...)" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                                <i class="bi bi-send-fill me-1"></i> Kirim Data Alumni
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection
