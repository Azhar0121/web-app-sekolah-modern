@extends('layouts.app')

@section('title', 'Edit Surat')

@section('content')

<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <span class="text-uppercase text-muted small fw-semibold">Persuratan Digital</span>
        <h1 class="h4 fw-bold mb-1">Edit Surat <code>{{ $correspondence->number }}</code></h1>
        <p class="text-muted mb-0">Jenis surat & nomor tidak bisa diubah, hanya detail isinya.</p>
    </div>
    <a href="{{ route('admin.correspondences.show', $correspondence) }}" class="btn btn-outline-secondary btn-sm">
        &larr; Kembali
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width: 700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.correspondences.update', $correspondence) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.correspondences.form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.correspondences.show', $correspondence) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
