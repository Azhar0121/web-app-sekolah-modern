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

                            <button
                                type="button"
                                id="togglePasswordBtn"
                                class="password-toggle-btn"
                                aria-label="Lihat atau sembunyikan password"
                                title="Lihat password"
                            >
                                {{-- Ikon Mata Terbuka (Show) --}}
                                <svg
                                    id="eyeIconOpen"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>

                                {{-- Ikon Mata Tertutup / Silang (Hide) --}}
                                <svg
                                    id="eyeIconClosed"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    style="display: none;"
                                >
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                    <line x1="2" y1="2" x2="22" y2="22"></line>
                                </svg>
                            </button>

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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');
    const eyeIconOpen = document.getElementById('eyeIconOpen');
    const eyeIconClosed = document.getElementById('eyeIconClosed');

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const isPassword = passwordInput.getAttribute('type') === 'password';

            if (isPassword) {
                passwordInput.setAttribute('type', 'text');
                eyeIconOpen.style.display = 'none';
                eyeIconClosed.style.display = 'block';
                togglePasswordBtn.setAttribute('title', 'Sembunyikan password');
                togglePasswordBtn.setAttribute('aria-label', 'Sembunyikan password');
            } else {
                passwordInput.setAttribute('type', 'password');
                eyeIconOpen.style.display = 'block';
                eyeIconClosed.style.display = 'none';
                togglePasswordBtn.setAttribute('title', 'Lihat password');
                togglePasswordBtn.setAttribute('aria-label', 'Lihat password');
            }

            // Tetap fokuskan kursor ke input password
            passwordInput.focus();
        });
    }
});
</script>

@endsection