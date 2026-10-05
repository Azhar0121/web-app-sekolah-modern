@extends('layouts.public')

@section('title', 'Akademik & Kurikulum — ' . config('app.name'))

@push('styles')

<link rel="stylesheet" href="{{ asset('css/public/academic.css') }}">
@endpush

@section('content')

<section class="academic-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="academic-breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">
                        <i class="bi bi-house-door-fill"></i>
                        Beranda
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Akademik & Kurikulum
                </li>
            </ol>
        </nav>


    <div class="academic-hero-content">
        <span class="academic-hero-label">AKADEMIK & PEMBELAJARAN</span>

        <h1>Akademik & Program Pembelajaran</h1>

        <p>
            Informasi struktur kurikulum, program keahlian, daftar mata pelajaran,
            kalender akademik publik, serta unduh panduan & silabus kurikulum resmi
            {{ $settings['school_name'] ?? config('app.name') }}.
        </p>
    </div>
</div>


</section>

<div class="container academic-container">


{{-- =========================================================
     SECTION 1 — KURIKULUM
     ========================================================= --}}
<section id="kurikulum" class="academic-section">

    <div class="academic-section-heading">
        <div class="academic-heading-icon">
            <i class="bi bi-journal-bookmark-fill"></i>
        </div>

        <div>
            <span class="academic-eyebrow">SISTEM PEMBELAJARAN</span>
            <h2>Implementasi Kurikulum Merdeka</h2>
        </div>
    </div>

    <div class="row g-4 align-items-stretch">

        <div class="col-lg-6">
            <div class="academic-content-block">
                <p class="academic-intro">
                    <strong>{{ $settings['school_name'] ?? config('app.name') }}</strong>
                    menerapkan <strong>Kurikulum Merdeka</strong> yang berfokus pada
                    pengembangan minat, bakat, kepribadian berbasis Profil Pelajar
                    Pancasila, serta penguasaan literasi dan numerasi digital terkini.
                </p>

                <div class="academic-feature-grid">

                    <div class="academic-feature-card">
                        <div class="academic-feature-icon">
                            <i class="bi bi-laptop"></i>
                        </div>

                        <div>
                            <h3>Digital Smart Classroom</h3>
                            <p>
                                Pembelajaran interaktif didukung LMS, E-Learning,
                                dan lab komputer CBT modern.
                            </p>
                        </div>
                    </div>

                    <div class="academic-feature-card">
                        <div class="academic-feature-icon green">
                            <i class="bi bi-kanban"></i>
                        </div>

                        <div>
                            <h3>Project-Based Learning (P5)</h3>
                            <p>
                                Projek Penguatan Profil Pelajar Pancasila mengasah
                                kemampuan memecahkan masalah nyata.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-lg-6">
            <div class="academic-info-card">

                <div class="academic-card-title">
                    <div class="academic-small-icon">
                        <i class="bi bi-award-fill"></i>
                    </div>

                    <h3>Standar Mutu Pembelajaran</h3>
                </div>

                <ul class="academic-check-list">

                    <li>
                        <i class="bi bi-check-circle-fill"></i>

                        <div>
                            <strong>Modul Pembelajaran Terdigitalisasi</strong>
                            <p>
                                Seluruh silabus, RPP, dan bahan ajar dapat diakses
                                peserta didik secara gratis melalui portal e-learning.
                            </p>
                        </div>
                    </li>

                    <li>
                        <i class="bi bi-check-circle-fill"></i>

                        <div>
                            <strong>Evaluasi & Asesmen Terintegrasi</strong>
                            <p>
                                Pelaksanaan Asesmen Sumatif/Formatif menggunakan
                                sistem CBT mandiri dengan hasil transparan.
                            </p>
                        </div>
                    </li>

                    <li>
                        <i class="bi bi-check-circle-fill"></i>

                        <div>
                            <strong>Bimbingan Karir & Konseling Akademik</strong>
                            <p>
                                Layanan konsultasi pemilihan peminatan jurusan,
                                SNMPTN/SNBP, perguruan tinggi unggulan, serta
                                persiapan dunia kerja.
                            </p>
                        </div>
                    </li>

                </ul>

            </div>
        </div>

    </div>
</section>


{{-- =========================================================
     SECTION 2 — JURUSAN
     ========================================================= --}}
<section id="jurusan" class="academic-section">

    <div class="academic-section-header">

        <div class="academic-section-heading">
            <div class="academic-heading-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div>
                <span class="academic-eyebrow">PEMINATAN KEAHLIAN</span>
                <h2>Program Keahlian & Jurusan</h2>
            </div>
        </div>

        <span class="academic-count-badge">
            {{ $departments->count() ?: 3 }} Jurusan Aktif
        </span>

    </div>


    @if ($departments->isNotEmpty())

        <div class="row g-4">

            @foreach ($departments as $dept)

                <div class="col-md-6 col-lg-4">
                    <div class="department-card">

                        <div class="department-top">

                            <div class="department-icon">
                                <i class="bi bi-cpu-fill"></i>
                            </div>

                            <div class="department-title">
                                <span>{{ $dept->code }}</span>
                                <h3>{{ $dept->name }}</h3>
                            </div>

                        </div>

                        <p>
                            {{ $dept->description ?? 'Program keahlian unggulan yang membekali peserta didik dengan kompetensi keahlian praktis, sertifikasi profesional, dan persiapan studi lanjut.' }}
                        </p>

                    </div>
                </div>

            @endforeach

        </div>

    @else

        <div class="row g-4">

            <div class="col-md-4">
                <div class="department-card department-blue">
                    <div class="department-top">
                        <div class="department-icon">
                            <i class="bi bi-code-slash"></i>
                        </div>

                        <div class="department-title">
                            <span>RPL</span>
                            <h3>Rekayasa Perangkat Lunak</h3>
                        </div>
                    </div>

                    <p>
                        Fokus pemograman web, mobile app development, basis data,
                        dan kecerdasan buatan (AI).
                    </p>
                </div>
            </div>


            <div class="col-md-4">
                <div class="department-card department-info">
                    <div class="department-top">
                        <div class="department-icon">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>

                        <div class="department-title">
                            <span>TKJ</span>
                            <h3>Teknik Komputer & Jaringan</h3>
                        </div>
                    </div>

                    <p>
                        Fokus arsitektur jaringan komputer, cloud computing,
                        administrasi server, dan cybersecurity.
                    </p>
                </div>
            </div>


            <div class="col-md-4">
                <div class="department-card department-green">
                    <div class="department-top">
                        <div class="department-icon">
                            <i class="bi bi-calculator-fill"></i>
                        </div>

                        <div class="department-title">
                            <span>MIPA</span>
                            <h3>Matematika & Ilmu Alam</h3>
                        </div>
                    </div>

                    <p>
                        Pendalaman riset ilmiah Fisika, Kimia, Biologi,
                        dan Matematika tingkat lanjut untuk Olimpiade/PTN.
                    </p>
                </div>
            </div>

        </div>

    @endif

</section>


{{-- =========================================================
     SECTION 3 — MATA PELAJARAN
     ========================================================= --}}
<section id="mapel" class="academic-section">

    <div class="academic-section-header academic-subject-header">

        <div class="academic-section-heading">
            <div class="academic-heading-icon">
                <i class="bi bi-book-half"></i>
            </div>

            <div>
                <span class="academic-eyebrow">STRUKTUR MAPEL</span>
                <h2>Daftar Mata Pelajaran Terdaftar</h2>
            </div>
        </div>


        @if ($subjects->isNotEmpty())

            <div class="subject-filter-container" id="subject-filter-container">

                <button type="button"
                        class="subject-filter-btn active"
                        data-filter="all">
                    Semua
                    <span>{{ $subjects->count() }}</span>
                </button>

                <button type="button"
                        class="subject-filter-btn"
                        data-filter="umum">
                    Umum
                    <span>{{ $subjects->whereNull('department_id')->count() }}</span>
                </button>

                @foreach ($departments as $dept)

                    @php
                        $deptSubjectCount = $subjects->where('department_id', $dept->id)->count();
                    @endphp

                    <button type="button"
                            class="subject-filter-btn"
                            data-filter="{{ $dept->id }}">
                        {{ $dept->code ?? $dept->name }}
                        <span>{{ $deptSubjectCount }}</span>
                    </button>

                @endforeach

            </div>

        @endif

    </div>


    <div class="subjects-wrapper">

        @if ($subjects->isNotEmpty())

            <div class="row g-3" id="subject-list-container">

                @foreach ($subjects as $subj)

                    <div class="col-md-6 col-lg-4 subject-card-item"
                         data-department="{{ $subj->department_id ?? 'umum' }}">

                        <div class="subject-card">

                            <div class="subject-code">
                                {{ strtoupper(substr($subj->code ?? $subj->name, 0, 3)) }}
                            </div>

                            <div class="subject-content">

                                <div class="subject-meta">

                                    <small>
                                        Kode: {{ $subj->code ?? '-' }}
                                    </small>

                                    @if ($subj->department)

                                        <span>
                                            {{ $subj->department->code ?? $subj->department->name }}
                                        </span>

                                    @else

                                        <span class="general">
                                            Umum
                                        </span>

                                    @endif

                                </div>

                                <strong title="{{ $subj->name }}">
                                    {{ $subj->name }}
                                </strong>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            <div id="no-subject-alert" class="subject-empty d-none">

                <i class="bi bi-journal-x"></i>

                <p>
                    Tidak ada mata pelajaran terdaftar untuk kategori jurusan
                    yang dipilih.
                </p>

            </div>

        @else

            <div class="row g-3">

                @php
                    $defaultSubjects = [
                        ['icon' => 'bi-book', 'name' => 'Matematika Wajib'],
                        ['icon' => 'bi-translate', 'name' => 'Bahasa Indonesia'],
                        ['icon' => 'bi-globe', 'name' => 'Bahasa Inggris'],
                        ['icon' => 'bi-moon-stars', 'name' => 'Pendidikan Agama & Budi Pekerti'],
                        ['icon' => 'bi-flag', 'name' => 'Pendidikan Pancasila'],
                        ['icon' => 'bi-cpu', 'name' => 'Informatika & Pemrograman'],
                    ];
                @endphp

                @foreach ($defaultSubjects as $subject)

                    <div class="col-md-6 col-lg-4">

                        <div class="default-subject">
                            <i class="bi {{ $subject['icon'] }}"></i>
                            <span>{{ $subject['name'] }}</span>
                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

</section>


{{-- =========================================================
     SECTION 4 — KALENDER
     ========================================================= --}}
<section id="kalender" class="academic-section">

    <div class="academic-section-heading">
        <div class="academic-heading-icon">
            <i class="bi bi-calendar-event-fill"></i>
        </div>

        <div>
            <span class="academic-eyebrow">AGENDA SEKOLAH</span>
            <h2>Kalender Akademik & Jadwal Ujian</h2>
        </div>
    </div>


    <div class="row g-4">

        <div class="col-lg-6">

            <div class="calendar-card">

                <div class="calendar-title">
                    <i class="bi bi-calendar3"></i>
                    <h3>Agenda Semester Ganjil 2026/2027</h3>
                </div>

                <ul class="calendar-list">

                    <li>
                        <span>Awal Masuk Tahun Ajaran Baru</span>
                        <strong>15 Juli 2026</strong>
                    </li>

                    <li>
                        <span>Masa Pengenalan Lingkungan Sekolah (MPLS)</span>
                        <strong>16 – 18 Juli 2026</strong>
                    </li>

                    <li>
                        <span>Penilaian Tengah Semester (PTS) Ganjil</span>
                        <strong>21 – 26 Sept 2026</strong>
                    </li>

                    <li>
                        <span>Penilaian Akhir Semester (PAS) Ganjil CBT</span>
                        <strong>30 Nov – 10 Des 2026</strong>
                    </li>

                    <li>
                        <span>Pembagian Rapor Digital & Libur Semester</span>
                        <strong>18 Des 2026</strong>
                    </li>

                </ul>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="calendar-card">

                <div class="calendar-title">
                    <i class="bi bi-calendar3-range"></i>
                    <h3>Agenda Semester Genap 2026/2027</h3>
                </div>

                <ul class="calendar-list">

                    <li>
                        <span>Awal KBM Semester Genap</span>
                        <strong>05 Januari 2027</strong>
                    </li>

                    <li>
                        <span>Uji Kompetensi Keahlian (UKK / Ujian Praktik)</span>
                        <strong>15 – 27 Feb 2027</strong>
                    </li>

                    <li>
                        <span>Asesmen Sekolah Utama (Kelas XII)</span>
                        <strong>15 – 24 Maret 2027</strong>
                    </li>

                    <li>
                        <span>Penilaian Akhir Tahun (PAT) Kelas X & XI</span>
                        <strong>07 – 17 Juni 2027</strong>
                    </li>

                    <li>
                        <span>Wisuda & Pelepasan Kelulusan Siswa</span>
                        <strong>24 Juni 2027</strong>
                    </li>

                </ul>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     SECTION 5 — PANDUAN KURIKULUM & SILABUS PUBLIK
     ========================================================= --}}
<section id="dokumen" class="academic-section academic-section-last">
    <span id="repositori"></span>

    <div class="academic-section-heading">
        <div class="academic-heading-icon">
            <i class="bi bi-file-earmark-arrow-down-fill"></i>
        </div>

        <div>
            <span class="academic-eyebrow">DOKUMEN RESMI KURIKULUM</span>
            <h2>Buku Panduan Akademik & Silabus</h2>
        </div>
    </div>

    {{-- Notice Hak Akses Materi Guru --}}
    <div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-start gap-3 p-3 mb-4" style="background: #eef6ff; border: 1px solid #cce0fc !important; border-radius: 10px;">
        <div class="text-primary fs-4" style="flex-shrink: 0; line-height: 1;">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div class="small">
            <strong class="d-block text-dark mb-1">Informasi Hak Akses Materi KBM Harian:</strong>
            <span class="text-secondary">
                Seluruh dokumen di bawah ini merupakan pedoman umum, kalender resmi, dan silabus kurikulum terbuka untuk publik. Untuk materi KBM mingguan, modul ajar guru, slide presentasi, dan bank soal latihan tersimpan secara terlindungi di <strong>Portal Siswa</strong> dan hanya dapat diakses oleh siswa terdaftar yang diampu oleh masing-masing guru kelas.
            </span>
        </div>
    </div>

    @if (!empty($academicDocuments))

        <div class="materials-wrapper">

            <div class="table-responsive">

                <table class="materials-table">

                    <thead>
                        <tr>
                            <th style="width: 45px;">No</th>
                            <th>Nama Dokumen</th>
                            <th>Kategori</th>
                            <th>Deskripsi Isi</th>
                            <th>Pembaruan</th>
                            <th class="text-end" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($academicDocuments as $index => $doc)

                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 32px; height: 32px; flex-shrink: 0; background: rgba(23, 105, 213, 0.08); color: var(--academic-blue);">
                                            <i class="bi {{ $doc['icon'] }} fs-5"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $doc['title'] }}</strong>
                                            <span style="font-size: 9px; color: #8b98a8; display: block;">{{ $doc['file_name'] }} &bull; {{ $doc['file_size'] }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="material-subject">
                                        {{ $doc['category'] }}
                                    </span>
                                </td>

                                <td>
                                    <span class="material-description" title="{{ $doc['description'] }}">
                                        {{ $doc['description'] }}
                                    </span>
                                </td>

                                <td style="white-space: nowrap; font-size: 10px; color: #66758a;">
                                    <i class="bi bi-clock-history me-1"></i>{{ $doc['updated_at'] }}
                                </td>

                                <td class="text-end">
                                    <button type="button" class="material-action primary" onclick="alert('Mengunduh {{ $doc['file_name'] }}...\nDokumen resmi {{ $doc['title'] }} akan tersimpan di perangkat Anda.')">
                                        <i class="bi bi-download"></i>
                                        Unduh PDF
                                    </button>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @else

        <div class="materials-empty">

            <div class="materials-empty-icon">
                <i class="bi bi-folder-x"></i>
            </div>

            <h3>Dokumen Panduan Akademik</h3>

            <p>
                Dokumen silabus resmi dan buku pedoman kurikulum sedang dalam proses pembaruan oleh tim kurikulum sekolah.
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

    if (!filterButtons.length || !subjectCards.length) {
        return;
    }

    filterButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const filter = this.getAttribute('data-filter');

            filterButtons.forEach(function (btn) {
                btn.classList.remove('active');
            });

            this.classList.add('active');

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
