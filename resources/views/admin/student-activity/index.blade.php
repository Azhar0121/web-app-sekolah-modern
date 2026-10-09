@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/student-activity/index.css') }}">

<div class="student-act-page">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <div class="d-flex align-items-center mb-1 fw-bold">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                Terdapat kesalahan dalam pengisian formulir:
            </div>
            <ul class="mb-0 ps-4 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="student-act-hero">
        <div>
            <span class="student-act-hero-badge"><i class="bi bi-stars me-1"></i> Kesiswaan & Pengembangan Karakter</span>
            <h1>Manajemen Aktivitas Kesiswaan</h1>
            <p>Kelola agenda dan pengurus OSIS, portofolio ekstrakurikuler, dan rekam prestasi siswa berprestasi lengkap dengan pengunggahan foto resolusi tinggi & galeri dokumentasi.</p>
        </div>
        <div>
            <a href="{{ route('student-activity.show') }}" target="_blank" class="btn btn-outline-light d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold" style="font-size: 13px; border-radius: 8px;">
                <i class="bi bi-box-arrow-up-right"></i> Pratinjau Halaman Publik
            </a>
        </div>
    </div>

    @php
        $activeTab = request()->query('tab', $currentTab ?? 'osis');
    @endphp

    <div class="student-act-tabs">
        <a href="{{ route('admin.student-activities.index', ['tab' => 'osis']) }}" 
           class="student-act-tab-btn {{ $activeTab === 'osis' ? 'active' : '' }}" 
           data-tab="osis">
            <i class="bi bi-people-fill"></i> Organisasi OSIS
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ $osisActivities->total() + $osisMembers->total() }}</span>
        </a>
        <a href="{{ route('admin.student-activities.index', ['tab' => 'ekskul']) }}" 
           class="student-act-tab-btn {{ $activeTab === 'ekskul' ? 'active' : '' }}" 
           data-tab="ekskul">
            <i class="bi bi-trophy-fill"></i> Ekstrakurikuler
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ $extracurriculars->total() }}</span>
        </a>
        <a href="{{ route('admin.student-activities.index', ['tab' => 'prestasi']) }}" 
           class="student-act-tab-btn {{ $activeTab === 'prestasi' ? 'active' : '' }}" 
           data-tab="prestasi">
            <i class="bi bi-award-fill"></i> Rekam Prestasi
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ $achievements->total() }}</span>
        </a>
    </div>

    <div id="tab-content-osis" class="tab-pane-content" style="{{ $activeTab === 'osis' ? 'display: block;' : 'display: none;' }}">
        @include('admin.student-activity.partials.tab-osis')
    </div>

    <div id="tab-content-ekskul" class="tab-pane-content" style="{{ $activeTab === 'ekskul' ? 'display: block;' : 'display: none;' }}">
        @include('admin.student-activity.partials.tab-ekskul')
    </div>

    <div id="tab-content-prestasi" class="tab-pane-content" style="{{ $activeTab === 'prestasi' ? 'display: block;' : 'display: none;' }}">
        @include('admin.student-activity.partials.tab-prestasi')
    </div>

</div>

@include('admin.student-activity.partials.modals-osis')
@include('admin.student-activity.partials.modals-ekskul')
@include('admin.student-activity.partials.modals-prestasi')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.student-act-tab-btn');
    const tabPanes = {
        'osis': document.getElementById('tab-content-osis'),
        'ekskul': document.getElementById('tab-content-ekskul'),
        'prestasi': document.getElementById('tab-content-prestasi')
    };

    function switchTab(tabKey, updateUrl = true) {
        if (!tabPanes[tabKey]) return;

        tabButtons.forEach(btn => {
            if (btn.dataset.tab === tabKey) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        Object.keys(tabPanes).forEach(key => {
            if (tabPanes[key]) {
                tabPanes[key].style.display = key === tabKey ? 'block' : 'none';
            }
        });

        if (updateUrl) {
            const url = new URL(window.location);
            url.searchParams.set('tab', tabKey);
            window.history.replaceState({}, '', url);
        }
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            const targetTab = this.dataset.tab;
            if (targetTab && tabPanes[targetTab]) {
                // If it's a plain click without modifying pagination, switch without reload
                const url = new URL(this.href);
                // Keep pagination params clean if just tab switching
                e.preventDefault();
                switchTab(targetTab, true);
            }
        });
    });
});
</script>
@endsection
