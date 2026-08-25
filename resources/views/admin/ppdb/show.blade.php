@extends('layouts.admin')

@section('title', 'Detail Pendaftar PPDB')

@section('content')
<p><a href="{{ route('admin.ppdb.index') }}">&larr; Kembali ke Daftar</a></p>
<h4 class="fw-bold mb-4">{{ $registration->full_name }}</h4>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Data Calon Siswa</h6>
                <table class="table table-sm mb-0">
                    <tr><th style="width: 180px;">No. Pendaftaran</th><td>{{ $registration->registration_number }}</td></tr>
                    <tr><th>NISN</th><td>{{ $registration->nisn ?? '-' }}</td></tr>
                    <tr><th>NIK</th><td>{{ $registration->nik ?? '-' }}</td></tr>
                    <tr><th>Jenis Kelamin</th><td>{{ $registration->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    <tr><th>Tempat, Tanggal Lahir</th><td>{{ $registration->birth_place }}, {{ $registration->birth_date->format('d M Y') }}</td></tr>
                    <tr><th>Alamat</th><td>{{ $registration->address }}</td></tr>
                    <tr><th>No. HP</th><td>{{ $registration->phone }}</td></tr>
                    <tr><th>Orang Tua/Wali</th><td>{{ $registration->parent_name }} ({{ $registration->parent_phone }})</td></tr>
                    <tr><th>Asal Sekolah</th><td>{{ $registration->previous_school }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Dokumen Terlampir</h6>
                @forelse ($registration->documents as $document)
                    <p class="mb-1">
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($document->file_path) }}" target="_blank">
                            {{ $document->documentTypeLabel() }} — {{ $document->original_name }}
                        </a>
                    </p>
                @empty
                    <p class="text-muted mb-0">Belum ada dokumen diunggah.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-5">
        @if (session('success'))
            <div class="alert alert-success py-2 small">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
        @endif

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Status & Verifikasi</h6>

                <p>Status saat ini: <span class="badge text-bg-secondary">{{ $registration->statusLabel() }}</span></p>

                @if ($registration->verifiedBy)
                    <p class="small text-muted">
                        Diverifikasi oleh {{ $registration->verifiedBy->name }}
                        pada {{ $registration->verified_at?->format('d M Y H:i') }}
                    </p>
                @endif

                {{-- Ubah status verifikasi/approval hanya relevan sebelum "accepted".
                     Setelah accepted, alur berikutnya adalah daftar ulang (panel di bawah),
                     bukan ubah status verifikasi lagi. --}}
                @if (! in_array($registration->status, ['accepted', 'registered_ulang']))
                    <form method="POST" action="{{ route('admin.ppdb.update-status', $registration) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <label class="form-label small">Ubah Status</label>
                            <select name="status" class="form-select" required>
                                <option value="verified" @selected($registration->status === 'verified')>Terverifikasi</option>
                                <option value="accepted" @selected($registration->status === 'accepted')>Diterima</option>
                                <option value="rejected" @selected($registration->status === 'rejected')>Ditolak</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Catatan (opsional)</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $registration->notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan Status</button>
                    </form>
                @elseif ($registration->notes)
                    <p class="small text-muted mb-0"><strong>Catatan:</strong> {{ $registration->notes }}</p>
                @endif
            </div>
        </div>

        {{-- Panel Daftar Ulang & Pembayaran (OFFLINE) — cuma muncul kalau statusnya
             "accepted" (menunggu daftar ulang) atau sudah "registered_ulang" (riwayat). --}}
        @if ($registration->status === 'accepted' || $registration->status === 'registered_ulang')
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-1">Daftar Ulang & Pembayaran</h6>
                    <p class="text-muted small mb-3">
                        Pembayaran dilakukan siswa secara <strong>offline langsung ke sekolah</strong>.
                        Form ini hanya untuk mencatat/mengonfirmasi bukti pembayaran yang sudah diterima.
                    </p>

                    @if ($registration->status === 'registered_ulang')
                        <div class="alert alert-success py-2 small mb-0">
                            <strong>Daftar ulang sudah dikonfirmasi.</strong><br>
                            No. Bukti: {{ $registration->re_registration_reference }}<br>
                            @if ($registration->re_registration_notes)
                                Catatan: {{ $registration->re_registration_notes }}<br>
                            @endif
                            Dikonfirmasi oleh {{ $registration->reRegistrationConfirmedBy?->name }}
                            pada {{ $registration->re_registration_confirmed_at?->format('d M Y H:i') }}
                        </div>
                    @else
                        <form method="POST" action="{{ route('admin.ppdb.confirm-re-registration', $registration) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-2">
                                <label class="form-label small">No. Bukti Pembayaran / Kwitansi</label>
                                <input type="text" name="re_registration_reference" class="form-control"
                                       placeholder="Contoh: KW-2026-0142" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small">Catatan (opsional)</label>
                                <textarea name="re_registration_notes" class="form-control" rows="2"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                Konfirmasi Daftar Ulang & Pembayaran
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
