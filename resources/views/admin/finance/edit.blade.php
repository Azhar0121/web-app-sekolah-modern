@extends('layouts.admin')

@section('title', 'Ubah Transaksi ' . $transaction->number)

@section('content')
<div class="container-fluid px-0" style="max-width: 860px;">

    <div class="mb-4">
        <a href="{{ route('admin.finance.show', $transaction) }}" class="text-decoration-none small">&larr; Kembali ke detail</a>
        <h3 class="fw-bold mt-2 mb-1">Ubah Transaksi {{ $transaction->number }}</h3>
        <p class="text-muted mb-0">Jenis transaksi ({{ $transaction->typeLabel() }}) dan nomor bukti tidak dapat diubah. Setiap perubahan tercatat di Audit Log.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.finance.update', $transaction) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.finance._form')

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.finance.show', $transaction) }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
