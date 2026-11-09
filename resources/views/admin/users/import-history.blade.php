@extends('layouts.admin')

@section('title', 'Riwayat & Arsip Import Akun')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin-user.css') }}">

<style>
    .btn-action-slip {
        background-color: #047857 !important;
        border-color: #047857 !important;
        color: #ffffff !important;
        text-decoration: none !important;
        transition: background-color 0.15s ease, transform 0.15s ease;
    }
    .btn-action-slip:hover {
        background-color: #065f46 !important;
        border-color: #065f46 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }
    .btn-action-rekap {
        background-color: #0284c7 !important;
        border-color: #0284c7 !important;
        color: #ffffff !important;
        text-decoration: none !important;
        transition: background-color 0.15s ease, transform 0.15s ease;
    }
    .btn-action-rekap:hover {
        background-color: #0369a1 !important;
        border-color: #0369a1 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }
</style>

<div class="users-page">

    <div class="users-header">

        <div class="users-header-content">
            <span class="users-label">
                MANAJEMEN PENGGUNA
            </span>

            <h1>
                Riwayat &amp; Arsip Import Akun
            </h1>

            <p>
                Daftar sesi import massal akun Guru &amp; TU. Setiap sesi tercatat secara terpisah sehingga data lama tidak tercampur dengan akun baru.
            </p>
        </div>

        <div class="users-header-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-import-users" style="background: #334155; text-decoration: none;">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Kelola User</span>
            </a>

            <button type="button" class="btn-import-users" data-bs-toggle="modal" data-bs-target="#importUserModal">
                <i class="bi bi-file-earmark-arrow-up-fill"></i>
                <span>Import File Baru</span>
            </button>
        </div>

    </div>

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; border-radius: 8px; padding: 12px 18px; font-weight: 500;">
            <i class="bi bi-check-circle-fill fs-5" style="color: #10b981;"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 8px; padding: 12px 18px; font-weight: 500;">
            <i class="bi bi-exclamation-triangle-fill fs-5" style="color: #ef4444;"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-white rounded-2 border" style="border-color: #e2e8f0 !important; min-height: 38px;">
            <div style="width: 26px; height: 26px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                <i class="bi bi-collection-fill"></i>
            </div>
            <span class="text-muted" style="font-size: 12px;">Total Sesi Import:</span>
            <strong class="text-dark" style="font-size: 12.5px;">{{ $totalBatches }} Sesi</strong>
        </div>

        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-white rounded-2 border" style="border-color: #e2e8f0 !important; min-height: 38px;">
            <div style="width: 26px; height: 26px; border-radius: 6px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                <i class="bi bi-people-fill"></i>
            </div>
            <span class="text-muted" style="font-size: 12px;">Total Akun Dibuat:</span>
            <strong class="text-dark" style="font-size: 12.5px;">{{ $totalAccounts }} Akun</strong>
        </div>
    </div>

    <div class="users-table-card">

        <div class="users-table-header">
            <div>
                <h2>Daftar Arsip Sesi Import</h2>
                <p>Klik tombol Cetak Slip atau Unduh Rekap pada baris sesi tertentu untuk mengakses kartu slip akun batch tersebut.</p>
            </div>
            <div class="user-total">
                {{ $batches->total() }} Sesi Tercatat
            </div>
        </div>

        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Kode Sesi &amp; Waktu</th>
                        <th>File Sumber</th>
                        <th>Jumlah Akun</th>
                        <th>Petugas</th>
                        <th style="text-align: right; width: 340px;">Aksi Arsip</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($batches as $index => $batch)
                        <tr>
                            <td>{{ $batches->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                    <span class="badge {{ $loop->first ? 'bg-success' : 'bg-secondary' }}" style="font-size: 10.5px; font-weight: 600;">
                                        {{ $loop->first ? 'TERBARU' : 'ARSIP' }}
                                    </span>
                                    <span>{{ $batch->batch_code }}</span>
                                </div>
                                <div class="text-muted small mt-1">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $batch->created_at->format('d M Y, H:i') }} WIB
                                </div>
                            </td>
                            <td>
                                <span class="d-inline-flex align-items-center gap-1 text-muted" style="font-family: monospace; font-size: 12.5px;">
                                    <i class="bi bi-filetype-csv text-success fs-6"></i>
                                    {{ $batch->file_name ?: 'import-guru-tu.csv' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 12px; font-weight: 600;">
                                    <i class="bi bi-person-check-fill me-1"></i> {{ $batch->total_accounts }} Akun
                                </span>
                            </td>
                            <td>
                                <span class="small text-muted">
                                    {{ $batch->creator?->name ?? 'Administrator' }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="d-inline-flex align-items-center gap-2">
                                    <a href="{{ route('admin.users.import.print-slips', $batch) }}" target="_blank" class="btn btn-sm btn-action-slip fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 12px; padding: 6px 12px;" title="Cetak slip kartu akun sesi ini">
                                        <i class="bi bi-printer-fill" style="color: #ffffff;"></i> <span style="color: #ffffff;">Cetak Slip</span>
                                    </a>

                                    <a href="{{ route('admin.users.import.export-rekap', $batch) }}" class="btn btn-sm btn-action-rekap fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 12px; padding: 6px 12px;" title="Unduh rekap CSV sesi ini">
                                        <i class="bi bi-file-earmark-excel-fill" style="color: #ffffff;"></i> <span style="color: #ffffff;">CSV</span>
                                    </a>

                                    <form action="{{ route('admin.users.import.batches.destroy', $batch) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus arsip sesi import {{ $batch->batch_code }}? Catatan: Akun user yang sudah dibuat tetap ada di sistem, hanya riwayat kartu cetak &amp; password sementara sesi ini yang dibersihkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" style="font-size: 12px; padding: 6px 10px;" title="Hapus arsip sesi ini">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="users-empty">
                                Belum ada riwayat sesi import.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($batches->hasPages())
            <div class="users-pagination">
                {{ $batches->links() }}
            </div>
        @endif

    </div>

</div>

<div class="modal fade" id="importUserModal" tabindex="-1" aria-labelledby="importUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #071b35; color: #fff; border-radius: 12px 12px 0 0; padding: 16px 22px;">
                <h5 class="modal-title fw-bold fs-6" id="importUserModalLabel">
                    <i class="bi bi-cloud-arrow-up-fill me-2 text-primary"></i> Import Akun Guru &amp; TU Massal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Unggah file CSV berisi data nama, email, dan role (guru atau tu). Kata sandi acak unik akan dibuat otomatis untuk setiap akun.
                    </p>

                    <div class="mb-3 p-3 bg-light rounded border d-flex align-items-center justify-content-between">
                        <div>
                            <strong class="d-block small text-dark">Belum punya format file?</strong>
                            <span class="text-muted" style="font-size: 11px;">Gunakan template standar agar tidak terjadi kesalahan.</span>
                        </div>
                        <a href="{{ route('admin.users.import.template') }}" class="btn btn-sm btn-outline-primary fw-semibold">
                            <i class="bi bi-download me-1"></i> Unduh Template
                        </a>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Pilih File CSV <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".csv,text/csv,text/plain" required>
                    </div>
                </div>

                <div class="modal-footer bg-light" style="border-radius: 0 0 12px 12px; padding: 14px 22px;">
                    <button type="button" class="btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold" style="background: #1769d5; border-color: #1769d5;">
                        <i class="bi bi-cloud-arrow-up-fill"></i> Unggah &amp; Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
