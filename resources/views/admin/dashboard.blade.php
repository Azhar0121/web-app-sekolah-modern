@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')

@section('content')

<div class="mb-4">
    <span class="text-uppercase text-primary small fw-bold">Administrator</span>
    <h1 class="h3 fw-bold mb-1">Dashboard Super Admin</h1>
    <p class="text-muted mb-0">Kelola dan pantau sistem informasi sekolah dari satu tempat.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon"><x-icon name="user-group" :size="22" /></div>
            <div>
                <div class="stat-value">{{ \App\Models\User::count() }}</div>
                <div class="stat-label">Total User Terdaftar</div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon"><x-icon name="shield" :size="22" /></div>
            <div>
                <div class="stat-value">{{ \App\Models\Role::count() }}</div>
                <div class="stat-label">Role dalam Sistem</div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon"><x-icon name="clipboard-list" :size="22" /></div>
            <div>
                <div class="stat-value">{{ \App\Models\Permission::count() }}</div>
                <div class="stat-label">Hak Akses (Permission)</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4 d-flex gap-3">
        <div class="stat-icon flex-shrink-0"><x-icon name="graduation-cap" :size="24" /></div>
        <div>
            <span class="text-uppercase text-primary small fw-bold">Sistem Informasi Sekolah</span>
            <h2 class="h5 fw-bold mt-1 mb-2">Selamat datang, {{ auth()->user()->name }}!</h2>
            <p class="text-muted mb-0">
                Gunakan menu di samping untuk mengelola user, permission, master data akademik,
                dan pendaftaran PPDB. Modul lain akan menyusul sesuai roadmap.
            </p>
        </div>
    </div>
</div>

@endsection
