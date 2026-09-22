@extends('layouts.app')

@section('content')
<div class="container">
    @include('layouts.partials.announcements-banner')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Riwayat Pengajuan Izin</h2>
        <a href="{{ route('ortu.leave-requests.create') }}" class="btn btn-primary">Ajukan Izin Baru</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Anak</th>
                            <th>Jenis</th>
                            <th>Tanggal</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Catatan Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td>{{ $req->student->name }}</td>
                            <td>
                                <span class="badge {{ $req->type === 'sakit' ? 'bg-danger' : 'bg-warning text-dark' }}">{{ ucfirst($req->type) }}</span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($req->start_date)->format('d/m/Y') }} 
                                @if($req->start_date != $req->end_date)
                                 - {{ \Carbon\Carbon::parse($req->end_date)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>{{ Str::limit($req->reason, 50) }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-secondary">Menunggu</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($req->status === 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $req->process_notes ?: '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat pengajuan izin.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        {{ $requests->links() }}
    </div>
</div>
@endsection
