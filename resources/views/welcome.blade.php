@extends('layouts.public')

@section('title', config('app.name'))

@section('content')

    <section class="hero">
        <div class="hero-container">

            {{-- LEFT --}}
            <div class="hero-content">
                <div class="hero-eyebrow">
                    <span></span>
                    SISTEM INFORMASI SEKOLAH
                </div>

                <h1>
                    Pendidikan Modern,
                    <span>Masa Depan Gemilang.</span>
                </h1>

                <p class="hero-description">
                    Selamat datang di <strong>{{ config('app.name') }}</strong>.
                    Platform digital sekolah yang membantu menghadirkan pengelolaan
                    informasi dan layanan pendidikan secara lebih terintegrasi,
                    efektif, dan profesional.
                </p>

                {{-- DUA CTA: Login & PPDB --}}
                <div class="hero-cta-group">
                    <a href="{{ url('/login') }}" class="main-login-button">
                        <span class="login-icon">&#8599;</span>
                        <span class="login-text">
                            <small>Akses Portal Internal</small>
                            <strong>Masuk ke Portal</strong>
                        </span>
                        <span class="login-arrow">&rarr;</span>
                    </a>

                    <a href="{{ route('ppdb.index') }}" class="main-ppdb-button">
                        <span class="login-icon">&#128196;</span>
                        <span class="login-text">
                            <small>Tahun Ajaran Baru</small>
                            <strong>PPDB Online</strong>
                        </span>
                        <span class="login-arrow">&rarr;</span>
                    </a>
                </div>

                <div class="hero-note">
                    <span class="check">&check;</span>
                    Portal resmi {{ config('app.name') }}
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="hero-image-wrapper">
                <div class="image-frame">
                    <img
                        src="https://file.data.kemendikdasmen.go.id/sekolahkita/69/6996/69969076-3.jpg"
                        alt="Gedung sekolah"
                    >
                    <div class="image-gradient"></div>
                    <div class="image-caption">
                        <span>DIGITAL SCHOOL</span>
                        <strong>Membangun generasi unggul melalui pendidikan.</strong>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="quick-section">
        <div class="quick-container">
            <div class="quick-intro">
                <span>DIGITAL SCHOOL</span>
                <strong>Satu platform untuk kebutuhan sekolah.</strong>
            </div>

            <div class="quick-item">
                <div class="quick-icon">&check;</div>
                <div>
                    <strong>Terintegrasi</strong>
                    <span>Sistem dalam satu platform</span>
                </div>
            </div>

            <div class="quick-item">
                <div class="quick-icon">&#9671;</div>
                <div>
                    <strong>Efisien</strong>
                    <span>Informasi lebih terstruktur</span>
                </div>
            </div>

            <div class="quick-item">
                <div class="quick-icon">&#10022;</div>
                <div>
                    <strong>Modern</strong>
                    <span>Teknologi untuk pendidikan</span>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section">
        <div class="about-container">
            <div class="about-image">
                <img
                    src="https://file.data.kemendikdasmen.go.id/sekolahkita/69/6996/69969076-3.jpg"
                    alt="Lingkungan sekolah"
                >
                <div class="about-number">
                    <strong>01</strong>
                    <span>Digital<br>Education</span>
                </div>
            </div>

            <div class="about-content">
                <div class="section-label">TENTANG PLATFORM</div>
                <h2>
                    Teknologi yang
                    <span>mendukung pendidikan.</span>
                </h2>
                <p>
                    {{ config('app.name') }} hadir sebagai bagian dari transformasi
                    digital sekolah. Sistem ini dirancang untuk membantu penyampaian
                    informasi dan layanan sekolah secara lebih mudah, terstruktur,
                    dan profesional.
                </p>
                <div class="about-line"></div>
                <span class="about-caption">Modern School Management System</span>
            </div>
        </div>
    </section>

    <section class="portal-section">
        <div class="portal-container">
            <div>
                <div class="portal-label">PORTAL & PENDAFTARAN</div>
                <h2>
                    Masuk ke portal atau
                    <span>daftar PPDB sekarang.</span>
                </h2>
                <p>Akses sistem informasi sekolah atau mulai pendaftaran siswa baru.</p>
            </div>

            <div class="portal-cta-group">
                <a href="{{ route('ppdb.index') }}" class="portal-login-button">
                    <span>PPDB Online</span>
                    <strong>&rarr;</strong>
                </a>
                <a href="{{ url('/login') }}" class="portal-login-button">
                    <span>Masuk ke Portal</span>
                    <strong>&rarr;</strong>
                </a>
            </div>
        </div>
    </section>

@endsection
