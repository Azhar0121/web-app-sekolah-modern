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

        {{-- HEADER --}}
        <header class="status-header">

            <a href="{{ route('ppdb.index') }}" class="back-button">
                <span>&larr;</span>
                PPDB Online
            </a>

            <div class="header-badge">
                CEK STATUS
            </div>

        </header>


        <div class="status-container">

            {{-- HERO --}}
            <section class="status-hero">

                <div class="hero-content">
                    <span class="hero-label">
                        PENERIMAAN PESERTA DIDIK BARU
                    </span>

                    <h1>
                        Cek Status Pendaftaran
                    </h1>

                    <p>
                        Masukkan nomor pendaftaran Anda untuk melihat status
                        terkini dari proses seleksi PPDB.
                    </p>
                </div>

                <div class="hero-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
                    </svg>
                </div>

            </section>


            {{-- FORM --}}
            <div class="status-form-card">

                <div class="section-heading">
                    <div class="heading-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>

                    <div>
                        <span>PENCARIAN</span>
                        <h2>Masukkan Nomor Pendaftaran</h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('ppdb.cek-status') }}">
                    @csrf

                    <div class="form-group">
                        <label>Nomor Pendaftaran</label>

                        <div class="input-wrapper">
                            <span class="input-icon">#</span>
                            <input type="text" name="registration_number" placeholder="PPDB-2026-00001"
                                   value="{{ old('registration_number', $registration->registration_number ?? '') }}"
                                   required>
                        </div>

                        <small>
                            Lupa nomor pendaftaran?
                            <a href="{{ route('ppdb.lupa-nomor.form') }}">Cari di sini</a>
                        </small>
                    </div>

                    <button type="submit" class="check-button">
                        <span>Cek Status</span>
                        <span class="arrow">&rarr;</span>
                    </button>
                </form>

            </div>


            {{-- HASIL --}}
            @if ($searched ?? false)

                @if ($registration)

                    <div class="result-card">

                        <div class="result-header">
                            <div class="result-success-icon">✓</div>

                            <div>
                                <span class="result-label">DITEMUKAN</span>
                                <h2>{{ $registration->registration_number }}</h2>
                            </div>
                        </div>

                        <div class="result-data">

                            <div class="data-item">
                                <span class="data-label">Nama Calon Siswa</span>
                                <strong>{{ $registration->full_name }}</strong>
                            </div>

                            <div class="data-item">
                                <span class="data-label">Status</span>
                                <strong class="registration-status">{{ $registration->statusLabel() }}</strong>
                            </div>

                            @if ($registration->status === 'accepted')
                                <div class="data-item full">
                                    <span class="data-label">Batas Waktu Daftar Ulang</span>
                                    <strong>{{ $registration->reRegistrationDeadlineLabel() }}</strong>
                                    <p class="notes" style="margin-top: 8px;">
                                        Silakan datang langsung ke sekolah sebelum tanggal tersebut
                                        untuk menyelesaikan daftar ulang & pembayaran.
                                    </p>
                                </div>
                            @endif

                            @if ($registration->status === 'registered_ulang')
                                <div class="data-item full">
                                    <span class="data-label">Akun Portal Siswa</span>
                                    <p class="notes" style="margin-top: 8px;">
                                        Selamat! Daftar ulang Anda sudah dikonfirmasi dan akun Portal Siswa
                                        sudah aktif. Kredensial login (email &amp; password sementara) sudah
                                        kami kirimkan ke email yang Anda daftarkan.
                                    </p>
                                    <div style="margin-top: 12px;">
                                        <a href="{{ url('/login') }}" class="check-button"
                                           style="width: auto; padding: 0 22px; text-decoration: none; display: inline-flex;">
                                            <span>Login ke Portal Siswa</span>
                                            <span class="arrow">&rarr;</span>
                                        </a>
                                    </div>
                                    <p class="notes" style="margin-top: 10px; font-size: 13px;">
                                        Belum menerima email atau lupa password? Hubungi Tata Usaha sekolah.
                                    </p>
                                </div>
                            @endif

                            @if ($registration->notes)
                                <div class="data-item full">
                                    <span class="data-label">Catatan</span>
                                    <p class="notes">{{ $registration->notes }}</p>
                                </div>
                            @endif

                            <div class="data-item full">
                                <span class="data-label">Tanggal Daftar</span>
                                <strong>{{ $registration->created_at->format('d M Y H:i') }}</strong>
                            </div>

                        </div>

                        <div style="padding-top: 20px; display: flex; justify-content: flex-end;">
                            <a href="{{ route('ppdb.cetak', $registration->registration_number) }}"
                               class="check-button" style="width: auto; padding: 0 22px; text-decoration: none;"
                               target="_blank" rel="noopener">
                                <span>Cetak Bukti Pendaftaran</span>
                            </a>
                        </div>

                    </div>

                @else

                    <div class="result-card">
                        <div class="not-found">
                            <div class="not-found-icon">!</div>

                            <div>
                                <h2>Nomor Pendaftaran Tidak Ditemukan</h2>
                                <p>Periksa kembali penulisan nomor pendaftaran Anda, atau gunakan fitur "Lupa Nomor Pendaftaran".</p>
                            </div>
                        </div>
                    </div>

                @endif

            @endif

            {{-- FOOTER --}}
            <div class="status-footer">
                <div class="footer-line"></div>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }} • Sistem PPDB Online</p>
            </div>

        </div>

    </div>

</body>

</html>
