@php
    $kepsek = auth()->user();
@endphp

<section class="kepsek-header">
    <div class="kepsek-header-content d-flex align-items-center gap-3">
        <x-avatar :user="$kepsek" :size="64" class="border border-3 border-white shadow-sm flex-shrink-0" />

        <div class="kepsek-header-text">
            <span class="kepsek-header-label">
                MONITORING EKSEKUTIF SEKOLAH
            </span>

            <h1 class="mb-1">Ringkasan & Analitik Sekolah</h1>

            <p class="mb-0">
                Selamat datang, <strong>{{ $kepsek->name }}</strong>. Pantau kondisi akademik, kehadiran, guru, PPDB, alumni, dan finansial sekolah.
            </p>

            <div class="d-flex align-items-center gap-2 mt-2">
                <span class="badge bg-white text-dark fw-semibold" style="font-size: 0.72rem;">
                    <i class="bi bi-mortarboard-fill me-1 text-primary"></i>Kepala Sekolah
                </span>
                <a href="{{ route('account.profile.edit') }}" class="text-white-50 text-decoration-none small" style="font-size: 0.75rem;">
                    <i class="bi bi-camera me-1"></i>Ubah Foto
                </a>
            </div>
        </div>
    </div>

    <div class="kepsek-header-decoration">
        <span></span>
        <span></span>
        <span></span>
    </div>
</section>
