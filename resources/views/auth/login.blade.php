@extends('layouts.guest')

@section('title', 'Login - ' . config('app.name'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/base.css') }}">
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<div class="login-page">

    <div class="login-card">

        {{-- =====================================================
             BAGIAN KIRI - GAMBAR SEKOLAH
             ===================================================== --}}
        <div class="login-visual">

            <div class="visual-overlay"></div>

            <div class="visual-content">

                <div class="school-badge">
                    <span class="badge-dot"></span>
                    PORTAL RESMI SEKOLAH
                </div>

                <h1>
                    Selamat Datang
                    <span>di Sekolah Modern</span>
                </h1>

                <div class="visual-line"></div>

                <p>
                    Sistem informasi sekolah modern yang terintegrasi
                    untuk mendukung kegiatan akademik, administrasi,
                    dan komunikasi sekolah.
                </p>

            </div>

        </div>


        {{-- =====================================================
             BAGIAN KANAN - LOGIN
             ===================================================== --}}
        <div class="login-panel">

            <div class="login-content">

                {{-- Logo --}}
                <div class="school-logo">
                    <span>{{ strtoupper(substr(config('app.name'), 0, 1)) }}</span>
                </div>


                {{-- Heading --}}
                <div class="login-heading">

                    <h2>Masuk ke Portal</h2>

                    <p>
                        Silakan masuk menggunakan akun Anda
                        untuk mengakses layanan sekolah.
                    </p>

                </div>


                {{-- Error --}}
                @if ($errors->any())

                    <div class="login-alert">

                        <div class="alert-icon">!</div>

                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>

                    </div>

                @endif


                {{-- =================================================
                     FORM LOGIN
                     ================================================= --}}
                <form method="POST" action="{{ url('/login') }}">

                    @csrf


                    {{-- EMAIL --}}
                    <div class="form-group">

                        <label for="email">
                            Email
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon">

                                <svg
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <rect
                                        x="3"
                                        y="5"
                                        width="18"
                                        height="14"
                                        rx="2"
                                    ></rect>

                                    <path d="m3 7 9 6 9-6"></path>
                                </svg>

                            </span>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                class="@error('email') input-error @enderror"
                                placeholder="Masukkan email Anda"
                                required
                                autofocus
                            >

                        </div>

                        @error('email')

                            <span class="field-error">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- PASSWORD --}}
                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon">

                                <svg
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <rect
                                        x="5"
                                        y="10"
                                        width="14"
                                        height="10"
                                        rx="2"
                                    ></rect>

                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>

                                </svg>

                            </span>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Masukkan password"
                                required
                            >

                        </div>

                    </div>


                    {{-- INGAT SAYA --}}
                    <div class="login-options">

                        <label class="remember-me">

                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                            >

                            <span class="custom-checkbox"></span>

                            <span>Ingat saya</span>

                        </label>

                    </div>


                    {{-- BUTTON --}}
                    <button
                        type="submit"
                        class="login-button"
                    >

                        <span>Masuk ke Portal</span>

                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M5 12h14"></path>
                            <path d="m13 6 6 6-6 6"></path>
                        </svg>

                    </button>

                </form>


                {{-- =================================================
                     AKUN DEMO
                     ================================================= --}}
                <div class="demo-section">

                    <div class="demo-title">

                        <span></span>

                        Akun Demo

                        <span></span>

                    </div>

                    <p class="demo-password">
                        Password:
                        <code>password123</code>
                    </p>

                    <div class="demo-accounts">

                        <span>admin@sekolah.test</span>

                        <span>guru@sekolah.test</span>

                        <span>siswa@sekolah.test</span>

                        <span>ortu@sekolah.test</span>

                        <span>tu@sekolah.test</span>

                        <span>kepsek@sekolah.test</span>

                    </div>

                </div>


                {{-- Footer --}}
                <div class="login-footer">

                    <span></span>

                    <p>
                        Sistem Informasi Sekolah Modern
                    </p>

                    <span></span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection