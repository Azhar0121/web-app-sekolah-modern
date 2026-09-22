@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Daftar Pengajuan Izin Siswa</h2>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ request('status', 'pending') === 'pending' ? 'active' : '' }}" href="{{ route('guru.leave-requests.index', ['status' => 'pending']) }}">Menunggu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'approved' ? 'active' : '' }}" href="{{ route('guru.leave-requests.index', ['status' => 'approved']) }}">Disetujui</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'rejected' ? 'active' : '' }}" href="{{ route('guru.leave-requests.index', ['status' => 'rejected']) }}">Ditolak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'all' ? 'active' : '' }}" href="{{ route('guru.leave-requests.index', ['status' => 'all']) }}">Semua</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Jenis</th>
                            <th>Tanggal</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td>
                                <strong>{{ $req->student->name ?? 'Siswa' }}</strong><br>
                                <small class="text-muted d-block">NISN: {{ $req->student->studentProfile->nisn ?? '-' }}</small>
                                <small class="text-muted"><i class="bi bi-person"></i> Ortu: {{ $req->parent->name ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $req->type === 'sakit' ? 'bg-danger' : 'bg-warning text-dark' }}">{{ ucfirst($req->type) }}</span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($req->start_date)->format('d/m/Y') }} 
                                @if($req->start_date != $req->end_date)
                                 - {{ \Carbon\Carbon::parse($req->end_date)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>
                                <div>{{ $req->reason }}</div>
                                @if($req->attachment_path)
                                <div class="mt-1">
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($req->attachment_path) }}" target="_blank" class="badge bg-info text-decoration-none">
                                        <i class="bi bi-paperclip"></i> Lihat Lampiran
                                    </a>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-secondary">Menunggu</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($req->status === 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($req->status === 'pending')
                                <form action="{{ route('guru.leave-requests.process', $req->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm mb-1">
                                        <select name="decision" class="form-select" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="approved">Setuju</option>
                                            <option value="rejected">Tolak</option>
                                        </select>
                                    </div>
                                    <textarea name="process_notes" class="form-control form-control-sm mb-1" placeholder="Catatan (opsional)" rows="1"></textarea>
                                    <button type="submit" class="btn btn-sm btn-primary w-100">Proses</button>
                                </form>
                                @else
                                <small class="text-muted d-block">Diproses oleh: {{ $req->processedBy->name ?? '-' }}</small>
                                <small class="text-muted">Catatan: {{ $req->process_notes ?: '-' }}</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pengajuan izin.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
