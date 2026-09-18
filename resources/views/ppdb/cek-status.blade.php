<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Cek Status Pendaftaran - {{ config('app.name') }}</title>

<link rel="stylesheet" href="{{ asset('css/base.css') }}">
<link rel="stylesheet" href="{{ asset('css/ppdb-status.css') }}">

</head>

<body>

<div class="status-page">


    <main class="status-container">

        {{-- HERO --}}
        <section class="status-hero">

            <div class="hero-content">

                <span class="hero-label">
                    PENERIMAAN PESERTA DIDIK BARU
                </span>

                <h1>Cek Status Pendaftaran</h1>

                <p>
                    Masukkan nomor pendaftaran Anda untuk melihat status
                    terkini dari proses seleksi PPDB.
                </p>

                <a href="{{ route('ppdb.index') }}" class="hero-back-button">
                    <span>&larr;</span>
                    PPDB Online
                </a>

            </div>

            <div class="hero-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
                </svg>
            </div>

        </section>


        {{-- FORM --}}
        <section class="status-form-card">

            <div class="section-heading">

                <div class="heading-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>

                <div class="heading-text">
                    <span>PENCARIAN</span>
                    <h2>Masukkan Nomor Pendaftaran</h2>
                </div>

            </div>


            <form method="POST" action="{{ route('ppdb.cek-status') }}" class="status-form">
                @csrf

                <div class="form-group">

                    <label for="registration_number">
                        Nomor Pendaftaran
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">#</span>

                        <input
                            type="text"
                            id="registration_number"
                            name="registration_number"
                            placeholder="PPDB-2026-00001"
                            value="{{ old('registration_number', $registration->registration_number ?? '') }}"
                            required
                        >

                    </div>

                    <small class="form-help">
                        Lupa nomor pendaftaran?
                        <a href="{{ route('ppdb.lupa-nomor.form') }}">
                            Cari di sini
                        </a>
                    </small>

                </div>


                <div class="form-action">
                    <button type="submit" class="check-button">
                        Cek Status
                    </button>
                </div>

            </form>

        </section>


        {{-- HASIL --}}
        @if ($searched ?? false)

            @if ($registration)

                <section class="result-card">

                    <div class="result-header">

                        <div class="result-success-icon">
                            ✓
                        </div>

                        <div class="result-header-content">
                            <span>DITEMUKAN</span>
                            <h2>{{ $registration->registration_number }}</h2>
                        </div>

                    </div>


                    <div class="result-data">

                        <div class="data-item">
                            <span class="data-label">
                                Nama Calon Siswa
                            </span>

                            <strong>
                                {{ $registration->full_name }}
                            </strong>
                        </div>


                        <div class="data-item">
                            <span class="data-label">
                                Pilihan Jurusan
                            </span>

                            <strong>
                                {{ $registration->first_major ?: '-' }}
                            </strong>

                            @if ($registration->second_major)
                                <small class="secondary-note">
                                    Pilihan 2: {{ $registration->second_major }}
                                </small>
                            @endif
                        </div>


                        <div class="data-item">
                            <span class="data-label">
                                Status
                            </span>

                            <strong class="registration-status">
                                {{ $registration->statusLabel() }}
                            </strong>
                        </div>


                        @if ($registration->status === 'accepted')

                            <div class="data-item full">

                                <span class="data-label">
                                    Batas Waktu Daftar Ulang
                                </span>

                                <strong>
                                    {{ $registration->reRegistrationDeadlineLabel() }}
                                </strong>

                                <p class="notes">
                                    Silakan datang langsung ke sekolah sebelum tanggal tersebut
                                    untuk menyelesaikan daftar ulang dan pembayaran.
                                </p>


                                @if ($registration->isReRegistrationOverdue())

                                    <div class="overdue-warning">

                                        <div class="overdue-warning-icon">
                                            !
                                        </div>

                                        <p>
                                            Batas waktu daftar ulang Anda sudah lewat.
                                            Segera hubungi pihak sekolah (Tata Usaha)
                                            untuk menanyakan apakah daftar ulang masih
                                            bisa diproses.
                                        </p>

                                    </div>

                                @endif

                            </div>

                        @endif


                        @if ($registration->status === 'registered_ulang')

                            <div class="data-item full">

                                <span class="data-label">
                                    Akun Portal Siswa
                                </span>

                                <p class="notes">
                                    Selamat! Daftar ulang Anda sudah dikonfirmasi dan
                                    akun Portal Siswa sudah aktif. Kredensial login
                                    (email dan password sementara) sudah kami kirimkan
                                    ke email yang Anda daftarkan.
                                </p>

                                <div class="portal-action">

                                    <a href="{{ url('/login') }}" class="secondary-button">
                                        Login ke Portal Siswa
                                    </a>

                                </div>

                                <p class="secondary-note">
                                    Belum menerima email atau lupa password?
                                    Hubungi Tata Usaha sekolah.
                                </p>

                            </div>

                        @endif


                        @if ($registration->notes)

                            <div class="data-item full">

                                <span class="data-label">
                                    Catatan
                                </span>

                                <p class="notes">
                                    {{ $registration->notes }}
                                </p>

                            </div>

                        @endif


                        <div class="data-item full">

                            <span class="data-label">
                                Tanggal Daftar
                            </span>

                            <strong>
                                {{ $registration->created_at->format('d M Y H:i') }}
                            </strong>

                        </div>

                    </div>


                    <div class="result-action">

                        <a
                            href="{{ route('ppdb.cetak', $registration->registration_number) }}"
                            class="secondary-button"
                            target="_blank"
                            rel="noopener"
                        >
                            Cetak Bukti Pendaftaran
                        </a>

                    </div>

                </section>

            @else

                <section class="result-card">

                    <div class="not-found">

                        <div class="not-found-icon">
                            !
                        </div>

                        <div class="not-found-content">

                            <h2>
                                Nomor Pendaftaran Tidak Ditemukan
                            </h2>

                            <p>
                                Periksa kembali penulisan nomor pendaftaran Anda,
                                atau gunakan fitur "Lupa Nomor Pendaftaran".
                            </p>

                        </div>

                    </div>

                </section>

            @endif

        @endif


        {{-- FOOTER --}}
        <footer class="status-footer">

            <div class="footer-line"></div>

            <p>
                &copy; {{ date('Y') }} {{ config('app.name') }}
                <span>•</span>
                Sistem PPDB Online
            </p>

        </footer>

    </main>

</div>

</body>

</html>
