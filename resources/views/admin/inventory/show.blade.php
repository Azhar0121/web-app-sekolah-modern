@extends('layouts.admin')

@section('title', 'Detail Inventaris — ' . $item->name)

@section('content')

<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
                <span class="badge bg-light text-dark font-monospace border px-2 py-1">{{ $item->code }}</span>
            </div>
            <h3 class="fw-bold mb-1">{{ $item->name }}</h3>
            <p class="text-muted mb-0">Informasi spesifikasi lengkap, penempatan ruangan, dan riwayat kondisi aset.</p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#mutateInventoryModal">
                <i class="bi bi-arrow-left-right"></i>
                <span>Mutasi / Pindah Ruangan</span>
            </button>
            <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#editInventoryModal">
                <i class="bi bi-pencil-square"></i>
                <span>Ubah Data</span>
            </button>
            <button type="button" class="btn btn-outline-danger d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteInventoryModal">
                <i class="bi bi-trash3"></i>
                <span>Hapus</span>
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

    <div class="row g-4">

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-image me-1 text-primary"></i> Foto Fisik Barang
                </div>
                <div class="card-body text-center d-flex flex-column justify-content-center align-items-center p-4">
                    @if ($item->photoUrl())
                        <img src="{{ $item->photoUrl() }}" alt="{{ $item->name }}" class="img-fluid rounded border shadow-sm mb-3" style="max-height: 260px; object-fit: cover;">
                    @else
                        <div class="rounded bg-light border d-flex flex-column align-items-center justify-content-center w-100 py-5 mb-3" style="min-height: 200px;">
                            <i class="bi bi-camera text-secondary fs-1 mb-2 opacity-50"></i>
                            <span class="small text-muted">Belum ada foto fisik barang</span>
                        </div>
                    @endif

                    <div class="small text-muted w-100 p-3 bg-light rounded text-start">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Dicatat Oleh:</span>
                            <strong class="text-dark">{{ $item->creator?->name ?? 'Sistem / Admin' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Waktu Input:</span>
                            <span class="text-dark">{{ $item->created_at ? $item->created_at->translatedFormat('d F Y H:i') : '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Pembaruan Terakhir:</span>
                            <span class="text-dark">{{ $item->updated_at ? $item->updated_at->translatedFormat('d F Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-upc-scan me-1 text-primary"></i> Tag Identitas Aset
                </div>
                <div class="card-body text-center p-4">
                    <div class="p-3 border rounded bg-light mb-3">
                        <div class="small text-muted mb-1 text-uppercase fw-semibold" style="letter-spacing: 1px;">KODE INVENTARIS</div>
                        <div class="fs-4 fw-bold font-monospace text-primary">{{ $item->code }}</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted small">Status Kondisi</span>
                        <span class="badge rounded-pill {{ $item->conditionBadgeClass() }} px-3 py-1">
                            {{ $item->conditionLabel() }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted small">Kuantitas Total</span>
                        <span class="fw-bold text-dark">{{ $item->quantity }} {{ $item->unit }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-info-circle me-1 text-primary"></i> Rincian Aset &amp; Penempatan
                    </div>
                    <span class="badge rounded-pill {{ $item->conditionBadgeClass() }} px-3 py-1">
                        {{ $item->conditionLabel() }}
                    </span>
                </div>
                <div class="card-body p-4">

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <label class="small text-muted d-block">Kategori Barang</label>
                            <span class="fw-bold text-dark fs-6">{{ $item->category?->name ?? '-' }}</span>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="small text-muted d-block">Ruangan / Lokasi Penempatan</label>
                            <span class="fw-bold text-primary fs-6">
                                <i class="bi bi-door-closed me-1"></i>
                                {{ $item->room ? "{$item->room->name} ({$item->room->code})" : 'Gudang / Belum ditempatkan' }}
                            </span>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="small text-muted d-block">Jumlah Kuantitas</label>
                            <span class="fw-bold text-dark fs-6">{{ $item->quantity }} {{ $item->unit }}</span>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="small text-muted d-block">Status Kondisi Fisik</label>
                            <span class="badge rounded-pill {{ $item->conditionBadgeClass() }} px-3 py-1">
                                {{ $item->conditionLabel() }}
                            </span>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25">

                    <h6 class="fw-bold mb-3 text-secondary">Informasi Anggaran &amp; Pengadaan</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-4">
                            <label class="small text-muted d-block">Sumber Dana</label>
                            <span class="fw-semibold text-dark">{{ $item->source_of_fund ?? '-' }}</span>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="small text-muted d-block">Tahun Pengadaan</label>
                            <span class="fw-semibold text-dark">{{ $item->procurement_year ?? '-' }}</span>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="small text-muted d-block">Harga Satuan</label>
                            <span class="fw-semibold text-dark">{{ $item->formattedPrice() }}</span>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="small text-muted d-block">Total Nilai Taksiran Aset</label>
                            <span class="fw-bold text-success fs-5">{{ $item->formattedTotalValue() }}</span>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25">

                    <h6 class="fw-bold mb-2 text-secondary">Catatan, Spesifikasi &amp; Riwayat Mutasi</h6>
                    @if ($item->notes)
                        <div class="p-3 bg-light rounded text-dark small" style="white-space: pre-line; line-height: 1.6;">
                            {!! nl2br(e($item->notes)) !!}
                        </div>
                    @else
                        <p class="text-muted mb-0 bg-light p-3 rounded small">
                            Tidak ada catatan atau riwayat mutasi tambahan untuk barang ini.
                        </p>
                    @endif

                </div>
            </div>
        </div>

    </div>

</div>

<div class="modal fade" id="mutateInventoryModal" tabindex="-1" aria-labelledby="mutateInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded p-2 text-white bg-primary">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="mutateInventoryModalLabel">Mutasi Ruangan &amp; Kondisi</h5>
                        <small class="text-muted">Pindahkan lokasi ruangan atau perbarui status kondisi fisik barang</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.inventory.mutate', $item) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body p-4">

                    <div class="alert alert-info border-0 py-2 px-3 small mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i> Aset: <strong>{{ $item->name }}</strong> ({{ $item->code }})
                    </div>

                    <div class="row g-3">
                        {{-- Ruangan Baru --}}
                        <div class="col-12">
                            <label for="mutate_room_id" class="form-label fw-semibold">Pindah ke Ruangan <span class="text-danger">*</span></label>
                            <select name="room_id" id="mutate_room_id" class="form-select">
                                <option value="" {{ is_null($item->room_id) ? 'selected' : '' }}>-- Gudang / Belum Ditempatkan --</option>
                                @foreach ($rooms as $r)
                                    <option value="{{ $r->id }}" {{ $item->room_id == $r->id ? 'selected' : '' }}>
                                        {{ $r->name }} ({{ $r->code }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Pilih ruangan tujuan penempatan barang.</div>
                        </div>

                        {{-- Kondisi Fisik --}}
                        <div class="col-12">
                            <label for="mutate_condition" class="form-label fw-semibold">Kondisi Fisik Terkini <span class="text-danger">*</span></label>
                            <select name="condition" id="mutate_condition" class="form-select" required>
                                <option value="good" {{ $item->condition === 'good' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
                                <option value="light_damage" {{ $item->condition === 'light_damage' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="heavy_damage" {{ $item->condition === 'heavy_damage' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="lost" {{ $item->condition === 'lost' ? 'selected' : '' }}>Hilang</option>
                            </select>
                        </div>

                        {{-- Catatan Mutasi --}}
                        <div class="col-12">
                            <label for="mutate_notes" class="form-label fw-semibold">Alasan Mutasi / Catatan Kondisi</label>
                            <textarea name="notes" id="mutate_notes" rows="3" class="form-control" placeholder="Contoh: Dipindahkan dari Lab Komputer ke Ruang Guru untuk kebutuhan asesmen..."></textarea>
                            <div class="form-text">Catatan ini akan otomatis dibubuhi tanggal mutasi dan disimpan di riwayat aset.</div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Simpan Mutasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editInventoryModal" tabindex="-1" aria-labelledby="editInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded p-2 text-white bg-warning">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="editInventoryModalLabel">Ubah Data Aset Inventaris</h5>
                        <small class="text-muted">Perbarui data spesifikasi, kuantitas, atau foto aset</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.inventory.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">

                    <div class="row g-3">
                        {{-- Nama Barang --}}
                        <div class="col-12 col-md-8">
                            <label for="edit_name" class="form-label fw-semibold">Nama Barang / Aset <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" value="{{ old('name', $item->name) }}" required maxlength="255">
                        </div>

                        {{-- Kategori --}}
                        <div class="col-12 col-md-4">
                            <label for="edit_inventory_category_id" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="inventory_category_id" id="edit_inventory_category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('inventory_category_id', $item->inventory_category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Ruangan Penempatan --}}
                        <div class="col-12 col-md-6">
                            <label for="edit_room_id" class="form-label fw-semibold">Lokasi / Ruangan</label>
                            <select name="room_id" id="edit_room_id" class="form-select">
                                <option value="" {{ is_null(old('room_id', $item->room_id)) ? 'selected' : '' }}>-- Gudang / Belum Ditempatkan --</option>
                                @foreach ($rooms as $r)
                                    <option value="{{ $r->id }}" {{ old('room_id', $item->room_id) == $r->id ? 'selected' : '' }}>
                                        {{ $r->name }} ({{ $r->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kondisi --}}
                        <div class="col-12 col-md-6">
                            <label for="edit_condition" class="form-label fw-semibold">Kondisi Fisik <span class="text-danger">*</span></label>
                            <select name="condition" id="edit_condition" class="form-select" required>
                                <option value="good" {{ old('condition', $item->condition) === 'good' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
                                <option value="light_damage" {{ old('condition', $item->condition) === 'light_damage' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="heavy_damage" {{ old('condition', $item->condition) === 'heavy_damage' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="lost" {{ old('condition', $item->condition) === 'lost' ? 'selected' : '' }}>Hilang</option>
                            </select>
                        </div>

                        {{-- Kuantitas & Satuan --}}
                        <div class="col-6 col-md-3">
                            <label for="edit_quantity" class="form-label fw-semibold">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="edit_quantity" class="form-control" value="{{ old('quantity', $item->quantity) }}" min="1" required>
                        </div>
                        <div class="col-6 col-md-3">
                            <label for="edit_unit" class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                            <input type="text" name="unit" id="edit_unit" class="form-control" value="{{ old('unit', $item->unit) }}" required>
                        </div>

                        {{-- Harga Satuan --}}
                        <div class="col-12 col-md-6">
                            <label for="edit_price_per_unit" class="form-label fw-semibold">Harga Satuan (Rp) <span class="text-muted fw-normal">(opsional)</span></label>
                            <input type="number" name="price_per_unit" id="edit_price_per_unit" class="form-control" value="{{ old('price_per_unit', $item->price_per_unit) }}" min="0">
                        </div>

                        {{-- Sumber Dana --}}
                        <div class="col-12 col-md-6">
                            <label for="edit_source_of_fund" class="form-label fw-semibold">Sumber Dana</label>
                            <input type="text" name="source_of_fund" id="edit_source_of_fund" class="form-control" value="{{ old('source_of_fund', $item->source_of_fund) }}">
                        </div>

                        {{-- Tahun Pengadaan --}}
                        <div class="col-12 col-md-6">
                            <label for="edit_procurement_year" class="form-label fw-semibold">Tahun Pengadaan</label>
                            <input type="number" name="procurement_year" id="edit_procurement_year" class="form-control" value="{{ old('procurement_year', $item->procurement_year) }}" min="2000" max="{{ date('Y') + 1 }}">
                        </div>

                        {{-- Foto Fisik Barang --}}
                        <div class="col-12">
                            <label for="edit_photo" class="form-label fw-semibold">Ganti Foto Fisik <span class="text-muted fw-normal">(opsional, biarkan kosong jika tidak diubah)</span></label>
                            <input type="file" name="photo" id="edit_photo" class="form-control" accept=".jpg,.jpeg,.png">
                            @if ($item->photo_path)
                                <div class="form-text text-success">
                                    <i class="bi bi-check-circle me-1"></i> Saat ini barang sudah memiliki foto fisik. Mengunggah foto baru akan mengganti foto yang lama.
                                </div>
                            @endif
                        </div>

                        {{-- Catatan / Spesifikasi --}}
                        <div class="col-12">
                            <label for="edit_notes" class="form-label fw-semibold">Catatan / Spesifikasi Teknis</label>
                            <textarea name="notes" id="edit_notes" rows="4" class="form-control">{{ old('notes', $item->notes) }}</textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark d-inline-flex align-items-center gap-2 fw-semibold">
                        <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteInventoryModal" tabindex="-1" aria-labelledby="deleteInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-bottom-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <h5 class="modal-title fw-bold mb-0" id="deleteInventoryModalLabel">Konfirmasi Hapus Aset</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <p class="mb-2">Apakah Anda yakin ingin menghapus data aset berikut?</p>
                <div class="p-3 bg-light rounded border mb-3">
                    <div class="fw-bold text-dark fs-6">{{ $item->name }}</div>
                    <div class="small text-muted font-monospace">{{ $item->code }} &bull; {{ $item->category?->name }}</div>
                    <div class="small text-secondary mt-1">
                        Lokasi: {{ $item->room ? $item->room->name : 'Gudang' }} &bull; {{ $item->quantity }} {{ $item->unit }}
                    </div>
                </div>
                <div class="alert alert-danger py-2 px-3 small mb-0 border-0">
                    <i class="bi bi-shield-exclamation me-1"></i> Data dan foto fisik aset ini akan dihapus secara permanen dari sistem inventaris.
                </div>
            </div>

            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-2">
                        <i class="bi bi-trash3-fill"></i> Ya, Hapus Aset
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
