@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=4">

    <div class="dashboard-page">

        {{-- HERO SUPER ADMIN --}}
        <section class="dashboard-hero">
            <div class="dashboard-hero-content">
                <div class="dashboard-hero-text">
                    <span class="dashboard-eyebrow">
                        <x-icon name="shield" :size="15" />
                        SUPER ADMINISTRATOR
                    </span>

                    <h1>Dashboard Super Admin</h1>

                    <p>
                        Pusat pengelolaan sistem informasi sekolah
                        untuk memantau pengguna dan hak akses.
                    </p>
                </div>

                <div class="dashboard-hero-symbol">
                    <div class="hero-symbol-inner">
                        <x-icon name="shield" :size="44" />
                    </div>
                </div>
            </div>

            <div class="dashboard-hero-footer">
                <span class="hero-status-dot"></span>
                Sistem Informasi Sekolah Modern
            </div>
        </section>

        {{-- RINGKASAN SISTEM --}}
        <section class="dashboard-overview">
            <div class="dashboard-section-title">
                <div>
                    <h2>Ringkasan Sistem</h2>
                    <p>Jumlah data utama yang tercatat saat ini.</p>
                </div>
            </div>

            <div class="dashboard-stats">

                {{-- TOTAL PENGGUNA --}}
                <article class="dashboard-stat-card stat-users">
                    <div class="stat-heading">
                        <div class="stat-icon">
                            <x-icon name="user-group" :size="23" />
                        </div>
                        <span class="stat-category">PENGGUNA</span>
                    </div>

                    <div class="stat-number">
                        {{ \App\Models\User::count() }}
                    </div>

                    <h3>Total User Terdaftar</h3>
                    <div class="stat-bottom-line"></div>
                </article>

                {{-- TOTAL ROLE --}}
                <article class="dashboard-stat-card stat-roles">
                    <div class="stat-heading">
                        <div class="stat-icon">
                            <x-icon name="shield" :size="23" />
                        </div>
                        <span class="stat-category">PERAN</span>
                    </div>

                    <div class="stat-number">
                        {{ \App\Models\Role::count() }}
                    </div>

                    <h3>Role dalam Sistem</h3>
                    <div class="stat-bottom-line"></div>
                </article>

                {{-- TOTAL PERMISSION --}}
                <article class="dashboard-stat-card stat-permissions">
                    <div class="stat-heading">
                        <div class="stat-icon">
                            <x-icon name="clipboard-list" :size="23" />
                        </div>
                        <span class="stat-category">HAK AKSES</span>
                    </div>

                    <div class="stat-number">
                        {{ \App\Models\Permission::count() }}
                    </div>

                    <h3>Permission Sistem</h3>
                    <div class="stat-bottom-line"></div>
                </article>

            </div>
        </section>

        {{-- SELAMAT DATANG --}}
        <section class="dashboard-welcome">
            <div class="welcome-icon">
                <x-icon name="graduation-cap" :size="29" />
            </div>

            <div class="welcome-content">
                <span class="welcome-eyebrow">SELAMAT DATANG</span>

                <h2>{{ auth()->user()->name }}!</h2>

                <p>
                    Kelola pengguna, role, hak akses, data akademik,
                    dan pendaftaran PPDB melalui menu navigasi di samping.
                </p>
            </div>

            <div class="welcome-side">
                <span class="welcome-side-line"></span>
                <span>ADMIN PANEL</span>
            </div>
        </section>

    </div>

@endsection
