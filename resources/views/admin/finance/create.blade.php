@extends('layouts.admin')

@section('title', $type === 'income' ? 'Catat Kas Masuk' : 'Catat Kas Keluar')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/finance/create.css') }}">

<div class="finance-create-page">

    @php
        $isIncome = $type === 'income';
    @endphp

    {{-- HERO --}}
    <section class="finance-create-hero {{ $isIncome ? 'is-income' : 'is-expense' }}">
        <div class="finance-create-hero-content">
            <div class="finance-create-eyebrow">
                <i class="bi bi-wallet2"></i>
                <span>KEUANGAN SEKOLAH</span>
            </div>

            <h1>{{ $isIncome ? 'Catat Kas Masuk' : 'Catat Kas Keluar' }}</h1>

            <p>
                {{ $isIncome
                    ? 'Catat penerimaan dana sekolah dengan lengkap dan akurat.'
                    : 'Catat pengeluaran dana sekolah agar administrasi keuangan tetap tertata.' }}
            </p>

            <div class="finance-create-status">
                <i class="bi {{ $isIncome ? 'bi-arrow-down-left-circle' : 'bi-arrow-up-right-circle' }}"></i>
                <span>{{ $isIncome ? 'Transaksi Penerimaan' : 'Transaksi Pengeluaran' }}</span>
            </div>
        </div>

        <div class="finance-create-hero-icon">
            <i class="bi {{ $isIncome ? 'bi-cash-stack' : 'bi-receipt' }}"></i>
        </div>
    </section>

    {{-- INFORMASI TRANSAKSI --}}
    <section class="finance-create-info">
        <div class="finance-create-info-icon {{ $isIncome ? 'is-income' : 'is-expense' }}">
            <i class="bi {{ $isIncome ? 'bi-plus-circle' : 'bi-dash-circle' }}"></i>
        </div>

        <div class="finance-create-info-content">
            <span class="finance-create-info-label">Informasi Nomor Bukti</span>
            <h2>Nomor transaksi dibuat otomatis</h2>
            <p>
                Nomor bukti akan dihasilkan oleh sistem setelah transaksi berhasil disimpan.
            </p>
        </div>
    </section>

    {{-- FORM CARD --}}
    <section class="finance-create-card">

        <div class="finance-create-card-header">
            <div class="finance-create-card-heading">
                <span class="finance-create-section-icon">
                    <i class="bi bi-sliders"></i>
                </span>

                <div>
                    <h3>Informasi Transaksi</h3>
                    <p>Lengkapi data berikut sebelum menyimpan transaksi.</p>
                </div>
            </div>
        </div>

        <div class="finance-create-card-body">
            <form
                method="POST"
                action="{{ route('admin.finance.store') }}"
                enctype="multipart/form-data"
            >
                @csrf

                @include('admin.finance._form')

                {{-- FOOTER --}}
                <div class="finance-create-form-footer">

                    <div class="finance-create-audit-note">
                        <i class="bi bi-shield-check"></i>
                        <span>Pastikan data transaksi sudah benar.</span>
                    </div>

                    <div class="finance-create-actions">

                        {{-- BATAL --}}
                        <a
                            href="{{ route('admin.finance.index') }}"
                            class="finance-create-btn finance-create-btn-cancel"
                        >
                            Batal
                        </a>

                        {{-- SIMPAN --}}
                        <button
                            type="submit"
                            class="finance-create-btn {{ $isIncome ? 'finance-create-btn-income' : 'finance-create-btn-expense' }}"
                        >
                            <i class="bi bi-check2-circle"></i>
                            Simpan Transaksi
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </section>

</div>
@endsection
