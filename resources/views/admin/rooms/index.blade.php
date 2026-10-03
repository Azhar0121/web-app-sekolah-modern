@extends('layouts.admin')

@section('title', 'Master Data Ruangan & Fasilitas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/rooms/index.css') }}">

<div class="rooms-page">


{{-- HERO --}}
<div class="rooms-hero">
    <div class="rooms-hero-content">
        <span class="rooms-hero-label">MASTER DATA</span>
        <h1>Ruangan & Fasilitas</h1>
        <p>Kelola data ruang kelas, laboratorium, perpustakaan, dan fasilitas sekolah.</p>
    </div>

    <button type="button"
            class="rooms-add-btn"
            data-bs-toggle="modal"
            data-bs-target="#createModal">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Ruangan</span>
    </button>
</div>

{{-- DATA RUANGAN --}}
<div class="rooms-section">

    <div class="rooms-section-header">
        <div>
            <span class="rooms-section-label">DATA RUANGAN</span>

            <h2>
                <i class="bi bi-grid-1x2-fill"></i>
                Daftar Ruangan & Fasilitas
            </h2>
        </div>

        <div class="rooms-count">
            {{ $rooms->total() }} Data
        </div>
    </div>

    {{-- TABLE --}}
    <div class="rooms-table-wrapper">
        <table class="rooms-table">
            <thead>
                <tr>
                    <th class="col-no">No.</th>
                    <th>Kode</th>
                    <th>Nama Ruangan / Fasilitas</th>
                    <th>Tipe</th>
                    <th>Kapasitas</th>
                    <th>Lokasi</th>
                    <th class="text-center">Status</th>
                    <th class="text-end col-action">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($rooms as $room)
                    <tr>
                        <td class="room-number">
                            {{ $loop->iteration + $rooms->firstItem() - 1 }}
                        </td>

                        <td>
                            <span class="room-code">
                                {{ $room->code }}
                            </span>
                        </td>

                        <td>
                            <span class="room-name">
                                {{ $room->name }}
                            </span>
                        </td>

                        <td>
                            <span class="room-type">
                                {{ $room->typeLabel() }}
                            </span>
                        </td>

                        <td>
                            <span class="room-capacity">
                                {{ $room->capacity ? $room->capacity . ' Kursi' : '—' }}
                            </span>
                        </td>

                        <td>
                            <span class="room-location">
                                {{ $room->location ?? '—' }}
                            </span>
                        </td>

                        <td class="text-center">
                            @if ($room->is_active)
                                <span class="room-status status-active">
                                    Siap Pakai
                                </span>
                            @else
                                <span class="room-status status-inactive">
                                    Non-Aktif
                                </span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="room-actions">

                                <button type="button"
                                        class="room-action-btn edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $room->id }}"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('admin.rooms.destroy', $room) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus ruangan ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="room-action-btn delete"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                    {{-- EDIT MODAL --}}
                    <div class="modal fade room-modal"
                         id="editModal{{ $room->id }}"
                         tabindex="-1"
                         aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered">

                            <form action="{{ route('admin.rooms.update', $room) }}"
                                  method="POST"
                                  class="modal-content">

                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <div>
                                        <span class="modal-label">DATA RUANGAN</span>
                                        <h5 class="modal-title">
                                            Edit Data Ruangan
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
                                            <label for="code{{ $room->id }}">
                                                Kode Ruangan
                                            </label>

                                            <input type="text"
                                                   id="code{{ $room->id }}"
                                                   name="code"
                                                   class="form-control"
                                                   value="{{ $room->code }}"
                                                   required>
                                        </div>

                                        <div class="form-group">
                                            <label for="type{{ $room->id }}">
                                                Tipe Ruangan
                                            </label>

                                            <select name="type"
                                                    id="type{{ $room->id }}"
                                                    class="form-select"
                                                    required>
                                                <option value="kelas" @selected($room->type === 'kelas')>
                                                    Ruang Kelas
                                                </option>

                                                <option value="laboratorium" @selected($room->type === 'laboratorium')>
                                                    Laboratorium
                                                </option>

                                                <option value="perpustakaan" @selected($room->type === 'perpustakaan')>
                                                    Perpustakaan
                                                </option>

                                                <option value="aula" @selected($room->type === 'aula')>
                                                    Aula
                                                </option>

                                                <option value="lapangan" @selected($room->type === 'lapangan')>
                                                    Lapangan
                                                </option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label for="name{{ $room->id }}">
                                            Nama Ruangan
                                        </label>

                                        <input type="text"
                                               id="name{{ $room->id }}"
                                               name="name"
                                               class="form-control"
                                               value="{{ $room->name }}"
                                               required>
                                    </div>

                                    <div class="form-grid">

                                        <div class="form-group">
                                            <label for="capacity{{ $room->id }}">
                                                Kapasitas (Kursi)
                                            </label>

                                            <input type="number"
                                                   id="capacity{{ $room->id }}"
                                                   name="capacity"
                                                   class="form-control"
                                                   value="{{ $room->capacity }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="location{{ $room->id }}">
                                                Lokasi / Gedung
                                            </label>

                                            <input type="text"
                                                   id="location{{ $room->id }}"
                                                   name="location"
                                                   class="form-control"
                                                   value="{{ $room->location }}">
                                        </div>

                                    </div>

                                    <div class="active-option">
                                        <input type="checkbox"
                                               name="is_active"
                                               value="1"
                                               class="form-check-input"
                                               id="actR{{ $room->id }}"
                                               @checked($room->is_active)>

                                        <label for="actR{{ $room->id }}">
                                            <strong>Ruangan Aktif</strong>
                                            <span>
                                                Ruangan dapat digunakan dalam sistem.
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
                        <td colspan="8">
                            <div class="rooms-empty">
                                <i class="bi bi-door-open"></i>

                                <strong>
                                    Belum ada data ruangan
                                </strong>

                                <span>
                                    Tambahkan ruangan atau fasilitas untuk mulai mengelola data.
                                </span>
                            </div>
                        </td>
                    </tr>

                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if ($rooms->hasPages())
        <div class="rooms-pagination">
            {{ $rooms->links() }}
        </div>
    @endif

</div>


</div>

{{-- CREATE MODAL --}}

<div class="modal fade room-modal"
     id="createModal"
     tabindex="-1"
     aria-hidden="true">


<div class="modal-dialog modal-dialog-centered">

    <form action="{{ route('admin.rooms.store') }}"
          method="POST"
          class="modal-content">

        @csrf

        <div class="modal-header">
            <div>
                <span class="modal-label">MASTER DATA</span>

                <h5 class="modal-title">
                    Tambah Ruangan Baru
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
                        Kode Ruangan
                    </label>

                    <input type="text"
                           id="createCode"
                           name="code"
                           class="form-control"
                           placeholder="R-101"
                           required>
                </div>

                <div class="form-group">
                    <label for="createType">
                        Tipe Ruangan
                    </label>

                    <select name="type"
                            id="createType"
                            class="form-select"
                            required>
                        <option value="kelas">
                            Ruang Kelas
                        </option>

                        <option value="laboratorium">
                            Laboratorium
                        </option>

                        <option value="perpustakaan">
                            Perpustakaan
                        </option>

                        <option value="aula">
                            Aula
                        </option>

                        <option value="lapangan">
                            Lapangan
                        </option>
                    </select>
                </div>

            </div>

            <div class="form-group">
                <label for="createName">
                    Nama Ruangan
                </label>

                <input type="text"
                       id="createName"
                       name="name"
                       class="form-control"
                       placeholder="Laboratorium Komputer 1"
                       required>
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="createCapacity">
                        Kapasitas (Kursi)
                    </label>

                    <input type="number"
                           id="createCapacity"
                           name="capacity"
                           class="form-control"
                           placeholder="36">
                </div>

                <div class="form-group">
                    <label for="createLocation">
                        Lokasi / Gedung
                    </label>

                    <input type="text"
                           id="createLocation"
                           name="location"
                           class="form-control"
                           placeholder="Lantai 2 Gedung B">
                </div>

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
                Simpan Ruangan
            </button>
        </div>

    </form>

</div>


</div>
@endsection
