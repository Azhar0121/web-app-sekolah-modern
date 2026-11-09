@extends('layouts.admin')

@section('title', 'Kelola PPDB')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin-ppdb.css') }}">

<div class="ppdb-page">

    {{-- HEADER --}}
    <div class="ppdb-header">


    <div class="ppdb-header-content">

        <span class="ppdb-label">
            ADMINISTRATOR
        </span>

        <h1>
            Kelola Pendaftar PPDB
        </h1>

        <p>
            Kelola, pantau, dan verifikasi data pendaftar
            PPDB sekolah melalui halaman administrasi.
        </p>

    </div>

    <div class="ppdb-header-info">

        <div class="ppdb-header-icon">

            <svg
                width="30"
                height="30"
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
                <line x1="8" y1="17" x2="14" y2="17"></line>
                <line x1="8" y1="9" x2="9" y2="9"></line>
            </svg>

        </div>

        <div class="ppdb-header-info-text">
            <strong>PPDB Online</strong>
            <span>Penerimaan Peserta Didik Baru</span>
        </div>

    </div>


    </div>

    {{-- ALERT SUKSES --}}
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; border-radius: 8px; padding: 12px 18px; font-weight: 500;">
            <i class="bi bi-check-circle-fill fs-5" style="color: #10b981;"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- KARTU STATUS & PENGATURAN PERIODE PPDB AKTIF --}}
    <div class="ppdb-period-card">
        <div class="ppdb-period-left">
            <div class="ppdb-period-icon-wrap {{ $activePeriod && $activePeriod->isOpenForRegistration() ? 'is-open' : 'is-closed' }}">
                <i class="bi {{ $activePeriod && $activePeriod->isOpenForRegistration() ? 'bi-calendar-check-fill' : 'bi-calendar-x-fill' }}"></i>
            </div>
            <div>
                <div class="ppdb-period-title-group">
                    <h2 class="ppdb-period-title">
                        {{ $activePeriod ? $activePeriod->name : 'Belum Ada Periode PPDB' }}
                    </h2>
                    @if ($activePeriod && $activePeriod->isOpenForRegistration())
                        <span class="badge-status-open">
                            <span class="dot-pulse"></span> Pendaftaran Dibuka
                        </span>
                    @else
                        <span class="badge-status-closed">
                            <span class="dot-closed"></span> Pendaftaran Ditutup
                        </span>
                    @endif
                </div>
                <div class="ppdb-period-meta">
                    @if ($activePeriod)
                        <span><i class="bi bi-calendar3"></i> {{ $activePeriod->start_date?->format('d M Y') }} s/d {{ $activePeriod->end_date?->format('d M Y') }}</span>
                        <span>•</span>
                        <span><i class="bi bi-people-fill"></i> Kuota: <strong>{{ $activePeriod->registrations()->count() }} / {{ $activePeriod->quota ?? '∞' }}</strong> Pendaftar</span>
                        <span>•</span>
                        <span><i class="bi bi-clock-history"></i> Batas Daftar Ulang: <strong>{{ $activePeriod->re_registration_days ?? 7 }} Hari</strong></span>
                    @else
                        <span class="text-muted">Silakan atur periode baru agar pendaftaran publik dapat dibuka.</span>
                    @endif
                </div>
            </div>
        </div>

        @if(auth()->user()->hasRole('super-admin') || auth()->user()->hasPermission('ppdb.manage'))
        <div class="ppdb-period-actions">
            <button type="button" class="btn-config-period" data-bs-toggle="modal" data-bs-target="#editPeriodModal">
                <i class="bi bi-gear-fill"></i>
                <span>Atur Periode PPDB</span>
            </button>
        </div>
        @endif
    </div>

    {{-- FILTER --}}
    <div class="ppdb-filter-card">

        <div class="ppdb-filter-info">

            <div class="ppdb-filter-icon">

                <svg
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M4 6h16"></path>
                    <path d="M7 12h10"></path>
                    <path d="M10 18h4"></path>
                </svg>

            </div>

            <div>

                <div class="ppdb-filter-title">
                    Filter Status Pendaftar
                </div>

                <div class="ppdb-filter-description">
                    Tampilkan pendaftar berdasarkan status proses PPDB.
                </div>

            </div>

        </div>


        <div class="ppdb-filter-form">

            <form
                method="GET"
                action="{{ route('admin.ppdb.index') }}"
            >

                <select
                    name="status"
                    class="ppdb-status-select"
                    onchange="this.form.submit()"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="submitted"
                        @selected($statusFilter === 'submitted')
                    >
                        Menunggu Verifikasi
                    </option>

                    <option
                        value="verified"
                        @selected($statusFilter === 'verified')
                    >
                        Terverifikasi
                    </option>

                    <option
                        value="accepted"
                        @selected($statusFilter === 'accepted')
                    >
                        Diterima — Menunggu Daftar Ulang
                    </option>

                    <option
                        value="registered_ulang"
                        @selected($statusFilter === 'registered_ulang')
                    >
                        Daftar Ulang Selesai
                    </option>

                    <option
                        value="rejected"
                        @selected($statusFilter === 'rejected')
                    >
                        Ditolak
                    </option>

                </select>

            </form>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="ppdb-table-card">

        <div class="ppdb-table-wrapper">

            <table class="ppdb-table">

                <thead>

                    <tr>

                        <th>
                            No. Pendaftaran
                        </th>

                        <th>
                            Nama
                        </th>

                        <th>
                            Jurusan
                        </th>

                        <th>
                            Asal Sekolah
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Tanggal Daftar
                        </th>

                        <th class="ppdb-action-column">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($registrations as $registration)

                        <tr>

                            <td>

                                <code class="ppdb-registration-code">
                                    {{ $registration->registration_number }}
                                </code>

                            </td>


                            <td>

                                <span class="ppdb-name">
                                    {{ $registration->full_name }}
                                </span>

                            </td>


                            <td>
                                @if ($registration->accepted_major)
                                    <span class="badge bg-success text-white px-2 py-1 d-inline-block text-truncate" style="max-width: 220px;" title="{{ $registration->accepted_major }}">
                                        &#10003; {{ $registration->accepted_major }}
                                    </span>
                                    <small class="text-success d-block fw-semibold" style="font-size: 0.72rem;">
                                        ({{ $registration->acceptedMajorChoiceLabel() ?? 'Diterima' }})
                                    </small>
                                @else
                                    <span class="badge bg-light text-dark border px-2 py-1 d-inline-block text-truncate" style="max-width: 220px;" title="{{ $registration->first_major }}">
                                        {{ $registration->first_major ?: '-' }}
                                    </span>
                                    @if ($registration->second_major)
                                        <small class="text-muted d-block text-truncate" style="font-size: 0.72rem; max-width: 220px;" title="Pilihan 2: {{ $registration->second_major }}">
                                            P2: {{ $registration->second_major }}
                                        </small>
                                    @endif
                                @endif
                            </td>


                            <td>

                                <span class="ppdb-school">
                                    {{ $registration->previous_school }}
                                </span>

                            </td>


                            <td>

                                <span class="ppdb-status-badge">
                                    {{ $registration->statusLabel() }}
                                </span>

                            </td>


                            <td>

                                <span class="ppdb-date">
                                    {{ $registration->created_at->format('d M Y') }}
                                </span>

                            </td>


                            <td class="ppdb-action-column">

                                <div class="ppdb-action">

                                    <a
                                        href="{{ route('admin.ppdb.show', $registration) }}"
                                        class="ppdb-detail-button"
                                    >
                                        Detail
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="ppdb-empty"
                            >

                                <div class="ppdb-empty-icon">

                                    <svg
                                        width="21"
                                        height="21"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>

                                </div>

                                Belum ada pendaftar.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if ($registrations->hasPages())

            <div class="ppdb-pagination">

                {{ $registrations->links() }}

            </div>

        @endif

    </div>

</div>

{{-- MODAL PENGATURAN PERIODE PPDB --}}
@if(auth()->user()->hasRole('super-admin') || auth()->user()->hasPermission('ppdb.manage'))
<div class="modal fade" id="editPeriodModal" tabindex="-1" aria-labelledby="editPeriodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #071b35; color: #fff; border-radius: 12px 12px 0 0; padding: 16px 22px;">
                <h5 class="modal-title fw-bold fs-6" id="editPeriodModalLabel">
                    <i class="bi bi-calendar-event me-2 text-warning"></i> Atur Periode &amp; Jadwal PPDB
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.ppdb.period.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="period_id" value="{{ $activePeriod?->id }}">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Nama Gelombang / Periode <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $activePeriod?->name ?? 'Gelombang 1 Tahun Ajaran ' . date('Y') . '/' . (date('Y') + 1)) }}" required placeholder="Contoh: Gelombang 1 Tahun Ajaran 2026/2027">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-dark small">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $activePeriod?->start_date?->format('Y-m-d') ?? date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-dark small">Tanggal Selesai (Penutupan) <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $activePeriod?->end_date?->format('Y-m-d') ?? date('Y-12-31')) }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-dark small">Kuota Pendaftar</label>
                            <input type="number" name="quota" min="1" class="form-control" value="{{ old('quota', $activePeriod?->quota ?? 100) }}" placeholder="Contoh: 100">
                            <div class="form-text" style="font-size: 11px;">Kosongkan jika kuota tidak dibatasi.</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-dark small">Batas Waktu Daftar Ulang (Hari) <span class="text-danger">*</span></label>
                            <input type="number" name="re_registration_days" min="1" max="60" class="form-control" value="{{ old('re_registration_days', $activePeriod?->re_registration_days ?? 7) }}" required>
                            <div class="form-text" style="font-size: 11px;">Maksimal hari setelah siswa diterima.</div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark small">Status Periode</label>
                        <select name="is_active" class="form-select">
                            <option value="1" @selected(old('is_active', $activePeriod?->is_active ?? true))>Aktif (Pendaftaran Buka Sesuai Jadwal)</option>
                            <option value="0" @selected(!old('is_active', $activePeriod?->is_active ?? true))>Nonaktif (Tutup Pendaftaran Manual)</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer bg-light" style="border-radius: 0 0 12px 12px; padding: 14px 22px;">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold" style="background: #1769d5; border-color: #1769d5;">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection