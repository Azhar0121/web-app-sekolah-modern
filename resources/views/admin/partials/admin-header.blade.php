@php
    $admin = auth()->user();
@endphp

<div class="dashboard-header">

    <div class="dashboard-header-content d-flex align-items-center gap-3">
        <x-avatar :user="$admin" :size="64" class="border border-3 border-white shadow-sm flex-shrink-0" />

        <div class="dashboard-title-area">
            <span class="dashboard-label">
                ADMINISTRATOR SISTEM
            </span>

            <h1 class="mb-1">
                Dashboard Super Admin
            </h1>

            <p class="mb-0">
                Selamat datang, <strong>{{ $admin->name }}</strong>. Kelola dan pantau seluruh sistem informasi sekolah dari satu tempat.
            </p>

            <div class="d-flex align-items-center gap-2 mt-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem;">
                    <i class="bi bi-shield-check me-1"></i>Super Administrator
                </span>
                <a href="{{ route('account.profile.edit') }}" class="text-white-50 text-decoration-none small" style="font-size: 0.75rem;">
                    <i class="bi bi-camera me-1"></i>Ubah Foto
                </a>
            </div>
        </div>

    </div>

    <div class="dashboard-header-icon d-none d-md-flex">
        <x-icon name="shield" :size="28" />
    </div>

</div>
