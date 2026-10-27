
@extends('layouts.admin')

@section('title', 'Keuangan Sekolah')

@section('content')
@use('App\Models\FinanceTransaction', 'Tx')

<link rel="stylesheet" href="{{ asset('css/admin/finance/index.css') }}">

@php
    $queryString = request()->query();
    $net = $totalIncome - $totalExpense;
@endphp

<div class="finance-page">

    {{-- HERO --}}
    <section class="finance-hero">
        <div class="finance-hero-content">
            <div class="finance-eyebrow">
                <i class="bi bi-building me-2"></i>
                PORTAL TATA USAHA
            </div>

            <h1>Keuangan Sekolah</h1>
            <p>
                Catat dan pantau kas masuk serta kas keluar sekolah
                secara terpusat dan terstruktur.
            </p>
        </div>

        @if ($canManage)
            <div class="finance-hero-actions">
                <a href="{{ route('admin.finance.create', ['type' => 'income']) }}"
                   class="finance-btn finance-btn-light">
                    <i class="bi bi-arrow-down-circle"></i>
                    <span>Catat Kas Masuk</span>
                </a>

                <a href="{{ route('admin.finance.create', ['type' => 'expense']) }}"
                   class="finance-btn finance-btn-outline">
                    <i class="bi bi-arrow-up-circle"></i>
                    <span>Catat Kas Keluar</span>
                </a>
            </div>
        @endif
    </section>

    {{-- NAVIGASI --}}
    <div class="finance-navigation">
        @include('admin.finance._nav', ['active' => 'transaksi'])
    </div>

    {{-- RINGKASAN --}}
    <section class="finance-summary">
        <div class="finance-summary-card">
            <div class="finance-summary-top">
                <span class="finance-summary-label">Pemasukan Periode Ini</span>
                <span class="finance-summary-icon income-icon">
                    <i class="bi bi-arrow-down-left-circle"></i>
                </span>
            </div>

            <div class="finance-summary-value income-value">
                {{ Tx::rupiah($totalIncome) }}
            </div>

            <div class="finance-summary-note">
                <i class="bi bi-calendar3"></i>
                {{ Tx::tanggal($filters['from'], true) }}
                – {{ Tx::tanggal($filters['to'], true) }}
            </div>
        </div>

        <div class="finance-summary-card">
            <div class="finance-summary-top">
                <span class="finance-summary-label">Pengeluaran Periode Ini</span>
                <span class="finance-summary-icon expense-icon">
                    <i class="bi bi-arrow-up-right-circle"></i>
                </span>
            </div>

            <div class="finance-summary-value expense-value">
                {{ Tx::rupiah($totalExpense) }}
            </div>

            <div class="finance-summary-note">
                <i class="bi bi-shield-check"></i>
                Hanya transaksi sah
            </div>
        </div>

        <div class="finance-summary-card">
            <div class="finance-summary-top">
                <span class="finance-summary-label">Selisih Periode</span>
                <span class="finance-summary-icon {{ $net >= 0 ? 'income-icon' : 'expense-icon' }}">
                    <i class="bi bi-calculator"></i>
                </span>
            </div>

            <div class="finance-summary-value {{ $net >= 0 ? 'income-value' : 'expense-value' }}">
                {{ Tx::rupiah($net) }}
            </div>

            <div class="finance-summary-note">
                <span class="finance-status-dot {{ $net >= 0 ? 'dot-income' : 'dot-expense' }}"></span>
                {{ $net >= 0 ? 'Surplus' : 'Defisit' }}
            </div>
        </div>

        <div class="finance-summary-card finance-balance-card">
            <div class="finance-summary-top">
                <span class="finance-summary-label">Saldo Kas</span>
                <span class="finance-summary-icon balance-icon">
                    <i class="bi bi-wallet2"></i>
                </span>
            </div>

            <div class="finance-summary-value">
                {{ Tx::rupiah($cashBalance) }}
            </div>

            <div class="finance-summary-note">
                <i class="bi bi-calendar-check"></i>
                s.d. {{ Tx::tanggal($filters['to'], true) }}
            </div>

            <div class="finance-balance-caption">
                Akumulasi seluruh transaksi sah
            </div>
        </div>
    </section>

    {{-- FILTER --}}
    <section class="finance-panel finance-filter-panel">
        <div class="finance-panel-heading">
            <div class="finance-heading-icon">
                <i class="bi bi-funnel"></i>
            </div>
            <div>
                <h2>Filter Transaksi</h2>
                <p>Atur kriteria untuk menemukan data yang dibutuhkan.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.finance.index') }}"
              class="finance-filter-form">

            <div class="finance-filter-grid">
                <div class="finance-field">
                    <label for="from">Dari Tanggal</label>
                    <input type="date" id="from" name="from"
                           class="form-control"
                           value="{{ $filters['from']->toDateString() }}">
                </div>

                <div class="finance-field">
                    <label for="to">Sampai Tanggal</label>
                    <input type="date" id="to" name="to"
                           class="form-control"
                           value="{{ $filters['to']->toDateString() }}">
                </div>

                <div class="finance-field">
                    <label for="type">Jenis Transaksi</label>
                    <select id="type" name="type" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="income" @selected($filters['type'] === 'income')>
                            Pemasukan
                        </option>
                        <option value="expense" @selected($filters['type'] === 'expense')>
                            Pengeluaran
                        </option>
                    </select>
                </div>

                <div class="finance-field">
                    <label for="category_id">Kategori</label>
                    <select id="category_id" name="category_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories->groupBy('type') as $type => $group)
                            <optgroup label="{{ $type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}">
                                @foreach ($group as $category)
                                    <option value="{{ $category->id }}"
                                        @selected((string) $filters['category_id'] === (string) $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="finance-field">
                    <label for="status">Status Transaksi</label>
                    <select id="status" name="status" class="form-select">
                        <option value="posted" @selected($filters['status'] === 'posted')>
                            Sah
                        </option>
                        <option value="void" @selected($filters['status'] === 'void')>
                            Dibatalkan
                        </option>
                        <option value="all" @selected($filters['status'] === 'all')>
                            Semua Status
                        </option>
                    </select>
                </div>

                <div class="finance-field finance-search-field">
                    <label for="search">Pencarian</label>
                    <div class="finance-search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" id="search" name="search"
                               class="form-control"
                               value="{{ $filters['search'] }}"
                               placeholder="Nomor bukti, uraian, barang, pihak terkait...">
                    </div>
                </div>
            </div>

            <div class="finance-filter-actions">
                <button type="submit" class="finance-btn finance-btn-navy">
                    <i class="bi bi-funnel"></i>
                    Terapkan Filter
                </button>

                <a href="{{ route('admin.finance.index') }}"
                   class="finance-btn finance-btn-muted">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>

                <a href="{{ route('admin.finance.export', $queryString) }}"
                   class="finance-btn finance-btn-export"
                   title="Unduh CSV yang dapat dibuka di Excel">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    Ekspor CSV
                </a>
            </div>
        </form>
    </section>

    {{-- DAFTAR TRANSAKSI --}}
    <section class="finance-panel finance-table-panel">
        <div class="finance-table-heading">
            <div class="finance-panel-heading mb-0">
                <div class="finance-heading-icon">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <h2>Daftar Transaksi</h2>
                    <p>Rincian pemasukan dan pengeluaran sekolah.</p>
                </div>
            </div>

            @if ($canManage)
                <form method="POST"
                      action="{{ route('admin.finance.sync-billing') }}"
                      onsubmit="return confirm('Catat semua pembayaran tagihan siswa yang sudah lunas tetapi belum masuk ke kas?')">
                    @csrf
                    <button type="submit" class="finance-btn finance-btn-sync">
                        <i class="bi bi-arrow-repeat"></i>
                        Sinkronkan Tagihan Lunas
                    </button>
                </form>
            @endif
        </div>

        <div class="finance-table-meta">
            <span>
                <i class="bi bi-info-circle me-1"></i>
                Nilai pemasukan dan pengeluaran ditampilkan terpisah.
            </span>
        </div>

        <div class="table-responsive finance-table-responsive">
            <table class="table finance-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>No. Bukti</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Uraian Transaksi</th>
                        <th class="text-end">Pemasukan</th>
                        <th class="text-end">Pengeluaran</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transactions as $t)
                        <tr class="{{ $t->isVoid() ? 'finance-row-void' : '' }}">
                            <td>
                                <a href="{{ route('admin.finance.show', $t) }}"
                                   class="finance-receipt-number">
                                    {{ $t->number }}
                                </a>

                                @if ($t->isVoid())
                                    <span class="finance-badge badge-void">Dibatalkan</span>
                                @endif
                            </td>

                            <td class="text-nowrap">
                                <span class="finance-date">
                                    {{ $t->transaction_date->format('d/m/Y') }}
                                </span>
                            </td>

                            <td>
                                <span class="finance-badge {{ $t->isIncome() ? 'badge-income' : 'badge-expense' }}">
                                    {{ $t->category?->name ?? '-' }}
                                </span>
                            </td>

                            <td class="finance-description-cell">
                                <div class="finance-description {{ $t->isVoid() ? 'text-decoration-line-through' : '' }}">
                                    {{ $t->description }}
                                </div>

                                <div class="finance-description-meta">
                                    @if ($t->item_name)
                                        {{ $t->item_name }}@if ($t->quantity) &times; {{ $t->quantity }}@endif
                                        @if ($t->room) &middot; {{ $t->room->name }} @endif
                                    @elseif ($t->party)
                                        {{ $t->party }}
                                    @endif

                                    @if ($t->isFromBilling())
                                        <span class="finance-badge badge-automatic">
                                            <i class="bi bi-arrow-repeat"></i>
                                            Otomatis dari tagihan
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="text-end text-nowrap">
                                @if ($t->isIncome())
                                    <span class="{{ $t->isVoid() ? '' : 'finance-amount-income' }}">
                                        {{ $t->formattedAmount() }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-end text-nowrap">
                                @if (!$t->isIncome())
                                    <span class="{{ $t->isVoid() ? '' : 'finance-amount-expense' }}">
                                        {{ $t->formattedAmount() }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-end text-nowrap">
                                <div class="finance-row-actions">
                                    <a href="{{ route('admin.finance.show', $t) }}"
                                       class="finance-action-btn"
                                       title="Lihat detail"
                                       aria-label="Lihat detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if ($canManage && $t->isEditable())
                                        <a href="{{ route('admin.finance.edit', $t) }}"
                                           class="finance-action-btn"
                                           title="Ubah transaksi"
                                           aria-label="Ubah transaksi">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="finance-empty-state">
                                <div class="finance-empty-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <strong>Belum Ada Transaksi</strong>
                                <p>Belum ada transaksi pada periode atau filter yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="finance-pagination">
                {{ $transactions->links() }}
            </div>
        @endif
    </section>

</div>
@endsection
