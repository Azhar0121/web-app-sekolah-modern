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

        {{-- HEADER --}}
        <header class="status-header">

            <a href="{{ route('ppdb.cek-status.form') }}" class="back-button">
                <span>&larr;</span>
                Cek Status
            </a>

            <div class="header-badge">
                LUPA NOMOR
            </div>

        </header>


        <div class="status-container">

            {{-- HERO --}}
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
                </div>

                <div class="hero-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M4 21c0-4 3.5-7 8-7s8 3 8 7"></path>
                    </svg>
                </div>

            </section>


            {{-- FORM --}}
            <div class="status-form-card">

                <div class="section-heading">
                    <div class="heading-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 21c0-4 3.5-7 8-7s8 3 8 7"></path>
                        </svg>
                    </div>

                    <div>
                        <span>VERIFIKASI IDENTITAS</span>
                        <h2>Data Calon Siswa</h2>
                    </div>
                </div>

                <p class="intro-text">
                    Masukkan nama lengkap dan tanggal lahir calon siswa persis
                    seperti saat mendaftar. Kedua data ini dipakai untuk
                    memverifikasi identitas Anda sebelum menampilkan nomor
                    pendaftaran.
                </p>

                @if ($errors->any())
                    <div class="error-box" style="margin-bottom: 20px;">
                        <div class="error-icon">!</div>
                        <div>
                            <strong>Periksa kembali data yang Anda isi</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('ppdb.lupa-nomor') }}">
                    @csrf

                    <div class="form-group">
                        <label>Nama Lengkap Calon Siswa</label>

                        <div class="input-wrapper">
                            <span class="input-icon">&#128100;</span>
                            <input type="text" name="full_name" value="{{ old('full_name') }}"
                                   placeholder="Sesuai saat mendaftar" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Lahir</label>

                        <div class="input-wrapper">
                            <span class="input-icon">&#128197;</span>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}" required>
                        </div>
                    </div>

                    <button type="submit" class="check-button">
                        <span>Cari Nomor Pendaftaran</span>
                        <span class="arrow">&rarr;</span>
                    </button>
                </form>

            </div>


            {{-- HASIL --}}
            @isset($registrations)

                <div class="result-card">

                    @if ($registrations->isEmpty())

                        <div class="not-found">
                            <div class="not-found-icon">!</div>

                            <div>
                                <h2>Tidak Ditemukan</h2>
                                <p>
                                    Tidak ada pendaftaran dengan nama dan tanggal lahir tersebut.
                                    Periksa kembali penulisan nama — harus persis sama dengan saat mendaftar.
                                </p>
                            </div>
                        </div>

                    @else

                        <div class="result-header">
                            <div class="result-success-icon">✓</div>

                            <div>
                                <span class="result-label">DITEMUKAN</span>
                                <h2>{{ $registrations->count() }} Pendaftaran</h2>
                            </div>
                        </div>

                        <div class="found-list" style="padding-top: 22px;">
                            @foreach ($registrations as $registration)
                                <div class="found-item">
                                    <div>
                                        <p class="found-number">{{ $registration->registration_number }}</p>
                                        <p class="found-meta">
                                            Status: <strong>{{ $registration->statusLabel() }}</strong>
                                            &middot;
                                            Didaftarkan {{ $registration->created_at->format('d M Y') }}
                                        </p>
                                    </div>

                                    <a href="{{ route('ppdb.cek-status.form') }}?registration_number={{ $registration->registration_number }}"
                                       class="check-link">
                                        Lihat Status
                                    </a>
                                </div>
                            @endforeach
                        </div>

                    @endif

                </div>

            @endisset

            {{-- FOOTER --}}
            <div class="status-footer">
                <div class="footer-line"></div>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }} • Sistem PPDB Online</p>
            </div>

        </div>

    </div>

</body>

</html>
