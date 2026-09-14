@extends('layouts.app')

@section('title', 'Detail Surat - ' . $correspondence->number)

@section('content')

<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <span class="text-uppercase text-muted small fw-semibold">
            {{ $correspondence->type === 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }}
        </span>
        <h1 class="h4 fw-bold mb-1"><code>{{ $correspondence->number }}</code></h1>
        <p class="text-muted mb-0">{{ $correspondence->subject }}</p>
    </div>
    <div>
        <a href="{{ route('admin.correspondences.index', ['type' => $correspondence->type]) }}" class="btn btn-outline-secondary btn-sm">
            &larr; Kembali
        </a>
        <a href="{{ route('admin.correspondences.edit', $correspondence) }}" class="btn btn-outline-primary btn-sm">Edit</a>
        <form method="POST" action="{{ route('admin.correspondences.destroy', $correspondence) }}" class="d-inline"
              onsubmit="return confirm('Hapus surat {{ $correspondence->number }} dari arsip? Tindakan ini tidak bisa dibatalkan.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Informasi Surat</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Nomor Surat</dt>
                    <dd class="col-sm-8"><code>{{ $correspondence->number }}</code></dd>

                    <dt class="col-sm-4 text-muted">Tanggal Surat</dt>
                    <dd class="col-sm-8">{{ $correspondence->letter_date->format('d M Y') }}</dd>

                    <dt class="col-sm-4 text-muted">Kategori</dt>
                    <dd class="col-sm-8">{{ $correspondence->category }}</dd>

                    <dt class="col-sm-4 text-muted">Perihal</dt>
                    <dd class="col-sm-8">{{ $correspondence->subject }}</dd>

                    <dt class="col-sm-4 text-muted">{{ $correspondence->type === 'masuk' ? 'Pengirim' : 'Tujuan' }}</dt>
                    <dd class="col-sm-8">{{ $correspondence->correspondent }}</dd>

                    @if ($correspondence->description)
                        <dt class="col-sm-4 text-muted">Catatan</dt>
                        <dd class="col-sm-8" style="white-space: pre-line;">{{ $correspondence->description }}</dd>
                    @endif

                    @if ($correspondence->type === 'masuk' && $correspondence->disposition)
                        <dt class="col-sm-4 text-muted">Disposisi</dt>
                        <dd class="col-sm-8" style="white-space: pre-line;">{{ $correspondence->disposition }}</dd>
                    @endif

                    <dt class="col-sm-4 text-muted">Dicatat oleh</dt>
                    <dd class="col-sm-8">{{ $correspondence->creator?->name ?? '-' }} &middot; {{ $correspondence->created_at->format('d M Y, H:i') }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Status</div>
            <div class="card-body">
                @php
                    $statusColor = match ($correspondence->status) {
                        'selesai', 'terkirim' => 'success',
                        'diproses' => 'warning',
                        default => 'secondary',
                    };
                @endphp
                <span class="badge text-bg-{{ $statusColor }} fs-6">
                    {{ \App\Models\Correspondence::statusOptions($correspondence->type)[$correspondence->status] ?? $correspondence->status }}
                </span>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Lampiran</div>
            <div class="card-body">
                @if ($correspondence->hasFile())
                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($correspondence->file_path) }}"
                       target="_blank" class="btn btn-outline-primary btn-sm w-100">
                        📎 {{ $correspondence->file_original_name }}
                    </a>
                @else
                    <p class="text-muted small mb-0">Tidak ada lampiran/scan surat.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
