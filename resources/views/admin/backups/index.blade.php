@extends('layouts.admin')

@section('title', 'Backup & Sistem Pemulihan')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-database-down text-primary me-2"></i>Backup & Pemulihan Database
            </h5>
            <small class="text-muted">Buat salinan data (backup) manual, unduh file SQL backup, atau kelola arsip pemulihan data.</small>
        </div>
        <form action="{{ route('admin.backups.create') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-cloud-arrow-up me-1"></i>Buat Backup Database Sekarang
            </button>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px;">No.</th>
                        <th>Nama File Backup</th>
                        <th>Ukuran File</th>
                        <th>Dibuat Oleh</th>
                        <th>Waktu Backup</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($backups as $bk)
                    <tr>
                        <td>{{ $loop->iteration + $backups->firstItem() - 1 }}</td>
                        <td><span class="fw-semibold text-dark font-monospace small"><i class="bi bi-file-earmark-code text-primary me-1"></i>{{ $bk->filename }}</span></td>
                        <td class="small">{{ $bk->formattedSize() }}</td>
                        <td class="small">{{ $bk->creator?->name ?? 'Sistem' }}</td>
                        <td class="small text-muted">{{ $bk->created_at->format('d M Y H:i:s') }}</td>
                        <td class="text-center"><span class="badge bg-success-subtle text-success">Berhasil</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.backups.download', $bk) }}" class="btn btn-sm btn-outline-primary me-1" title="Download SQL">
                                <i class="bi bi-download me-1"></i>Unduh
                            </a>
                            <form action="{{ route('admin.backups.destroy', $bk) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus file backup ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Backup"><i class="bi bi-trash me-1"></i>Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada file backup database yang dibuat. Klik tombol "Buat Backup Database Sekarang" di atas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($backups->hasPages())
        <div class="card-footer bg-white py-2">
            {{ $backups->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
