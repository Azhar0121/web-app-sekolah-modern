@extends('layouts.admin')

@section('title', 'Keuangan Sekolah')

@section('content')

@use('App\Models\FinanceTransaction', 'Tx')

@php($queryString = request()->query())

<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <span class="text-uppercase small fw-semibold text-primary">Portal Tata Usaha</span>
            <h3 class="fw-bold mb-1">Keuangan Sekolah</h3>
            <p class="text-muted mb-0">Catat dan pantau kas masuk serta kas keluar sekolah secara terpusat.</p>
        </div>

        @if ($canManage)
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.finance.create', ['type' => 'income']) }}" class="btn btn-success">
                    <i class="bi bi-arrow-down-circle me-1"></i> Catat Kas Masuk
                </a>
                <a href="{{ route('admin.finance.create', ['type' => 'expense']) }}" class="btn btn-danger">
                    <i class="bi bi-arrow-up-circle me-1"></i> Catat Kas Keluar
                </a>
            </div>
        @endif
    </div>

    @include('admin.finance._nav', ['active' => 'transaksi'])

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Pemasukan periode ini</div>
                    <div class="fs-4 fw-bold text-success">{{ Tx::rupiah($totalIncome) }}</div>
                    <div class="small text-muted">{{ Tx::tanggal($filters['from'], true) }} - {{ Tx::tanggal($filters['to'], true) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Pengeluaran periode ini</div>
                    <div class="fs-4 fw-bold text-danger">{{ Tx::rupiah($totalExpense) }}</div>
                    <div class="small text-muted">Hanya transaksi sah</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Selisih periode</div>
                    @php($net = $totalIncome - $totalExpense)
                    <div class="fs-4 fw-bold {{ $net >= 0 ? 'text-success' : 'text-danger' }}">{{ Tx::rupiah($net) }}</div>
                    <div class="small text-muted">{{ $net >= 0 ? 'Surplus' : 'Defisit' }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body">
                    <div class="small opacity-75">Saldo kas s.d. {{ Tx::tanggal($filters['to'], true) }}</div>
                    <div class="fs-4 fw-bold">{{ Tx::rupiah($cashBalance) }}</div>
                    <div class="small opacity-75">Akumulasi seluruh transaksi sah</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.finance.index') }}" class="row g-3 align-items-end">
                <div class="col-6 col-lg-2">
                    <label for="from" class="form-label small mb-1">Dari tanggal</label>
                    <input type="date" id="from" name="from" class="form-control" value="{{ $filters['from']->toDateString() }}">
                </div>
                <div class="col-6 col-lg-2">
                    <label for="to" class="form-label small mb-1">Sampai tanggal</label>
                    <input type="date" id="to" name="to" class="form-control" value="{{ $filters['to']->toDateString() }}">
                </div>
                <div class="col-6 col-lg-2">
                    <label for="type" class="form-label small mb-1">Jenis</label>
                    <select id="type" name="type" class="form-select">
                        <option value="">Semua</option>
                        <option value="income" @selected($filters['type'] === 'income')>Pemasukan</option>
                        <option value="expense" @selected($filters['type'] === 'expense')>Pengeluaran</option>
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <label for="category_id" class="form-label small mb-1">Kategori</label>
                    <select id="category_id" name="category_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($categories->groupBy('type') as $type => $group)
                            <optgroup label="{{ $type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}">
                                @foreach ($group as $category)
                                    <option value="{{ $category->id }}" @selected((string) $filters['category_id'] === (string) $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <label for="status" class="form-label small mb-1">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="posted" @selected($filters['status'] === 'posted')>Sah</option>
                        <option value="void" @selected($filters['status'] === 'void')>Dibatalkan</option>
                        <option value="all" @selected($filters['status'] === 'all')>Semua</option>
                    </select>
                </div>
                <div class="col-12 col-lg-8">
                    <label for="search" class="form-label small mb-1">Cari</label>
                    <input type="text" id="search" name="search" class="form-control" value="{{ $filters['search'] }}"
                           placeholder="No. bukti, uraian, nama barang, pihak terkait...">
                </div>
                <div class="col-12 col-lg-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1"></i> Terapkan
                    </button>
                    <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary">Reset</a>
                    <a href="{{ route('admin.finance.export', $queryString) }}" class="btn btn-outline-success" title="Unduh CSV (bisa dibuka di Excel)">
                        <i class="bi bi-download"></i> CSV
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
            <strong>Daftar Transaksi</strong>

            @if ($canManage)
                <form method="POST" action="{{ route('admin.finance.sync-billing') }}"
                      onsubmit="return confirm('Catat semua pembayaran tagihan siswa yang sudah lunas tetapi belum masuk ke kas?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-repeat me-1"></i> Sinkronkan Tagihan Lunas
                    </button>
                </form>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Bukti</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Uraian</th>
                        <th class="text-end">Pemasukan</th>
                        <th class="text-end">Pengeluaran</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $t)
                        <tr class="{{ $t->isVoid() ? 'text-muted' : '' }}">
                            <td>
                                <a href="{{ route('admin.finance.show', $t) }}" class="fw-semibold text-decoration-none {{ $t->isVoid() ? 'text-muted' : '' }}">{{ $t->number }}</a>
                                @if ($t->isVoid())
                                    <span class="badge text-bg-secondary ms-1">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="text-nowrap">{{ $t->transaction_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $t->isIncome() ? 'text-bg-success' : 'text-bg-danger' }} bg-opacity-75">{{ $t->category?->name ?? '-' }}</span>
                            </td>
                            <td style="min-width: 240px;">
                                <div class="{{ $t->isVoid() ? 'text-decoration-line-through' : '' }}">{{ $t->description }}</div>
                                <div class="small text-muted">
                                    @if ($t->item_name)
                                        {{ $t->item_name }}@if ($t->quantity) &times; {{ $t->quantity }}@endif
                                        @if ($t->room) &middot; {{ $t->room->name }} @endif
                                    @elseif ($t->party)
                                        {{ $t->party }}
                                    @endif
                                    @if ($t->isFromBilling())
                                        <span class="badge text-bg-info">Otomatis dari tagihan</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end text-nowrap {{ $t->isVoid() ? '' : 'text-success fw-semibold' }}">
                                {{ $t->isIncome() ? $t->formattedAmount() : '' }}
                            </td>
                            <td class="text-end text-nowrap {{ $t->isVoid() ? '' : 'text-danger fw-semibold' }}">
                                {{ $t->isIncome() ? '' : $t->formattedAmount() }}
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.finance.show', $t) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if ($canManage && $t->isEditable())
                                    <a href="{{ route('admin.finance.edit', $t) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Belum ada transaksi pada periode / filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="card-footer bg-white">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
