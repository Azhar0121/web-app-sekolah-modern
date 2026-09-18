<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PPDB Online - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ppdb.css') }}">
</head>

<body>

<main class="ppdb-page">

    {{-- HERO --}}
    <section class="ppdb-hero">

        <div class="ppdb-hero-content">

            <div class="ppdb-badge">
                PPDB ONLINE
            </div>

            <span class="ppdb-eyebrow">
                PENERIMAAN PESERTA DIDIK BARU
            </span>

            <h1>
                PPDB Online
            </h1>

            <p class="ppdb-description">
                Daftarkan diri kamu melalui sistem penerimaan peserta didik baru
                secara mudah, cepat, dan terintegrasi.
            </p>

            <a href="{{ url('/') }}" class="ppdb-dashboard-link">
                ← Kembali ke Dashboard
            </a>

        </div>

        <div class="ppdb-hero-image">
            <img
                src="https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1400&q=85"
                alt="Gedung sekolah"
                loading="eager"
                decoding="async"
                referrerpolicy="no-referrer"
            >
        </div>

    </section>


    {{-- CONTENT --}}
    <section class="ppdb-content">

        {{-- ERROR --}}
        @if (session('error'))
            <div class="ppdb-alert ppdb-alert-error">
                {{ session('error') }}
            </div>
        @endif


        {{-- PERIODE PPDB --}}
        @if ($activePeriod)

            @php
                $isOpen = $activePeriod->isOpenForRegistration();
            @endphp

            <div class="ppdb-period-card">

                <div class="ppdb-period-heading">
                    <span class="ppdb-period-label">
                        PERIODE PENDAFTARAN
                    </span>

                    <h2>
                        Periode PPDB Aktif
                    </h2>
                </div>


                <div class="ppdb-period-status {{ $isOpen ? 'is-open' : 'is-closed' }}">

                    <span class="ppdb-status-dot"></span>

                    {{ $isOpen ? 'Pendaftaran Dibuka' : 'Pendaftaran Ditutup' }}

                </div>


                <div class="ppdb-period-date">

                    <span>
                        Periode Pendaftaran
                    </span>

                    <strong>
                        {{ $activePeriod->start_date->format('d M Y') }}
                        -
                        {{ $activePeriod->end_date->format('d M Y') }}
                    </strong>

                </div>


                <div class="ppdb-actions">

                    @if ($isOpen)

                        <a
                            href="{{ route('ppdb.create') }}"
                            class="ppdb-btn ppdb-btn-primary"
                        >
                            Daftar Sekarang
                        </a>

                    @endif


                    <a
                        href="{{ route('ppdb.cek-status.form') }}"
                        class="ppdb-btn ppdb-btn-secondary"
                    >
                        Cek Status Pendaftaran
                    </a>

                </div>

            </div>

        @else

            {{-- EMPTY STATE --}}
            <div class="ppdb-empty">

                <div class="ppdb-empty-icon">
                    !
                </div>

                <h2>
                    Belum Ada Periode Pendaftaran
                </h2>

                <p>
                    Saat ini belum tersedia periode PPDB yang sedang aktif.
                    Silakan kembali lagi nanti untuk informasi pendaftaran berikutnya.
                </p>

            </div>

        @endif

    </section>


    {{-- FOOTER --}}
    <footer class="ppdb-footer">
        &copy; {{ date('Y') }} {{ config('app.name') }}
        • Sistem PPDB Online
    </footer>

</main>

</body>
</html>