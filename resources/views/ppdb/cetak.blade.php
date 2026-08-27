<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bukti Pendaftaran {{ $registration->registration_number }} - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ppdb-print.css') }}">
</head>

<body>

    <div class="print-page">

        {{-- TOOLBAR (tidak ikut tercetak) --}}
        <div class="print-toolbar no-print">

            <a href="{{ route('ppdb.cek-status.form') }}?registration_number={{ $registration->registration_number }}"
               class="toolbar-back">
                <span>&larr;</span>
                Kembali ke Status
            </a>

            <button type="button" class="toolbar-print" onclick="window.print()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak Bukti Pendaftaran</span>
            </button>

        </div>


        {{-- DOKUMEN --}}
        <div class="print-sheet">

            <div class="kop-surat">
                <div class="kop-logo">
                    {{ strtoupper(substr(config('app.name'), 0, 1)) }}
                </div>

                <div class="kop-text">
                    <h1>{{ config('app.name') }}</h1>
                    <p>Sistem Penerimaan Peserta Didik Baru (PPDB) Online</p>
                </div>
            </div>

            <div class="doc-title">
                <h2>Bukti Pendaftaran Peserta Didik Baru</h2>
                <span>{{ $registration->period->name ?? '-' }}</span>
            </div>

            <div class="reg-summary">
                <div>
                    <span>Nomor Pendaftaran</span>
                    <strong>{{ $registration->registration_number }}</strong>
                </div>

                <div>
                    <span>Status Saat Ini</span>
                    <strong>{{ $registration->statusLabel() }}</strong>
                </div>

                <div>
                    <span>Tanggal Daftar</span>
                    <strong>{{ $registration->created_at->format('d M Y, H:i') }} WIB</strong>
                </div>
            </div>

            {{-- DATA CALON SISWA --}}
            <div class="data-section">
                <h3>Data Calon Siswa</h3>

                <table class="data-table">
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->full_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">NISN</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->nisn ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">NIK</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->nik ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kelamin</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tempat, Tanggal Lahir</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->birth_place }}, {{ $registration->birth_date->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->address }}</td>
                    </tr>
                    <tr>
                        <td class="label">No. HP</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->phone }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->email }}</td>
                    </tr>
                </table>
            </div>

            {{-- DATA ORANG TUA --}}
            <div class="data-section">
                <h3>Data Orang Tua / Wali</h3>

                <table class="data-table">
                    <tr>
                        <td class="label">Nama Orang Tua/Wali</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->parent_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">No. HP Orang Tua/Wali</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->parent_phone }}</td>
                    </tr>
                </table>
            </div>

            {{-- ASAL SEKOLAH --}}
            <div class="data-section">
                <h3>Asal Sekolah</h3>

                <table class="data-table">
                    <tr>
                        <td class="label">Sekolah Asal (SMP)</td>
                        <td class="sep">:</td>
                        <td class="value">{{ $registration->previous_school }}</td>
                    </tr>
                </table>
            </div>

            {{-- DOKUMEN --}}
            @if ($registration->documents->isNotEmpty())
                <div class="data-section">
                    <h3>Dokumen Terlampir</h3>

                    <table class="data-table">
                        @foreach ($registration->documents as $document)
                            <tr>
                                <td class="label">{{ $document->documentTypeLabel() }}</td>
                                <td class="sep">:</td>
                                <td class="value">{{ $document->original_name }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif

            @if ($registration->status === 'accepted' && $registration->re_registration_deadline)
                <div class="data-section">
                    <h3>Instruksi Daftar Ulang</h3>
                    <table class="data-table">
                        <tr>
                            <td class="label">Batas Waktu Daftar Ulang</td>
                            <td class="sep">:</td>
                            <td class="value">{{ $registration->reRegistrationDeadlineLabel() }}</td>
                        </tr>
                        <tr>
                            <td class="label">Cara Daftar Ulang</td>
                            <td class="sep">:</td>
                            <td class="value">
                                Datang langsung ke sekolah membawa dokumen asli & lakukan
                                pembayaran daftar ulang secara offline sebelum batas waktu
                                di atas. Panitia akan mencatat bukti pembayaran Anda di sistem.
                            </td>
                        </tr>
                    </table>
                </div>
            @endif

            {{-- TANDA TANGAN --}}
            <div class="signature-area">
                <div class="signature-box">
                    <p class="role">Calon Siswa / Orang Tua</p>
                    <p class="name">{{ $registration->full_name }}</p>
                </div>

                <div class="signature-box">
                    <p class="role">Panitia PPDB</p>
                    <p class="name">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
                </div>
            </div>

            <div class="doc-footer">
                Dokumen ini dicetak otomatis dari Sistem PPDB Online {{ config('app.name') }}
                pada {{ now()->translatedFormat('d F Y, H:i') }} WIB dan sah sebagai bukti
                pendaftaran selama nomor pendaftaran dapat diverifikasi melalui menu
                "Cek Status Pendaftaran" pada website resmi sekolah.
            </div>

        </div>

    </div>

</body>

</html>
