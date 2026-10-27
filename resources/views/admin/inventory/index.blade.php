@extends('layouts.admin')

@section('title', 'Inventaris & Sarana Prasarana')

@section('content')

<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <span class="text-uppercase small fw-semibold text-primary">Operasional &amp; Tata Usaha</span>
            <h3 class="fw-bold mb-1">Inventaris &amp; Sarana Prasarana</h3>
            <p class="text-muted mb-0">Kelola dan pantau seluruh aset sekolah, mebel, peralatan lab, IT, dan kondisi sarana per ruangan.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addInventoryModal">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Tambah Barang Baru</span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Terjadi kesalahan saat memproses data:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">TOTAL BARANG</span>
                        <div class="rounded-circle p-2 bg-primary-subtle text-primary">
                            <i class="bi bi-box-seam fs-5"></i>
                        </div>
                    </div>
                    <div class="fs-3 fw-bold text-dark">{{ number_format($totalItems, 0, ',', '.') }}</div>
                    <div class="small text-muted">Seluruh aset yang tercatat</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">KONDISI BAIK</span>
                        <div class="rounded-circle p-2 bg-success-subtle text-success">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        </div>
                    </div>
                    <div class="fs-3 fw-bold text-success">{{ number_format($goodCondition, 0, ',', '.') }}</div>
                    <div class="small text-muted">Siap &amp; layak digunakan</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">RUSAK RINGAN</span>
                        <div class="rounded-circle p-2 bg-warning-subtle text-warning-emphasis">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        </div>
                    </div>
                    <div class="fs-3 fw-bold text-warning-emphasis">{{ number_format($lightDamage, 0, ',', '.') }}</div>
                    <div class="small text-muted">Perlu perbaikan berkala</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">RUSAK BERAT / HILANG</span>
                        <div class="rounded-circle p-2 bg-danger-subtle text-danger">
                            <i class="bi bi-x-octagon-fill fs-5"></i>
                        </div>
                    </div>
                    <div class="fs-3 fw-bold text-danger">{{ number_format($heavyDamage + $lostCount, 0, ',', '.') }}</div>
                    <div class="small text-muted">Perlu penggantian baru</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="row g-3 align-items-end">

                {{-- Cari Nama / Kode --}}
                <div class="col-12 col-md-4">
                    <label for="search" class="form-label small fw-semibold text-muted mb-1">CARI BARANG / KODE</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" id="search" class="form-control border-start-0" placeholder="Ketik nama atau kode barang..." value="{{ $search }}">
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label for="room_id" class="form-label small fw-semibold text-muted mb-1">RUANGAN / LOKASI</label>
                    <select name="room_id" id="room_id" class="form-select">
                        <option value="">Semua Ruangan</option>
                        @foreach ($rooms as $r)
                            <option value="{{ $r->id }}" @selected((string)$roomId === (string)$r->id)>{{ $r->name }} ({{ $r->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label for="category_id" class="form-label small fw-semibold text-muted mb-1">KATEGORI ASET</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((string)$categoryId === (string)$cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label for="condition" class="form-label small fw-semibold text-muted mb-1">KONDISI</label>
                    <select name="condition" id="condition" class="form-select">
                        <option value="">Semua Kondisi</option>
                        <option value="good" @selected($condition === 'good')>Baik</option>
                        <option value="light_damage" @selected($condition === 'light_damage')>Rusak Ringan</option>
                        <option value="heavy_damage" @selected($condition === 'heavy_damage')>Rusak Berat</option>
                        <option value="lost" @selected($condition === 'lost')>Hilang</option>
                    </select>
                </div>

                <div class="col-12 d-flex gap-2 justify-content-end">
                    @if ($search || $roomId || $categoryId || $condition)
                        <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i> Reset Filter
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 140px;">Kode Aset</th>
                        <th>Nama Barang &amp; Spesifikasi</th>
                        <th>Kategori</th>
                        <th>Ruangan / Lokasi</th>
                        <th class="text-center" style="width: 90px;">Jumlah</th>
                        <th class="text-center" style="width: 120px;">Kondisi</th>
                        <th>Sumber Dana</th>
                        <th class="text-end pe-4" style="width: 110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-light text-dark font-monospace border px-2 py-1">
                                    {{ $item->code }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-bold text-dark">{{ $item->name }}</div>
                                @if ($item->notes)
                                    <small class="text-muted d-block text-truncate" style="max-width: 280px;">{{ $item->notes }}</small>
                                @endif
                            </td>

                            <td>
                                <span class="small text-secondary">
                                    {{ $item->category?->name ?? '-' }}
                                </span>
                            </td>

                            <td>
                                @if ($item->room)
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-door-closed text-primary"></i>
                                        <span class="fw-semibold small">{{ $item->room->name }}</span>
                                    </div>
                                    <small class="text-muted">{{ $item->room->code }}</small>
                                @else
                                    <span class="text-muted small">Gudang / Belum Ditempatkan</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <span class="fw-bold text-dark">{{ $item->quantity }}</span>
                                <small class="text-muted d-block">{{ $item->unit }}</small>
                            </td>

                            <td class="text-center">
                                <span class="badge rounded-pill {{ $item->conditionBadgeClass() }} px-3 py-1">
                                    {{ $item->conditionLabel() }}
                                </span>
                            </td>

                            <td>
                                <div class="small fw-semibold text-dark">{{ $item->source_of_fund ?? '-' }}</div>
                                @if ($item->procurement_year)
                                    <small class="text-muted">Tahun {{ $item->procurement_year }}</small>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <a href="{{ route('admin.inventory.show', $item->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" title="Lihat Detail Barang">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-box-seam fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                    <span class="fw-semibold">Belum ada data barang inventaris yang sesuai.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $items->links() }}
            </div>
        @endif
    </div>

</div>

<div class="modal fade" id="addInventoryModal" tabindex="-1" aria-labelledby="addInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded p-2 text-white bg-primary">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="addInventoryModalLabel">Tambah Aset Inventaris Baru</h5>
                        <small class="text-muted">Kode aset unik akan digenerate otomatis oleh sistem</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.inventory.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">

                    <div class="row g-3">
                        {{-- Nama Barang --}}
                        <div class="col-12 col-md-8">
                            <label for="name" class="form-label fw-semibold">Nama Barang / Aset <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Kursi Siswa Kayu Jati / PC All-in-One Core i5" required maxlength="255">
                        </div>

                        {{-- Kategori --}}
                        <div class="col-12 col-md-4">
                            <label for="modal_category_id" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="inventory_category_id" id="modal_category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Ruangan Penempatan --}}
                        <div class="col-12 col-md-6">
                            <label for="modal_room_id" class="form-label fw-semibold">Lokasi / Ruangan</label>
                            <select name="room_id" id="modal_room_id" class="form-select">
                                <option value="">-- Gudang / Belum Ditempatkan --</option>
                                @foreach ($rooms as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->code }})</option>
                                @endforeach
                            </select>
                            <div class="form-text">Pilih ruangan tempat aset ini disimpan.</div>
                        </div>

                        {{-- Kondisi --}}
                        <div class="col-12 col-md-6">
                            <label for="modal_condition" class="form-label fw-semibold">Kondisi Fisik Awal <span class="text-danger">*</span></label>
                            <select name="condition" id="modal_condition" class="form-select" required>
                                <option value="good" selected>Baik (Siap Pakai)</option>
                                <option value="light_damage">Rusak Ringan</option>
                                <option value="heavy_damage">Rusak Berat</option>
                                <option value="lost">Hilang</option>
                            </select>
                        </div>

                        {{-- Kuantitas & Satuan --}}
                        <div class="col-6 col-md-3">
                            <label for="quantity" class="form-label fw-semibold">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-6 col-md-3">
                            <label for="unit" class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                            <input type="text" name="unit" id="unit" class="form-control" value="unit" placeholder="unit / set / buah" required>
                        </div>

                        {{-- Harga Satuan --}}
                        <div class="col-12 col-md-6">
                            <label for="price_per_unit" class="form-label fw-semibold">Harga Satuan (Rp) <span class="text-muted fw-normal">(opsional)</span></label>
                            <input type="number" name="price_per_unit" id="price_per_unit" class="form-control" placeholder="Contoh: 350000" min="0">
                        </div>

                        {{-- Sumber Dana --}}
                        <div class="col-12 col-md-6">
                            <label for="source_of_fund" class="form-label fw-semibold">Sumber Dana</label>
                            <input type="text" name="source_of_fund" id="source_of_fund" class="form-control" placeholder="Contoh: Dana BOS / BOP / Komite">
                        </div>

                        {{-- Tahun Pengadaan --}}
                        <div class="col-12 col-md-6">
                            <label for="procurement_year" class="form-label fw-semibold">Tahun Pengadaan</label>
                            <input type="number" name="procurement_year" id="procurement_year" class="form-control" value="{{ date('Y') }}" min="2000" max="{{ date('Y') + 1 }}">
                        </div>

                        {{-- Foto Fisik Barang --}}
                        <div class="col-12">
                            <label for="photo" class="form-label fw-semibold">Unggah Foto Fisik <span class="text-muted fw-normal">(opsional, maks 4MB)</span></label>
                            <input type="file" name="photo" id="photo" class="form-control" accept=".jpg,.jpeg,.png">
                        </div>

                        {{-- Catatan / Spesifikasi --}}
                        <div class="col-12">
                            <label for="notes" class="form-label fw-semibold">Catatan / Spesifikasi Teknis</label>
                            <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Merk, seri, nomor seri (SN), atau keterangan spesifikasi lainnya..."></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Simpan Aset Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
