@extends('layouts.admin')

@section('title', 'Inventaris & Sarana Prasarana')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/inventory/index.css') }}">

<div class="inventory-page">

    {{-- HEADER --}}
    <section class="inventory-page-header">
        <div class="inventory-header-copy">
            <div class="inventory-eyebrow">
                <i class="bi bi-buildings-fill"></i>
                OPERASIONAL &amp; TATA USAHA
            </div>

            <h2>Inventaris &amp; Sarana Prasarana</h2>

            <p>
                Kelola aset sekolah, peralatan laboratorium, perangkat IT,
                mebel, serta kondisi sarana di setiap ruangan.
            </p>
        </div>

        <button
            type="button"
            class="btn inventory-add-btn"
            data-bs-toggle="modal"
            data-bs-target="#addInventoryModal"
        >
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Barang Baru</span>
        </button>
    </section>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert inventory-alert inventory-alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert inventory-alert inventory-alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>

            <div>
                <strong>Data belum dapat diproses.</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- STATISTIK --}}
    <section class="inventory-stat-grid">

        <div class="inventory-stat-card stat-total">
            <div class="inventory-stat-top">
                <span class="inventory-stat-label">TOTAL BARANG</span>
                <div class="inventory-stat-icon">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
            </div>

            <div class="inventory-stat-number">
                {{ number_format($totalItems, 0, ',', '.') }}
            </div>

            <div class="inventory-stat-foot">
                <span class="stat-foot-dot"></span>
                Seluruh aset yang tercatat
            </div>
        </div>

        <div class="inventory-stat-card stat-good">
            <div class="inventory-stat-top">
                <span class="inventory-stat-label">KONDISI BAIK</span>
                <div class="inventory-stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>

            <div class="inventory-stat-number">
                {{ number_format($goodCondition, 0, ',', '.') }}
            </div>

            <div class="inventory-stat-foot">
                <span class="stat-foot-dot"></span>
                Siap dan layak digunakan
            </div>
        </div>

        <div class="inventory-stat-card stat-light-damage">
            <div class="inventory-stat-top">
                <span class="inventory-stat-label">RUSAK RINGAN</span>
                <div class="inventory-stat-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>

            <div class="inventory-stat-number">
                {{ number_format($lightDamage, 0, ',', '.') }}
            </div>

            <div class="inventory-stat-foot">
                <span class="stat-foot-dot"></span>
                Perlu perbaikan berkala
            </div>
        </div>

        <div class="inventory-stat-card stat-heavy-damage">
            <div class="inventory-stat-top">
                <span class="inventory-stat-label">RUSAK BERAT / HILANG</span>
                <div class="inventory-stat-icon">
                    <i class="bi bi-x-octagon-fill"></i>
                </div>
            </div>

            <div class="inventory-stat-number">
                {{ number_format($heavyDamage + $lostCount, 0, ',', '.') }}
            </div>

            <div class="inventory-stat-foot">
                <span class="stat-foot-dot"></span>
                Perlu tindak lanjut
            </div>
        </div>

    </section>

    {{-- FILTER --}}
    <section class="inventory-filter-card">
        <div class="inventory-section-heading">
            <div class="inventory-section-icon">
                <i class="bi bi-sliders2"></i>
            </div>

            <div>
                <h5>Filter Inventaris</h5>
                <p>Cari aset berdasarkan nama, lokasi, kategori, atau kondisi.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.inventory.index') }}" class="inventory-filter-form">

            <div class="inventory-filter-grid">

                <div class="inventory-field inventory-search-field">
                    <label for="search">CARI BARANG / KODE</label>

                    <div class="inventory-input-wrap">
                        <i class="bi bi-search"></i>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Nama atau kode barang..."
                            value="{{ $search }}"
                        >
                    </div>
                </div>

                <div class="inventory-field">
                    <label for="room_id">RUANGAN / LOKASI</label>

                    <select name="room_id" id="room_id" class="form-select">
                        <option value="">Semua Ruangan</option>
                        @foreach ($rooms as $r)
                            <option
                                value="{{ $r->id }}"
                                @selected((string) $roomId === (string) $r->id)
                            >
                                {{ $r->name }} ({{ $r->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="inventory-field">
                    <label for="category_id">KATEGORI ASET</label>

                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option
                                value="{{ $cat->id }}"
                                @selected((string) $categoryId === (string) $cat->id)
                            >
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="inventory-field">
                    <label for="condition">KONDISI</label>

                    <select name="condition" id="condition" class="form-select">
                        <option value="">Semua Kondisi</option>
                        <option value="good" @selected($condition === 'good')>Baik</option>
                        <option value="light_damage" @selected($condition === 'light_damage')>Rusak Ringan</option>
                        <option value="heavy_damage" @selected($condition === 'heavy_damage')>Rusak Berat</option>
                        <option value="lost" @selected($condition === 'lost')>Hilang</option>
                    </select>
                </div>

            </div>

            <div class="inventory-filter-actions">
                @if ($search || $roomId || $categoryId || $condition)
                    <a href="{{ route('admin.inventory.index') }}" class="btn inventory-reset-btn">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset Filter
                    </a>
                @endif

                <button type="submit" class="btn inventory-filter-btn">
                    <i class="bi bi-funnel-fill"></i>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </section>

    {{-- TABEL INVENTARIS --}}
    <section class="inventory-table-card">

        <div class="inventory-table-heading">
            <div class="inventory-section-heading">
                <div class="inventory-section-icon inventory-table-heading-icon">
                    <i class="bi bi-list-ul"></i>
                </div>

                <div>
                    <h5>Daftar Inventaris</h5>
                    <p>Data aset dan sarana prasarana sekolah.</p>
                </div>
            </div>

            <div class="inventory-table-count">
                <i class="bi bi-box-seam"></i>
                {{ $items->total() }} data
            </div>
        </div>

        <div class="table-responsive">
            <table class="table inventory-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="inventory-col-code">KODE ASET</th>
                        <th>NAMA BARANG &amp; SPESIFIKASI</th>
                        <th>KATEGORI</th>
                        <th>RUANGAN / LOKASI</th>
                        <th class="text-center">JUMLAH</th>
                        <th class="text-center">KONDISI</th>
                        <th>SUMBER DANA</th>
                        <th class="text-end">AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>
                                <span class="inventory-asset-code">
                                    {{ $item->code }}
                                </span>
                            </td>

                            <td>
                                <div class="inventory-item-name">
                                    {{ $item->name }}
                                </div>

                                @if ($item->notes)
                                    <div class="inventory-item-notes" title="{{ $item->notes }}">
                                        {{ $item->notes }}
                                    </div>
                                @else
                                    <div class="inventory-item-notes inventory-muted">
                                        Tidak ada catatan
                                    </div>
                                @endif
                            </td>

                            <td>
                                <span class="inventory-category">
                                    <i class="bi bi-grid-fill"></i>
                                    {{ $item->category?->name ?? '-' }}
                                </span>
                            </td>

                            <td>
                                @if ($item->room)
                                    <div class="inventory-location">
                                        <span class="inventory-location-icon">
                                            <i class="bi bi-door-closed-fill"></i>
                                        </span>

                                        <div>
                                            <div class="inventory-location-name">
                                                {{ $item->room->name }}
                                            </div>
                                            <div class="inventory-location-code">
                                                {{ $item->room->code }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inventory-storage-label">
                                        <i class="bi bi-house-door-fill"></i>
                                        Gudang / Belum Ditempatkan
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="inventory-quantity">
                                    {{ number_format($item->quantity, 0, ',', '.') }}
                                </div>
                                <div class="inventory-unit">{{ $item->unit }}</div>
                            </td>

                            <td class="text-center">
                                <span class="badge inventory-condition-badge {{ $item->conditionBadgeClass() }}">
                                    {{ $item->conditionLabel() }}
                                </span>
                            </td>

                            <td>
                                <div class="inventory-fund">
                                    {{ $item->source_of_fund ?? '-' }}
                                </div>

                                @if ($item->procurement_year)
                                    <div class="inventory-year">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $item->procurement_year }}
                                    </div>
                                @endif
                            </td>

                            <td class="text-end">
                                <a
                                    href="{{ route('admin.inventory.show', $item->id) }}"
                                    class="btn inventory-detail-btn"
                                    title="Lihat detail barang"
                                >
                                    <i class="bi bi-eye-fill"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="inventory-empty-state">
                                    <div class="inventory-empty-icon">
                                        <i class="bi bi-inboxes"></i>
                                    </div>

                                    <h6>Data inventaris tidak ditemukan</h6>
                                    <p>Belum ada barang yang sesuai dengan pencarian atau filter yang dipilih.</p>

                                    @if ($search || $roomId || $categoryId || $condition)
                                        <a href="{{ route('admin.inventory.index') }}" class="btn inventory-reset-btn">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                            Tampilkan Semua Data
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="inventory-pagination">
                <div class="inventory-pagination-info">
                    Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }}
                    dari {{ $items->total() }} barang
                </div>

                <div>
                    {{ $items->links() }}
                </div>
            </div>
        @endif

    </section>

</div>

{{-- MODAL TAMBAH INVENTARIS --}}
<div
    class="modal fade inventory-modal"
    id="addInventoryModal"
    tabindex="-1"
    aria-labelledby="addInventoryModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header inventory-modal-header">
                <div class="inventory-modal-heading">
                    <div class="inventory-modal-icon">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>

                    <div>
                        <h5 class="modal-title" id="addInventoryModalLabel">
                            Tambah Aset Inventaris
                        </h5>
                        <p>Kode aset akan dibuat otomatis oleh sistem.</p>
                    </div>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>

            <form
                action="{{ route('admin.inventory.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="modal-body inventory-modal-body">

                    <div class="inventory-form-section">
                        <div class="inventory-form-section-title">
                            <span class="inventory-form-section-number">
                                <i class="bi bi-box-seam"></i>
                            </span>
                            <div>
                                <h6>Identitas Aset</h6>
                                <p>Informasi utama barang inventaris.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-8">
                                <label for="name" class="form-label">
                                    Nama Barang / Aset <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control"
                                    placeholder="Contoh: Kursi Siswa atau Komputer"
                                    required
                                    maxlength="255"
                                >
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="modal_category_id" class="form-label mb-0">
                                        Kategori <span class="text-danger">*</span>
                                    </label>
                                    <button
                                        type="button"
                                        class="btn btn-link p-0 inventory-quick-add-link"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#quickAddCategoryCollapse"
                                        aria-expanded="false"
                                    >
                                        <i class="bi bi-plus-circle"></i>
                                        <span>Kategori Baru</span>
                                    </button>
                                </div>

                                <select
                                    name="inventory_category_id"
                                    id="modal_category_id"
                                    class="form-select"
                                    required
                                >
                                    <option value="">Pilih kategori</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>

                                {{-- Inline Box Tambah Kategori Cepat --}}
                                <div class="collapse mt-2" id="quickAddCategoryCollapse">
                                    <div class="p-2.5 rounded border shadow-sm" style="background: #f8fafc; border-color: #cbd5e1 !important; padding: 10px;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold" style="font-size: 11.5px; color: var(--inv-navy, #102a49);">
                                                <i class="bi bi-tag-fill me-1 text-primary"></i> Buat Kategori Baru
                                            </span>
                                            <button type="button" class="btn-close" style="font-size: 9px;" data-bs-toggle="collapse" data-bs-target="#quickAddCategoryCollapse"></button>
                                        </div>
                                        <div class="mb-2">
                                            <input
                                                type="text"
                                                id="quick_category_name"
                                                class="form-control form-control-sm"
                                                style="font-size: 12px;"
                                                placeholder="Contoh: Alat Kebersihan, Sarana UKS"
                                            >
                                            <div class="invalid-feedback d-none" id="quick_category_error" style="font-size: 11px;"></div>
                                        </div>
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" class="btn btn-sm btn-light border py-1 px-2" style="font-size: 11px;" data-bs-toggle="collapse" data-bs-target="#quickAddCategoryCollapse">
                                                Batal
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 11px;" id="btnSubmitQuickCategory">
                                                <i class="bi bi-check2"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="modal_room_id" class="form-label mb-0">Lokasi / Ruangan</label>
                                    <button
                                        type="button"
                                        class="btn btn-link p-0 inventory-quick-add-link"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#quickAddRoomCollapse"
                                        aria-expanded="false"
                                    >
                                        <i class="bi bi-plus-circle"></i>
                                        <span>Ruangan Baru</span>
                                    </button>
                                </div>

                                <select name="room_id" id="modal_room_id" class="form-select">
                                    <option value="">Gudang / Belum Ditempatkan</option>
                                    @foreach ($rooms as $r)
                                        <option value="{{ $r->id }}">
                                            {{ $r->name }} ({{ $r->code }})
                                        </option>
                                    @endforeach
                                </select>

                                <div class="form-text">Pilih lokasi penyimpanan aset.</div>

                                {{-- Inline Box Tambah Ruangan Cepat --}}
                                <div class="collapse mt-2" id="quickAddRoomCollapse">
                                    <div class="p-2.5 rounded border shadow-sm" style="background: #f8fafc; border-color: #cbd5e1 !important; padding: 10px;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold" style="font-size: 11.5px; color: var(--inv-navy, #102a49);">
                                                <i class="bi bi-door-open-fill me-1 text-primary"></i> Buat Ruangan Baru
                                            </span>
                                            <button type="button" class="btn-close" style="font-size: 9px;" data-bs-toggle="collapse" data-bs-target="#quickAddRoomCollapse"></button>
                                        </div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-12 col-sm-8">
                                                <input
                                                    type="text"
                                                    id="quick_room_name"
                                                    class="form-control form-control-sm"
                                                    style="font-size: 12px;"
                                                    placeholder="Nama ruangan (cth: Gudang B, Ruang Robotik)"
                                                >
                                                <div class="invalid-feedback d-none" id="quick_room_error" style="font-size: 11px;"></div>
                                            </div>
                                            <div class="col-12 col-sm-4">
                                                <input
                                                    type="text"
                                                    id="quick_room_code"
                                                    class="form-control form-control-sm text-uppercase"
                                                    style="font-size: 12px;"
                                                    placeholder="Kode (opsional)"
                                                >
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" class="btn btn-sm btn-light border py-1 px-2" style="font-size: 11px;" data-bs-toggle="collapse" data-bs-target="#quickAddRoomCollapse">
                                                Batal
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 11px;" id="btnSubmitQuickRoom">
                                                <i class="bi bi-check2"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="modal_condition" class="form-label">
                                    Kondisi Fisik Awal <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="condition"
                                    id="modal_condition"
                                    class="form-select"
                                    required
                                >
                                    <option value="good" selected>Baik (Siap Pakai)</option>
                                    <option value="light_damage">Rusak Ringan</option>
                                    <option value="heavy_damage">Rusak Berat</option>
                                    <option value="lost">Hilang</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="inventory-form-section">
                        <div class="inventory-form-section-title">
                            <span class="inventory-form-section-number">
                                <i class="bi bi-calculator"></i>
                            </span>
                            <div>
                                <h6>Jumlah &amp; Pengadaan</h6>
                                <p>Kuantitas, nilai, dan sumber pengadaan aset.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label for="quantity" class="form-label">
                                    Jumlah <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    class="form-control"
                                    value="1"
                                    min="1"
                                    required
                                >
                            </div>

                            <div class="col-6 col-md-3">
                                <label for="unit" class="form-label">
                                    Satuan <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="unit"
                                    id="unit"
                                    class="form-control"
                                    value="unit"
                                    placeholder="Unit / buah"
                                    required
                                >
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="price_per_unit" class="form-label">
                                    Harga Satuan (Rp)
                                    <span class="inventory-optional">Opsional</span>
                                </label>

                                <input
                                    type="number"
                                    name="price_per_unit"
                                    id="price_per_unit"
                                    class="form-control"
                                    placeholder="Contoh: 350000"
                                    min="0"
                                >
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="source_of_fund" class="form-label">Sumber Dana</label>

                                <input
                                    type="text"
                                    name="source_of_fund"
                                    id="source_of_fund"
                                    class="form-control"
                                    placeholder="Dana BOS / BOP / Komite"
                                >
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="procurement_year" class="form-label">Tahun Pengadaan</label>

                                <input
                                    type="number"
                                    name="procurement_year"
                                    id="procurement_year"
                                    class="form-control"
                                    value="{{ date('Y') }}"
                                    min="2000"
                                    max="{{ date('Y') + 1 }}"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="inventory-form-section inventory-form-section-last">
                        <div class="inventory-form-section-title">
                            <span class="inventory-form-section-number">
                                <i class="bi bi-card-text"></i>
                            </span>
                            <div>
                                <h6>Dokumentasi &amp; Catatan</h6>
                                <p>Tambahkan foto dan spesifikasi jika tersedia.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="photo" class="form-label">
                                    Foto Fisik Barang
                                    <span class="inventory-optional">Opsional · Maks. 4 MB</span>
                                </label>

                                <input
                                    type="file"
                                    name="photo"
                                    id="photo"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png"
                                >

                                <div class="form-text">Format yang didukung: JPG, JPEG, dan PNG.</div>
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">Catatan / Spesifikasi Teknis</label>

                                <textarea
                                    name="notes"
                                    id="notes"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Merek, seri, nomor seri, atau keterangan spesifikasi lainnya..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer inventory-modal-footer">
                    <button type="button" class="btn inventory-cancel-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        <span>Batal</span>
                    </button>

                    <button type="submit" class="btn inventory-save-btn">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Simpan Aset Baru</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quickCategoryUrl = "{{ route('admin.inventory.categories.quick-store') }}";
    const quickRoomUrl     = "{{ route('admin.inventory.rooms.quick-store') }}";
    const csrfToken        = "{{ csrf_token() }}";

    // Toast Alert Helper
    function showQuickAlert(message, type = 'success') {
        const alertBox = document.createElement('div');
        alertBox.className = `alert alert-${type} alert-dismissible fade show position-fixed shadow`;
        alertBox.style.cssText = 'top: 24px; right: 24px; z-index: 9999; min-width: 300px; max-width: 420px; font-size: 13px; font-weight: 500;';
        alertBox.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill text-success fs-5' : 'bi-exclamation-triangle-fill text-danger fs-5'}"></i>
                <div class="flex-grow-1">${message}</div>
                <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.body.appendChild(alertBox);
        setTimeout(() => {
            alertBox.classList.remove('show');
            setTimeout(() => alertBox.remove(), 300);
        }, 3500);
    }

    // ==========================================
    // 1. INLINE QUICK ADD KATEGORI
    // ==========================================
    const btnSubmitCategory = document.getElementById('btnSubmitQuickCategory');
    const inputCategoryName = document.getElementById('quick_category_name');
    const errorCategory     = document.getElementById('quick_category_error');
    const categoryCollapse  = document.getElementById('quickAddCategoryCollapse');

    if (btnSubmitCategory && inputCategoryName) {
        btnSubmitCategory.addEventListener('click', async function () {
            const name = inputCategoryName.value.trim();
            if (!name) {
                inputCategoryName.classList.add('is-invalid');
                errorCategory.textContent = 'Nama kategori wajib diisi.';
                errorCategory.classList.remove('d-none');
                inputCategoryName.focus();
                return;
            }

            inputCategoryName.classList.remove('is-invalid');
            errorCategory.classList.add('d-none');
            btnSubmitCategory.disabled = true;
            btnSubmitCategory.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

            try {
                const response = await fetch(quickCategoryUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ name: name })
                });

                const result = await response.json();

                if (!response.ok) {
                    const errMsg = result.errors?.name?.[0] || result.message || 'Gagal menyimpan kategori.';
                    inputCategoryName.classList.add('is-invalid');
                    errorCategory.textContent = errMsg;
                    errorCategory.classList.remove('d-none');
                    return;
                }

                // Tambahkan opsi ke dropdown kategori dan pilih otomatis
                const catSelect = document.getElementById('modal_category_id');
                if (catSelect) {
                    let existingOpt = Array.from(catSelect.options).find(opt => opt.value == result.data.id);
                    if (!existingOpt) {
                        existingOpt = new Option(result.data.name, result.data.id, true, true);
                        catSelect.add(existingOpt);
                    }
                    catSelect.value = result.data.id;
                }

                inputCategoryName.value = '';
                const bsCollapse = bootstrap.Collapse.getInstance(categoryCollapse) || new bootstrap.Collapse(categoryCollapse, { toggle: false });
                bsCollapse.hide();

                showQuickAlert(result.message, 'success');
            } catch (err) {
                inputCategoryName.classList.add('is-invalid');
                errorCategory.textContent = 'Terjadi kesalahan saat memproses data.';
                errorCategory.classList.remove('d-none');
            } finally {
                btnSubmitCategory.disabled = false;
                btnSubmitCategory.innerHTML = '<i class="bi bi-check2"></i> Simpan';
            }
        });

        inputCategoryName.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                btnSubmitCategory.click();
            }
        });
    }

    // ==========================================
    // 2. INLINE QUICK ADD RUANGAN
    // ==========================================
    const btnSubmitRoom  = document.getElementById('btnSubmitQuickRoom');
    const inputRoomName  = document.getElementById('quick_room_name');
    const inputRoomCode  = document.getElementById('quick_room_code');
    const errorRoom      = document.getElementById('quick_room_error');
    const roomCollapse   = document.getElementById('quickAddRoomCollapse');

    if (btnSubmitRoom && inputRoomName) {
        btnSubmitRoom.addEventListener('click', async function () {
            const name = inputRoomName.value.trim();
            const code = inputRoomCode ? inputRoomCode.value.trim() : '';

            if (!name) {
                inputRoomName.classList.add('is-invalid');
                errorRoom.textContent = 'Nama ruangan wajib diisi.';
                errorRoom.classList.remove('d-none');
                inputRoomName.focus();
                return;
            }

            inputRoomName.classList.remove('is-invalid');
            errorRoom.classList.add('d-none');
            btnSubmitRoom.disabled = true;
            btnSubmitRoom.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

            try {
                const response = await fetch(quickRoomUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ name: name, code: code })
                });

                const result = await response.json();

                if (!response.ok) {
                    const errMsg = result.errors?.name?.[0] || result.errors?.code?.[0] || result.message || 'Gagal menyimpan ruangan.';
                    inputRoomName.classList.add('is-invalid');
                    errorRoom.textContent = errMsg;
                    errorRoom.classList.remove('d-none');
                    return;
                }

                // Tambahkan opsi ke dropdown ruangan dan pilih otomatis
                const roomSelect = document.getElementById('modal_room_id');
                if (roomSelect) {
                    let existingOpt = Array.from(roomSelect.options).find(opt => opt.value == result.data.id);
                    if (!existingOpt) {
                        existingOpt = new Option(result.data.label, result.data.id, true, true);
                        roomSelect.add(existingOpt);
                    }
                    roomSelect.value = result.data.id;
                }

                inputRoomName.value = '';
                if (inputRoomCode) inputRoomCode.value = '';
                const bsCollapse = bootstrap.Collapse.getInstance(roomCollapse) || new bootstrap.Collapse(roomCollapse, { toggle: false });
                bsCollapse.hide();

                showQuickAlert(result.message, 'success');
            } catch (err) {
                inputRoomName.classList.add('is-invalid');
                errorRoom.textContent = 'Terjadi kesalahan saat memproses data.';
                errorRoom.classList.remove('d-none');
            } finally {
                btnSubmitRoom.disabled = false;
                btnSubmitRoom.innerHTML = '<i class="bi bi-check2"></i> Simpan';
            }
        });

        inputRoomName.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                btnSubmitRoom.click();
            }
        });
    }
});
</script>
@endpush