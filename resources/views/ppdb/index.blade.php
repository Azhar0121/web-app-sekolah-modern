<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PPDB Online - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ppdb.css') }}">
</head>

<body>

    <div class="ppdb-page">

        {{-- HEADER --}}
        <header class="ppdb-header">

            <a href="{{ url('/') }}" class="back-button">
                <span>&larr;</span>
                Beranda
            </a>

            <div class="header-badge">
                PPDB ONLINE
            </div>

        </header>


        {{-- HERO --}}
        <section class="ppdb-hero">

            <div class="hero-content">

                <span class="hero-label">
                    PENERIMAAN PESERTA DIDIK BARU
                </span>

                <h1>
                    PPDB Online
                </h1>

                <p>
                    Daftarkan diri kamu melalui sistem penerimaan
                    peserta didik baru secara mudah, cepat, dan terintegrasi.
                </p>

            </div>

            <div class="hero-decoration">
                <div class="decoration-circle"></div>

                <div class="school-icon">
                    <svg
                        width="42"
                        height="42"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M3 10.5 12 4l9 6.5"></path>
                        <path d="M5 10v9h14v-9"></path>
                        <path d="M9 19v-5h6v5"></path>
                    </svg>
                </div>
            </div>

        </section>


        {{-- ERROR --}}
        @if (session('error'))
            <div class="alert-error">
                <div class="alert-icon">!</div>

                <div>
                    <strong>Terjadi Kesalahan</strong>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif


        {{-- PERIODE PPDB --}}
        @if ($activePeriod)

            <section class="period-card">

                <div class="period-top">

                    <div>
                        <span class="section-label">
                            PERIODE PENDAFTARAN
                        </span>

                        <h2>
                            {{ $activePeriod->name }}
                        </h2>
                    </div>

                    @if ($activePeriod->isOpenForRegistration())
                        <span class="status-badge status-open">
                            <span></span>
                            DIBUKA
                        </span>
                    @else
                        <span class="status-badge status-closed">
                            <span></span>
                            DITUTUP
                        </span>
                    @endif

                </div>


                <div class="period-info">

                    <div class="info-item">

                        <div class="info-icon">
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>

                        <div>
                            <span>Periode Pendaftaran</span>

                            <strong>
                                {{ $activePeriod->start_date->format('d M Y') }}
                                -
                                {{ $activePeriod->end_date->format('d M Y') }}
                            </strong>
                        </div>

                    </div>

                </div>


                {{-- ACTION --}}
                <div class="period-actions">

                    @if ($activePeriod->isOpenForRegistration())

                        <a href="{{ route('ppdb.create') }}" class="primary-button">

                            <span>Daftar Sekarang</span>

                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>

                        </a>

                    @endif

                    <a href="{{ route('ppdb.cek-status.form') }}" class="secondary-button">

                        <svg
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
                        </svg>

                        <span>Cek Status Pendaftaran</span>

                    </a>

                </div>

            </section>

        @else

            {{-- EMPTY STATE --}}
            <section class="empty-card">

                <div class="empty-icon">
                    <svg
                        width="34"
                        height="34"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>

                <h2>
                    Belum Ada Periode PPDB
                </h2>

                <p>
                    Saat ini belum terdapat periode PPDB yang aktif.
                    Silakan kembali lagi nanti untuk mendapatkan informasi
                    pendaftaran terbaru.
                </p>

                <a href="{{ route('ppdb.cek-status.form') }}" class="secondary-button">
                    Cek Status Pendaftaran
                </a>

            </section>

        @endif


        {{-- FOOTER --}}
        <footer class="ppdb-footer">

            <div class="footer-line"></div>

            <p>
                &copy; {{ date('Y') }} {{ config('app.name') }}
                &nbsp;•&nbsp;
                Sistem PPDB Online
            </p>

        </footer>

    </div>

</body>

</html>