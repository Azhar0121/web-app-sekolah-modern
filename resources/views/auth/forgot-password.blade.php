@extends('layouts.guest')

@section('title', 'Lupa Password - ' . config('app.name'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/base.css') }}">
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .login-spinner {
        animation: spin 0.75s linear infinite;
        display: inline-block;
        flex-shrink: 0;
    }
    .login-button:disabled {
        opacity: 0.75;
        cursor: not-allowed;
        pointer-events: none;
        transform: none !important;
        box-shadow: none !important;
    }
</style>

<div class="login-page">
    <div class="login-card">
        {{-- Visual Kiri --}}
        <div class="login-visual">
            <div class="visual-overlay"></div>
            <div class="visual-content">
                <div class="school-badge">
                    <span class="badge-dot"></span>
                    PEMULIHAN AKUN
                </div>
                <h1>Lupa Password? <span>Kami Siap Membantu</span></h1>
                <div class="visual-line"></div>
                <p>Masukkan alamat email yang terdaftar pada akun Anda. Sistem akan mengirimkan tautan verifikasi resmi untuk mengatur ulang kata sandi dengan aman.</p>
            </div>
        </div>

        {{-- Panel Kanan --}}
        <div class="login-panel">
            <div class="login-content">
                <div class="school-logo">
                    <span>{{ strtoupper(substr(config('app.name'), 0, 1)) }}</span>
                </div>

                <div class="login-heading">
                    <h2>Reset Password</h2>
                    <p>Masukkan email akun Anda untuk menerima tautan pemulihan kata sandi.</p>
                </div>

                {{-- Alert Box Container --}}
                <div id="alertContainer">
                    @if (session('status'))
                        <div class="login-alert login-alert-success">
                            <div class="alert-icon">✓</div>
                            <div>{{ session('status') }}</div>
                        </div>
                    @endif

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
                </div>

                <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email Terdaftar</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>
                            </span>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="@error('email') input-error @enderror" placeholder="nama@email.com" required autofocus>
                        </div>
                        @error('email')
                            <span class="field-error" id="emailFieldError">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" id="submitBtn" class="login-button">
                        <span id="btnText">Kirim Link Reset Password</span>
                        <svg id="btnIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>

                    <a href="{{ route('login') }}" class="back-to-home-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
                        <span>Kembali ke Halaman Login</span>
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
    const form = document.getElementById('forgotPasswordForm');
    const emailInput = document.getElementById('email');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnIcon = document.getElementById('btnIcon');
    const alertContainer = document.getElementById('alertContainer');
    const fieldError = document.getElementById('emailFieldError');

    if (!form || !submitBtn) return;

    form.addEventListener('submit', async function (e) {
        // Jika browser tidak mendukung fetch API, biarkan submit form standar
        if (!window.fetch) return;

        e.preventDefault();

        const email = emailInput.value.trim();
        if (!email) {
            emailInput.focus();
            return;
        }

        // 1. Tampilkan state loading responsif & nonaktifkan tombol untuk cegah spam/double-click
        submitBtn.disabled = true;
        btnText.textContent = 'Mengirim Tautan...';
        btnIcon.outerHTML = `
            <svg id="btnIcon" class="login-spinner" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
                <path d="M12 2a10 10 0 0 1 10 10"></path>
            </svg>
        `;

        if (fieldError) fieldError.style.display = 'none';

        const token = form.querySelector('input[name="_token"]')?.value || '';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ email: email })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                alertContainer.innerHTML = `
                    <div class="login-alert login-alert-success">
                        <div class="alert-icon">✓</div>
                        <div>${data.message || 'Tautan reset password telah dikirim ke email Anda! Silakan periksa kotak masuk atau spam.'}</div>
                    </div>
                `;
                emailInput.value = '';
                btnText.textContent = 'Kirim Ulang Tautan';
            } else {
                const msg = data.message || (data.errors && data.errors.email ? data.errors.email[0] : 'Email tidak terdaftar dalam sistem kami.');
                alertContainer.innerHTML = `
                    <div class="login-alert">
                        <div class="alert-icon">!</div>
                        <div>${msg}</div>
                    </div>
                `;
                btnText.textContent = 'Kirim Link Reset Password';
            }
        } catch (err) {
            // Jika terjadi kesalahan koneksi atau timeout, fallback submit normal
            form.submit();
            return;
        } finally {
            submitBtn.disabled = false;
            const currentIcon = document.getElementById('btnIcon');
            if (currentIcon) {
                currentIcon.outerHTML = `
                    <svg id="btnIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                `;
            }
        }
    });
});
</script>
@endsection
