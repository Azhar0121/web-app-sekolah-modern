@extends('layouts.admin')

@section('title', 'Ubah Transaksi ' . $transaction->number)

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/finance/edit.css') }}">

<div class="finance-edit-page">

    {{-- HERO --}}
    <section class="finance-edit-hero">
        <div class="finance-edit-hero-content">
            <div class="finance-edit-eyebrow">
                <i class="bi bi-wallet2"></i>
                <span>KEUANGAN SEKOLAH</span>
            </div>

            <h1>Ubah Transaksi</h1>

            <p>
                Perbarui informasi transaksi dengan teliti.
                Setiap perubahan akan tercatat dalam Audit Log.
            </p>
        </div>

        <div class="finance-edit-hero-icon">
            <i class="bi bi-pencil-square"></i>
        </div>
    </section>

    {{-- INFORMASI TRANSAKSI --}}
    <section class="finance-edit-summary">
        <div class="finance-edit-summary-icon">
            <i class="bi bi-receipt"></i>
        </div>

        <div class="finance-edit-summary-content">
            <span class="finance-edit-summary-label">
                Nomor Bukti Transaksi
            </span>

            <h2>{{ $transaction->number }}</h2>

            <span class="finance-edit-type">
                {{ $transaction->typeLabel() }}
            </span>
        </div>

        <div class="finance-edit-summary-note">
            <i class="bi bi-lock-fill"></i>
            <span>
                Jenis transaksi dan nomor bukti tidak dapat diubah.
            </span>
        </div>
    </section>

    {{-- FORM --}}
    <section class="finance-edit-card">
        <div class="finance-edit-card-header">
            <div class="finance-edit-card-heading">
                <span class="finance-edit-section-icon">
                    <i class="bi bi-sliders"></i>
                </span>

                <div>
                    <h3>Informasi Transaksi</h3>
                    <p>
                        Periksa kembali data sebelum menyimpan perubahan.
                    </p>
                </div>
            </div>
        </div>

        <div class="finance-edit-card-body">
            <form
                method="POST"
                action="{{ route('admin.finance.update', $transaction) }}"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                @include('admin.finance._form')

                {{-- FOOTER FORM --}}
                <div class="finance-edit-form-footer">

                    <div class="finance-edit-audit-note">
                        <i class="bi bi-shield-check"></i>
                        <span>
                            Perubahan akan tercatat di Audit Log.
                        </span>
                    </div>

                    <div class="finance-edit-actions">

                        {{-- BATAL --}}
                        <a
                            href="{{ route('admin.finance.show', $transaction) }}"
                            class="finance-edit-btn finance-edit-btn-cancel"
                        >
                            Batal
                        </a>

                        {{-- SIMPAN --}}
                        <button
                            type="submit"
                            class="finance-edit-btn finance-edit-btn-save"
                        >
                            <i class="bi bi-check2-circle"></i>
                            Simpan Perubahan
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </section>

</div>
@endsection
