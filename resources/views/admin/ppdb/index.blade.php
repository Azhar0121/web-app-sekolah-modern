@extends('layouts.admin')

@section('title', 'Kelola PPDB')

@section('content')
<h4 class="fw-bold mb-4">Kelola Pendaftar PPDB</h4>

<div class="mb-3">
    <form method="GET" action="{{ route('admin.ppdb.index') }}" class="d-flex gap-2">
        <select name="status" class="form-select" style="max-width: 260px;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="submitted" @selected($statusFilter === 'submitted')>Menunggu Verifikasi</option>
            <option value="verified" @selected($statusFilter === 'verified')>Terverifikasi</option>
            <option value="accepted" @selected($statusFilter === 'accepted')>Diterima — Menunggu Daftar Ulang</option>
            <option value="registered_ulang" @selected($statusFilter === 'registered_ulang')>Daftar Ulang Selesai</option>
            <option value="rejected" @selected($statusFilter === 'rejected')>Ditolak</option>
        </select>
    </form>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Pendaftaran</th>
                    <th>Nama</th>
                    <th>Asal Sekolah</th>
                    <th>Status</th>
                    <th>Tanggal Daftar</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($registrations as $registration)
                    <tr>
                        <td><code>{{ $registration->registration_number }}</code></td>
                        <td>{{ $registration->full_name }}</td>
                        <td>{{ $registration->previous_school }}</td>
                        <td><span class="badge text-bg-secondary">{{ $registration->statusLabel() }}</span></td>
                        <td>{{ $registration->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.ppdb.show', $registration) }}" class="btn btn-sm btn-outline-secondary">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada pendaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($registrations->hasPages())
        <div class="card-body">
            {{ $registrations->links() }}
        </div>
    @endif
</div>
@endsection
