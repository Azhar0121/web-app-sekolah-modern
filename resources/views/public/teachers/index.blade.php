
@extends('layouts.public')

@section('title', 'Direktori Pengajar & Tenaga Kependidikan — ' . ($settings['school_name'] ?? config('app.name')))

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/public/teachers/index.css') }}">

@php
    $schoolName = $settings['school_name'] ?? config('app.name');
    $teacherCount = $teachers->count();
    $staffCount = $staff->count();
@endphp

{{-- HERO --}}
<section class="directory-hero">
    <div class="directory-hero-decoration directory-decoration-one"></div>
    <div class="directory-hero-decoration directory-decoration-two"></div>

    <div class="container directory-hero-container">
        <nav aria-label="breadcrumb" class="directory-breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">
                        <i class="bi bi-house-door-fill"></i>
                        Beranda
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('profile.show') }}">Profil Sekolah</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Pengajar & Staf
                </li>
            </ol>
        </nav>

        <div class="directory-hero-grid">
            <div class="directory-hero-copy">
                <span class="directory-eyebrow">
                    <span class="directory-eyebrow-dot"></span>
                    SUMBER DAYA MANUSIA
                </span>

                <h1>
                    Mengenal Para
                    <span>Pendidik Terbaik</span>
                </h1>

                <p>
                    Kenali jajaran guru, pimpinan, dan tenaga kependidikan
                    yang mendukung proses belajar serta perkembangan siswa
                    di <strong>{{ $schoolName }}</strong>.
                </p>

                <div class="directory-hero-points">
                    <span>
                        <i class="bi bi-patch-check-fill"></i>
                        Pendidik profesional
                    </span>
                    <span>
                        <i class="bi bi-people-fill"></i>
                        Tim sekolah berdedikasi
                    </span>
                </div>

                <a href="#direktori-personalia" class="directory-hero-button">
                    Jelajahi Direktori
                    <i class="bi bi-arrow-down-right"></i>
                </a>
            </div>

            <div class="directory-hero-stats">
                <div class="directory-stat-card directory-stat-teachers">
                    <div class="directory-stat-top">
                        <span class="directory-stat-icon">
                            <i class="bi bi-person-workspace"></i>
                        </span>
                        <span class="directory-stat-label">PENDIDIK</span>
                    </div>
                    <div class="directory-stat-number">{{ $teacherCount }}</div>
                    <div class="directory-stat-title">Guru Pengajar</div>
                    <p>Tenaga pendidik yang mendampingi pembelajaran siswa.</p>
                </div>

                <div class="directory-stat-card directory-stat-staff">
                    <div class="directory-stat-top">
                        <span class="directory-stat-icon">
                            <i class="bi bi-briefcase-fill"></i>
                        </span>
                        <span class="directory-stat-label">KEPENDIDIKAN</span>
                    </div>
                    <div class="directory-stat-number">{{ $staffCount }}</div>
                    <div class="directory-stat-title">Staf & Tata Usaha</div>
                    <p>Tim pendukung administrasi dan operasional sekolah.</p>
                </div>

                <div class="directory-stat-footer">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Data tenaga sekolah {{ $schoolName }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="directory-hero-bottom">
        <div class="container">
            <div class="directory-bottom-inner">
                <span><i class="bi bi-diagram-3-fill"></i> Struktur organisasi</span>
                <span><i class="bi bi-mortarboard-fill"></i> Dewan guru</span>
                <span><i class="bi bi-building-fill"></i> Tenaga kependidikan</span>
            </div>
        </div>
    </div>
</section>

{{-- MAIN CONTENT --}}
<main class="directory-main" id="direktori-personalia">
    <div class="container">

        {{-- SEARCH AND TABS --}}
        <section class="directory-control-panel">
            <div class="directory-control-heading">
                <div>
                    <span class="directory-section-kicker">DIREKTORI SEKOLAH</span>
                    <h2>Jelajahi Tim Kami</h2>
                    <p>Pilih kategori untuk melihat informasi personel sekolah.</p>
                </div>

                <div class="directory-control-symbol">
                    <i class="bi bi-people"></i>
                </div>
            </div>

            <div class="directory-control-row">
                <ul class="nav nav-pills directory-tabs"
                    id="teacherTabs"
                    role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active"
                                id="tab-struktur-btn"
                                data-bs-toggle="pill"
                                data-bs-target="#tab-struktur"
                                type="button"
                                role="tab"
                                aria-controls="tab-struktur"
                                aria-selected="true">
                            <i class="bi bi-diagram-3-fill"></i>
                            <span>Struktur Organisasi</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                                id="tab-guru-btn"
                                data-bs-toggle="pill"
                                data-bs-target="#tab-guru"
                                type="button"
                                role="tab"
                                aria-controls="tab-guru"
                                aria-selected="false">
                            <i class="bi bi-person-workspace"></i>
                            <span>Dewan Guru</span>
                            <span class="directory-tab-count">{{ $teacherCount }}</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                                id="tab-staf-btn"
                                data-bs-toggle="pill"
                                data-bs-target="#tab-staf"
                                type="button"
                                role="tab"
                                aria-controls="tab-staf"
                                aria-selected="false">
                            <i class="bi bi-briefcase-fill"></i>
                            <span>Staf & TU</span>
                            <span class="directory-tab-count">{{ $staffCount }}</span>
                        </button>
                    </li>
                </ul>

                <div class="directory-search">
                    <i class="bi bi-search"></i>
                    <input type="search"
                           id="liveSearchInput"
                           placeholder="Cari nama atau mata pelajaran..."
                           aria-label="Cari guru atau staf">
                    <span class="directory-search-shortcut">
                        <i class="bi bi-funnel"></i>
                    </span>
                </div>
            </div>
        </section>

        {{-- TAB PANELS --}}
        <div class="tab-content directory-tab-content" id="teacherTabContent">

            {{-- STRUKTUR ORGANISASI --}}
            <div class="tab-pane fade show active"
                 id="tab-struktur"
                 role="tabpanel"
                 aria-labelledby="tab-struktur-btn"
                 tabindex="0">

                <section class="directory-content-section">
                    <div class="directory-section-heading directory-heading-blue">
                        <div class="directory-section-icon">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>
                        <div class="directory-section-copy">
                            <span class="directory-section-kicker">TATA KELOLA SEKOLAH</span>
                            <h2>Bagan Struktur Organisasi</h2>
                            <p>
                                Kenali susunan kepemimpinan dan pembagian peran
                                dalam pengelolaan {{ $schoolName }}.
                            </p>
                        </div>
                        <div class="directory-heading-decoration">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                    </div>

                    <div class="directory-panel-body directory-org-body">
                        @include('public.teachers.partials.org-chart')
                    </div>
                </section>
            </div>

            {{-- DEWAN GURU --}}
            <div class="tab-pane fade"
                 id="tab-guru"
                 role="tabpanel"
                 aria-labelledby="tab-guru-btn"
                 tabindex="0">

                <section class="directory-content-section">
                    <div class="directory-section-heading directory-heading-purple">
                        <div class="directory-section-icon">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div class="directory-section-copy">
                            <span class="directory-section-kicker">TENAGA PENDIDIK</span>
                            <h2>Jajaran Dewan Guru</h2>
                            <p>
                                Temukan profil pendidik dan bidang mata pelajaran
                                yang mereka ampu.
                            </p>
                        </div>
                        <div class="directory-heading-total">
                            <strong>{{ $teacherCount }}</strong>
                            <span>Guru terdaftar</span>
                        </div>
                    </div>

                    <div class="directory-panel-body">
                        @include('public.teachers.partials.teachers-grid')
                    </div>
                </section>
            </div>

            {{-- STAF DAN TATA USAHA --}}
            <div class="tab-pane fade"
                 id="tab-staf"
                 role="tabpanel"
                 aria-labelledby="tab-staf-btn"
                 tabindex="0">

                <section class="directory-content-section">
                    <div class="directory-section-heading directory-heading-teal">
                        <div class="directory-section-icon">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <div class="directory-section-copy">
                            <span class="directory-section-kicker">TENAGA KEPENDIDIKAN</span>
                            <h2>Staf & Tata Usaha</h2>
                            <p>
                                Kenali tim yang mendukung administrasi akademik,
                                persuratan, dan operasional sekolah.
                            </p>
                        </div>
                        <div class="directory-heading-total">
                            <strong>{{ $staffCount }}</strong>
                            <span>Staf terdaftar</span>
                        </div>
                    </div>

                    <div class="directory-panel-body">
                        @include('public.teachers.partials.staff-grid')
                    </div>
                </section>
            </div>

        </div>

        {{-- BOTTOM INFORMATION --}}
        <section class="directory-bottom-note">
            <div class="directory-bottom-note-icon">
                <i class="bi bi-info-circle-fill"></i>
            </div>
            <div>
                <h3>Informasi Direktori</h3>
                <p>
                    Informasi pengajar dan tenaga kependidikan ditampilkan
                    berdasarkan data yang tersedia pada sistem sekolah.
                    Gunakan kolom pencarian untuk menemukan nama dengan lebih cepat.
                </p>
            </div>
        </section>

    </div>
</main>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('liveSearchInput');
    const teacherCards = document.querySelectorAll('.teacher-search-item');
    const staffCards = document.querySelectorAll('.staff-search-item');
    const noTeacherFound = document.getElementById('noTeacherFound');
    const noStaffFound = document.getElementById('noStaffFound');

    if (!searchInput) return;

    searchInput.addEventListener('input', (event) => {
        const query = event.target.value.toLowerCase().trim();

        const activeTab = document.querySelector('#teacherTabs .nav-link.active');

        if (activeTab && activeTab.id === 'tab-struktur-btn' && query.length > 0) {
            const teacherTabButton = document.getElementById('tab-guru-btn');

            if (teacherTabButton && window.bootstrap) {
                bootstrap.Tab.getOrCreateInstance(teacherTabButton).show();
            } else if (teacherTabButton) {
                teacherTabButton.click();
            }
        }

        let matchTeacherCount = 0;

        teacherCards.forEach((card) => {
            const name = (card.getAttribute('data-name') || '').toLowerCase();
            const subject = (card.getAttribute('data-subject') || '').toLowerCase();
            const matched = name.includes(query) || subject.includes(query);

            card.style.display = matched ? '' : 'none';

            if (matched) matchTeacherCount++;
        });

        if (noTeacherFound) {
            noTeacherFound.classList.toggle('d-none', matchTeacherCount > 0);
        }

        let matchStaffCount = 0;

        staffCards.forEach((card) => {
            const name = (card.getAttribute('data-name') || '').toLowerCase();
            const matched = name.includes(query);

            card.style.display = matched ? '' : 'none';

            if (matched) matchStaffCount++;
        });

        if (noStaffFound) {
            noStaffFound.classList.toggle('d-none', matchStaffCount > 0);
        }
    });
});
</script>
@endpush
@endsection