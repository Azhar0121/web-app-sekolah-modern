@extends('layouts.guest')

@section('title', 'Lupa Password - ' . config('app.name'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/base.css') }}">
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

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

                <form method="POST" action="{{ route('password.email') }}">
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
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="login-button">
                        <span>Kirim Link Reset Password</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
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
@endsection
