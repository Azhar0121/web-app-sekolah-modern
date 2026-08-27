<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pendaftaran Berhasil - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ppdb-success.css') }}">
</head>

<body>

    <div class="success-page">

        <div class="success-card">

            <div class="success-icon">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <div class="success-header">
                <span class="success-label">PENDAFTARAN TERKIRIM</span>

                <h1>Pendaftaran Berhasil Dikirim</h1>

                <p>
                    Terima kasih! Data pendaftaran Anda telah kami terima dan
                    akan segera diverifikasi oleh panitia PPDB.
                </p>
            </div>

            <div class="registration-box">
                <span class="registration-label">NOMOR PENDAFTARAN ANDA</span>
                <div class="registration-number">{{ $registration->registration_number }}</div>

                <p>
                    Nomor ini juga sudah kami kirimkan ke email
                    <strong>{{ $registration->email }}</strong>.
                    Simpan baik-baik untuk mengecek status pendaftaran Anda nanti.
                </p>
            </div>

            <div class="status-box">
                <div class="status-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>

                <div>
                    <span>Status Saat Ini</span>
                    <strong>{{ $registration->statusLabel() }}</strong>
                </div>
            </div>

            <div class="success-actions">

                <a href="{{ route('ppdb.cetak', $registration->registration_number) }}" class="btn btn-primary" target="_blank" rel="noopener">
                    <span>Cetak Bukti Pendaftaran</span>

                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                </a>

                <a href="{{ route('ppdb.cek-status.form') }}" class="btn btn-secondary">
                    <span>Cek Status Pendaftaran</span>

                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>

                <a href="{{ route('ppdb.lupa-nomor.form') }}" class="btn btn-secondary">
                    Lupa Nomor Pendaftaran?
                </a>

                <a href="{{ url('/') }}" class="btn btn-secondary">
                    Kembali ke Beranda
                </a>

            </div>

            <div class="success-footer">
                <span></span>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }} • Sistem PPDB Online</p>
                <span></span>
            </div>

        </div>

    </div>

</body>

</html>
