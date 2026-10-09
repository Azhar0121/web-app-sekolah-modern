@php
    $guru = auth()->user();
@endphp

<div class="guru-hero-main">
    <div class="guru-hero-content">

        <div class="d-flex align-items-center gap-3 mb-3">
            <x-avatar :user="$guru" :size="72" class="border border-3 border-white shadow-sm" />
            <div>
                <span class="guru-hero-label mb-1">
                    PORTAL GURU & WALI KELAS
                </span>
                <h1 class="mb-0 text-white" style="font-size: 1.5rem;">
                    Selamat datang,
                    <span>{{ $guru->name }}</span>
                </h1>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-white text-primary fw-semibold" style="font-size: 0.72rem;">
                        <i class="bi bi-person-badge me-1"></i>{{ $guru->role->name ?? 'Guru Pengajar' }}
                    </span>
                    <a href="{{ route('account.profile.edit') }}" class="text-white-50 text-decoration-none small hover-white" style="font-size: 0.75rem;">
                        <i class="bi bi-pencil-square me-1"></i>Ubah Foto
                    </a>
                </div>
            </div>
        </div>

        <p>
            Kelola aktivitas mengajar, presensi, materi,
            dan tugas kelas Anda dari satu tempat secara terpadu.
        </p>

        @if ($activeYear)
            <div class="guru-year-info">
                <div class="guru-year-icon">
                    A
                </div>

                <div>
                    <small>TAHUN AJARAN AKTIF</small>
                    <strong>{{ $activeYear->name }}</strong>
                </div>
            </div>
        @else
            <div class="guru-year-warning">
                <strong>Belum ada tahun ajaran aktif</strong>
                <span>Hubungi Super Admin untuk pengaturan tahun ajaran.</span>
            </div>
        @endif

    </div>

    <div class="guru-hero-illustration">
        <div class="guru-orbit guru-orbit-one"></div>
        <div class="guru-orbit guru-orbit-two"></div>

        <div class="guru-dot guru-dot-one"></div>
        <div class="guru-dot guru-dot-two"></div>
        <div class="guru-dot guru-dot-three"></div>
        <div class="guru-dot guru-dot-four"></div>

        <div class="guru-teacher-shape">
            <div class="guru-circle-decoration">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
</div>
