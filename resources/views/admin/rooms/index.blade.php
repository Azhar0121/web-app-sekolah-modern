@extends('layouts.admin')

@section('title', 'Master Data Ruangan & Fasilitas')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-door-open-fill text-primary me-2"></i>Alokasi Ruangan & Fasilitas
            </h5>
            <small class="text-muted">Kelola data ruang kelas, laboratorium, perpustakaan, dan fasilitas sekolah.</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-lg me-1"></i>Tambah Ruangan
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px;">No.</th>
                        <th>Kode</th>
                        <th>Nama Ruangan / Fasilitas</th>
                        <th>Tipe</th>
                        <th>Kapasitas</th>
                        <th>Lokasi</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $room)
                    <tr>
                        <td>{{ $loop->iteration + $rooms->firstItem() - 1 }}</td>
                        <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold">{{ $room->code }}</span></td>
                        <td class="fw-semibold">{{ $room->name }}</td>
                        <td><span class="badge bg-info-subtle text-info">{{ $room->typeLabel() }}</span></td>
                        <td>{{ $room->capacity ? $room->capacity . ' Kursi' : '—' }}</td>
                        <td class="small text-muted">{{ $room->location ?? '—' }}</td>
                        <td class="text-center">
                            @if ($room->is_active)
                                <span class="badge bg-success-subtle text-success">Siap Pakai</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $room->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus ruangan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $room->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.rooms.update', $room) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title fw-bold">Edit Data Ruangan</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Kode Ruangan</label>
                                                <input type="text" name="code" class="form-control" value="{{ $room->code }}" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Tipe Ruangan</label>
                                                <select name="type" class="form-select" required>
                                                    <option value="kelas" @selected($room->type==='kelas')>Ruang Kelas</option>
                                                    <option value="laboratorium" @selected($room->type==='laboratorium')>Laboratorium</option>
                                                    <option value="perpustakaan" @selected($room->type==='perpustakaan')>Perpustakaan</option>
                                                    <option value="aula" @selected($room->type==='aula')>Aula</option>
                                                    <option value="lapangan" @selected($room->type==='lapangan')>Lapangan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Nama Ruangan</label>
                                            <input type="text" name="name" class="form-control" value="{{ $room->name }}" required>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Kapasitas (Kursi)</label>
                                                <input type="number" name="capacity" class="form-control" value="{{ $room->capacity }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Lokasi / Gedung</label>
                                                <input type="text" name="location" class="form-control" value="{{ $room->location }}">
                                            </div>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="actR{{ $room->id }}" @checked($room->is_active)>
                                            <label class="form-check-label small" for="actR{{ $room->id }}">Ruangan Aktif</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data ruangan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($rooms->hasPages())
        <div class="card-footer bg-white py-2">
            {{ $rooms->links() }}
        </div>
        @endif
    </div>

</div>

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.rooms.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Ruangan Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Kode (Contoh: R-101)</label>
                            <input type="text" name="code" class="form-control" placeholder="R-101" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tipe Ruangan</label>
                            <select name="type" class="form-select" required>
                                <option value="kelas">Ruang Kelas</option>
                                <option value="laboratorium">Laboratorium</option>
                                <option value="perpustakaan">Perpustakaan</option>
                                <option value="aula">Aula</option>
                                <option value="lapangan">Lapangan</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Ruangan</label>
                        <input type="text" name="name" class="form-control" placeholder="Laboratorium Komputer 1" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Kapasitas (Kursi)</label>
                            <input type="number" name="capacity" class="form-control" placeholder="36">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Lokasi / Gedung</label>
                            <input type="text" name="location" class="form-control" placeholder="Lantai 2 Gedung B">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
