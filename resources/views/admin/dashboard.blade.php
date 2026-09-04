@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">

<div class="dashboard-page">

    {{-- HEADER --}}
    <div class="dashboard-header">

        <div class="dashboard-header-content">

            <div class="dashboard-title-area">

                <span class="dashboard-label">
                    ADMINISTRATOR
                </span>

                <h1>
                    Dashboard Super Admin
                </h1>

                <p>
                    Kelola dan pantau sistem informasi sekolah dari satu tempat.
                </p>

            </div>

            <div class="dashboard-header-icon">
                <x-icon name="shield" :size="28" />
            </div>

        </div>

    </div>


    {{-- STATISTICS --}}
    <div class="dashboard-stats">

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon">
                <x-icon name="user-group" :size="22" />
            </div>

            <div class="dashboard-stat-content">
                <div class="dashboard-stat-value">
                    {{ \App\Models\User::count() }}
                </div>

                <div class="dashboard-stat-label">
                    Total User Terdaftar
                </div>
            </div>

            <div class="dashboard-stat-decoration"></div>

        </div>


        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon">
                <x-icon name="shield" :size="22" />
            </div>

            <div class="dashboard-stat-content">
                <div class="dashboard-stat-value">
                    {{ \App\Models\Role::count() }}
                </div>

                <div class="dashboard-stat-label">
                    Role dalam Sistem
                </div>
            </div>

            <div class="dashboard-stat-decoration"></div>

        </div>


        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon">
                <x-icon name="clipboard-list" :size="22" />
            </div>

            <div class="dashboard-stat-content">
                <div class="dashboard-stat-value">
                    {{ \App\Models\Permission::count() }}
                </div>

                <div class="dashboard-stat-label">
                    Hak Akses (Permission)
                </div>
            </div>

            <div class="dashboard-stat-decoration"></div>

        </div>

    </div>


    {{-- WELCOME CARD --}}
    <div class="dashboard-welcome-card">

        <div class="dashboard-welcome-decoration"></div>

        <div class="dashboard-welcome-icon">
            <x-icon name="graduation-cap" :size="25" />
        </div>

        <div class="dashboard-welcome-content">

            <span class="dashboard-welcome-label">
                SISTEM INFORMASI SEKOLAH
            </span>

            <h2>
                Selamat datang, {{ auth()->user()->name }}!
            </h2>

            <p>
                Gunakan menu di samping untuk mengelola user, permission,
                master data akademik, dan pendaftaran PPDB.
                Modul lain akan menyusul sesuai roadmap.
            </p>

        </div>

    </div>

</div>

@endsection