@php
    $staff = auth()->user();
@endphp

<div class="tu-header">

    <div class="tu-header-content d-flex align-items-center gap-3">
        <x-avatar :user="$staff" :size="64" class="border border-3 border-white shadow-sm" />

        <div>
            <span class="tu-eyebrow">
                PORTAL TATA USAHA & ADMINISTRASI
            </span>

            <h1 class="mb-1">
                Dashboard Tata Usaha
            </h1>

            <p class="mb-0">
                Selamat datang,
                <strong>{{ $staff->name }}</strong>.
                Kelola administrasi, persuratan, dan operasional sekolah melalui dashboard ini.
            </p>

            <div class="d-flex align-items-center gap-2 mt-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem;">
                    <i class="bi bi-briefcase me-1"></i>{{ $staff->role->name ?? 'Staf Tata Usaha' }}
                </span>
                <a href="{{ route('account.profile.edit') }}" class="text-secondary text-decoration-none small" style="font-size: 0.75rem;">
                    <i class="bi bi-camera me-1"></i>Ubah Foto
                </a>
            </div>
        </div>

    </div>

    <div class="tu-header-icon d-none d-md-flex">
        <x-icon name="layout-dashboard" :size="28" />
    </div>

</div>
