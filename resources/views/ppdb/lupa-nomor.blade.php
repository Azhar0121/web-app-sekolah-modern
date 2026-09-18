<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lupa Nomor Pendaftaran - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ppdb-status.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ppdb-lupa-nomor.css') }}">
</head>

<body>

    <div class="status-page">

        <div class="status-container">

            {{-- =====================================================
                 HERO
                 ===================================================== --}}
            <section class="status-hero">

                <div class="hero-content">

                    <span class="hero-label">
                        PEMULIHAN NOMOR PENDAFTARAN
                    </span>

                    <h1>
                        Lupa Nomor Pendaftaran?
                    </h1>

                    <p>
                        Cari kembali nomor pendaftaran Anda menggunakan nama
                        lengkap dan tanggal lahir calon siswa.
                    </p>

                    {{-- CEK STATUS --}}
                    <a href="{{ route('ppdb.cek-status.form') }}" class="back-button">
                        <span>&larr;</span>
                        <span>Cek Status</span>
                    </a>

                </div>

                {{-- ICON HERO --}}
                <div class="hero-icon" aria-hidden="true">

                    <svg width="40" height="40"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <circle cx="12" cy="8" r="4"></circle>

                        <path d="M4 21c0-4 3.5-7 8-7s8 3 8 7"></path>

                    </svg>

                </div>

            </section>


            {{-- =====================================================
                 FORM
                 ===================================================== --}}
            <div class="status-form-card">

                <div class="section-heading">

                    <div class="heading-icon" aria-hidden="true">

                        <svg width="22" height="22"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round">

                            <circle cx="12" cy="8" r="4"></circle>

                            <path d="M4 21c0-4 3.5-7 8-7s8 3 8 7"></path>

                        </svg>

                    </div>

                    <div>

                        <span>
                            VERIFIKASI IDENTITAS
                        </span>

                        <h2>
                            Data Calon Siswa
                        </h2>

                    </div>

                </div>


                <p class="intro-text">
                    Masukkan nama lengkap dan tanggal lahir calon siswa persis
                    seperti saat mendaftar. Kedua data ini dipakai untuk
                    memverifikasi identitas Anda sebelum menampilkan nomor
                    pendaftaran.
                </p>


                {{-- ERROR --}}
                @if ($errors->any())

                    <div class="error-box">

                        <div class="error-icon" aria-hidden="true">
                            !
                        </div>

                        <div>

                            <strong>
                                Periksa kembali data yang Anda isi
                            </strong>

                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>

                    </div>

                @endif


                {{-- FORM PENCARIAN --}}
                <form method="POST" action="{{ route('ppdb.lupa-nomor') }}">

                    @csrf

                    {{-- NAMA --}}
                    <div class="form-group">

                        <label for="full_name">
                            Nama Lengkap Calon Siswa
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon" aria-hidden="true">

                                <svg width="18" height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">

                                    <circle cx="12" cy="8" r="4"></circle>

                                    <path d="M4 21c0-4 3.5-7 8-7s8 3 8 7"></path>

                                </svg>

                            </span>

                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                value="{{ old('full_name') }}"
                                placeholder="Sesuai saat mendaftar"
                                autocomplete="name"
                                required
                            >

                        </div>

                    </div>


                    {{-- TANGGAL LAHIR --}}
                    <div class="form-group">

                        <label for="birth_date">
                            Tanggal Lahir
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon" aria-hidden="true">

                                <svg width="18" height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">

                                    <rect
                                        x="3"
                                        y="4"
                                        width="18"
                                        height="17"
                                        rx="2">
                                    </rect>

                                    <line
                                        x1="16"
                                        y1="2"
                                        x2="16"
                                        y2="6">
                                    </line>

                                    <line
                                        x1="8"
                                        y1="2"
                                        x2="8"
                                        y2="6">
                                    </line>

                                    <line
                                        x1="3"
                                        y1="10"
                                        x2="21"
                                        y2="10">
                                    </line>

                                </svg>

                            </span>

                            <input
                                type="date"
                                id="birth_date"
                                name="birth_date"
                                value="{{ old('birth_date') }}"
                                autocomplete="bday"
                                required
                            >

                        </div>

                    </div>


                    {{-- TOMBOL CARI --}}
                    <button type="submit" class="check-button">
                        Cari Nomor Pendaftaran
                    </button>

                </form>

            </div>


            {{-- =====================================================
                 HASIL PENCARIAN
                 ===================================================== --}}
            @isset($registrations)

                <div class="result-card">

                    @if ($registrations->isEmpty())

                        {{-- TIDAK DITEMUKAN --}}
                        <div class="not-found">

                            <div class="not-found-icon" aria-hidden="true">
                                !
                            </div>

                            <div>

                                <h2>
                                    Tidak Ditemukan
                                </h2>

                                <p>
                                    Tidak ada pendaftaran dengan nama dan
                                    tanggal lahir tersebut. Periksa kembali
                                    penulisan nama — harus persis sama dengan
                                    saat mendaftar.
                                </p>

                            </div>

                        </div>

                    @else

                        {{-- DITEMUKAN --}}
                        <div class="result-header">

                            <div class="result-success-icon" aria-hidden="true">
                                ✓
                            </div>

                            <div>

                                <span class="result-label">
                                    DITEMUKAN
                                </span>

                                <h2>
                                    {{ $registrations->count() }} Pendaftaran
                                </h2>

                            </div>

                        </div>


                        <div class="found-list">

                            @foreach ($registrations as $registration)

                                <div class="found-item">

                                    <div>

                                        <p class="found-number">
                                            {{ $registration->registration_number }}
                                        </p>

                                        <p class="found-meta">

                                            Status:

                                            <strong>
                                                {{ $registration->statusLabel() }}
                                            </strong>

                                            &middot;

                                            Didaftarkan
                                            {{ $registration->created_at->format('d M Y') }}

                                        </p>

                                    </div>

                                    <a
                                        href="{{ route('ppdb.cek-status.form') }}?registration_number={{ $registration->registration_number }}"
                                        class="check-link"
                                    >
                                        Lihat Status
                                    </a>

                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>

            @endisset


            {{-- =====================================================
                 FOOTER
                 ===================================================== --}}
            <div class="status-footer">

                <div class="footer-line"></div>

                <p>
                    &copy; {{ date('Y') }}
                    {{ config('app.name') }}
                    • Sistem PPDB Online
                </p>

            </div>

        </div>

    </div>

</body>

</html>