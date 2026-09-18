<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Bukti Pendaftaran {{ $registration->registration_number }} - Sekolah Modern
    </title>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ppdb-print.css') }}">
</head>

<body>

    <div class="print-page">

        <div class="print-sheet">

            {{-- KOP SURAT --}}
            <div class="kop-surat">

                <div class="kop-logo">
                    <svg
                        viewBox="0 0 64 64"
                        xmlns="http://www.w3.org/2000/svg"
                        aria-label="Logo Sekolah"
                    >
                        <path
                            d="M32 4
                               L55 12
                               V29
                               C55 44 45 54 32 60
                               C19 54 9 44 9 29
                               V12
                               Z"
                            fill="#ffffff"
                        />

                        <path
                            d="M32 10
                               L49 16
                               V29
                               C49 40 42 48 32 53
                               C22 48 15 40 15 29
                               V16
                               Z"
                            fill="#0f2747"
                        />

                        <path
                            d="M32 15
                               L34.5 21
                               L41 21
                               L36 25
                               L38 31
                               L32 27
                               L26 31
                               L28 25
                               L23 21
                               L29.5 21
                               Z"
                            fill="#ffffff"
                        />

                        <path
                            d="M19 34
                               C23 32 27 33 32 36
                               C37 33 41 32 45 34
                               V44
                               C41 42 37 42 32 45
                               C27 42 23 42 19 44
                               Z"
                            fill="#ffffff"
                        />

                        <path
                            d="M32 36 V45"
                            stroke="#0f2747"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <div class="kop-text">
                    <h1>SEKOLAH MODERN</h1>

                    <p>
                        Sistem Penerimaan Peserta Didik Baru (PPDB) Online
                    </p>
                </div>

            </div>


            {{-- JUDUL DOKUMEN --}}
            <div class="doc-title">

                <h2>
                    Bukti Pendaftaran Peserta Didik Baru
                </h2>

                <span>
                    {{ $registration->period->name ?? '-' }}
                </span>

            </div>


            {{-- RINGKASAN PENDAFTARAN --}}
            <div class="reg-summary">

                <div>
                    <span>Nomor Pendaftaran</span>

                    <strong>
                        {{ $registration->registration_number }}
                    </strong>
                </div>

                <div>
                    <span>Status Saat Ini</span>

                    <strong>
                        {{ $registration->statusLabel() }}
                    </strong>
                </div>

                <div>
                    <span>Tanggal Daftar</span>

                    <strong>
                        {{ $registration->created_at->format('d M Y, H:i') }} WIB
                    </strong>
                </div>

            </div>


            {{-- DATA CALON SISWA --}}
            <div class="data-section">

                <h3>
                    Data Calon Siswa
                </h3>

                <table class="data-table">

                    <tr>
                        <td class="label">
                            Nama Lengkap
                        </td>

                        <td class="sep">
                            :
                        </td>

                        <td class="value">
                            {{ $registration->full_name }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            NISN
                        </td>

                        <td class="sep">
                            :
                        </td>

                        <td class="value">
                            {{ $registration->nisn ?: '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            NIK
                        </td>

                        <td class="sep">
                            :
                        </td>

                        <td class="value">
                            {{ $registration->nik ?: '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            Jenis Kelamin
                        </td>

                        <td class="sep">
                            :
                        </td>

                        <td class="value">
                            {{ $registration->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            Tempat, Tanggal Lahir
                        </td>

                        <td class="sep">
                            :
                        </td>

                        <td class="value">
                            {{ $registration->birth_place }},
                            {{ $registration->birth_date->translatedFormat('d F Y') }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            Alamat
                        </td>

                        <td class="sep">
                            :
                        </td>

                        <td class="value">
                            {{ $registration->address }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            No. HP
                        </td>

                        <td class="sep">
                            :
                        </td>

                        <td class="value">
                            {{ $registration->phone }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            Email
                        </td>

                        <td class="sep">
                            :
                        </td>

                        <td class="value">
                            {{ $registration->email }}
                        </td>
                    </tr>

                </table>

            </div>


            {{-- DATA ORANG TUA --}}
            <div class="data-section">

                <h3>
                    Data Orang Tua / Wali
                </h3>

                <table class="data-table">

                    <tr>
                        <td class="label">
                            Nama Orang Tua/Wali
                        </td>

                        <td class="sep">
                            :
                        </td>

                        <td class="value">
                            {{ $registration->parent_name }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            No. HP Orang Tua/Wali
                        </td>

                        <td class="sep">
                            :
                        </td>

                        <td class="value">
                            {{ $registration->parent_phone }}
                        </td>
                    </tr>

                </table>

            </div>
            {{-- PILIHAN JURUSAN & ASAL SEKOLAH --}}
            <div class="data-section">
                <h3>Pilihan Jurusan & Asal Sekolah</h3>

                <table class="data-table">

                    <tr>
                        <td class="label">Pilihan Jurusan 1</td>
                        <td class="sep">:</td>
                        <td class="value"><strong>{{ $registration->first_major ?: '-' }}</strong></td>
                    </tr>

                    @if ($registration->second_major)
                        <tr>
                            <td class="label">Pilihan Jurusan 2</td>
                            <td class="sep">:</td>
                            <td class="value">{{ $registration->second_major }}</td>
                        </tr>
                    @endif

                    <tr>
                        <td class="label">Sekolah Asal (SMP)</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->previous_school }}</td>
                    </tr>

                </table>

            </div>


            {{-- DOKUMEN TERLAMPIR --}}
            @if ($registration->documents->isNotEmpty())

                <div class="data-section">

                    <h3>
                        Dokumen Terlampir
                    </h3>

                    <table class="data-table">

                        @foreach ($registration->documents as $document)

                            <tr>
                                <td class="label">
                                    {{ $document->documentTypeLabel() }}
                                </td>

                                <td class="sep">
                                    :
                                </td>

                                <td class="value">
                                    {{ $document->original_name }}
                                </td>
                            </tr>

                        @endforeach

                    </table>

                </div>

            @endif


            {{-- INSTRUKSI DAFTAR ULANG --}}
            @if (
                $registration->status === 'accepted' &&
                $registration->re_registration_deadline
            )

                <div class="data-section">

                    <h3>
                        Instruksi Daftar Ulang
                    </h3>

                    <table class="data-table">

                        <tr>
                            <td class="label">
                                Batas Waktu Daftar Ulang
                            </td>

                            <td class="sep">
                                :
                            </td>

                            <td class="value">
                                {{ $registration->reRegistrationDeadlineLabel() }}
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                Cara Daftar Ulang
                            </td>

                            <td class="sep">
                                :
                            </td>

                            <td class="value">
                                Datang langsung ke sekolah membawa dokumen asli
                                & lakukan pembayaran daftar ulang secara offline
                                sebelum batas waktu di atas. Panitia akan mencatat
                                bukti pembayaran Anda di sistem.
                            </td>
                        </tr>

                    </table>

                </div>

            @endif


            {{-- TANDA TANGAN --}}
            <div class="signature-area">

                <div class="signature-box">

                    <p class="role">
                        Calon Siswa / Orang Tua
                    </p>

                    <p class="name">
                        {{ $registration->full_name }}
                    </p>

                </div>


                <div class="signature-box">

                    <p class="role">
                        Panitia PPDB
                    </p>

                    <p class="name">
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </p>

                </div>

            </div>


            {{-- FOOTER DOKUMEN --}}
            <div class="doc-footer">

                Dokumen ini dicetak otomatis dari Sistem PPDB Online
                Sekolah Modern pada
                {{ now()->translatedFormat('d F Y, H:i') }} WIB
                dan sah sebagai bukti pendaftaran selama nomor pendaftaran
                dapat diverifikasi melalui menu
                "Cek Status Pendaftaran" pada website resmi sekolah.

            </div>


            {{-- TOMBOL --}}
            <div class="print-toolbar no-print">

                <a
                    href="{{ route('ppdb.cek-status.form') }}?registration_number={{ $registration->registration_number }}"
                    class="toolbar-back"
                >
                    <span>&larr;</span>

                    <span>
                        Kembali ke Status
                    </span>
                </a>


                <button
                    type="button"
                    class="toolbar-print"
                    onclick="window.print()"
                >

                    <svg
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>

                        <path
                            d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"
                        ></path>

                        <rect
                            x="6"
                            y="14"
                            width="12"
                            height="8"
                        ></rect>
                    </svg>

                    <span>
                        Cetak Bukti Pendaftaran
                    </span>

                </button>

            </div>

        </div>

    </div>

</body>
</html>