@extends('layouts.app')

@section('title', 'Dashboard Tata Usaha')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-1">Dashboard Tata Usaha</h4>
        <p class="text-muted mb-0">Selamat datang, <strong>{{ auth()->user()->name }}</strong>.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon"><x-icon name="file-text" :size="20" /></div>
            <div>
                <div class="stat-value">{{ $stats['submitted'] }}</div>
                <div class="stat-label">Menunggu Verifikasi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon"><x-icon name="user-check" :size="20" /></div>
            <div>
                <div class="stat-value">{{ $stats['verified'] }}</div>
                <div class="stat-label">Terverifikasi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon"><x-icon name="calendar" :size="20" /></div>
            <div>
                <div class="stat-value">{{ $stats['accepted'] }}</div>
                <div class="stat-label">Menunggu Daftar Ulang</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon"><x-icon name="user-group" :size="20" /></div>
            <div>
                <div class="stat-value">{{ $stats['registered_ulang'] }}</div>
                <div class="stat-label">Daftar Ulang Selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span>Menunggu Konfirmasi Daftar Ulang</span>
                <a href="{{ route('admin.ppdb.index', ['status' => 'accepted']) }}" class="small">Lihat semua &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Batas Daftar Ulang</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($awaitingReRegistration as $registration)
                            <tr>
                                <td>
                                    {{ $registration->full_name }}
                                    <div class="text-muted small">{{ $registration->registration_number }}</div>
                                </td>
                                <td>
                                    @if ($registration->isReRegistrationOverdue())
                                        <span class="badge text-bg-danger">Lewat batas</span>
                                    @endif
                                    {{ $registration->reRegistrationDeadlineLabel() ?? '-' }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.ppdb.show', $registration) }}" class="btn btn-sm btn-primary">Konfirmasi</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Tidak ada yang menunggu konfirmasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Riwayat Penempatan Kelas Otomatis</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentEnrollments as $registration)
                            <tr>
                                <td>
                                    {{ $registration->full_name }}
                                    <div class="text-muted small">{{ $registration->user->email }}</div>
                                </td>
                                <td>
                                    @if ($registration->placedClassroom)
                                        <span class="badge text-bg-success">{{ $registration->placedClassroom->name }}</span>
                                    @else
                                        <span class="badge text-bg-warning">Belum ditempatkan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">Belum ada penempatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.ppdb.index') }}" class="btn btn-outline-primary">Kelola Semua Pendaftaran PPDB &rarr;</a>
</div>
@endsection
