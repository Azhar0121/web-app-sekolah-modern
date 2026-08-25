<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pendaftaran Berhasil - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/ppdb-success.css') }}">
</head>

<body>

    <main class="success-page">

        <div class="success-card">

            {{-- ICON SUCCESS --}}
            <div class="success-icon">
                <svg
                    width="32"
                    height="32"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M20 6 9 17l-5-5"></path>
                </svg>
            </div>


            {{-- JUDUL --}}
            <div class="success-header">
                <span class="success-label">
                    PENDAFTARAN PPDB
                </span>

                <h1>
                    Pendaftaran Berhasil!
                </h1>

                <p>
                    Data pendaftaran Anda telah berhasil dikirim.
                    Silakan simpan nomor pendaftaran berikut untuk
                    mengecek status pendaftaran Anda.
                </p>
            </div>


            {{-- NOMOR PENDAFTARAN --}}
            <div class="registration-box">

                <span class="registration-label">
                    NOMOR PENDAFTARAN ANDA
                </span>

                <div class="registration-number">
                    {{ $registration->registration_number }}
                </div>

                <p>
                    Simpan nomor ini dengan baik untuk keperluan
                    pengecekan status pendaftaran.
                </p>

            </div>


            {{-- STATUS --}}
            <div class="status-box">

                <div class="status-icon">
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
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 7v5l3 2"></path>
                    </svg>
                </div>

                <div>
                    <span>Status Pendaftaran</span>
                    <strong>
                        {{ $registration->statusLabel() }}
                    </strong>
                </div>

            </div>


            {{-- ACTION --}}
            <div class="success-actions">

                <a
                    href="{{ route('ppdb.cek-status.form') }}"
                    class="btn btn-primary"
                >
                    Cek Status Pendaftaran
                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M5 12h14"></path>
                        <path d="m13 6 6 6-6 6"></path>
                    </svg>
                </a>

                <a
                    href="{{ url('/') }}"
                    class="btn btn-secondary"
                >
                    &larr; Kembali ke Beranda
                </a>

            </div>


            {{-- FOOTER --}}
            <div class="success-footer">
                <span></span>

                <p>
                    {{ config('app.name') }}
                </p>

                <span></span>
            </div>

        </div>

    </main>

</body>
</html>