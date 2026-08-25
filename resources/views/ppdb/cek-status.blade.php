<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Cek Status PPDB - {{ config('app.name') }}
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/ppdb-status.css') }}"
    >
</head>

<body>

    <div class="status-page">

        {{-- HEADER --}}
        <header class="status-header">

            <a
                href="{{ route('ppdb.index') }}"
                class="back-button"
            >
                <span>&larr;</span>
                Kembali ke PPDB
            </a>

            <div class="header-badge">
                PPDB ONLINE
            </div>

        </header>


        <main class="status-container">

            {{-- HERO --}}
            <section class="status-hero">

                <div class="hero-content">

                    <span class="hero-label">
                        INFORMASI PENDAFTARAN
                    </span>

                    <h1>
                        Cek Status Pendaftaran
                    </h1>

                    <p>
                        Masukkan nomor pendaftaran Anda untuk
                        melihat status dan informasi pendaftaran
                        PPDB secara cepat.
                    </p>

                </div>

                <div class="hero-icon">

                    <svg
                        width="42"
                        height="42"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle
                            cx="11"
                            cy="11"
                            r="7"
                        ></circle>

                        <path d="m20 20-4-4"></path>
                    </svg>

                </div>

            </section>


            {{-- FORM --}}
            <section class="status-form-card">

                <div class="section-heading">

                    <div class="heading-icon">

                        <svg
                            width="22"
                            height="22"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M4 7h16"></path>
                            <path d="M4 12h16"></path>
                            <path d="M4 17h10"></path>
                        </svg>

                    </div>

                    <div>
                        <span>
                            NOMOR PENDAFTARAN
                        </span>

                        <h2>
                            Masukkan Nomor Pendaftaran
                        </h2>
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route('ppdb.cek-status') }}"
                >

                    @csrf

                    <div class="form-group">

                        <label for="registration_number">
                            Nomor Pendaftaran
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon">
                                #
                            </span>

                            <input
                                type="text"
                                name="registration_number"
                                id="registration_number"
                                placeholder="PPDB-2026-00001"
                                value="{{ old('registration_number', $registration->registration_number ?? '') }}"
                                required
                            >

                        </div>

                        <small>
                            Contoh: PPDB-2026-00001
                        </small>

                    </div>


                    <button
                        type="submit"
                        class="check-button"
                    >
                        <span>Cek Status Pendaftaran</span>

                        <span class="arrow">
                            &rarr;
                        </span>
                    </button>

                </form>

            </section>


            {{-- HASIL --}}
            @isset($registration)

                <section class="result-card">

                    @if ($registration)

                        <div class="result-header">

                            <div class="result-success-icon">
                                ✓
                            </div>

                            <div>

                                <span class="result-label">
                                    HASIL PENCARIAN
                                </span>

                                <h2>
                                    Data Pendaftaran Ditemukan
                                </h2>

                            </div>

                        </div>


                        <div class="result-data">

                            <div class="data-item">

                                <span class="data-label">
                                    Nama Lengkap
                                </span>

                                <strong>
                                    {{ $registration->full_name }}
                                </strong>

                            </div>


                            <div class="data-item">

                                <span class="data-label">
                                    Status Pendaftaran
                                </span>

                                <strong class="registration-status">
                                    {{ $registration->statusLabel() }}
                                </strong>

                            </div>


                            @if ($registration->notes)

                                <div class="data-item full">

                                    <span class="data-label">
                                        Catatan
                                    </span>

                                    <div class="notes">
                                        {{ $registration->notes }}
                                    </div>

                                </div>

                            @endif


                            <div class="data-item">

                                <span class="data-label">
                                    Tanggal Pendaftaran
                                </span>

                                <strong>
                                    {{ $registration->created_at->format('d M Y H:i') }}
                                </strong>

                            </div>

                        </div>

                    @else

                        <div class="not-found">

                            <div class="not-found-icon">
                                !
                            </div>

                            <div>

                                <h2>
                                    Data Tidak Ditemukan
                                </h2>

                                <p>
                                    Nomor pendaftaran tidak ditemukan.
                                    Periksa kembali penulisannya dan
                                    pastikan nomor yang dimasukkan sudah benar.
                                </p>

                            </div>

                        </div>

                    @endif

                </section>

            @endisset


            {{-- FOOTER --}}
            <footer class="status-footer">

                <span>
                    PPDB Online
                </span>

                <span class="footer-line"></span>

                <span>
                    {{ config('app.name') }}
                </span>

            </footer>

        </main>

    </div>

</body>
</html>