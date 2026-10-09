{{-- resources/views/admin/rooms/partials/edit-modal.blade.php --}}
<div class="modal fade room-modal"
     id="editModal{{ $room->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">

        <form action="{{ route('admin.rooms.update', $room) }}"
              method="POST"
              enctype="multipart/form-data"
              class="modal-content">

            @csrf
            @method('PUT')

            <div class="modal-header">
                <div>
                    <span class="modal-label">DATA RUANGAN & FASILITAS</span>
                    <h5 class="modal-title">
                        Edit Ruangan: {{ $room->name }}
                    </h5>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">

                {{-- KODE & TIPE RUANGAN --}}
                <div class="form-grid">

                    <div class="form-group">
                        <label for="code{{ $room->id }}">
                            Kode Ruangan <span class="text-danger">*</span>
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
                            Tipe Ruangan <span class="text-danger">*</span>
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

                {{-- NAMA RUANGAN --}}
                <div class="form-group">
                    <label for="name{{ $room->id }}">
                        Nama Ruangan / Fasilitas <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           id="name{{ $room->id }}"
                           name="name"
                           class="form-control"
                           value="{{ $room->name }}"
                           required>
                </div>

                {{-- KAPASITAS & LOKASI --}}
                <div class="form-grid">

                    <div class="form-group">
                        <label for="capacity{{ $room->id }}">
                            Kapasitas (Kursi / Orang)
                        </label>

                        <input type="number"
                               id="capacity{{ $room->id }}"
                               name="capacity"
                               class="form-control"
                               placeholder="Contoh: 36"
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
                               placeholder="Contoh: Lantai 2 Gedung B"
                               value="{{ $room->location }}">
                    </div>

                </div>

                {{-- DESKRIPSI FASILITAS --}}
                <div class="form-group">
                    <label for="desc{{ $room->id }}">
                        Deskripsi & Penjelasan Fasilitas
                    </label>
                    <textarea id="desc{{ $room->id }}"
                              name="description"
                              class="form-control"
                              rows="3"
                              placeholder="Jelaskan fasilitas, spesifikasi perlengkapan, kapasitas alat, dan kegunaan ruangan ini...">{{ $room->description }}</textarea>
                    <span class="form-help-text">Deskripsi ini akan tampil saat pengunjung mengklik kartu ruangan di halaman fasilitas profil sekolah.</span>
                </div>

                {{-- FOTO UTAMA --}}
                <div class="form-group mt-3">
                    <label for="photo{{ $room->id }}" class="fw-semibold">
                        Foto Utama Ruangan
                    </label>

                    @if ($room->photo_url)
                        <div class="room-photo-card">
                            <img src="{{ $room->photo_url }}" alt="{{ $room->name }}" class="room-photo-card-img">
                            <div class="flex-grow-1">
                                <div class="room-photo-card-label">Foto Utama Saat Ini</div>
                                <div class="form-check mt-1">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="remove_photo"
                                           id="remPhoto{{ $room->id }}"
                                           value="1">
                                    <label class="form-check-label text-danger" for="remPhoto{{ $room->id }}">
                                        <i class="bi bi-trash me-1"></i>Hapus foto utama ini
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif

                    <input type="file"
                           name="photo"
                           id="photo{{ $room->id }}"
                           class="form-control"
                           accept="image/png,image/jpeg,image/webp">
                    <span class="form-help-text">Format didukung: JPG, PNG, WEBP (Maksimal 3MB). Mengunggah foto baru akan mengganti foto utama lama.</span>
                </div>

                {{-- GALERI FOTO TAMBAHAN --}}
                <div class="form-group mt-3">
                    <label for="gallery{{ $room->id }}" class="fw-semibold">
                        Galeri Foto Tambahan (Bisa Banyak Foto)
                    </label>

                    @if (!empty($room->gallery) && is_array($room->gallery) && count($room->gallery) > 0)
                        <div class="mb-2">
                            <span class="form-help-text mb-2 d-block">Centang foto yang ingin dihapus dari galeri:</span>
                            <div class="room-gallery-grid">
                                @foreach ($room->gallery as $gPath)
                                    @if ($gPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($gPath))
                                        <div class="room-gallery-card">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($gPath) }}" alt="Foto Galeri">
                                            <label>
                                                <input type="checkbox" name="delete_gallery[]" value="{{ $gPath }}"> Hapus
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <input type="file"
                           name="gallery[]"
                           id="gallery{{ $room->id }}"
                           class="form-control"
                           multiple
                           accept="image/png,image/jpeg,image/webp">
                    <span class="form-help-text">Tahan Ctrl / Cmd untuk memilih beberapa file sekaligus. Foto-foto ini akan tampil pada pop-up galeri ruangan.</span>
                </div>

                {{-- STATUS RUANGAN AKTIF --}}
                <div class="active-option mt-4">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="form-check-input"
                           id="actR{{ $room->id }}"
                           @checked($room->is_active)>

                    <label for="actR{{ $room->id }}">
                        <strong>Ruangan Siap Digunakan (Aktif)</strong>
                        <span>
                            Ruangan aktif akan ditampilkan di jadwal pelajaran dan katalog fasilitas publik.
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
                    <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>
