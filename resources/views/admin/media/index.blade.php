@extends('layouts.admin')

@section('title', 'Media Library (Asymmetric File Manager)')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-images text-primary me-2"></i>Media Library & File Manager
            </h5>
            <small class="text-muted">Upload massal gambar/dokumen, pengorganisasian berbasis folder, optimasi ukuran, & Alt Text SEO.</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-cloud-upload me-1"></i>+ Upload Media Massal
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.media.index') }}" class="row g-2">
                <div class="col-md-3">
                    <select name="folder" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all">-- Semua Folder --</option>
                        @foreach ($folders as $f)
                            <option value="{{ $f }}" @selected($folderFilter === $f)>Folder: {{ ucfirst($f) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-7">
                    <input type="text" name="search" class="form-control form-control-sm" value="{{ $search }}" placeholder="Cari nama file, Alt Text SEO, atau caption...">
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i>Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        @forelse ($mediaFiles as $media)
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden position-relative group-hover">
                <div class="ratio ratio-1x1 bg-light d-flex align-items-center justify-content-center">
                    @if ($media->isImage())
                        <img src="{{ $media->url() }}" alt="{{ $media->alt_text }}" class="object-fit-cover w-100 h-100">
                    @else
                        <div class="p-3 text-center text-muted">
                            <i class="bi bi-file-earmark-text fs-1 d-block mb-1 text-primary"></i>
                            <span class="small font-monospace text-uppercase" style="font-size:0.7rem;">{{ pathinfo($media->filename, PATHINFO_EXTENSION) }}</span>
                        </div>
                    @endif
                </div>
                <div class="card-body p-2">
                    <div class="fw-semibold text-truncate small text-dark" title="{{ $media->original_name }}">{{ $media->original_name }}</div>
                    <div class="d-flex justify-content-between align-items-center mt-1 text-muted" style="font-size:0.7rem;">
                        <span>{{ $media->formattedSize() }}</span>
                        <span class="badge bg-secondary-subtle text-secondary">{{ $media->folder }}</span>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 p-2 pt-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-link p-0 text-primary" data-bs-toggle="modal" data-bs-target="#editMedia{{ $media->id }}" title="Edit Meta / Alt Text SEO">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <a href="{{ $media->url() }}" target="_blank" class="btn btn-sm btn-link p-0 text-info" title="Buka Link">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                    <form action="{{ route('admin.media.destroy', $media) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus file media ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-link p-0 text-danger" title="Hapus File"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editMedia{{ $media->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('admin.media.update', $media) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title fw-bold">Meta Info File: {{ $media->original_name }}</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Alt Text (Optimasi SEO Gambar)</label>
                                <input type="text" name="alt_text" class="form-control" value="{{ $media->alt_text }}" placeholder="Deskripsi gambar untuk pembaca layar & Google SEO">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Caption / Keterangan File</label>
                                <input type="text" name="caption" class="form-control" value="{{ $media->caption }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Folder Kategori</label>
                                <input type="text" name="folder" class="form-control" value="{{ $media->folder }}">
                            </div>
                            <div class="mb-0">
                                <label class="form-label small fw-semibold">Direct URL File</label>
                                <input type="text" readonly class="form-control form-control-sm font-monospace" value="{{ $media->url() }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm">Simpan Meta SEO</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted border rounded-3 bg-white">
            <i class="bi bi-images fs-1 d-block mb-2 text-secondary"></i>
            <div class="fw-semibold">Belum Ada Media di Media Library</div>
            <small>Klik tombol "+ Upload Media Massal" di atas untuk mengunggah gambar dan dokumen.</small>
        </div>
        @endforelse
    </div>

    @if ($mediaFiles->hasPages())
    <div class="mt-4">
        {{ $mediaFiles->links() }}
    </div>
    @endif

</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Upload Media Massal</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih File (Bisa Pilih Banyak File Sekaligus)</label>
                        <input type="file" name="files[]" class="form-control" multiple required>
                        <small class="text-muted" style="font-size:0.75rem;">Mendukung gambar (JPG, PNG, WEBP), dokumen (PDF, DOCX, XLSX), dan ZIP (Max 10MB per file).</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Folder Kategori</label>
                        <input type="text" name="folder" class="form-control" placeholder="general, banner, berita, fasilitas">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alt Text SEO (Default)</label>
                        <input type="text" name="alt_text" class="form-control" placeholder="Kata kunci SEO gambar">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Mulai Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
