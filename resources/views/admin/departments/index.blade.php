@extends('layouts.admin')

@section('title', 'Master Data Jurusan & Program Keahlian')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Jurusan & Program Keahlian
            </h5>
            <small class="text-muted">Kelola daftar program keahlian / konsentrasi keahlian di sekolah.</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-lg me-1"></i>Tambah Jurusan
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px;">No.</th>
                        <th>Kode</th>
                        <th>Nama Jurusan / Program Keahlian</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $dept)
                    <tr>
                        <td>{{ $loop->iteration + $departments->firstItem() - 1 }}</td>
                        <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold">{{ $dept->code }}</span></td>
                        <td class="fw-semibold">{{ $dept->name }}</td>
                        <td class="small text-muted">{{ $dept->description ?? '—' }}</td>
                        <td class="text-center">
                            @if ($dept->is_active)
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $dept->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jurusan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $dept->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.departments.update', $dept) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title fw-bold">Edit Jurusan</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Kode Jurusan</label>
                                            <input type="text" name="code" class="form-control" value="{{ $dept->code }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Nama Jurusan</label>
                                            <input type="text" name="name" class="form-control" value="{{ $dept->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Deskripsi</label>
                                            <textarea name="description" class="form-control" rows="2">{{ $dept->description }}</textarea>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="act{{ $dept->id }}" @checked($dept->is_active)>
                                            <label class="form-check-label small" for="act{{ $dept->id }}">Jurusan Aktif</label>
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
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data jurusan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($departments->hasPages())
        <div class="card-footer bg-white py-2">
            {{ $departments->links() }}
        </div>
        @endif
    </div>

</div>

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.departments.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Jurusan Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kode (Contoh: RPL, TKJ, IPA)</label>
                        <input type="text" name="code" class="form-control" placeholder="RPL" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Jurusan</label>
                        <input type="text" name="name" class="form-control" placeholder="Rekayasa Perangkat Lunak" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
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
