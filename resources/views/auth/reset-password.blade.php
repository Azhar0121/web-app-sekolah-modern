@extends('layouts.guest')

@section('title', 'Buat Password Baru - ' . config('app.name'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/base.css') }}">
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<div class="login-page">
    <div class="login-card">
        <div class="login-visual">
            <div class="visual-overlay"></div>
            <div class="visual-content">
                <div class="school-badge">
                    <span class="badge-dot"></span>
                    KEAMANAN AKUN
                </div>
                <h1>Buat Kata Sandi <span>Baru yang Aman</span></h1>
                <div class="visual-line"></div>
                <p>Gunakan kombinasi minimal 8 karakter huruf, angka, atau simbol untuk menjaga keamanan akun dan privasi data Anda di portal sekolah.</p>
            </div>
        </div>

        <div class="login-panel">
            <div class="login-content">
                <div class="school-logo">
                    <span>{{ strtoupper(substr(config('app.name'), 0, 1)) }}</span>
                </div>

                <div class="login-heading">
                    <h2>Atur Ulang Password</h2>
                    <p>Silakan buat kata sandi baru untuk akun Anda di bawah ini.</p>
                </div>

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

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label for="email">Email Akun</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>
                            </span>
                            <input type="email" name="email" id="email" value="{{ old('email', $email) }}" class="@error('email') input-error @enderror" placeholder="nama@email.com" required readonly style="background-color: #f1f5f9; cursor: not-allowed;">
                        </div>
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru (Min. 8 karakter)</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="10" width="14" height="10" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path></svg>
                            </span>
                            <input type="password" name="password" id="password" placeholder="Masukkan password baru" required autofocus>
                            <button type="button" id="togglePasswordBtn" class="password-toggle-btn" aria-label="Lihat password" title="Lihat password">
                                <svg id="eyeIconOpen" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg id="eyeIconClosed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display: none;"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path><line x1="2" y1="2" x2="22" y2="22"></line></svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Ulangi Password Baru</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ketik ulang password baru" required>
                            <button type="button" id="toggleConfirmPasswordBtn" class="password-toggle-btn" aria-label="Lihat konfirmasi password" title="Lihat konfirmasi password">
                                <svg id="eyeConfirmOpen" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg id="eyeConfirmClosed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display: none;"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path><line x1="2" y1="2" x2="22" y2="22"></line></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-button">
                        <span>Simpan Password Baru</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </button>

                    <a href="{{ route('login') }}" class="back-to-home-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
                        <span>Batal & Kembali ke Login</span>
                    </a>
                </form>

                <div class="login-footer">
                    <span></span>
                    <p>Sistem Informasi Sekolah Modern</p>
                    <span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function setupPasswordToggle(inputEl, btnEl, openEl, closedEl) {
        if (!inputEl || !btnEl) return;
        btnEl.addEventListener('click', function (e) {
            e.preventDefault();
            const isPassword = inputEl.getAttribute('type') === 'password';
            inputEl.setAttribute('type', isPassword ? 'text' : 'password');
            openEl.style.display = isPassword ? 'none' : 'block';
            closedEl.style.display = isPassword ? 'block' : 'none';
            btnEl.setAttribute('title', isPassword ? 'Sembunyikan password' : 'Lihat password');
            inputEl.focus();
        });
    }

    setupPasswordToggle(
        document.getElementById('password'),
        document.getElementById('togglePasswordBtn'),
        document.getElementById('eyeIconOpen'),
        document.getElementById('eyeIconClosed')
    );

    setupPasswordToggle(
        document.getElementById('password_confirmation'),
        document.getElementById('toggleConfirmPasswordBtn'),
        document.getElementById('eyeConfirmOpen'),
        document.getElementById('eyeConfirmClosed')
    );
});
</script>
@endsection
