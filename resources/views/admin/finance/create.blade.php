@extends('layouts.admin')

@section('title', $type === 'income' ? 'Catat Kas Masuk' : 'Catat Kas Keluar')

@section('content')
<div class="container-fluid px-0" style="max-width: 860px;">

    <div class="mb-4">
        <a href="{{ route('admin.finance.index') }}" class="text-decoration-none small">&larr; Kembali ke Keuangan</a>
        <h3 class="fw-bold mt-2 mb-1">{{ $type === 'income' ? 'Catat Kas Masuk' : 'Catat Kas Keluar' }}</h3>
        <p class="text-muted mb-0">Nomor bukti dibuat otomatis setelah transaksi disimpan.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.finance.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.finance._form')

                <div class="d-flex gap-2">
                    <button type="submit" class="btn {{ $type === 'income' ? 'btn-success' : 'btn-danger' }}">Simpan Transaksi</button>
                    <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
