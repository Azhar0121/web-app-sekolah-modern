@extends('layouts.admin')

@section('title', 'Detail Inventaris — ' . $item->name)

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/inventory/show.css') }}">

<div class="inventory-detail">

    {{-- HEADER --}}
    <section class="inventory-detail__hero">
        <div class="inventory-detail__hero-main">
            <a href="{{ route('admin.inventory.index') }}" class="inventory-back">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Daftar
            </a>

            <div class="inventory-detail__eyebrow">
                <i class="bi bi-box-seam"></i>
                MANAJEMEN INVENTARIS
            </div>

            <h2>{{ $item->name }}</h2>

            <p>
                Informasi spesifikasi, penempatan ruangan, kondisi fisik,
                dan nilai aset inventaris sekolah.
            </p>

            <div class="inventory-detail__hero-meta">
                <span class="inventory-code">
                    <i class="bi bi-upc-scan"></i>
                    {{ $item->code }}
                </span>

                <span class="inventory-hero-status {{ $item->conditionBadgeClass() }}">
                    <span class="inventory-status-dot"></span>
                    {{ $item->conditionLabel() }}
                </span>
            </div>
        </div>

        <div class="inventory-detail__actions">
            <button type="button"
                    class="inventory-action inventory-action--move"
                    data-bs-toggle="modal"
                    data-bs-target="#mutateInventoryModal">
                <i class="bi bi-arrow-left-right"></i>
                <span>Mutasi Ruangan</span>
            </button>

            <button type="button"
                    class="inventory-action inventory-action--edit"
                    data-bs-toggle="modal"
                    data-bs-target="#editInventoryModal">
                <i class="bi bi-pencil-square"></i>
                <span>Ubah Data</span>
            </button>

            <button type="button"
                    class="inventory-action inventory-action--delete"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteInventoryModal">
                <i class="bi bi-trash3"></i>
                <span>Hapus Aset</span>
            </button>
        </div>
    </section>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm inventory-alert" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm inventory-alert" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Data belum berhasil diproses.</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- KONTEN UTAMA --}}
    <div class="row g-4">

        {{-- KOLOM KIRI --}}
        <div class="col-12 col-xl-4">

            {{-- FOTO ASET --}}
            <section class="inventory-panel inventory-photo-panel">
                <div class="inventory-panel__heading">
                    <div class="inventory-heading-icon inventory-heading-icon--blue">
                        <i class="bi bi-image"></i>
                    </div>

                    <div>
                        <h5>Foto Fisik Barang</h5>
                        <p>Dokumentasi visual aset</p>
                    </div>
                </div>

                <div class="inventory-photo-body">
                    @if ($item->photoUrl())
                        <div class="inventory-photo-frame">
                            <img src="{{ $item->photoUrl() }}"
                                 alt="Foto {{ $item->name }}"
                                 class="inventory-photo">
                        </div>
                    @else
                        <div class="inventory-photo-empty">
                            <div class="inventory-photo-empty__icon">
                                <i class="bi bi-camera"></i>
                            </div>
                            <strong>Foto belum tersedia</strong>
                            <span>Belum ada dokumentasi fisik barang.</span>
                        </div>
                    @endif

                    <div class="inventory-record">
                        <div class="inventory-record__item">
                            <span><i class="bi bi-person-check"></i> Dicatat oleh</span>
                            <strong>{{ $item->creator?->name ?? 'Sistem / Admin' }}</strong>
                        </div>

                        <div class="inventory-record__item">
                            <span><i class="bi bi-calendar-plus"></i> Waktu input</span>
                            <strong>
                                {{ $item->created_at ? $item->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </strong>
                        </div>

                        <div class="inventory-record__item">
                            <span><i class="bi bi-arrow-repeat"></i> Terakhir diperbarui</span>
                            <strong>
                                {{ $item->updated_at ? $item->updated_at->translatedFormat('d M Y, H:i') : '-' }}
                            </strong>
                        </div>
                    </div>
                </div>
            </section>

            {{-- IDENTITAS ASET --}}
            <section class="inventory-panel inventory-identity-panel">
                <div class="inventory-panel__heading">
                    <div class="inventory-heading-icon inventory-heading-icon--navy">
                        <i class="bi bi-upc-scan"></i>
                    </div>

                    <div>
                        <h5>Identitas Aset</h5>
                        <p>Kode dan jumlah inventaris</p>
                    </div>
                </div>

                <div class="inventory-identity-body">
                    <div class="inventory-identity-code">
                        <span>KODE INVENTARIS</span>
                        <strong>{{ $item->code }}</strong>
                        <div class="inventory-code-decoration">
                            <i class="bi bi-upc"></i>
                        </div>
                    </div>

                    <div class="inventory-identity-row">
                        <span>Kondisi aset</span>
                        <span class="badge {{ $item->conditionBadgeClass() }}">
                            {{ $item->conditionLabel() }}
                        </span>
                    </div>

                    <div class="inventory-identity-row">
                        <span>Kuantitas tersedia</span>
                        <strong>{{ $item->quantity }} {{ $item->unit }}</strong>
                    </div>
                </div>
            </section>

        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-12 col-xl-8">

            {{-- RINCIAN ASET --}}
            <section class="inventory-panel inventory-information-panel">

                <div class="inventory-panel__heading inventory-panel__heading--main">
                    <div class="inventory-heading-icon inventory-heading-icon--blue">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>
                        <h5>Rincian Aset</h5>
                        <p>Informasi barang dan lokasi penempatan</p>
                    </div>
                </div>

                <div class="inventory-section">
                    <div class="inventory-section__title">
                        <span class="inventory-section__number">01</span>
                        <h6>Informasi Barang</h6>
                    </div>

                    <div class="inventory-info-grid">
                        <div class="inventory-info-item">
                            <span class="inventory-info-label">
                                <i class="bi bi-tag"></i> Kategori barang
                            </span>
                            <strong>{{ $item->category?->name ?? '-' }}</strong>
                        </div>

                        <div class="inventory-info-item inventory-info-item--location">
                            <span class="inventory-info-label">
                                <i class="bi bi-geo-alt"></i> Ruangan / lokasi
                            </span>
                            <strong>
                                {{ $item->room ? "{$item->room->name} ({$item->room->code})" : 'Gudang / Belum ditempatkan' }}
                            </strong>
                        </div>

                        <div class="inventory-info-item">
                            <span class="inventory-info-label">
                                <i class="bi bi-boxes"></i> Jumlah kuantitas
                            </span>
                            <strong>{{ $item->quantity }} {{ $item->unit }}</strong>
                        </div>

                        <div class="inventory-info-item">
                            <span class="inventory-info-label">
                                <i class="bi bi-shield-check"></i> Kondisi fisik
                            </span>
                            <div>
                                <span class="badge {{ $item->conditionBadgeClass() }}">
                                    {{ $item->conditionLabel() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="inventory-section">
                    <div class="inventory-section__title">
                        <span class="inventory-section__number">02</span>
                        <h6>Anggaran & Pengadaan</h6>
                    </div>

                    <div class="inventory-info-grid inventory-info-grid--three">
                        <div class="inventory-info-item">
                            <span class="inventory-info-label">
                                <i class="bi bi-wallet2"></i> Sumber dana
                            </span>
                            <strong>{{ $item->source_of_fund ?: '-' }}</strong>
                        </div>

                        <div class="inventory-info-item">
                            <span class="inventory-info-label">
                                <i class="bi bi-calendar3"></i> Tahun pengadaan
                            </span>
                            <strong>{{ $item->procurement_year ?: '-' }}</strong>
                        </div>

                        <div class="inventory-info-item">
                            <span class="inventory-info-label">
                                <i class="bi bi-cash-stack"></i> Harga satuan
                            </span>
                            <strong>{{ $item->formattedPrice() }}</strong>
                        </div>
                    </div>

                    <div class="inventory-total-value">
                        <div class="inventory-total-value__icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>

                        <div>
                            <span>Total Nilai Taksiran Aset</span>
                            <strong>{{ $item->formattedTotalValue() }}</strong>
                            <small>Berdasarkan kuantitas dan harga satuan yang tercatat.</small>
                        </div>
                    </div>
                </div>

                {{-- CATATAN --}}
                <div class="inventory-section inventory-section--last">
                    <div class="inventory-section__title">
                        <span class="inventory-section__number">03</span>
                        <h6>Catatan & Spesifikasi</h6>
                    </div>

                    @if ($item->notes)
                        <div class="inventory-notes">
                            <div class="inventory-notes__icon">
                                <i class="bi bi-journal-text"></i>
                            </div>

                            <div class="inventory-notes__content">
                                {!! nl2br(e($item->notes)) !!}
                            </div>
                        </div>
                    @else
                        <div class="inventory-notes-empty">
                            <i class="bi bi-chat-square-text"></i>
                            <span>Belum ada catatan atau spesifikasi tambahan untuk aset ini.</span>
                        </div>
                    @endif
                </div>

            </section>
        </div>
    </div>
</div>

{{-- =====================================================
     MODAL MUTASI
===================================================== --}}
<div class="modal fade inventory-modal" id="mutateInventoryModal" tabindex="-1"
     aria-labelledby="mutateInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div class="inventory-modal-heading">
                    <div class="inventory-modal-icon inventory-modal-icon--blue">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>

                    <div>
                        <h5 class="modal-title" id="mutateInventoryModalLabel">Mutasi Aset</h5>
                        <p>Pindahkan ruangan atau perbarui kondisi barang.</p>
                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.inventory.mutate', $item) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    <div class="inventory-modal-asset">
                        <span>ASET YANG DIPROSES</span>
                        <strong>{{ $item->name }}</strong>
                        <small>{{ $item->code }}</small>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="mutate_room_id" class="form-label mb-0">
                                Pindah ke Ruangan
                            </label>
                            <button
                                type="button"
                                class="btn btn-link p-0 inventory-quick-add-link"
                                data-bs-toggle="collapse"
                                data-bs-target="#quickAddRoomCollapseMutate"
                                aria-expanded="false"
                            >
                                <i class="bi bi-plus-circle"></i>
                                <span>Ruangan Baru</span>
                            </button>
                        </div>

                        <div class="custom-select-wrap dropdown position-relative" id="wrap_mutate_room">
                            <input type="hidden" name="room_id" id="mutate_room_id" value="{{ old('room_id', $item->room_id) }}">
                            <button
                                type="button"
                                class="custom-select-trigger"
                                data-bs-toggle="dropdown"
                                data-bs-auto-close="outside"
                                aria-expanded="false"
                                id="btn_mutate_room"
                            >
                                <span class="selected-text text-truncate flex-grow-1 {{ is_null(old('room_id', $item->room_id)) ? 'is-placeholder' : '' }}">
                                    {{ $item->room ? $item->room->name . ' (' . $item->room->code . ')' : 'Gudang / Belum Ditempatkan' }}
                                </span>
                                <i class="bi bi-chevron-down chevron-icon"></i>
                            </button>
                            <div class="dropdown-menu custom-select-menu w-100 shadow border">
                                <div class="custom-option-item {{ is_null(old('room_id', $item->room_id)) ? 'active' : '' }}" data-value="" data-label="Gudang / Belum Ditempatkan">
                                    <span class="option-label text-muted"><i class="bi bi-dash-lg me-1"></i>Gudang / Belum Ditempatkan</span>
                                </div>
                                <div class="dropdown-divider my-1"></div>
                                <div class="custom-options-list custom-options-list-room" id="list_mutate_room_options">
                                    @foreach ($rooms as $r)
                                        <div class="custom-option-item {{ old('room_id', $item->room_id) == $r->id ? 'active' : '' }}" data-value="{{ $r->id }}" data-label="{{ $r->name }} ({{ $r->code }})">
                                            <span class="option-label flex-grow-1 text-truncate me-2">
                                                <i class="bi bi-check2 option-check"></i>{{ $r->name }} <small class="text-muted">({{ $r->code }})</small>
                                            </span>
                                            <button type="button" class="btn-delete-opt" data-id="{{ $r->id }}" data-name="{{ $r->name }}" data-type="room" title="Hapus ruangan {{ $r->name }}">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="form-text">Pilih lokasi penempatan aset yang baru.</div>

                        {{-- Inline Box Tambah Ruangan Cepat (Mutasi) --}}
                        <div class="collapse mt-2" id="quickAddRoomCollapseMutate">
                            <div class="p-2.5 rounded border shadow-sm" style="background: #f8fafc; border-color: #cbd5e1 !important; padding: 10px;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold" style="font-size: 11.5px; color: #102a49;">
                                        <i class="bi bi-door-open-fill me-1 text-primary"></i> Buat Ruangan Baru
                                    </span>
                                    <button type="button" class="btn-close btn-close-mutate-room" style="font-size: 9px;" aria-label="Tutup"></button>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-12 col-sm-8">
                                        <input
                                            type="text"
                                            id="quick_mutate_room_name"
                                            class="form-control form-control-sm"
                                            style="font-size: 12px;"
                                            placeholder="Nama ruangan (cth: Gudang B)"
                                        >
                                        <div class="invalid-feedback d-none" id="quick_mutate_room_error" style="font-size: 11px;"></div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <input
                                            type="text"
                                            id="quick_mutate_room_code"
                                            class="form-control form-control-sm text-uppercase"
                                            style="font-size: 12px;"
                                            placeholder="Kode (opsional)"
                                        >
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-1">
                                    <button type="button" class="btn btn-sm btn-light border py-1 px-2 btn-cancel-mutate-room" style="font-size: 11px;">
                                        Batal
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 11px;" id="btnSubmitQuickMutateRoom">
                                        <i class="bi bi-check2"></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mutate_condition" class="form-label">
                            Kondisi Fisik Terkini <span class="text-danger">*</span>
                        </label>

                        <select name="condition" id="mutate_condition" class="form-select" required>
                            <option value="good" {{ $item->condition === 'good' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
                            <option value="light_damage" {{ $item->condition === 'light_damage' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="heavy_damage" {{ $item->condition === 'heavy_damage' ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="lost" {{ $item->condition === 'lost' ? 'selected' : '' }}>Hilang</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label for="mutate_notes" class="form-label">
                            Alasan Mutasi / Catatan Kondisi
                        </label>

                        <textarea name="notes" id="mutate_notes" rows="3" class="form-control"
                                  placeholder="Tuliskan alasan pemindahan atau perubahan kondisi aset..."></textarea>

                        <div class="form-text">
                            Catatan akan diproses sesuai mekanisme riwayat mutasi yang berlaku.
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Simpan Mutasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =====================================================
     MODAL EDIT
===================================================== --}}
<div class="modal fade inventory-modal" id="editInventoryModal" tabindex="-1"
     aria-labelledby="editInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <div class="inventory-modal-heading">
                    <div class="inventory-modal-icon inventory-modal-icon--gold">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                    <div>
                        <h5 class="modal-title" id="editInventoryModalLabel">Ubah Data Inventaris</h5>
                        <p>Perbarui informasi, spesifikasi, jumlah, atau foto aset.</p>
                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.inventory.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="inventory-form-section">
                        <h6><i class="bi bi-box-seam"></i> Informasi Utama</h6>

                        <div class="row g-3">
                            <div class="col-12 col-md-8">
                                <label for="edit_name" class="form-label">Nama Barang / Aset <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_name" class="form-control"
                                       value="{{ old('name', $item->name) }}" required maxlength="255">
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="edit_inventory_category_id" class="form-label mb-0">Kategori <span class="text-danger">*</span></label>
                                    <button
                                        type="button"
                                        class="btn btn-link p-0 inventory-quick-add-link"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#quickAddCategoryCollapseEdit"
                                        aria-expanded="false"
                                    >
                                        <i class="bi bi-plus-circle"></i>
                                        <span>Kategori Baru</span>
                                    </button>
                                </div>
                                <div class="custom-select-wrap dropdown position-relative" id="wrap_edit_category">
                                    <input type="hidden" name="inventory_category_id" id="edit_inventory_category_id" value="{{ old('inventory_category_id', $item->inventory_category_id) }}">
                                    <button
                                        type="button"
                                        class="custom-select-trigger"
                                        data-bs-toggle="dropdown"
                                        data-bs-auto-close="outside"
                                        aria-expanded="false"
                                        id="btn_edit_category"
                                    >
                                        <span class="selected-text text-truncate flex-grow-1 {{ is_null(old('inventory_category_id', $item->inventory_category_id)) ? 'is-placeholder' : '' }}">
                                            {{ $item->category ? $item->category->name : 'Pilih Kategori' }}
                                        </span>
                                        <i class="bi bi-chevron-down chevron-icon"></i>
                                    </button>
                                    <div class="dropdown-menu custom-select-menu w-100 shadow border">
                                        <div class="custom-option-item {{ is_null(old('inventory_category_id', $item->inventory_category_id)) ? 'active' : '' }}" data-value="" data-label="Pilih Kategori">
                                            <span class="option-label text-muted"><i class="bi bi-dash-lg me-1"></i>Pilih Kategori</span>
                                        </div>
                                        <div class="dropdown-divider my-1"></div>
                                        <div class="custom-options-list custom-options-list-category" id="list_edit_category_options">
                                            @foreach ($categories as $cat)
                                                <div class="custom-option-item {{ old('inventory_category_id', $item->inventory_category_id) == $cat->id ? 'active' : '' }}" data-value="{{ $cat->id }}" data-label="{{ $cat->name }}">
                                                    <span class="option-label flex-grow-1 text-truncate me-2">
                                                        <i class="bi bi-check2 option-check"></i>{{ $cat->name }}
                                                    </span>
                                                    <button type="button" class="btn-delete-opt" data-id="{{ $cat->id }}" data-name="{{ $cat->name }}" data-type="category" title="Hapus kategori {{ $cat->name }}">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Inline Box Tambah Kategori Cepat (Edit) --}}
                                <div class="collapse mt-2" id="quickAddCategoryCollapseEdit">
                                    <div class="p-2.5 rounded border shadow-sm" style="background: #f8fafc; border-color: #cbd5e1 !important; padding: 10px;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold" style="font-size: 11.5px; color: #102a49;">
                                                <i class="bi bi-tag-fill me-1 text-primary"></i> Buat Kategori Baru
                                            </span>
                                            <button type="button" class="btn-close btn-close-edit-cat" style="font-size: 9px;" aria-label="Tutup"></button>
                                        </div>
                                        <div class="mb-2">
                                            <input
                                                type="text"
                                                id="quick_edit_category_name"
                                                class="form-control form-control-sm"
                                                style="font-size: 12px;"
                                                placeholder="Contoh: Alat Kebersihan, Sarana UKS"
                                            >
                                            <div class="invalid-feedback d-none" id="quick_edit_category_error" style="font-size: 11px;"></div>
                                        </div>
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" class="btn btn-sm btn-light border py-1 px-2 btn-cancel-edit-cat" style="font-size: 11px;">
                                                Batal
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 11px;" id="btnSubmitQuickEditCategory">
                                                <i class="bi bi-check2"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="edit_room_id" class="form-label mb-0">Lokasi / Ruangan</label>
                                    <button
                                        type="button"
                                        class="btn btn-link p-0 inventory-quick-add-link"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#quickAddRoomCollapseEdit"
                                        aria-expanded="false"
                                    >
                                        <i class="bi bi-plus-circle"></i>
                                        <span>Ruangan Baru</span>
                                    </button>
                                </div>
                                <div class="custom-select-wrap dropdown position-relative" id="wrap_edit_room">
                                    <input type="hidden" name="room_id" id="edit_room_id" value="{{ old('room_id', $item->room_id) }}">
                                    <button
                                        type="button"
                                        class="custom-select-trigger"
                                        data-bs-toggle="dropdown"
                                        data-bs-auto-close="outside"
                                        aria-expanded="false"
                                        id="btn_edit_room"
                                    >
                                        <span class="selected-text text-truncate flex-grow-1 {{ is_null(old('room_id', $item->room_id)) ? 'is-placeholder' : '' }}">
                                            {{ $item->room ? $item->room->name . ' (' . $item->room->code . ')' : 'Gudang / Belum Ditempatkan' }}
                                        </span>
                                        <i class="bi bi-chevron-down chevron-icon"></i>
                                    </button>
                                    <div class="dropdown-menu custom-select-menu w-100 shadow border">
                                        <div class="custom-option-item {{ is_null(old('room_id', $item->room_id)) ? 'active' : '' }}" data-value="" data-label="Gudang / Belum Ditempatkan">
                                            <span class="option-label text-muted"><i class="bi bi-dash-lg me-1"></i>Gudang / Belum Ditempatkan</span>
                                        </div>
                                        <div class="dropdown-divider my-1"></div>
                                        <div class="custom-options-list custom-options-list-room" id="list_edit_room_options">
                                            @foreach ($rooms as $r)
                                                <div class="custom-option-item {{ old('room_id', $item->room_id) == $r->id ? 'active' : '' }}" data-value="{{ $r->id }}" data-label="{{ $r->name }} ({{ $r->code }})">
                                                    <span class="option-label flex-grow-1 text-truncate me-2">
                                                        <i class="bi bi-check2 option-check"></i>{{ $r->name }} <small class="text-muted">({{ $r->code }})</small>
                                                    </span>
                                                    <button type="button" class="btn-delete-opt" data-id="{{ $r->id }}" data-name="{{ $r->name }}" data-type="room" title="Hapus ruangan {{ $r->name }}">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Inline Box Tambah Ruangan Cepat (Edit) --}}
                                <div class="collapse mt-2" id="quickAddRoomCollapseEdit">
                                    <div class="p-2.5 rounded border shadow-sm" style="background: #f8fafc; border-color: #cbd5e1 !important; padding: 10px;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold" style="font-size: 11.5px; color: #102a49;">
                                                <i class="bi bi-door-open-fill me-1 text-primary"></i> Buat Ruangan Baru
                                            </span>
                                            <button type="button" class="btn-close btn-close-edit-room" style="font-size: 9px;" aria-label="Tutup"></button>
                                        </div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-12 col-sm-8">
                                                <input
                                                    type="text"
                                                    id="quick_edit_room_name"
                                                    class="form-control form-control-sm"
                                                    style="font-size: 12px;"
                                                    placeholder="Nama ruangan (cth: Gudang B)"
                                                >
                                                <div class="invalid-feedback d-none" id="quick_edit_room_error" style="font-size: 11px;"></div>
                                            </div>
                                            <div class="col-12 col-sm-4">
                                                <input
                                                    type="text"
                                                    id="quick_edit_room_code"
                                                    class="form-control form-control-sm text-uppercase"
                                                    style="font-size: 12px;"
                                                    placeholder="Kode (opsional)"
                                                >
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" class="btn btn-sm btn-light border py-1 px-2 btn-cancel-edit-room" style="font-size: 11px;">
                                                Batal
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 11px;" id="btnSubmitQuickEditRoom">
                                                <i class="bi bi-check2"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="edit_condition" class="form-label">Kondisi Fisik <span class="text-danger">*</span></label>
                                <select name="condition" id="edit_condition" class="form-select" required>
                                    <option value="good" {{ old('condition', $item->condition) === 'good' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
                                    <option value="light_damage" {{ old('condition', $item->condition) === 'light_damage' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="heavy_damage" {{ old('condition', $item->condition) === 'heavy_damage' ? 'selected' : '' }}>Rusak Berat</option>
                                    <option value="lost" {{ old('condition', $item->condition) === 'lost' ? 'selected' : '' }}>Hilang</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="inventory-form-section">
                        <h6><i class="bi bi-calculator"></i> Kuantitas & Pengadaan</h6>

                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label for="edit_quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="edit_quantity" class="form-control"
                                       value="{{ old('quantity', $item->quantity) }}" min="1" required>
                            </div>

                            <div class="col-6 col-md-3">
                                <label for="edit_unit" class="form-label">Satuan <span class="text-danger">*</span></label>
                                <input type="text" name="unit" id="edit_unit" class="form-control"
                                       value="{{ old('unit', $item->unit) }}" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="edit_price_per_unit" class="form-label">Harga Satuan (Rp)</label>
                                <input type="number" name="price_per_unit" id="edit_price_per_unit" class="form-control"
                                       value="{{ old('price_per_unit', $item->price_per_unit) }}" min="0">
                                <div class="form-text">Opsional.</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="edit_source_of_fund" class="form-label">Sumber Dana</label>
                                <input type="text" name="source_of_fund" id="edit_source_of_fund" class="form-control"
                                       value="{{ old('source_of_fund', $item->source_of_fund) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="edit_procurement_year" class="form-label">Tahun Pengadaan</label>
                                <input type="number" name="procurement_year" id="edit_procurement_year" class="form-control"
                                       value="{{ old('procurement_year', $item->procurement_year) }}"
                                       min="2000" max="{{ date('Y') + 1 }}">
                            </div>
                        </div>
                    </div>

                    <div class="inventory-form-section inventory-form-section--last">
                        <h6><i class="bi bi-card-image"></i> Foto & Catatan</h6>

                        <div class="mb-3">
                            <label for="edit_photo" class="form-label">Foto Fisik Barang</label>
                            <input type="file" name="photo" id="edit_photo" class="form-control" accept=".jpg,.jpeg,.png">

                            @if ($item->photo_path)
                                <div class="inventory-upload-note">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Foto saat ini tersedia. Unggah foto baru jika ingin menggantinya.
                                </div>
                            @endif
                        </div>

                        <div class="mb-0">
                            <label for="edit_notes" class="form-label">Catatan / Spesifikasi Teknis</label>
                            <textarea name="notes" id="edit_notes" rows="4" class="form-control">{{ old('notes', $item->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =====================================================
     MODAL HAPUS
===================================================== --}}
<div class="modal fade inventory-modal" id="deleteInventoryModal" tabindex="-1"
     aria-labelledby="deleteInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header inventory-delete-header">
                <div class="inventory-modal-heading">
                    <div class="inventory-modal-icon inventory-modal-icon--red">
                        <i class="bi bi-trash3"></i>
                    </div>

                    <div>
                        <h5 class="modal-title" id="deleteInventoryModalLabel">Hapus Aset Inventaris</h5>
                        <p>Konfirmasi penghapusan data aset.</p>
                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="inventory-delete-question">
                    Apakah kamu yakin ingin menghapus aset berikut?
                </p>

                <div class="inventory-delete-asset">
                    <div class="inventory-delete-asset__icon">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <div>
                        <strong>{{ $item->name }}</strong>
                        <span>{{ $item->code }}</span>
                        <small>{{ $item->category?->name ?? 'Tanpa kategori' }}</small>
                        <small>
                            Lokasi: {{ $item->room ? $item->room->name : 'Gudang' }}
                            &bull; {{ $item->quantity }} {{ $item->unit }}
                        </small>
                    </div>
                </div>

                <div class="inventory-delete-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>Penghapusan dapat menghilangkan data dan foto aset secara permanen sesuai mekanisme sistem.</span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>

                <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash3 me-1"></i> Ya, Hapus Aset
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
/* Custom Modern Select Component */
.custom-select-trigger {
    width: 100% !important;
    min-height: 41px !important;
    padding: 9px 12px !important;
    background-color: #ffffff !important;
    background: #ffffff !important;
    background-image: none !important;
    border: 1px solid #d7e1eb !important;
    border-radius: 6px !important;
    color: #2c4056 !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: 11px !important;
    font-weight: 500 !important;
    box-shadow: none !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    text-align: left !important;
    cursor: pointer !important;
    transition: all 0.2s ease-in-out !important;
}

.custom-select-trigger:hover {
    border-color: #93b5d6 !important;
    background-color: #ffffff !important;
    background: #ffffff !important;
}

.custom-select-trigger:focus,
.custom-select-trigger.show,
.custom-select-wrap.dropdown.show .custom-select-trigger {
    border-color: #4788bd !important;
    background-color: #ffffff !important;
    background: #ffffff !important;
    box-shadow: 0 0 0 3px rgba(36, 104, 167, 0.10) !important;
    outline: none !important;
}

.custom-select-trigger.is-invalid {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12) !important;
}

.custom-select-trigger .chevron-icon {
    font-size: 11px !important;
    color: #8fa1b4 !important;
    transition: transform 0.2s ease !important;
    flex-shrink: 0 !important;
    margin-left: 8px !important;
}

.custom-select-wrap.dropdown.show .chevron-icon {
    transform: rotate(180deg) !important;
}

.custom-select-trigger .selected-text {
    color: #2c4056 !important;
    font-weight: 600 !important;
}

.custom-select-trigger .selected-text.is-placeholder {
    color: #9aa7b6 !important;
    font-weight: 400 !important;
}

.custom-select-menu {
    background: #ffffff !important;
    background-color: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.12), 0 4px 6px -2px rgba(15, 23, 42, 0.05) !important;
    padding: 6px !important;
    max-height: 250px !important;
    overflow-y: auto !important;
    z-index: 1060 !important;
}

.custom-option-item {
    padding: 8px 10px !important;
    border-radius: 6px !important;
    font-size: 11.5px !important;
    font-weight: 500 !important;
    color: #334155 !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    transition: background-color 0.15s ease, color 0.15s ease !important;
    margin-bottom: 2px !important;
    user-select: none !important;
}

.custom-option-item:hover {
    background-color: #f1f5f9 !important;
    color: #0f172a !important;
}

.custom-option-item.active {
    background-color: #eff6ff !important;
    color: #0369a1 !important;
    font-weight: 600 !important;
}

.custom-option-item.active .option-check {
    display: inline-block !important;
}

.option-check {
    display: none;
    color: #0284c7;
    margin-right: 5px;
    font-size: 12px;
}

.btn-delete-opt {
    width: 22px !important;
    height: 22px !important;
    border-radius: 4px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: #94a3b8 !important;
    background: transparent !important;
    border: none !important;
    padding: 0 !important;
    margin-left: 6px !important;
    flex-shrink: 0 !important;
    font-size: 11px !important;
    line-height: 1 !important;
    transition: all 0.15s ease-in-out !important;
}

.btn-delete-opt:hover {
    color: #ef4444 !important;
    background-color: #fee2e2 !important;
    transform: scale(1.08) !important;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quickCategoryUrl       = "{{ route('admin.inventory.categories.quick-store') }}";
    const quickRoomUrl           = "{{ route('admin.inventory.rooms.quick-store') }}";
    const destroyCategoryBaseUrl = "{{ url('admin/inventaris/kategori') }}";
    const destroyRoomBaseUrl     = "{{ url('admin/inventaris/ruangan') }}";
    const csrfToken              = "{{ csrf_token() }}";

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

    function setCustomSelectValue(wrapId, value, label) {
        const wrap = document.getElementById(wrapId);
        if (!wrap) return;
        const hiddenInput = wrap.querySelector('input[type="hidden"]');
        const labelSpan = wrap.querySelector('.selected-text');
        const triggerBtn = wrap.querySelector('.custom-select-trigger');

        if (hiddenInput) hiddenInput.value = value;
        if (labelSpan) {
            labelSpan.textContent = label;
            if (!value) {
                labelSpan.classList.add('is-placeholder');
            } else {
                labelSpan.classList.remove('is-placeholder');
            }
        }

        wrap.querySelectorAll('.custom-option-item').forEach(item => {
            const itemVal = item.getAttribute('data-value') || '';
            if (value !== '' && itemVal == value) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });

        if (triggerBtn && value !== '') {
            triggerBtn.classList.remove('is-invalid');
            const err = wrap.querySelector('.invalid-feedback');
            if (err) err.remove();
        }
    }

    function addCustomOption(type, id, name, label) {
        const selector = (type === 'category') ? '.custom-options-list-category' : '.custom-options-list-room';
        document.querySelectorAll(selector).forEach(list => {
            let existing = list.querySelector(`.custom-option-item[data-value="${id}"]`);
            if (!existing) {
                const row = document.createElement('div');
                row.className = 'custom-option-item';
                row.setAttribute('data-value', id);
                row.setAttribute('data-label', label || name);

                const displayTitle = (type === 'category') ? name : `${name} <small class="text-muted">(${label.replace(name, '').replace(/[()]/g, '').trim() || ''})</small>`;

                row.innerHTML = `
                    <span class="option-label flex-grow-1 text-truncate me-2"><i class="bi bi-check2 option-check"></i>${displayTitle}</span>
                    <button type="button" class="btn-delete-opt" data-id="${id}" data-name="${name}" data-type="${type}" title="Hapus ${type === 'category' ? 'kategori' : 'ruangan'} ${name}">
                        <i class="bi bi-x-lg"></i>
                    </button>
                `;
                list.appendChild(row);
            }
        });
    }

    function removeCustomOption(type, id) {
        document.querySelectorAll(`.custom-option-item[data-value="${id}"]`).forEach(row => {
            const wrap = row.closest('.custom-select-wrap');
            if (wrap) {
                const hiddenInput = wrap.querySelector('input[type="hidden"]');
                const labelSpan = wrap.querySelector('.selected-text');
                if (hiddenInput && hiddenInput.value == id) {
                    hiddenInput.value = '';
                    if (labelSpan) {
                        labelSpan.textContent = (type === 'category') ? 'Pilih Kategori' : 'Gudang / Belum Ditempatkan';
                        labelSpan.classList.add('is-placeholder');
                    }
                }
            }
            row.remove();
        });
    }

    // ==========================================
    // 1. QUICK ADD RUANGAN DI MODAL MUTASI
    // ==========================================
    const btnMutateRoom = document.getElementById('btnSubmitQuickMutateRoom');
    const inputMutateRoomName = document.getElementById('quick_mutate_room_name');
    const inputMutateRoomCode = document.getElementById('quick_mutate_room_code');
    const errorMutateRoom = document.getElementById('quick_mutate_room_error');
    const collapseMutateRoom = document.getElementById('quickAddRoomCollapseMutate');

    if (btnMutateRoom && inputMutateRoomName) {
        btnMutateRoom.addEventListener('click', async function () {
            const name = inputMutateRoomName.value.trim();
            const code = inputMutateRoomCode ? inputMutateRoomCode.value.trim() : '';

            if (!name) {
                inputMutateRoomName.classList.add('is-invalid');
                errorMutateRoom.textContent = 'Nama ruangan wajib diisi.';
                errorMutateRoom.classList.remove('d-none');
                inputMutateRoomName.focus();
                return;
            }

            inputMutateRoomName.classList.remove('is-invalid');
            errorMutateRoom.classList.add('d-none');
            btnMutateRoom.disabled = true;
            btnMutateRoom.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

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
                    inputMutateRoomName.classList.add('is-invalid');
                    errorMutateRoom.textContent = errMsg;
                    errorMutateRoom.classList.remove('d-none');
                    return;
                }

                addCustomOption('room', result.data.id, result.data.name, result.data.label);
                setCustomSelectValue('wrap_mutate_room', result.data.id, result.data.label);

                inputMutateRoomName.value = '';
                if (inputMutateRoomCode) inputMutateRoomCode.value = '';
                if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                    const bsCollapse = bootstrap.Collapse.getInstance(collapseMutateRoom) || new bootstrap.Collapse(collapseMutateRoom, { toggle: false });
                    bsCollapse.hide();
                } else if (collapseMutateRoom) {
                    collapseMutateRoom.classList.remove('show');
                }

                showQuickAlert(result.message, 'success');
            } catch (err) {
                console.error('Gagal simpan ruangan mutasi:', err);
                inputMutateRoomName.classList.add('is-invalid');
                errorMutateRoom.textContent = err.message || 'Terjadi kesalahan saat memproses data.';
                errorMutateRoom.classList.remove('d-none');
            } finally {
                btnMutateRoom.disabled = false;
                btnMutateRoom.innerHTML = '<i class="bi bi-check2"></i> Simpan';
            }
        });
    }

    // ==========================================
    // 2. QUICK ADD KATEGORI DI MODAL EDIT
    // ==========================================
    const btnEditCategory = document.getElementById('btnSubmitQuickEditCategory');
    const inputEditCatName = document.getElementById('quick_edit_category_name');
    const errorEditCat = document.getElementById('quick_edit_category_error');
    const collapseEditCat = document.getElementById('quickAddCategoryCollapseEdit');

    if (btnEditCategory && inputEditCatName) {
        btnEditCategory.addEventListener('click', async function () {
            const name = inputEditCatName.value.trim();
            if (!name) {
                inputEditCatName.classList.add('is-invalid');
                errorEditCat.textContent = 'Nama kategori wajib diisi.';
                errorEditCat.classList.remove('d-none');
                inputEditCatName.focus();
                return;
            }

            inputEditCatName.classList.remove('is-invalid');
            errorEditCat.classList.add('d-none');
            btnEditCategory.disabled = true;
            btnEditCategory.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

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
                    inputEditCatName.classList.add('is-invalid');
                    errorEditCat.textContent = errMsg;
                    errorEditCat.classList.remove('d-none');
                    return;
                }

                addCustomOption('category', result.data.id, result.data.name, result.data.name);
                setCustomSelectValue('wrap_edit_category', result.data.id, result.data.name);

                inputEditCatName.value = '';
                if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                    const bsCollapse = bootstrap.Collapse.getInstance(collapseEditCat) || new bootstrap.Collapse(collapseEditCat, { toggle: false });
                    bsCollapse.hide();
                } else if (collapseEditCat) {
                    collapseEditCat.classList.remove('show');
                }

                showQuickAlert(result.message, 'success');
            } catch (err) {
                console.error('Gagal simpan kategori edit:', err);
                inputEditCatName.classList.add('is-invalid');
                errorEditCat.textContent = err.message || 'Terjadi kesalahan saat memproses data.';
                errorEditCat.classList.remove('d-none');
            } finally {
                btnEditCategory.disabled = false;
                btnEditCategory.innerHTML = '<i class="bi bi-check2"></i> Simpan';
            }
        });
    }

    // ==========================================
    // 3. QUICK ADD RUANGAN DI MODAL EDIT
    // ==========================================
    const btnEditRoom = document.getElementById('btnSubmitQuickEditRoom');
    const inputEditRoomName = document.getElementById('quick_edit_room_name');
    const inputEditRoomCode = document.getElementById('quick_edit_room_code');
    const errorEditRoom = document.getElementById('quick_edit_room_error');
    const collapseEditRoom = document.getElementById('quickAddRoomCollapseEdit');

    if (btnEditRoom && inputEditRoomName) {
        btnEditRoom.addEventListener('click', async function () {
            const name = inputEditRoomName.value.trim();
            const code = inputEditRoomCode ? inputEditRoomCode.value.trim() : '';

            if (!name) {
                inputEditRoomName.classList.add('is-invalid');
                errorEditRoom.textContent = 'Nama ruangan wajib diisi.';
                errorEditRoom.classList.remove('d-none');
                inputEditRoomName.focus();
                return;
            }

            inputEditRoomName.classList.remove('is-invalid');
            errorEditRoom.classList.add('d-none');
            btnEditRoom.disabled = true;
            btnEditRoom.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

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
                    inputEditRoomName.classList.add('is-invalid');
                    errorEditRoom.textContent = errMsg;
                    errorEditRoom.classList.remove('d-none');
                    return;
                }

                addCustomOption('room', result.data.id, result.data.name, result.data.label);
                setCustomSelectValue('wrap_edit_room', result.data.id, result.data.label);

                inputEditRoomName.value = '';
                if (inputEditRoomCode) inputEditRoomCode.value = '';
                if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                    const bsCollapse = bootstrap.Collapse.getInstance(collapseEditRoom) || new bootstrap.Collapse(collapseEditRoom, { toggle: false });
                    bsCollapse.hide();
                } else if (collapseEditRoom) {
                    collapseEditRoom.classList.remove('show');
                }

                showQuickAlert(result.message, 'success');
            } catch (err) {
                console.error('Gagal simpan ruangan edit:', err);
                inputEditRoomName.classList.add('is-invalid');
                errorEditRoom.textContent = err.message || 'Terjadi kesalahan saat memproses data.';
                errorEditRoom.classList.remove('d-none');
            } finally {
                btnEditRoom.disabled = false;
                btnEditRoom.innerHTML = '<i class="bi bi-check2"></i> Simpan';
            }
        });
    }

    // Helper penutupan & reset form collapse cepat di halaman detail
    function closeQuickCollapse(collapseEl, inputs, errorEl) {
        if (!collapseEl) return;
        if (inputs) {
            inputs.forEach(inp => {
                if (inp) {
                    inp.value = '';
                    inp.classList.remove('is-invalid');
                }
            });
        }
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.classList.add('d-none');
        }
        if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
            const inst = bootstrap.Collapse.getInstance(collapseEl);
            if (inst) {
                inst.hide();
            } else {
                new bootstrap.Collapse(collapseEl, { toggle: false }).hide();
            }
        } else {
            collapseEl.classList.remove('show');
        }
    }

    document.querySelectorAll('.btn-close-mutate-room, .btn-cancel-mutate-room').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeQuickCollapse(collapseMutateRoom, [inputMutateRoomName, inputMutateRoomCode], errorMutateRoom);
        });
    });

    document.querySelectorAll('.btn-close-edit-cat, .btn-cancel-edit-cat').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeQuickCollapse(collapseEditCat, [inputEditCatName], errorEditCat);
        });
    });

    document.querySelectorAll('.btn-close-edit-room, .btn-cancel-edit-room').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeQuickCollapse(collapseEditRoom, [inputEditRoomName, inputEditRoomCode], errorEditRoom);
        });
    });

    async function handleDeleteOption(btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        const type = btn.getAttribute('data-type');
        const labelType = (type === 'category') ? 'kategori' : 'ruangan';
        const warning = (type === 'category')
            ? `Aset terkait akan disetel menjadi tanpa kategori.`
            : `Aset terkait akan dialihkan ke "Gudang / Belum Ditempatkan".`;

        if (!confirm(`Yakin ingin menghapus ${labelType} "${name}"?\n\n${warning}`)) {
            return;
        }

        const origHtml = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width: 10px; height: 10px;"></span>';
        btn.disabled = true;

        try {
            const url = (type === 'category')
                ? `${destroyCategoryBaseUrl}/${id}`
                : `${destroyRoomBaseUrl}/${id}`;

            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const result = await response.json();
            if (!response.ok) {
                throw new Error(result.message || `Gagal menghapus ${labelType}.`);
            }

            removeCustomOption(type, id);
            showQuickAlert(result.message || `${labelType.charAt(0).toUpperCase() + labelType.slice(1)} "${name}" berhasil dihapus.`, 'success');
        } catch (err) {
            console.error(`Gagal hapus ${labelType}:`, err);
            alert(err.message || `Terjadi kesalahan saat menghapus ${labelType}.`);
            btn.innerHTML = origHtml;
            btn.disabled = false;
        }
    }

    document.addEventListener('click', function (e) {
        const deleteBtn = e.target.closest('.btn-delete-opt');
        if (deleteBtn) {
            e.preventDefault();
            e.stopPropagation();
            handleDeleteOption(deleteBtn);
            return;
        }

        const optionItem = e.target.closest('.custom-option-item');
        if (optionItem) {
            const wrap = optionItem.closest('.custom-select-wrap');
            if (!wrap) return;

            const val = optionItem.getAttribute('data-value') || '';
            const label = optionItem.getAttribute('data-label') || optionItem.textContent.trim();

            setCustomSelectValue(wrap.id, val, label);

            const triggerBtn = wrap.querySelector('.custom-select-trigger');
            if (triggerBtn && typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                const bsDrop = bootstrap.Dropdown.getInstance(triggerBtn);
                if (bsDrop) bsDrop.hide();
            }
        }
    });

    const editForm = document.querySelector('#editInventoryModal form');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            const catInput = document.getElementById('edit_inventory_category_id');
            const catBtn = document.getElementById('btn_edit_category');
            if (catInput && !catInput.value) {
                e.preventDefault();
                if (catBtn) {
                    catBtn.classList.add('is-invalid');
                    let err = catBtn.parentElement.querySelector('.invalid-feedback');
                    if (!err) {
                        err = document.createElement('div');
                        err.className = 'invalid-feedback d-block';
                        err.style.fontSize = '12px';
                        err.textContent = 'Kategori aset wajib dipilih.';
                        catBtn.parentElement.appendChild(err);
                    }
                    catBtn.focus();
                }
                return false;
            }
        });
    }
});
</script>
@endpush
