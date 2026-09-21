@extends('layouts.admin')

@section('title', 'Persuratan Digital')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="text-uppercase text-muted small fw-semibold">Portal Tata Usaha</span>
        <h4 class="fw-bold mb-1">Persuratan Digital</h4>
        <p class="text-muted mb-0">Kelola surat masuk & keluar dengan penomoran otomatis.</p>
    </div>
    <div>
        <a href="{{ route('tu.dashboard') }}" class="btn btn-outline-secondary btn-sm">&larr; Dashboard</a>
        <a href="{{ route('admin.correspondences.create', ['type' => $type]) }}" class="btn btn-primary btn-sm">
            + {{ $type === 'masuk' ? 'Catat Surat Masuk' : 'Buat Surat Keluar' }}
        </a>
    </div>
</div>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $type === 'masuk' ? 'active' : '' }}"
           href="{{ route('admin.correspondences.index', ['type' => 'masuk']) }}">
            Surat Masuk <span class="badge text-bg-secondary ms-1">{{ $counts['masuk'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $type === 'keluar' ? 'active' : '' }}"
           href="{{ route('admin.correspondences.index', ['type' => 'keluar']) }}">
            Surat Keluar <span class="badge text-bg-secondary ms-1">{{ $counts['keluar'] }}</span>
        </a>
    </li>
</ul>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.correspondences.index') }}" class="row g-2 align-items-end">
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="col-md-8">
                <label class="form-label small text-muted">Cari nomor / perihal / {{ $type === 'masuk' ? 'pengirim' : 'tujuan' }}</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Ketik untuk mencari...">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary w-100">Terapkan</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Perihal</th>
                    <th>{{ $type === 'masuk' ? 'Pengirim' : 'Tujuan' }}</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($correspondences as $item)
                    <tr>
                        <td class="text-nowrap"><code>{{ $item->number }}</code></td>
                        <td class="text-nowrap">{{ $item->letter_date->format('d M Y') }}</td>
                        <td>
                            {{ $item->subject }}
                            <div class="text-muted small">{{ $item->category }}</div>
                        </td>
                        <td>{{ $item->correspondent }}</td>
                        <td class="text-center">
                            @php
                                $statusColor = match ($item->status) {
                                    'selesai', 'terkirim' => 'success',
                                    'diproses' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge text-bg-{{ $statusColor }}">
                                {{ \App\Models\Correspondence::statusOptions($item->type)[$item->status] ?? $item->status }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.correspondences.show', $item) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            Belum ada {{ $type === 'masuk' ? 'surat masuk' : 'surat keluar' }} yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($correspondences->hasPages())
        <div class="card-body">
            {{ $correspondences->links() }}
        </div>
    @endif
</div>

@endsection
