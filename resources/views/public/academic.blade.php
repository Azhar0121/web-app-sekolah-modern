@extends('layouts.public')

@section('title', 'Akademik & Kurikulum — ' . config('app.name'))

@section('content')

{{-- ── HERO HEADER ──────────────────────────────────────────────── --}}
<section class="bg-dark text-white py-5 shadow-sm" style="background: linear-gradient(135deg, #071b35 0%, #0f2747 50%, #1e3a8a 100%);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 small">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-warning text-decoration-none"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">Akademik & Kurikulum</li>
            </ol>
        </nav>
        <h1 class="display-6 fw-bold mb-2">Akademik & Program Pembelajaran</h1>
        <p class="lead text-light opacity-75 max-w-2xl mb-0" style="font-size: 1.05rem;">
            Informasi struktur kurikulum, program keahlian, daftar mata pelajaran, kalender akademik publik, serta repositori materi pembelajaran digital {{ $settings['school_name'] ?? config('app.name') }}.
        </p>
    </div>
</section>

<div class="container py-5">

    {{-- ── SECTION 1: KURIKULUM & STRUKTUR PEMBELAJARAN ───────────── --}}
    <section id="kurikulum" class="mb-5 pt-2">
        <div class="row g-4 align-items-center mb-4">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; shrink-0;">
                        <i class="bi bi-journal-bookmark-fill fs-4"></i>
                    </div>
                    <div>
                        <span class="text-warning fw-bold small text-uppercase">SISTEM PEMBELAJARAN</span>
                        <h3 class="fw-bold text-dark mb-0">Implementasi Kurikulum Merdeka</h3>
                    </div>
                </div>
                <p class="text-secondary leading-relaxed mb-4">
                    <strong>{{ $settings['school_name'] ?? config('app.name') }}</strong> menerapkan <strong>Kurikulum Merdeka</strong> yang berfokus pada pengembangan minat, bakat, kepribadian berbasis Profil Pelajar Pancasila, serta penguasaan literasi & numerasi digital terkini.
                </p>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <i class="bi bi-laptop text-primary fs-4 d-block mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Digital Smart Classroom</h6>
                            <small class="text-muted">Pembelajaran interaktif didukung LMS, E-Learning, dan lab komputer CBT modern.</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <i class="bi bi-kanban text-success fs-4 d-block mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Project-Based Learning (P5)</h6>
                            <small class="text-muted">Projek Penguatan Profil Pelajar Pancasila mengasah kemampuan memecahkan masalah nyata.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-top border-4 border-primary shadow-sm rounded-3 p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-award-fill text-primary me-2"></i>Standar Mutu Pembelajaran</h5>
                    <ul class="list-unstyled text-secondary small mb-0">
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="bi bi-check-circle-fill text-success shrink-0 mt-1"></i>
                            <div>
                                <strong class="text-dark d-block">Modul Pembelajaran Terdigitalisasi</strong>
                                <span>Seluruh silabus, RPP, dan bahan ajar dapat diakses peserta didik secara gratis melalui portal e-learning.</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="bi bi-check-circle-fill text-success shrink-0 mt-1"></i>
                            <div>
                                <strong class="text-dark d-block">Evaluasi & Asesmen Terintegrasi</strong>
                                <span>Pelaksanaan Asesmen Sumatif/Formatif menggunakan sistem CBT mandiri dengan hasil transparan.</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success shrink-0 mt-1"></i>
                            <div>
                                <strong class="text-dark d-block">Bimbingan Karir & Konseling Akademik</strong>
                                <span>Layanan konsultasi pemilihan peminatan jurusan, SNMPTN/SNBP, perguruan tinggi unggulan, serta persiapan dunia kerja.</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SECTION 2: PROGRAM KEAHLIAN / JURUSAN ───────────────────── --}}
    <section id="jurusan" class="mb-5 pt-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; shrink-0;">
                    <i class="bi bi-mortarboard-fill fs-4"></i>
                </div>
                <div>
                    <span class="text-warning fw-bold small text-uppercase">PEMINATAN KEAHLIAN</span>
                    <h3 class="fw-bold text-dark mb-0">Program Keahlian & Jurusan</h3>
                </div>
            </div>
            <span class="badge text-bg-primary fs-7">{{ $departments->count() ?: 3 }} Jurusan Aktif</span>
        </div>

        @if ($departments->isNotEmpty())
        <div class="row g-4">
            @foreach ($departments as $dept)
            <div class="col-md-6 col-lg-4">
                <div class="card border-top border-4 border-warning shadow-sm rounded-3 p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning-emphasis rounded-3" style="width:44px; height:44px; shrink-0;">
                            <i class="bi bi-cpu-fill fs-4"></i>
                        </div>
                        <div>
                            <span class="badge bg-dark text-warning px-2 py-1 small">{{ $dept->code }}</span>
                            <h5 class="fw-bold text-dark mb-0 mt-1">{{ $dept->name }}</h5>
                        </div>
                    </div>
                    <p class="text-secondary small mb-0 leading-relaxed">
                        {{ $dept->description ?? 'Program keahlian unggulan yang membekali peserta didik dengan kompetensi keahlian praktis, sertifikasi profesional, dan persiapan studi lanjut.' }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-top border-4 border-primary shadow-sm rounded-3 p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:44px; height:44px; shrink-0;">
                            <i class="bi bi-code-slash fs-4"></i>
                        </div>
                        <div>
                            <span class="badge bg-primary px-2 py-1 small">RPL</span>
                            <h5 class="fw-bold text-dark mb-0 mt-1">Rekayasa Perangkat Lunak</h5>
                        </div>
                    </div>
                    <p class="text-secondary small mb-0">Fokus pemograman web, mobile app development, basis data, dan kecerdasan buatan (AI).</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-top border-4 border-info shadow-sm rounded-3 p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-3" style="width:44px; height:44px; shrink-0;">
                            <i class="bi bi-diagram-3-fill fs-4"></i>
                        </div>
                        <div>
                            <span class="badge bg-info text-dark px-2 py-1 small">TKJ</span>
                            <h5 class="fw-bold text-dark mb-0 mt-1">Teknik Komputer & Jaringan</h5>
                        </div>
                    </div>
                    <p class="text-secondary small mb-0">Fokus arsitektur jaringan komputer, cloud computing, administrasi server, dan cybersecurity.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-top border-4 border-success shadow-sm rounded-3 p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-3" style="width:44px; height:44px; shrink-0;">
                            <i class="bi bi-calculator-fill fs-4"></i>
                        </div>
                        <div>
                            <span class="badge bg-success px-2 py-1 small">MIPA</span>
                            <h5 class="fw-bold text-dark mb-0 mt-1">Matematika & Ilmu Alam</h5>
                        </div>
                    </div>
                    <p class="text-secondary small mb-0">Pendalaman riset ilmiah Fisika, Kimia, Biologi, dan Matematika tingkat lanjut untuk Olimpiade/PTN.</p>
                </div>
            </div>
        </div>
        @endif
    </section>

    {{-- ── SECTION 3: DAFTAR MATA PELAJARAN ────────────────────────── --}}
    <section id="mapel" class="mb-5 pt-3">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; flex-shrink: 0;">
                    <i class="bi bi-book-half fs-4"></i>
                </div>
                <div>
                    <span class="text-warning fw-bold small text-uppercase">STRUKTUR MAPEL</span>
                    <h3 class="fw-bold text-dark mb-0">Daftar Mata Pelajaran Terdaftar</h3>
                </div>
            </div>

            @if ($subjects->isNotEmpty())
            {{-- Filter Pills Jurusan / Umum --}}
            <div class="d-flex flex-wrap gap-2 align-items-center" id="subject-filter-container">
                <button type="button" class="btn btn-sm btn-primary active subject-filter-btn" data-filter="all">
                    Semua <span class="badge bg-white text-primary rounded-pill ms-1">{{ $subjects->count() }}</span>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary subject-filter-btn" data-filter="umum">
                    Umum <span class="badge bg-secondary text-white rounded-pill ms-1">{{ $subjects->whereNull('department_id')->count() }}</span>
                </button>
                @foreach ($departments as $dept)
                    @php
                        $deptSubjectCount = $subjects->where('department_id', $dept->id)->count();
                    @endphp
                    <button type="button" class="btn btn-sm btn-outline-secondary subject-filter-btn" data-filter="{{ $dept->id }}">
                        {{ $dept->code ?? $dept->name }}
                        <span class="badge bg-secondary text-white rounded-pill ms-1">{{ $deptSubjectCount }}</span>
                    </button>
                @endforeach
            </div>
            @endif
        </div>

        <div class="card border-0 shadow-sm rounded-3 p-4">
            @if ($subjects->isNotEmpty())
            <div class="row g-3" id="subject-list-container">
                @foreach ($subjects as $subj)
                <div class="col-md-6 col-lg-4 subject-card-item" data-department="{{ $subj->department_id ?? 'umum' }}">
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border h-100">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-2 fw-bold small" style="width:40px; height:40px; flex-shrink: 0;">
                            {{ strtoupper(substr($subj->code ?? $subj->name, 0, 3)) }}
                        </div>
                        <div class="overflow-hidden flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                                <small class="text-muted fs-xs">Kode: {{ $subj->code ?? '-' }}</small>
                                @if ($subj->department)
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" style="font-size: 0.7rem;">
                                        {{ $subj->department->code ?? $subj->department->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25" style="font-size: 0.7rem;">
                                        Umum
                                    </span>
                                @endif
                            </div>
                            <strong class="d-block text-dark small text-truncate" title="{{ $subj->name }}">{{ $subj->name }}</strong>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div id="no-subject-alert" class="text-center py-5 d-none">
                <i class="bi bi-journal-x text-muted display-6 d-block mb-2"></i>
                <p class="text-muted small mb-0">Tidak ada mata pelajaran terdaftar untuk kategori jurusan yang dipilih.</p>
            </div>
            @else
            <div class="row g-3">
                <div class="col-md-4"><div class="p-3 bg-light rounded-3 border fw-semibold text-dark"><i class="bi bi-book text-primary me-2"></i>Matematika Wajib</div></div>
                <div class="col-md-4"><div class="p-3 bg-light rounded-3 border fw-semibold text-dark"><i class="bi bi-translate text-primary me-2"></i>Bahasa Indonesia</div></div>
                <div class="col-md-4"><div class="p-3 bg-light rounded-3 border fw-semibold text-dark"><i class="bi bi-globe text-primary me-2"></i>Bahasa Inggris</div></div>
                <div class="col-md-4"><div class="p-3 bg-light rounded-3 border fw-semibold text-dark"><i class="bi bi-moon-stars text-primary me-2"></i>Pendidikan Agama & Budi Pekerti</div></div>
                <div class="col-md-4"><div class="p-3 bg-light rounded-3 border fw-semibold text-dark"><i class="bi bi-flag text-primary me-2"></i>Pendidikan Pancasila</div></div>
                <div class="col-md-4"><div class="p-3 bg-light rounded-3 border fw-semibold text-dark"><i class="bi bi-cpu text-primary me-2"></i>Informatika & Pemrograman</div></div>
            </div>
            @endif
        </div>
    </section>

    {{-- ── SECTION 4: KALENDER AKADEMIK & AGENDAS UJIAN ───────────── --}}
    <section id="kalender" class="mb-5 pt-3">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; shrink-0;">
                <i class="bi bi-calendar-event-fill fs-4"></i>
            </div>
            <div>
                <span class="text-warning fw-bold small text-uppercase">AGENDA SEKOLAH</span>
                <h3 class="fw-bold text-dark mb-0">Kalender Akademik & Jadwal Ujian</h3>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-calendar3 text-primary me-2"></i>Agenda Semester Ganjil 2026/2027</h5>
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Awal Masuk Tahun Ajaran Baru</span>
                            <span class="badge text-bg-primary">15 Juli 2026</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Masa Pengenalan Lingkungan Sekolah (MPLS)</span>
                            <span class="badge text-bg-info">16 – 18 Juli 2026</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Penilaian Tengah Semester (PTS) Ganjil</span>
                            <span class="badge text-bg-warning">21 – 26 Sept 2026</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Penilaian Akhir Semester (PAS) Ganjil CBT</span>
                            <span class="badge text-bg-danger">30 Nov – 10 Des 2026</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Pembagian Rapor Digital & Libur Semester</span>
                            <span class="badge text-bg-success">18 Des 2026</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-calendar3-range text-primary me-2"></i>Agenda Semester Genap 2026/2027</h5>
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Awal KBM Semester Genap</span>
                            <span class="badge text-bg-primary">05 Januari 2027</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Uji Kompetensi Keahlian (UKK / Ujian Praktik)</span>
                            <span class="badge text-bg-warning">15 – 27 Feb 2027</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Asesmen Sekolah Utama (Kelas XII)</span>
                            <span class="badge text-bg-danger">15 – 24 Maret 2027</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Penilaian Akhir Tahun (PAT) Kelas X & XI</span>
                            <span class="badge text-bg-info">07 – 17 Juni 2027</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Wisuda & Pelepasan Kelulusan Siswa</span>
                            <span class="badge text-bg-success">24 Juni 2027</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SECTION 5: REPOSITORI MATERI PEMBELAJARAN PUBLIK ───────── --}}
    <section id="repositori" class="mb-4 pt-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; shrink-0;">
                    <i class="bi bi-cloud-arrow-down-fill fs-4"></i>
                </div>
                <div>
                    <span class="text-warning fw-bold small text-uppercase">REPOSITORI MATERI</span>
                    <h3 class="fw-bold text-dark mb-0">Bahan Ajar & Modul Publik</h3>
                </div>
            </div>
        </div>

        @if ($materials->isNotEmpty())
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Judul Materi</th>
                            <th>Mata Pelajaran</th>
                            <th>Deskripsi</th>
                            <th class="text-end">Unduh / Tautan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $index => $mat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong class="text-dark">{{ $mat->title }}</strong></td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $mat->teachingAssignment?->subject?->name ?? 'Umum' }}</span></td>
                            <td class="text-secondary max-w-xs text-truncate">{{ $mat->description ?? '-' }}</td>
                            <td class="text-end">
                                @if ($mat->hasFile())
                                    <a href="{{ asset('storage/' . $mat->file_path) }}" target="_blank" download class="btn btn-sm btn-outline-primary fw-semibold">
                                        <i class="bi bi-download me-1"></i> Download File
                                    </a>
                                @elseif($mat->hasLink())
                                    <a href="{{ $mat->link }}" target="_blank" class="btn btn-sm btn-outline-secondary fw-semibold">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                                    </a>
                                @else
                                    <span class="text-muted fs-xs">Tidak Ada Lampiran</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="card border-0 shadow-sm rounded-3 p-5 text-center">
            <div class="d-inline-flex align-items-center justify-content-center bg-light text-secondary rounded-circle mx-auto mb-3" style="width:60px; height:60px;">
                <i class="bi bi-folder-x fs-2"></i>
            </div>
            <h5 class="fw-bold text-dark">Repositori Modul Pembelajaran</h5>
            <p class="text-muted small max-w-md mx-auto mb-0">
                Bahan ajar publik, modul PDF, dan kisi-kisi soal akan diunggah secara berkala oleh tim tenaga pendidik sekolah.
            </p>
        </div>
        @endif
    </section>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.subject-filter-btn');
    const subjectCards = document.querySelectorAll('.subject-card-item');
    const noSubjectAlert = document.getElementById('no-subject-alert');

    if (!filterButtons.length || !subjectCards.length) return;

    filterButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const filter = this.getAttribute('data-filter');

            // Reset active style for all buttons
            filterButtons.forEach(function (btn) {
                btn.classList.remove('btn-primary', 'active');
                btn.classList.add('btn-outline-secondary');
                const badge = btn.querySelector('.badge');
                if (badge) {
                    badge.classList.remove('bg-white', 'text-primary');
                    badge.classList.add('bg-secondary', 'text-white');
                }
            });

            // Set active style on clicked button
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary', 'active');
            const activeBadge = this.querySelector('.badge');
            if (activeBadge) {
                activeBadge.classList.remove('bg-secondary', 'text-white');
                activeBadge.classList.add('bg-white', 'text-primary');
            }

            // Filter cards
            let visibleCount = 0;
            subjectCards.forEach(function (card) {
                const cardDept = card.getAttribute('data-department');
                if (filter === 'all' || cardDept === filter) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Toggle empty state alert
            if (noSubjectAlert) {
                if (visibleCount === 0) {
                    noSubjectAlert.classList.remove('d-none');
                } else {
                    noSubjectAlert.classList.add('d-none');
                }
            }
        });
    });
});
</script>
@endpush

