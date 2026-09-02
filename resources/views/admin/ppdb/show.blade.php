@extends('layouts.admin')

@section('title', 'Detail Pendaftar PPDB')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin-ppdb-detail.css') }}">

<div class="ppdb-detail-page">

    {{-- KEMBALI --}}
    <a
        href="{{ route('admin.ppdb.index') }}"
        class="ppdb-back-button"
    >
        <span>&larr;</span>
        Kembali ke Daftar
    </a>


    {{-- HEADER --}}
    <div class="ppdb-detail-header">

        <div class="ppdb-detail-header-content">

            <span class="ppdb-detail-label">
                DETAIL PENDAFTAR PPDB
            </span>

            <h1>
                {{ $registration->full_name }}
            </h1>

            <p>
                Informasi lengkap calon siswa dan proses verifikasi pendaftaran.
            </p>

        </div>

    </div>


    <div class="row g-3 ppdb-detail-grid">

        {{-- =====================================================
             LEFT
             ===================================================== --}}

        <div class="col-md-7">

            {{-- DATA CALON SISWA --}}
            <div class="card border-0 shadow-sm ppdb-detail-card">

                <div class="card-body">

                    <div class="ppdb-detail-card-header">

                        <div class="ppdb-detail-card-icon">

                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>

                        </div>

                        <div>

                            <h6 class="ppdb-detail-card-title">
                                Data Calon Siswa
                            </h6>

                            <p class="ppdb-detail-card-subtitle">
                                Informasi pribadi dan data pendaftaran calon siswa.
                            </p>

                        </div>

                    </div>


                    <table class="ppdb-data-table">

                        <tr>
                            <th>No. Pendaftaran</th>

                            <td>
                                <span class="ppdb-detail-registration">
                                    {{ $registration->registration_number }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>NISN</th>
                            <td>{{ $registration->nisn ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>NIK</th>
                            <td>{{ $registration->nik ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Jenis Kelamin</th>

                            <td>
                                {{ $registration->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Tempat, Tanggal Lahir</th>

                            <td>
                                {{ $registration->birth_place }},
                                {{ $registration->birth_date->format('d M Y') }}
                            </td>
                        </tr>

                        <tr>
                            <th>Alamat</th>
                            <td>{{ $registration->address }}</td>
                        </tr>

                        <tr>
                            <th>No. HP</th>
                            <td>{{ $registration->phone }}</td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>{{ $registration->email }}</td>
                        </tr>

                        <tr>
                            <th>Orang Tua/Wali</th>

                            <td>
                                {{ $registration->parent_name }}
                                ({{ $registration->parent_phone }})
                            </td>
                        </tr>

                        <tr>
                            <th>Asal Sekolah</th>
                            <td>{{ $registration->previous_school }}</td>
                        </tr>

                    </table>

                </div>

            </div>


            {{-- DOKUMEN --}}
            <div class="card border-0 shadow-sm ppdb-detail-card">

                <div class="card-body">

                    <div class="ppdb-detail-card-header">

                        <div class="ppdb-detail-card-icon">

                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="8" y1="13" x2="16" y2="13"></line>
                                <line x1="8" y1="17" x2="16" y2="17"></line>
                            </svg>

                        </div>

                        <div>

                            <h6 class="ppdb-detail-card-title">
                                Dokumen Terlampir
                            </h6>

                            <p class="ppdb-detail-card-subtitle">
                                Dokumen yang telah diunggah oleh calon siswa.
                            </p>

                        </div>

                    </div>


                    <div class="ppdb-document-list">

                        @forelse ($registration->documents as $document)

                            <div class="ppdb-document-item">

                                <div class="ppdb-document-icon">

                                    <svg
                                        width="17"
                                        height="17"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>

                                </div>

                                <div>

                                    <a
                                        href="{{ \Illuminate\Support\Facades\Storage::url($document->file_path) }}"
                                        target="_blank"
                                        class="ppdb-document-link"
                                    >
                                        {{ $document->documentTypeLabel() }}
                                    </a>

                                    <span class="ppdb-document-name">
                                        {{ $document->original_name }}
                                    </span>

                                </div>

                            </div>

                        @empty

                            <div class="ppdb-document-empty">
                                Belum ada dokumen diunggah.
                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RIGHT
             ===================================================== --}}

        <div class="col-md-5">

            {{-- SUCCESS --}}
            @if (session('success'))

                <div class="alert ppdb-detail-alert ppdb-detail-alert-success">

                    {{ session('success') }}

                </div>

            @endif


            {{-- ERROR --}}
            @if (session('error'))

                <div class="alert ppdb-detail-alert ppdb-detail-alert-danger">

                    {{ session('error') }}

                </div>

            @endif


            {{-- STATUS & VERIFIKASI --}}
            <div class="card border-0 shadow-sm ppdb-detail-card">

                <div class="card-body">

                    <div class="ppdb-detail-card-header">

                        <div class="ppdb-detail-card-icon">

                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                <polyline points="9 12 11 14 15 10"></polyline>
                            </svg>

                        </div>

                        <div>

                            <h6 class="ppdb-detail-card-title">
                                Status & Verifikasi
                            </h6>

                            <p class="ppdb-detail-card-subtitle">
                                Kelola status proses pendaftaran calon siswa.
                            </p>

                        </div>

                    </div>


                    <div class="ppdb-current-status">

                        <span class="ppdb-current-status-label">
                            Status saat ini
                        </span>

                        <span class="ppdb-status-badge-detail">
                            {{ $registration->statusLabel() }}
                        </span>

                    </div>


                    @if ($registration->verifiedBy)

                        <div class="ppdb-verified-info">

                            Diverifikasi oleh
                            <strong>{{ $registration->verifiedBy->name }}</strong>
                            pada
                            {{ $registration->verified_at?->format('d M Y H:i') }}

                        </div>

                    @endif


                    @if (! in_array($registration->status, ['accepted', 'registered_ulang']))

                        <form
                            method="POST"
                            action="{{ route('admin.ppdb.update-status', $registration) }}"
                        >

                            @csrf
                            @method('PUT')


                            <div class="ppdb-detail-form-group">

                                <label
                                    class="ppdb-detail-form-label"
                                    for="status"
                                >
                                    Ubah Status
                                </label>

                                <select
                                    name="status"
                                    id="status"
                                    class="form-select ppdb-detail-form-control"
                                    required
                                >

                                    <option
                                        value="verified"
                                        @selected($registration->status === 'verified')
                                    >
                                        Terverifikasi
                                    </option>

                                    <option
                                        value="accepted"
                                        @selected($registration->status === 'accepted')
                                    >
                                        Diterima
                                    </option>

                                    <option
                                        value="rejected"
                                        @selected($registration->status === 'rejected')
                                    >
                                        Ditolak
                                    </option>

                                </select>


                                <small class="ppdb-detail-form-help">

                                    Kalau dipilih "Diterima", batas waktu daftar ulang akan otomatis
                                    dihitung
                                    ({{ $registration->period->re_registration_days ?? 7 }}
                                    hari dari hari ini) dan email notifikasi otomatis terkirim
                                    ke calon siswa.

                                </small>

                            </div>


                            <div class="ppdb-detail-form-group">

                                <label
                                    class="ppdb-detail-form-label"
                                    for="notes"
                                >
                                    Catatan (opsional)
                                </label>

                                <textarea
                                    name="notes"
                                    id="notes"
                                    class="form-control ppdb-detail-form-control ppdb-detail-textarea"
                                    rows="3"
                                >{{ $registration->notes }}</textarea>

                            </div>


                            <button
                                type="submit"
                                class="ppdb-save-status-button"
                            >
                                Simpan Perubahan Status
                            </button>

                        </form>

                    @elseif ($registration->notes)

                        <div class="ppdb-payment-info">

                            <strong>Catatan:</strong>
                            {{ $registration->notes }}

                        </div>

                    @endif

                </div>

            </div>


            {{-- JADWAL DAFTAR ULANG --}}
            @if ($registration->status === 'accepted' || $registration->status === 'registered_ulang')

                <div class="card border-0 shadow-sm ppdb-detail-card">

                    <div class="card-body">

                        <div class="ppdb-detail-card-header">

                            <div class="ppdb-detail-card-icon">

                                <svg
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <rect
                                        x="3"
                                        y="4"
                                        width="18"
                                        height="17"
                                        rx="2"
                                    ></rect>

                                    <line
                                        x1="16"
                                        y1="2"
                                        x2="16"
                                        y2="6"
                                    ></line>

                                    <line
                                        x1="8"
                                        y1="2"
                                        x2="8"
                                        y2="6"
                                    ></line>

                                    <line
                                        x1="3"
                                        y1="10"
                                        x2="21"
                                        y2="10"
                                    ></line>

                                </svg>

                            </div>

                            <div>

                                <h6 class="ppdb-detail-card-title">
                                    Jadwal Daftar Ulang
                                </h6>

                                <p class="ppdb-detail-card-subtitle">
                                    Informasi batas waktu daftar ulang.
                                </p>

                            </div>

                        </div>


                        <div class="ppdb-schedule-box">

                            <div class="ppdb-schedule-row">

                                <span class="ppdb-schedule-label">
                                    Diterima pada
                                </span>

                                <strong class="ppdb-schedule-value">
                                    {{ $registration->accepted_at?->format('d M Y') }}
                                </strong>

                            </div>


                            <div class="ppdb-schedule-row">

                                <span class="ppdb-schedule-label">
                                    Batas waktu daftar ulang
                                </span>

                                <strong class="ppdb-schedule-value">
                                    {{ $registration->reRegistrationDeadlineLabel() }}
                                </strong>

                            </div>

                        </div>


                        @if ($registration->isReRegistrationOverdue())

                            <div class="alert ppdb-detail-alert ppdb-detail-alert-warning mt-3 mb-0">

                                &#9888;
                                Sudah melewati batas waktu daftar ulang, tapi konfirmasi tetap
                                bisa dilakukan kalau sekolah memutuskan memberi kelonggaran.

                            </div>

                        @endif

                    </div>

                </div>


                {{-- DAFTAR ULANG & PEMBAYARAN --}}
                <div class="card border-0 shadow-sm ppdb-detail-card">

                    <div class="card-body">

                        <div class="ppdb-detail-card-header">

                            <div class="ppdb-detail-card-icon">

                                <svg
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <rect
                                        x="3"
                                        y="5"
                                        width="18"
                                        height="14"
                                        rx="2"
                                    ></rect>

                                    <line
                                        x1="3"
                                        y1="10"
                                        x2="21"
                                        y2="10"
                                    ></line>

                                </svg>

                            </div>

                            <div>

                                <h6 class="ppdb-detail-card-title">
                                    Daftar Ulang & Pembayaran
                                </h6>

                                <p class="ppdb-detail-card-subtitle">
                                    Konfirmasi pembayaran yang telah diterima sekolah.
                                </p>

                            </div>

                        </div>


                        <div class="ppdb-payment-info">

                            Pembayaran dilakukan siswa secara
                            <strong>offline langsung ke sekolah</strong>.

                            Form ini hanya untuk mencatat/mengonfirmasi
                            bukti pembayaran yang sudah diterima.

                        </div>


                        @if ($registration->status === 'registered_ulang')

                            <div class="ppdb-confirmed-box">

                                <strong>
                                    Daftar ulang sudah dikonfirmasi.
                                </strong>

                                <br>

                                No. Bukti:
                                {{ $registration->re_registration_reference }}

                                <br>

                                @if ($registration->re_registration_notes)

                                    Catatan:
                                    {{ $registration->re_registration_notes }}

                                    <br>

                                @endif

                                Dikonfirmasi oleh
                                {{ $registration->reRegistrationConfirmedBy?->name }}

                                pada
                                {{ $registration->re_registration_confirmed_at?->format('d M Y H:i') }}

                            </div>

                            <div class="ppdb-confirmed-box mt-2">

                                <strong>Akun & Penempatan Kelas</strong>

                                <br>

                                @if ($registration->user)

                                    Email login:
                                    {{ $registration->user->email }}

                                    <br>

                                    @php($classroom = $registration->user->currentClassroom())

                                    @if ($classroom)
                                        Kelas: <strong>{{ $classroom->name }}</strong>
                                    @else
                                        <span class="text-warning">
                                            Belum ditempatkan ke kelas manapun (kelas X penuh saat konfirmasi) —
                                            silakan tempatkan manual lewat menu
                                            <a href="{{ route('admin.student-placements.index') }}">Penempatan Siswa</a>.
                                        </span>
                                    @endif

                                @else
                                    <span class="text-danger">
                                        Akun siswa belum berhasil dibuat. Coba periksa log aplikasi.
                                    </span>
                                @endif

                            </div>

                        @else

                            <form
                                method="POST"
                                action="{{ route('admin.ppdb.confirm-re-registration', $registration) }}"
                            >

                                @csrf
                                @method('PUT')


                                <div class="ppdb-detail-form-group">

                                    <label
                                        class="ppdb-detail-form-label"
                                        for="re_registration_reference"
                                    >
                                        No. Bukti Pembayaran / Kwitansi
                                    </label>

                                    <input
                                        type="text"
                                        name="re_registration_reference"
                                        id="re_registration_reference"
                                        class="form-control ppdb-detail-form-control"
                                        placeholder="Contoh: KW-2026-0142"
                                        required
                                    >

                                </div>


                                <div class="ppdb-detail-form-group">

                                    <label
                                        class="ppdb-detail-form-label"
                                        for="re_registration_notes"
                                    >
                                        Catatan (opsional)
                                    </label>

                                    <textarea
                                        name="re_registration_notes"
                                        id="re_registration_notes"
                                        class="form-control ppdb-detail-form-control ppdb-detail-textarea"
                                        rows="2"
                                    ></textarea>

                                </div>


                                <button
                                    type="submit"
                                    class="ppdb-confirm-button"
                                >
                                    Konfirmasi Daftar Ulang & Pembayaran
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection