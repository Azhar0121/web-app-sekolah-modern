@extends('layouts.admin')

@section('title', 'Transaksi ' . $transaction->number)

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/finance/show.css') }}">

@php
    $t = $transaction;
    $isIncome = $t->isIncome();
    $isVoid = $t->isVoid();
@endphp

<div class="finance-show-page">

    {{-- HERO --}}
    <section class="finance-show-hero">
        <div class="finance-show-hero-content">

            <div class="finance-show-eyebrow">
                <i class="bi bi-wallet2"></i>
                <span>KEUANGAN SEKOLAH / DETAIL TRANSAKSI</span>
            </div>

            <h1>Detail Transaksi</h1>

            <p>
                Informasi lengkap pencatatan transaksi keuangan sekolah.
            </p>

            <div class="finance-show-number">
                <i class="bi bi-receipt"></i>
                <span>{{ $t->number }}</span>
            </div>
        </div>

        <div class="finance-show-hero-icon">
            <i class="bi bi-file-earmark-text"></i>
        </div>
    </section>

    {{-- STATUS TRANSAKSI --}}
    <section class="finance-show-status-card">
        <div class="finance-show-status-main">

            <div class="finance-show-status-icon {{ $isVoid ? 'is-void' : ($isIncome ? 'is-income' : 'is-expense') }}">
                <i class="bi {{ $isVoid ? 'bi-x-circle' : ($isIncome ? 'bi-arrow-down-left-circle' : 'bi-arrow-up-right-circle') }}"></i>
            </div>

            <div class="finance-show-status-info">
                <span class="finance-show-label">Jenis Transaksi</span>

                <div class="finance-show-badges">
                    <span class="finance-show-badge {{ $isVoid ? 'badge-void' : ($isIncome ? 'badge-income' : 'badge-expense') }}">
                        {{ $t->typeLabel() }}
                    </span>

                    @if ($isVoid)
                        <span class="finance-show-badge badge-void">
                            <i class="bi bi-x-octagon me-1"></i>Dibatalkan
                        </span>
                    @endif

                    @if ($t->isFromBilling())
                        <span class="finance-show-badge badge-billing">
                            <i class="bi bi-link-45deg me-1"></i>Dari Tagihan Siswa
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if ($canManage && $t->isEditable())
            <a
                href="{{ route('admin.finance.edit', $t) }}"
                class="finance-show-btn finance-show-btn-edit"
            >
                <i class="bi bi-pencil-square"></i>
                Ubah Transaksi
            </a>
        @endif
    </section>

    {{-- INFORMASI PEMBATALAN --}}
    @if ($isVoid)
        <section class="finance-show-void-alert">
            <div class="finance-show-alert-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div class="finance-show-alert-content">
                <h3>Transaksi Dibatalkan</h3>

                <p>
                    Pembatalan dilakukan oleh
                    <strong>{{ $t->voidedBy?->name ?? '-' }}</strong>
                    pada
                    {{ \App\Models\FinanceTransaction::tanggal($t->voided_at, false, true) }}.
                </p>

                <div class="finance-show-void-reason">
                    <span>Alasan pembatalan</span>
                    <p>{{ $t->void_reason ?: '-' }}</p>
                </div>
            </div>
        </section>
    @endif

    {{-- NOMINAL --}}
    <section class="finance-show-amount-card">
        <div class="finance-show-amount-top">
            <div>
                <span class="finance-show-label">Nominal Transaksi</span>

                <h2 class="{{ $isVoid ? 'is-void' : ($isIncome ? 'is-income' : 'is-expense') }}">
                    @if ($isVoid)
                        <span class="finance-show-void-prefix">Dibatalkan</span>
                    @endif

                    {{ $t->formattedAmount() }}
                </h2>
            </div>

            <div class="finance-show-amount-icon {{ $isVoid ? 'is-void' : ($isIncome ? 'is-income' : 'is-expense') }}">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>

        <div class="finance-show-amount-meta">
            <div>
                <i class="bi bi-calendar3"></i>
                <span>
                    {{ \App\Models\FinanceTransaction::tanggal($t->transaction_date) }}
                </span>
            </div>

            <div>
                <i class="bi bi-credit-card"></i>
                <span>{{ $t->paymentMethodLabel() }}</span>
            </div>
        </div>
    </section>

    {{-- DETAIL TRANSAKSI --}}
    <section class="finance-show-card">

        <div class="finance-show-card-header">
            <div class="finance-show-section-icon">
                <i class="bi bi-list-ul"></i>
            </div>

            <div>
                <h3>Informasi Transaksi</h3>
                <p>Rincian lengkap transaksi yang tercatat di sistem.</p>
            </div>
        </div>

        <div class="finance-show-card-body">
            <dl class="finance-show-details">

                <div class="finance-show-detail-row">
                    <dt><i class="bi bi-calendar-event"></i>Tanggal Transaksi</dt>
                    <dd>{{ \App\Models\FinanceTransaction::tanggal($t->transaction_date) }}</dd>
                </div>

                <div class="finance-show-detail-row">
                    <dt><i class="bi bi-tags"></i>Kategori</dt>
                    <dd>{{ $t->category?->name ?? '-' }}</dd>
                </div>

                <div class="finance-show-detail-row">
                    <dt><i class="bi bi-card-text"></i>Uraian</dt>
                    <dd>{{ $t->description ?: '-' }}</dd>
                </div>

                <div class="finance-show-detail-row">
                    <dt>
                        <i class="bi bi-people"></i>
                        {{ $isIncome ? 'Sumber Dana' : 'Dibayarkan Kepada' }}
                    </dt>
                    <dd>{{ $t->party ?: '-' }}</dd>
                </div>

                <div class="finance-show-detail-row">
                    <dt><i class="bi bi-credit-card"></i>Metode Pembayaran</dt>
                    <dd>{{ $t->paymentMethodLabel() }}</dd>
                </div>

                <div class="finance-show-detail-row">
                    <dt><i class="bi bi-upc"></i>No. Nota / Kwitansi</dt>
                    <dd>{{ $t->reference_number ?: '-' }}</dd>
                </div>

                @if ($t->item_name)
                    <div class="finance-show-detail-row">
                        <dt><i class="bi bi-box-seam"></i>Barang</dt>
                        <dd>
                            <strong>{{ $t->item_name }}</strong>

                            @if ($t->quantity)
                                <span class="finance-show-detail-sub">
                                    Jumlah: {{ $t->quantity }}
                                </span>
                            @endif

                            @if ($t->unit_price !== null)
                                <span class="finance-show-detail-sub">
                                    Harga satuan:
                                    {{ \App\Models\FinanceTransaction::rupiah($t->unit_price) }}
                                </span>
                            @endif
                        </dd>
                    </div>
                @endif

                @if ($t->room)
                    <div class="finance-show-detail-row">
                        <dt><i class="bi bi-geo-alt"></i>Lokasi / Ruangan</dt>
                        <dd>{{ $t->room->code }} - {{ $t->room->name }}</dd>
                    </div>
                @endif

                @if ($t->billing)
                    <div class="finance-show-detail-row">
                        <dt><i class="bi bi-receipt-cutoff"></i>Tagihan Terkait</dt>
                        <dd>
                            {{ $t->billing->description }}

                            @if ($t->billing->student)
                                <a
                                    href="{{ route('admin.billing.show', $t->billing->student) }}"
                                    class="finance-show-inline-link"
                                >
                                    <i class="bi bi-person"></i>
                                    {{ $t->billing->student->name }}
                                </a>
                            @endif
                        </dd>
                    </div>
                @endif

                <div class="finance-show-detail-row">
                    <dt><i class="bi bi-mortarboard"></i>Tahun Ajaran</dt>
                    <dd>{{ $t->academicYear?->name ?? '-' }}</dd>
                </div>

                <div class="finance-show-detail-row">
                    <dt><i class="bi bi-journal-text"></i>Catatan</dt>
                    <dd class="finance-show-notes">{{ $t->notes ?: '-' }}</dd>
                </div>

                <div class="finance-show-detail-row">
                    <dt><i class="bi bi-paperclip"></i>Bukti Transaksi</dt>
                    <dd>
                        @if ($t->hasProof())
                            <a
                                href="{{ route('admin.finance.proof', $t) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="finance-show-proof-link"
                            >
                                <i class="bi bi-file-earmark-arrow-down"></i>

                                <span>{{ $t->proof_original_name }}</span>

                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        @else
                            <span class="finance-show-empty">
                                Belum ada bukti diunggah
                            </span>
                        @endif
                    </dd>
                </div>

                <div class="finance-show-detail-row">
                    <dt><i class="bi bi-person-check"></i>Dicatat Oleh</dt>
                    <dd>
                        {{ $t->creator?->name ?? '-' }}

                        <span class="finance-show-detail-sub">
                            {{ \App\Models\FinanceTransaction::tanggal($t->created_at, false, true) }}
                        </span>
                    </dd>
                </div>

            </dl>
        </div>
    </section>

    {{-- PEMBATALAN TRANSAKSI --}}
    @if ($canManage && ! $isVoid)
        <section class="finance-show-cancel-card">

            <div class="finance-show-cancel-header">
                <div class="finance-show-cancel-icon">
                    <i class="bi bi-x-octagon"></i>
                </div>

                <div>
                    <h3>Batalkan Transaksi</h3>
                    <p>Periksa konsekuensi pembatalan sebelum melanjutkan.</p>
                </div>
            </div>

            <div class="finance-show-cancel-body">
                <div class="finance-show-warning">
                    <i class="bi bi-info-circle"></i>

                    <p>
                        Transaksi tidak dihapus permanen agar riwayat keuangan tetap utuh.
                        Setelah dibatalkan, transaksi tidak lagi dihitung dalam saldo dan laporan.

                        @if ($t->isFromBilling())
                            <br><br>
                            <strong>Perhatian:</strong>
                            status tagihan siswa tetap "Lunas".
                            Batalkan hanya bila pencatatan kas ini memang keliru.
                        @endif
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.finance.void', $t) }}"
                    onsubmit="return confirm('Batalkan transaksi {{ $t->number }}? Tindakan ini tidak dapat dikembalikan.')"
                >
                    @csrf

                    <div class="mb-3">
                        <label for="void_reason" class="finance-show-form-label">
                            Alasan Pembatalan
                            <span>*</span>
                        </label>

                        <textarea
                            name="void_reason"
                            id="void_reason"
                            rows="3"
                            maxlength="500"
                            placeholder="Tuliskan alasan pembatalan transaksi..."
                            class="form-control @error('void_reason') is-invalid @enderror"
                            required
                        >{{ old('void_reason') }}</textarea>

                        @error('void_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="finance-show-form-help">
                            Maksimal 500 karakter.
                        </div>
                    </div>

                    <button type="submit" class="finance-show-btn finance-show-btn-void">
                        <i class="bi bi-x-circle"></i>
                        Batalkan Transaksi
                    </button>
                </form>
            </div>
        </section>
    @endif

    {{-- FOOTER NAVIGASI --}}
    <div class="finance-show-footer">
        <a
            href="{{ route('admin.finance.index') }}"
            class="finance-show-btn finance-show-btn-back"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali ke Keuangan
        </a>

        @if ($canManage && $t->isEditable())
            <a
                href="{{ route('admin.finance.edit', $t) }}"
                class="finance-show-btn finance-show-btn-edit"
            >
                <i class="bi bi-pencil-square"></i>
                Ubah Transaksi
            </a>
        @endif
    </div>

</div>
@endsection
