@extends('layouts.admin')

@section('title', 'Master Data Jurusan & Program Keahlian')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/departments/index.css') }}">

<div class="departments-page">


{{-- HERO --}}
<div class="departments-hero">
    <div class="departments-hero-content">
        <span class="departments-hero-label">MASTER DATA</span>

        <h1>Jurusan & Program Keahlian</h1>

        <p>
            Kelola daftar program keahlian dan konsentrasi keahlian yang tersedia di sekolah.
        </p>
    </div>

    <button type="button"
            class="departments-add-btn"
            data-bs-toggle="modal"
            data-bs-target="#createModal">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Jurusan</span>
    </button>
</div>


{{-- DATA JURUSAN --}}
<div class="departments-section">

    <div class="departments-section-header">
        <div>
            <span class="departments-section-label">DATA JURUSAN</span>

            <h2>
                <i class="bi bi-journal-bookmark-fill"></i>
                Daftar Jurusan & Program Keahlian
            </h2>
        </div>

        <div class="departments-count">
            {{ $departments->total() }} Data
        </div>
    </div>


    {{-- TABLE --}}
    <div class="departments-table-wrapper">
        <table class="departments-table">

            <thead>
                <tr>
                    <th class="col-no">No.</th>
                    <th>Kode</th>
                    <th>Nama Jurusan / Program Keahlian</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Status</th>
                    <th class="text-end col-action">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($departments as $dept)

                    <tr>

                        <td class="department-number">
                            {{ $loop->iteration + $departments->firstItem() - 1 }}
                        </td>

                        <td>
                            <span class="department-code">
                                {{ $dept->code }}
                            </span>
                        </td>

                        <td>
                            <span class="department-name">
                                {{ $dept->name }}
                            </span>
                        </td>

                        <td>
                            <span class="department-description">
                                {{ $dept->description ?? '—' }}
                            </span>
                        </td>

                        <td class="text-center">
                            @if ($dept->is_active)
                                <span class="department-status status-active">
                                    Aktif
                                </span>
                            @else
                                <span class="department-status status-inactive">
                                    Non-Aktif
                                </span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="department-actions">

                                <button type="button"
                                        class="department-action-btn edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $dept->id }}"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('admin.departments.destroy', $dept) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus jurusan ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="department-action-btn delete"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>


                    {{-- EDIT MODAL --}}
                    <div class="modal fade department-modal"
                         id="editModal{{ $dept->id }}"
                         tabindex="-1"
                         aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered">

                            <form action="{{ route('admin.departments.update', $dept) }}"
                                  method="POST"
                                  class="modal-content">

                                @csrf
                                @method('PUT')

                                <div class="modal-header">

                                    <div>
                                        <span class="modal-label">
                                            DATA JURUSAN
                                        </span>

                                        <h5 class="modal-title">
                                            Edit Jurusan
                                        </h5>
                                    </div>

                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"></button>

                                </div>


                                <div class="modal-body">

                                    <div class="form-grid">

                                        <div class="form-group">
                                            <label for="code{{ $dept->id }}">
                                                Kode Jurusan
                                            </label>

                                            <input type="text"
                                                   id="code{{ $dept->id }}"
                                                   name="code"
                                                   class="form-control"
                                                   value="{{ $dept->code }}"
                                                   required>
                                        </div>

                                        <div class="form-group">
                                            <label for="name{{ $dept->id }}">
                                                Nama Jurusan
                                            </label>

                                            <input type="text"
                                                   id="name{{ $dept->id }}"
                                                   name="name"
                                                   class="form-control"
                                                   value="{{ $dept->name }}"
                                                   required>
                                        </div>

                                    </div>


                                    <div class="form-group">
                                        <label for="description{{ $dept->id }}">
                                            Deskripsi
                                        </label>

                                        <textarea name="description"
                                                  id="description{{ $dept->id }}"
                                                  class="form-control"
                                                  rows="3">{{ $dept->description }}</textarea>
                                    </div>


                                    <div class="active-option">

                                        <input type="checkbox"
                                               name="is_active"
                                               value="1"
                                               class="form-check-input"
                                               id="act{{ $dept->id }}"
                                               @checked($dept->is_active)>

                                        <label for="act{{ $dept->id }}">
                                            <strong>Jurusan Aktif</strong>

                                            <span>
                                                Jurusan dapat digunakan dalam sistem.
                                            </span>
                                        </label>

                                    </div>

                                </div>


                                <div class="modal-footer">

                                    <button type="button"
                                            class="modal-cancel"
                                            data-bs-dismiss="modal">
                                        Batal
                                    </button>

                                    <button type="submit"
                                            class="modal-save">
                                        Simpan Perubahan
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                @empty

                    <tr>
                        <td colspan="6">

                            <div class="departments-empty">

                                <i class="bi bi-journal-x"></i>

                                <strong>
                                    Belum ada data jurusan
                                </strong>

                                <span>
                                    Tambahkan jurusan atau program keahlian untuk mulai mengelola data.
                                </span>

                            </div>

                        </td>
                    </tr>

                @endforelse
            </tbody>

        </table>
    </div>


    {{-- PAGINATION --}}
    @if ($departments->hasPages())

        <div class="departments-pagination">
            {{ $departments->links() }}
        </div>

    @endif

</div>


</div>

{{-- CREATE MODAL --}}

<div class="modal fade department-modal"
     id="createModal"
     tabindex="-1"
     aria-hidden="true">


<div class="modal-dialog modal-dialog-centered">

    <form action="{{ route('admin.departments.store') }}"
          method="POST"
          class="modal-content">

        @csrf

        <div class="modal-header">

            <div>
                <span class="modal-label">
                    MASTER DATA
                </span>

                <h5 class="modal-title">
                    Tambah Jurusan Baru
                </h5>
            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>

        </div>


        <div class="modal-body">

            <div class="form-grid">

                <div class="form-group">

                    <label for="createCode">
                        Kode Jurusan
                    </label>

                    <input type="text"
                           id="createCode"
                           name="code"
                           class="form-control"
                           placeholder="RPL"
                           required>

                    <small class="form-help">
                        Contoh: RPL, TKJ, IPA
                    </small>

                </div>


                <div class="form-group">

                    <label for="createName">
                        Nama Jurusan
                    </label>

                    <input type="text"
                           id="createName"
                           name="name"
                           class="form-control"
                           placeholder="Rekayasa Perangkat Lunak"
                           required>

                </div>

            </div>


            <div class="form-group">

                <label for="createDescription">
                    Deskripsi
                </label>

                <textarea name="description"
                          id="createDescription"
                          class="form-control"
                          rows="3"
                          placeholder="Deskripsi singkat mengenai program keahlian..."></textarea>

                <small class="form-help">
                    Opsional.
                </small>

            </div>

        </div>


        <div class="modal-footer">

            <button type="button"
                    class="modal-cancel"
                    data-bs-dismiss="modal">
                Batal
            </button>

            <button type="submit"
                    class="modal-save">
                Simpan Jurusan
            </button>

        </div>

    </form>

</div>


</div>
@endsection
