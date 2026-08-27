@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')

<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

@section('content')

<div class="admin-dashboard">

    {{-- HEADER --}}
    <div class="dashboard-header">

        <div class="dashboard-header-text">
            <span class="dashboard-eyebrow">
                ADMINISTRATOR
            </span>

            <h1>Dashboard Super Admin</h1>

            <p>
                Kelola dan pantau sistem informasi sekolah
                dari satu tempat.
            </p>
        </div>

        <div class="dashboard-user">

            <div class="dashboard-user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="dashboard-user-info">
                <span>Selamat datang</span>
                <strong>{{ auth()->user()->name }}</strong>
            </div>

        </div>

    </div>


    {{-- STATISTIK --}}
    <div class="dashboard-stat-grid">

        {{-- TOTAL USER --}}
        <div class="dashboard-stat-card stat-blue">

            <div class="stat-card-top">

                <div class="stat-icon">

                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>

                </div>

                <div class="stat-card-info">

                    <span class="stat-label">
                        TOTAL USER
                    </span>

                    <div class="stat-number">
                        {{ \App\Models\User::count() }}
                    </div>

                </div>

            </div>

            <div class="stat-description">
                Pengguna terdaftar
            </div>

        </div>


        {{-- TOTAL ROLE --}}
        <div class="dashboard-stat-card stat-navy">

            <div class="stat-card-top">

                <div class="stat-icon">

                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>

                </div>

                <div class="stat-card-info">

                    <span class="stat-label">
                        TOTAL ROLE
                    </span>

                    <div class="stat-number">
                        {{ \App\Models\Role::count() }}
                    </div>

                </div>

            </div>

            <div class="stat-description">
                Role dalam sistem
            </div>

        </div>


        {{-- TOTAL PERMISSION --}}
        <div class="dashboard-stat-card stat-cyan">

            <div class="stat-card-top">

                <div class="stat-icon">

                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <rect
                            x="3"
                            y="4"
                            width="18"
                            height="16"
                            rx="2"
                        ></rect>

                        <path d="M8 9h8"></path>
                        <path d="M8 13h5"></path>
                    </svg>

                </div>

                <div class="stat-card-info">

                    <span class="stat-label">
                        TOTAL PERMISSION
                    </span>

                    <div class="stat-number">
                        {{ \App\Models\Permission::count() }}
                    </div>

                </div>

            </div>

            <div class="stat-description">
                Hak akses sistem
            </div>

        </div>

    </div>


    {{-- WELCOME / INFORMATION --}}
    <div class="dashboard-welcome-card">

        <div class="welcome-accent"></div>

        <div class="welcome-icon">

            <svg
                width="27"
                height="27"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="M3 10.5 12 4l9 6.5"></path>
                <path d="M5 10v9h14v-9"></path>
                <path d="M9 19v-5h6v5"></path>
            </svg>

        </div>

        <div class="welcome-content">

            <span>
                SISTEM INFORMASI SEKOLAH
            </span>

            <h2>
                Selamat datang, {{ auth()->user()->name }}!
            </h2>

            <p>
                Gunakan menu di samping untuk mengelola user dan
                permission tiap role. Modul lain (CMS, PPDB, dst)
                akan menyusul sesuai roadmap Fase 1.
            </p>

        </div>

    </div>

</div>

@endsection