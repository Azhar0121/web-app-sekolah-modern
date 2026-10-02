@extends('layouts.admin')

@section('title', 'Media Library & File Manager')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/media/index.css') }}">

<div class="media-page">


{{-- =====================================================
     HERO
====================================================== --}}
<div class="media-hero">

    <div class="media-hero-content">

        <div class="media-hero-text">
            <span class="media-hero-label">MEDIA & FILE MANAGEMENT</span>

            <h1>Media Library & File Manager</h1>

            <p>
                Kelola gambar dan dokumen, atur folder media,
                optimasi ukuran file, serta kelola Alt Text SEO.
            </p>
        </div>

        <div class="media-hero-icon">
            <i class="bi bi-images"></i>
        </div>

    </div>

</div>


{{-- =====================================================
     FILTER
====================================================== --}}
<div class="media-filter-card">

    <div class="media-filter-header">

        <div class="media-filter-title">
            <div class="media-section-icon">
                <i class="bi bi-funnel"></i>
            </div>

            <div>
                <span>MEDIA LIBRARY</span>
                <h2>Filter & Pencarian</h2>
            </div>
        </div>

        <button
            type="button"
            class="media-upload-button"
            data-bs-toggle="modal"
            data-bs-target="#uploadModal"
        >
            <i class="bi bi-cloud-upload"></i>
            <span>Upload Media Massal</span>
        </button>

    </div>

    <div class="media-filter-body">

        <form
            method="GET"
            action="{{ route('admin.media.index') }}"
            class="media-filter-form"
        >

            <div class="media-filter-field media-folder-field">
                <label for="folder">Folder</label>

                <select
                    name="folder"
                    id="folder"
                    class="media-select"
                    onchange="this.form.submit()"
                >
                    <option value="all">Semua Folder</option>

                    @foreach ($folders as $f)
                        <option
                            value="{{ $f }}"
                            @selected($folderFilter === $f)
                        >
                            Folder: {{ ucfirst($f) }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="media-filter-field media-search-field">
                <label for="search">Pencarian</label>

                <div class="media-search-box">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ $search }}"
                        placeholder="Cari nama file, Alt Text SEO, atau caption..."
                    >

                </div>
            </div>


            <div class="media-filter-action">

                <button type="submit" class="media-search-button">
                    <i class="bi bi-search"></i>
                    <span>Cari Media</span>
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =====================================================
     MEDIA GRID
====================================================== --}}
<div class="media-library-section">

    <div class="media-section-heading">

        <div>
            <span class="media-section-label">FILE TERSEDIA</span>
            <h2>Media Library</h2>
        </div>

        <span class="media-count">
            {{ $mediaFiles->total() }} File
        </span>

    </div>


    <div class="media-grid">

        @forelse ($mediaFiles as $media)

            <div class="media-item">

                {{-- PREVIEW --}}
                <div class="media-preview">

                    @if ($media->isImage())

                        <img
                            src="{{ $media->url() }}"
                            alt="{{ $media->alt_text }}"
                            loading="lazy"
                        >

                    @else

                        <div class="media-document-preview">

                            <div class="media-document-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>

                            <span>
                                {{ strtoupper(pathinfo($media->filename, PATHINFO_EXTENSION)) }}
                            </span>

                        </div>

                    @endif

                    <div class="media-preview-overlay">
                        <a
                            href="{{ $media->url() }}"
                            target="_blank"
                            class="media-preview-link"
                            title="Buka File"
                        >
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>

                </div>


                {{-- INFO --}}
                <div class="media-item-body">

                    <div
                        class="media-file-name"
                        title="{{ $media->original_name }}"
                    >
                        {{ $media->original_name }}
                    </div>

                    <div class="media-file-meta">

                        <span>
                            {{ $media->formattedSize() }}
                        </span>

                        <span class="media-folder-badge">
                            {{ $media->folder }}
                        </span>

                    </div>

                </div>


                {{-- ACTION --}}
                <div class="media-item-actions">

                    <button
                        type="button"
                        class="media-action-button media-action-edit"
                        data-bs-toggle="modal"
                        data-bs-target="#editMedia{{ $media->id }}"
                        title="Edit Meta / Alt Text SEO"
                    >
                        <i class="bi bi-pencil-square"></i>
                        <span>Edit</span>
                    </button>


                    <a
                        href="{{ $media->url() }}"
                        target="_blank"
                        class="media-action-button media-action-open"
                        title="Buka Link"
                    >
                        <i class="bi bi-box-arrow-up-right"></i>
                        <span>Buka</span>
                    </a>


                    <form
                        action="{{ route('admin.media.destroy', $media) }}"
                        method="POST"
                        class="media-delete-form"
                        onsubmit="return confirm('Hapus file media ini?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="media-action-button media-action-delete"
                            title="Hapus File"
                        >
                            <i class="bi bi-trash"></i>
                            <span>Hapus</span>
                        </button>

                    </form>

                </div>

            </div>


            {{-- =================================================
                 EDIT MODAL
            ================================================== --}}
            <div
                class="modal fade media-modal"
                id="editMedia{{ $media->id }}"
                tabindex="-1"
                aria-hidden="true"
            >

                <div class="modal-dialog modal-dialog-centered">

                    <form
                        action="{{ route('admin.media.update', $media) }}"
                        method="POST"
                        class="media-modal-form"
                    >

                        @csrf
                        @method('PUT')

                        <div class="modal-content">

                            <div class="modal-header">

                                <div class="media-modal-title">

                                    <div class="media-modal-icon">
                                        <i class="bi bi-pencil-square"></i>
                                    </div>

                                    <div>
                                        <span>MEDIA</span>
                                        <h5>Meta Info File</h5>
                                    </div>

                                </div>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                                ></button>

                            </div>


                            <div class="modal-body">

                                <div class="media-modal-file-name">
                                    {{ $media->original_name }}
                                </div>


                                <div class="media-modal-field">

                                    <label for="alt_text_{{ $media->id }}">
                                        Alt Text SEO
                                    </label>

                                    <input
                                        type="text"
                                        name="alt_text"
                                        id="alt_text_{{ $media->id }}"
                                        class="media-modal-input"
                                        value="{{ $media->alt_text }}"
                                        placeholder="Deskripsi gambar untuk pembaca layar & Google SEO"
                                    >

                                </div>


                                <div class="media-modal-field">

                                    <label for="caption_{{ $media->id }}">
                                        Caption / Keterangan File
                                    </label>

                                    <input
                                        type="text"
                                        name="caption"
                                        id="caption_{{ $media->id }}"
                                        class="media-modal-input"
                                        value="{{ $media->caption }}"
                                    >

                                </div>


                                <div class="media-modal-field">

                                    <label for="folder_{{ $media->id }}">
                                        Folder Kategori
                                    </label>

                                    <input
                                        type="text"
                                        name="folder"
                                        id="folder_{{ $media->id }}"
                                        class="media-modal-input"
                                        value="{{ $media->folder }}"
                                    >

                                </div>


                                <div class="media-modal-field media-modal-field-last">

                                    <label for="url_{{ $media->id }}">
                                        Direct URL File
                                    </label>

                                    <input
                                        type="text"
                                        id="url_{{ $media->id }}"
                                        class="media-modal-input media-modal-url"
                                        value="{{ $media->url() }}"
                                        readonly
                                    >

                                </div>

                            </div>


                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="media-modal-cancel"
                                    data-bs-dismiss="modal"
                                >
                                    Batal
                                </button>

                                <button
                                    type="submit"
                                    class="media-modal-save"
                                >
                                    <i class="bi bi-check-circle"></i>
                                    Simpan Meta SEO
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        @empty

            <div class="media-empty">

                <div class="media-empty-icon">
                    <i class="bi bi-images"></i>
                </div>

                <span class="media-empty-label">MEDIA LIBRARY</span>

                <h3>Belum Ada Media</h3>

                <p>
                    Belum ada gambar atau dokumen yang tersedia.
                    Gunakan tombol Upload Media Massal untuk menambahkan file.
                </p>

                <button
                    type="button"
                    class="media-empty-button"
                    data-bs-toggle="modal"
                    data-bs-target="#uploadModal"
                >
                    <i class="bi bi-cloud-upload"></i>
                    Upload Media Massal
                </button>

            </div>

        @endforelse

    </div>


    {{-- PAGINATION --}}
    @if ($mediaFiles->hasPages())

        <div class="media-pagination">
            {{ $mediaFiles->links() }}
        </div>

    @endif

</div>


</div>

{{-- =========================================================
UPLOAD MODAL
========================================================= --}}

<div
    class="modal fade media-modal"
    id="uploadModal"
    tabindex="-1"
    aria-hidden="true"
>


<div class="modal-dialog modal-dialog-centered">

    <form
        action="{{ route('admin.media.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="media-modal-form"
    >

        @csrf

        <div class="modal-content">

            <div class="modal-header">

                <div class="media-modal-title">

                    <div class="media-modal-icon">
                        <i class="bi bi-cloud-upload"></i>
                    </div>

                    <div>
                        <span>MEDIA LIBRARY</span>
                        <h5>Upload Media Massal</h5>
                    </div>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">

                <div class="media-upload-info">
                    <i class="bi bi-info-circle"></i>

                    <span>
                        Pilih satu atau beberapa file sekaligus.
                        Maksimal ukuran 10MB per file.
                    </span>
                </div>


                <div class="media-modal-field">

                    <label for="media_files">
                        Pilih File
                    </label>

                    <input
                        type="file"
                        name="files[]"
                        id="media_files"
                        class="media-file-input"
                        multiple
                        required
                    >

                    <small>
                        Mendukung JPG, PNG, WEBP, PDF, DOCX, XLSX, dan ZIP.
                    </small>

                </div>


                <div class="media-modal-field">

                    <label for="upload_folder">
                        Folder Kategori
                    </label>

                    <input
                        type="text"
                        name="folder"
                        id="upload_folder"
                        class="media-modal-input"
                        placeholder="general, banner, berita, fasilitas"
                    >

                </div>


                <div class="media-modal-field media-modal-field-last">

                    <label for="upload_alt_text">
                        Alt Text SEO (Default)
                    </label>

                    <input
                        type="text"
                        name="alt_text"
                        id="upload_alt_text"
                        class="media-modal-input"
                        placeholder="Kata kunci SEO gambar"
                    >

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="media-modal-cancel"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="media-modal-save"
                >
                    <i class="bi bi-cloud-arrow-up"></i>
                    Mulai Upload
                </button>

            </div>

        </div>

    </form>

</div>


</div>

@endsection
