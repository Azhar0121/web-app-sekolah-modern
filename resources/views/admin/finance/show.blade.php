@extends('layouts.admin')

@section('title', 'Transaksi ' . $transaction->number)

@section('content')
@php($t = $transaction)

<div class="container-fluid px-0" style="max-width: 900px;">

    <div class="mb-4">
        <a href="{{ route('admin.finance.index') }}" class="text-decoration-none small">&larr; Kembali ke Keuangan</a>
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mt-2">
            <div>
                <h3 class="fw-bold mb-1">{{ $t->number }}</h3>
                <span class="badge {{ $t->isIncome() ? 'text-bg-success' : 'text-bg-danger' }}">{{ $t->typeLabel() }}</span>
                @if ($t->isVoid())
                    <span class="badge text-bg-secondary">Dibatalkan</span>
                @endif
                @if ($t->isFromBilling())
                    <span class="badge text-bg-info">Otomatis dari tagihan siswa</span>
                @endif
            </div>

            @if ($canManage && $t->isEditable())
                <a href="{{ route('admin.finance.edit', $t) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i> Ubah
                </a>
            @endif
        </div>
    </div>

    @if ($t->isVoid())
        <div class="alert alert-secondary">
            <strong>Transaksi dibatalkan</strong> oleh {{ $t->voidedBy?->name ?? '-' }}
            pada {{ \App\Models\FinanceTransaction::tanggal($t->voided_at, false, true) }}.<br>
            <span class="small">Alasan: {{ $t->void_reason }}</span>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="text-muted small">Nominal</div>
            <div class="display-6 fw-bold {{ $t->isVoid() ? 'text-muted text-decoration-line-through' : ($t->isIncome() ? 'text-success' : 'text-danger') }} mb-4">
                {{ $t->formattedAmount() }}
            </div>

            <dl class="row mb-0">
                <dt class="col-sm-4 text-muted fw-normal">Tanggal Transaksi</dt>
                <dd class="col-sm-8">{{ \App\Models\FinanceTransaction::tanggal($t->transaction_date) }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">Kategori</dt>
                <dd class="col-sm-8">{{ $t->category?->name ?? '-' }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">Uraian</dt>
                <dd class="col-sm-8">{{ $t->description }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">{{ $t->isIncome() ? 'Sumber Dana' : 'Dibayarkan Kepada' }}</dt>
                <dd class="col-sm-8">{{ $t->party ?: '-' }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">Metode Pembayaran</dt>
                <dd class="col-sm-8">{{ $t->paymentMethodLabel() }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">No. Nota / Kwitansi</dt>
                <dd class="col-sm-8">{{ $t->reference_number ?: '-' }}</dd>

                @if ($t->item_name)
                    <dt class="col-sm-4 text-muted fw-normal">Barang</dt>
                    <dd class="col-sm-8">
                        {{ $t->item_name }}
                        @if ($t->quantity) &times; {{ $t->quantity }} @endif
                        @if ($t->unit_price !== null) dengan harga satuan {{ \App\Models\FinanceTransaction::rupiah($t->unit_price) }} @endif
                    </dd>
                @endif

                @if ($t->room)
                    <dt class="col-sm-4 text-muted fw-normal">Lokasi / Ruangan</dt>
                    <dd class="col-sm-8">{{ $t->room->code }} - {{ $t->room->name }}</dd>
                @endif

                @if ($t->billing)
                    <dt class="col-sm-4 text-muted fw-normal">Tagihan Terkait</dt>
                    <dd class="col-sm-8">
                        {{ $t->billing->description }}
                        @if ($t->billing->student)
                            &middot;
                            <a href="{{ route('admin.billing.show', $t->billing->student) }}" class="text-decoration-none">{{ $t->billing->student->name }}</a>
                        @endif
                    </dd>
                @endif

                <dt class="col-sm-4 text-muted fw-normal">Tahun Ajaran</dt>
                <dd class="col-sm-8">{{ $t->academicYear?->name ?? '-' }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">Catatan</dt>
                <dd class="col-sm-8">{{ $t->notes ?: '-' }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">Bukti Transaksi</dt>
                <dd class="col-sm-8">
                    @if ($t->hasProof())
                        <a href="{{ route('admin.finance.proof', $t) }}" target="_blank" class="text-decoration-none">
                            <i class="bi bi-paperclip"></i> {{ $t->proof_original_name }}
                        </a>
                    @else
                        <span class="text-muted">Belum ada bukti diunggah</span>
                    @endif
                </dd>

                <dt class="col-sm-4 text-muted fw-normal">Dicatat Oleh</dt>
                <dd class="col-sm-8">{{ $t->creator?->name ?? '-' }} <span class="text-muted small">&middot; {{ \App\Models\FinanceTransaction::tanggal($t->created_at, false, true) }}</span></dd>
            </dl>
        </div>
    </div>

    {{-- BATALKAN TRANSAKSI --}}
    @if ($canManage && ! $t->isVoid())
        <div class="card border-danger border-opacity-25 shadow-sm">
            <div class="card-body">
                <div class="fw-semibold text-danger mb-1"><i class="bi bi-x-octagon me-1"></i> Batalkan Transaksi</div>
                <p class="small text-muted">
                    Transaksi keuangan tidak dihapus permanen supaya riwayat tetap utuh. Transaksi yang dibatalkan tidak dihitung lagi dalam saldo dan laporan.
                    @if ($t->isFromBilling())
                        <br><strong>Perhatian:</strong> status tagihan siswa tetap "Lunas". Batalkan hanya bila pencatatan kas ini memang keliru.
                    @endif
                </p>

                <form method="POST" action="{{ route('admin.finance.void', $t) }}"
                      onsubmit="return confirm('Batalkan transaksi {{ $t->number }}? Tindakan ini tidak dapat dikembalikan.')">
                    @csrf
                    <div class="mb-2">
                        <label for="void_reason" class="form-label small mb-1">Alasan pembatalan</label>
                        <textarea name="void_reason" id="void_reason" rows="2" maxlength="500"
                                  class="form-control @error('void_reason') is-invalid @enderror" required>{{ old('void_reason') }}</textarea>
                        @error('void_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-outline-danger btn-sm">Batalkan Transaksi</button>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection
