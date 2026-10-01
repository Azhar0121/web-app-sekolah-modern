@extends('layouts.admin')

@section('title', 'Audit Log & Security Trail')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-shield-check text-primary me-2"></i>Audit Log & Rekam Jejak Digital
            </h5>
            <small class="text-muted">Mencatat aktivitas pengguna: waktu login, riwayat edit data, hapus, publikasi, download, dan persetujuan.</small>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="row g-2">
                <div class="col-md-3">
                    <select name="event" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Event Aktivitas --</option>
                        <option value="login" @selected($eventFilter === 'login')>Login</option>
                        <option value="logout" @selected($eventFilter === 'logout')>Logout</option>
                        <option value="create" @selected($eventFilter === 'create')>Tambah Data (Create)</option>
                        <option value="update" @selected($eventFilter === 'update')>Edit Data (Update)</option>
                        <option value="delete" @selected($eventFilter === 'delete')>Hapus Data (Delete)</option>
                        <option value="download" @selected($eventFilter === 'download')>Download Dokumen</option>
                        <option value="approve" @selected($eventFilter === 'approve')>Persetujuan (Approve)</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <input type="text" name="search" class="form-control form-control-sm" value="{{ $search }}" placeholder="Cari nama user, deskripsi aktivitas, atau IP Address...">
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i>Filter Log</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:160px;">Waktu</th>
                        <th>Pengguna</th>
                        <th class="text-center" style="width:100px;">Event</th>
                        <th>Deskripsi Aktivitas</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                    <tr>
                        <td class="small text-muted fw-semibold">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                        <td>
                            <div class="fw-semibold small text-dark">{{ $log->user_name }}</div>
                            @if ($log->user?->role)
                                <small class="text-muted" style="font-size:0.7rem;">{{ $log->user->role->name }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                            $badgeClass = match ($log->event) {
                                'create'   => 'text-bg-success',
                                'update'   => 'text-bg-warning',
                                'delete'   => 'text-bg-danger',
                                'download' => 'text-bg-info',
                                'login'    => 'text-bg-primary',
                                'logout'   => 'text-bg-secondary',
                                default    => 'text-bg-dark',
                            };
                            @endphp
                            <span class="badge {{ $badgeClass }} badge-sm">{{ strtoupper($log->event) }}</span>
                        </td>
                        <td class="small">{{ $log->description }}</td>
                        <td class="small text-muted font-monospace">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada catatan audit log aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
        <div class="card-footer bg-white py-2">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
