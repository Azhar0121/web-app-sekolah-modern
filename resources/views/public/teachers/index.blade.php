@extends('layouts.public')

@section('title', 'Direktori Pengajar & Tenaga Kependidikan — ' . ($settings['school_name'] ?? config('app.name')))

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- HERO SECTION --}}
<section class="py-5 text-white" style="background: radial-gradient(circle at 85% 20%, rgba(59, 130, 246, 0.3), transparent 30%), linear-gradient(135deg, #071b35 0%, #0d294d 50%, #1769d5 100%);">
    <div class="container py-3">

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}" class="text-white-50 text-decoration-none">
                        <i class="bi bi-house-door-fill me-1"></i>Beranda
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('profile.show') }}" class="text-white-50 text-decoration-none">
                        Profil Sekolah
                    </a>
                </li>
                <li class="breadcrumb-item active text-white" aria-current="page">
                    Pengajar & Staf
                </li>
            </ol>
        </nav>

        <div class="row align-items-center justify-content-between g-4">
            <div class="col-lg-8">
                <span class="badge bg-primary px-3 py-2 rounded-pill text-uppercase mb-2 font-monospace" style="letter-spacing: 1px; font-size: 0.72rem;">
                    SUMBER DAYA MANUSIA
                </span>
                <h1 class="display-6 fw-bold mb-2">
                    Direktori Pengajar & Tenaga Kependidikan
                </h1>
                <p class="lead text-white-50 mb-0 fs-6" style="max-width: 650px;">
                    Mengenal profil jajaran pendidik profesional, pimpinan sekolah, serta staf tenaga kependidikan berdedikasi tinggi di {{ $settings['school_name'] ?? config('app.name') }}.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex gap-3 bg-white bg-opacity-10 backdrop-blur p-3 rounded-4 border border-white border-opacity-10 shadow-sm text-center">
                    <div class="px-2">
                        <div class="h3 fw-bold mb-0 text-white">{{ $teachers->count() }}</div>
                        <small class="text-white-50" style="font-size: 0.75rem;">Guru Pengajar</small>
                    </div>
                    <div class="vr bg-white opacity-25"></div>
                    <div class="px-2">
                        <div class="h3 fw-bold mb-0 text-white">{{ $staff->count() }}</div>
                        <small class="text-white-50" style="font-size: 0.75rem;">Staf & TU</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- MAIN CONTENT --}}
<div class="container py-5">

    {{-- FILTER TABS & SEARCH ROW --}}
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div class="row g-3 align-items-center justify-content-between">

            {{-- NAV TABS --}}
            <div class="col-md-7 col-lg-8">
                <ul class="nav nav-pills gap-2" id="teacherTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-3 py-2 fw-semibold small"
                                id="tab-struktur-btn"
                                data-bs-toggle="pill"
                                data-bs-target="#tab-struktur"
                                type="button"
                                role="tab">
                            <i class="bi bi-diagram-3-fill me-1"></i> Struktur Organisasi
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-2 fw-semibold small"
                                id="tab-guru-btn"
                                data-bs-toggle="pill"
                                data-bs-target="#tab-guru"
                                type="button"
                                role="tab">
                            <i class="bi bi-person-workspace me-1"></i> Dewan Guru ({{ $teachers->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-2 fw-semibold small"
                                id="tab-staf-btn"
                                data-bs-toggle="pill"
                                data-bs-target="#tab-staf"
                                type="button"
                                role="tab">
                            <i class="bi bi-briefcase me-1"></i> Staf & TU ({{ $staff->count() }})
                        </button>
                    </li>
                </ul>
            </div>

            {{-- SEARCH INPUT --}}
            <div class="col-md-5 col-lg-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 rounded-start-pill text-muted ps-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           id="liveSearchInput"
                           class="form-control bg-light border-start-0 rounded-end-pill small"
                           placeholder="Cari nama guru atau mata pelajaran..."
                           style="font-size: 0.85rem;">
                </div>
            </div>

        </div>
    </div>

    {{-- TAB CONTENT PANELS --}}
    <div class="tab-content" id="teacherTabContent">

        {{-- TAB 1: STRUKTUR ORGANISASI --}}
        <div class="tab-pane fade show active" id="tab-struktur" role="tabpanel">
            <div class="mb-4">
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-diagram-3 text-primary me-2"></i>Bagan Struktur Organisasi Sekolah
                </h4>
                <p class="text-muted small mb-0">
                    Hirarki kepemimpinan dan pembagian peran tata kelola sekolah {{ $settings['school_name'] ?? config('app.name') }}.
                </p>
            </div>

            @include('public.teachers.partials.org-chart')
        </div>

        {{-- TAB 2: DEWAN GURU --}}
        <div class="tab-pane fade" id="tab-guru" role="tabpanel">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="bi bi-person-workspace text-primary me-2"></i>Jajaran Dewan Guru & Pendidik
                    </h4>
                    <p class="text-muted small mb-0">
                        Tenaga pendidik profesional dan bersertifikasi di berbagai mata pelajaran dan kejuruan.
                    </p>
                </div>
            </div>

            @include('public.teachers.partials.teachers-grid')
        </div>

        {{-- TAB 3: STAF & TATA USAHA --}}
        <div class="tab-pane fade" id="tab-staf" role="tabpanel">
            <div class="mb-4">
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-briefcase text-secondary me-2"></i>Staf Tata Usaha & Tenaga Kependidikan
                </h4>
                <p class="text-muted small mb-0">
                    Mendukung kelancaran administrasi akademik, kepegawaian, persuratan, dan operasional harian sekolah.
                </p>
            </div>

            @include('public.teachers.partials.staff-grid')
        </div>

    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('liveSearchInput');
    const teacherCards = document.querySelectorAll('.teacher-search-item');
    const staffCards = document.querySelectorAll('.staff-search-item');
    const noTeacherFound = document.getElementById('noTeacherFound');
    const noStaffFound = document.getElementById('noStaffFound');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();

            // Jika pengguna mengetik saat di tab struktur, otomatis switch ke tab guru
            const activeTab = document.querySelector('#teacherTabs .nav-link.active');
            if (activeTab && activeTab.id === 'tab-struktur-btn' && query.length > 0) {
                document.getElementById('tab-guru-btn').click();
            }

            // Filter Guru
            let matchTeacherCount = 0;
            teacherCards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                const subject = card.getAttribute('data-subject') || '';
                const match = name.includes(query) || subject.includes(query);
                card.style.display = match ? '' : 'none';
                if (match) matchTeacherCount++;
            });
            if (noTeacherFound) {
                noTeacherFound.classList.toggle('d-none', matchTeacherCount > 0);
            }

            // Filter Staf
            let matchStaffCount = 0;
            staffCards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                const match = name.includes(query);
                card.style.display = match ? '' : 'none';
                if (match) matchStaffCount++;
            });
            if (noStaffFound) {
                noStaffFound.classList.toggle('d-none', matchStaffCount > 0);
            }
        });
    }
});
</script>
@endpush
@endsection
