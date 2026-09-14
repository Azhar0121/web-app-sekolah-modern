@extends('layouts.app')

@section('title', 'Catat Surat')

@section('content')

<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <span class="text-uppercase text-muted small fw-semibold">Persuratan Digital</span>
        <h1 class="h4 fw-bold mb-1">
            {{ $type === 'masuk' ? 'Catat Surat Masuk' : 'Buat Surat Keluar' }}
        </h1>
        <p class="text-muted mb-0">
            Nomor {{ $type === 'masuk' ? 'agenda' : 'surat resmi' }} akan digenerate otomatis
            begitu disimpan.
        </p>
    </div>
    <a href="{{ route('admin.correspondences.index', ['type' => $type]) }}" class="btn btn-outline-secondary btn-sm">
        &larr; Kembali
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width: 700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.correspondences.store') }}" enctype="multipart/form-data">
            @csrf
            @php($correspondence = null)
            @include('admin.correspondences.form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.correspondences.index', ['type' => $type]) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
