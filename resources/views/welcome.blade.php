<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name') }}</title>

    @vite([
        'resources/sass/app.scss',
        'resources/js/app.js'
    ])

    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>

<body>

    {{-- ================= NAVBAR ================= --}}
    <nav class="school-navbar">

        <div class="navbar-container">

            <a href="/" class="school-brand">

                <div class="brand-logo">
                    {{ strtoupper(substr(config('app.name'), 0, 1)) }}
                </div>

                <div class="brand-info">
                    <strong>
                        {{ config('app.name') }}
                    </strong>

                    <span>
                        Modern School Management System
                    </span>
                </div>

            </a>


            <a href="http://localhost:8000/login"
               class="nav-login">

                <span>Masuk ke Portal</span>

                <span class="nav-arrow">
                    →
                </span>

            </a>

        </div>

    </nav>


    {{-- ================= HERO ================= --}}
    <main>

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

                        Selamat datang di
                        <strong>{{ config('app.name') }}</strong>.

                        Platform digital sekolah yang membantu
                        menghadirkan pengelolaan informasi dan layanan
                        pendidikan secara lebih terintegrasi,
                        efektif, dan profesional.

                    </p>


                    {{-- PRIMARY LOGIN --}}
                    <a href="http://localhost:8000/login"
                       class="main-login-button">

                        <span class="login-icon">
                            ↗
                        </span>

                        <span class="login-text">

                            <small>
                                Akses Portal Internal
                            </small>

                            <strong>
                                Masuk ke Portal
                            </strong>

                        </span>

                        <span class="login-arrow">
                            →
                        </span>

                    </a>


                    <div class="hero-note">

                        <span class="check">
                            ✓
                        </span>

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

                            <span>
                                DIGITAL SCHOOL
                            </span>

                            <strong>
                                Membangun generasi
                                unggul melalui pendidikan.
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- ================= QUICK INFORMATION ================= --}}
        <section class="quick-section">

            <div class="quick-container">

                <div class="quick-intro">

                    <span>
                        DIGITAL SCHOOL
                    </span>

                    <strong>
                        Satu platform untuk
                        kebutuhan sekolah.
                    </strong>

                </div>


                <div class="quick-item">

                    <div class="quick-icon">
                        ✓
                    </div>

                    <div>
                        <strong>
                            Terintegrasi
                        </strong>

                        <span>
                            Sistem dalam satu platform
                        </span>
                    </div>

                </div>


                <div class="quick-item">

                    <div class="quick-icon">
                        ◇
                    </div>

                    <div>
                        <strong>
                            Efisien
                        </strong>

                        <span>
                            Informasi lebih terstruktur
                        </span>
                    </div>

                </div>


                <div class="quick-item">

                    <div class="quick-icon">
                        ✦
                    </div>

                    <div>
                        <strong>
                            Modern
                        </strong>

                        <span>
                            Teknologi untuk pendidikan
                        </span>
                    </div>

                </div>

            </div>

        </section>


        {{-- ================= INTRODUCTION ================= --}}
        <section class="about-section">

            <div class="about-container">

                <div class="about-image">

                    <img
                        src="https://file.data.kemendikdasmen.go.id/sekolahkita/69/6996/69969076-3.jpg"
                        alt="Lingkungan sekolah"
                    >

                    <div class="about-number">
                        <strong>
                            01
                        </strong>

                        <span>
                            Digital
                            <br>
                            Education
                        </span>
                    </div>

                </div>


                <div class="about-content">

                    <div class="section-label">
                        TENTANG PLATFORM
                    </div>

                    <h2>
                        Teknologi yang
                        <span>
                            mendukung pendidikan.
                        </span>
                    </h2>

                    <p>
                        {{ config('app.name') }} hadir sebagai bagian
                        dari transformasi digital sekolah. Sistem ini
                        dirancang untuk membantu penyampaian informasi
                        dan layanan sekolah secara lebih mudah,
                        terstruktur, dan profesional.
                    </p>

                    <div class="about-line"></div>

                    <span class="about-caption">
                        Modern School Management System
                    </span>

                </div>

            </div>

        </section>


        {{-- ================= PORTAL CTA ================= --}}
        <section class="portal-section">

            <div class="portal-container">

                <div>

                    <div class="portal-label">
                        PORTAL INTERNAL
                    </div>

                    <h2>
                        Masuk ke portal
                        <span>
                            sekolah sekarang.
                        </span>
                    </h2>

                    <p>
                        Akses sistem informasi sekolah
                        melalui portal internal.
                    </p>

                </div>


                <a href="http://localhost:8000/login"
                   class="portal-login-button">

                    <span>
                        Masuk ke Portal
                    </span>

                    <strong>
                        →
                    </strong>

                </a>

            </div>

        </section>

    </main>


    {{-- ================= FOOTER ================= --}}
    <footer class="school-footer">

        <div class="footer-container">

            <div class="footer-brand">

                <div class="footer-logo">
                    {{ strtoupper(substr(config('app.name'), 0, 1)) }}
                </div>

                <div>

                    <strong>
                        {{ config('app.name') }}
                    </strong>

                    <span>
                        Modern School Management System
                    </span>

                </div>

            </div>


            <div class="footer-copy">

                © {{ date('Y') }}
                {{ config('app.name') }}

            </div>

        </div>

    </footer>

</body>

</html>