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
                                <div class="d-flex align-items-center gap-2">
                                    @if ($room->photo_url)
                                        <img src="{{ $room->photo_url }}" alt="{{ $room->name }}" class="rounded border object-fit-cover flex-shrink-0" style="width: 44px; height: 38px;">
                                    @else
                                        <div class="rounded bg-light border d-flex align-items-center justify-content-center text-muted flex-shrink-0" style="width: 44px; height: 38px; font-size: 14px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="room-name d-block">
                                            {{ $room->name }}
                                        </span>
                                        @if (!empty($room->gallery) && count($room->gallery) > 0)
                                            <small class="badge bg-secondary-subtle text-secondary border py-0 px-1" style="font-size: 10px;">
                                                <i class="bi bi-images me-1"></i>+{{ count($room->gallery) }} foto galeri
                                            </small>
                                        @endif
                                    </div>
                                </div>
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

{{-- EDIT MODALS (Diletakkan di luar struktur tabel agar valid HTML dan rendering rapi) --}}
@foreach ($rooms as $room)
    @include('admin.rooms.partials.edit-modal', ['room' => $room])
@endforeach

{{-- CREATE MODAL --}}
@include('admin.rooms.partials.create-modal')

@endsection
