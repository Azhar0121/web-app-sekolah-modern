{{-- resources/views/admin/rooms/partials/create-modal.blade.php --}}
<div class="modal fade room-modal"
     id="createModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">

        <form action="{{ route('admin.rooms.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="modal-content">

            @csrf

            <div class="modal-header">
                <div>
                    <span class="modal-label">MASTER DATA RUANGAN</span>
                    <h5 class="modal-title">
                        Tambah Ruangan / Fasilitas Baru
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
                        <label for="createCode">
                            Kode Ruangan <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="createCode"
                               name="code"
                               class="form-control"
                               placeholder="Contoh: R-101 / LAB-01"
                               value="{{ old('code') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="createType">
                            Tipe Ruangan <span class="text-danger">*</span>
                        </label>

                        <select name="type"
                                id="createType"
                                class="form-select"
                                required>
                            <option value="kelas" @selected(old('type') === 'kelas')>
                                Ruang Kelas
                            </option>

                            <option value="laboratorium" @selected(old('type') === 'laboratorium')>
                                Laboratorium
                            </option>

                            <option value="perpustakaan" @selected(old('type') === 'perpustakaan')>
                                Perpustakaan
                            </option>

                            <option value="aula" @selected(old('type') === 'aula')>
                                Aula
                            </option>

                            <option value="lapangan" @selected(old('type') === 'lapangan')>
                                Lapangan
                            </option>
                        </select>
                    </div>

                </div>

                {{-- NAMA RUANGAN --}}
                <div class="form-group">
                    <label for="createName">
                        Nama Ruangan / Fasilitas <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           id="createName"
                           name="name"
                           class="form-control"
                           placeholder="Contoh: Laboratorium Komputer Modern 1"
                           value="{{ old('name') }}"
                           required>
                </div>

                {{-- KAPASITAS & LOKASI --}}
                <div class="form-grid">

                    <div class="form-group">
                        <label for="createCapacity">
                            Kapasitas (Kursi / Orang)
                        </label>

                        <input type="number"
                               id="createCapacity"
                               name="capacity"
                               class="form-control"
                               placeholder="Contoh: 36"
                               value="{{ old('capacity') }}">
                    </div>

                    <div class="form-group">
                        <label for="createLocation">
                            Lokasi / Gedung
                        </label>

                        <input type="text"
                               id="createLocation"
                               name="location"
                               class="form-control"
                               placeholder="Contoh: Lantai 2 Gedung B"
                               value="{{ old('location') }}">
                    </div>

                </div>

                {{-- DESKRIPSI FASILITAS --}}
                <div class="form-group">
                    <label for="createDescription">
                        Deskripsi & Penjelasan Fasilitas
                    </label>
                    <textarea id="createDescription"
                              name="description"
                              class="form-control"
                              rows="3"
                              placeholder="Jelaskan fasilitas, spesifikasi perlengkapan, kapasitas komputer/alat, dan kegunaan ruangan...">{{ old('description') }}</textarea>
                    <span class="form-help-text">Deskripsi ini akan tampil saat pengunjung mengklik kartu ruangan di halaman fasilitas profil sekolah.</span>
                </div>

                {{-- FOTO UTAMA --}}
                <div class="form-group mt-3">
                    <label for="createPhoto" class="fw-semibold">
                        Foto Utama Ruangan
                    </label>
                    <input type="file"
                           id="createPhoto"
                           name="photo"
                           class="form-control"
                           accept="image/png,image/jpeg,image/webp">
                    <span class="form-help-text">Format didukung: JPG, PNG, WEBP (Maksimal 3MB). Foto ini akan menjadi thumbnail kartu fasilitas sekolah.</span>
                </div>

                {{-- GALERI FOTO TAMBAHAN --}}
                <div class="form-group mt-3">
                    <label for="createGallery" class="fw-semibold">
                        Galeri Foto Ruangan (Bisa Banyak Foto)
                    </label>
                    <input type="file"
                           id="createGallery"
                           name="gallery[]"
                           class="form-control"
                           multiple
                           accept="image/png,image/jpeg,image/webp">
                    <span class="form-help-text">Tahan Ctrl / Cmd untuk memilih beberapa foto sekaligus untuk galeri slide popup ruangan.</span>
                </div>

                {{-- STATUS RUANGAN AKTIF --}}
                <div class="active-option mt-4">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="form-check-input"
                           id="createIsActive"
                           checked>

                    <label for="createIsActive">
                        <strong>Ruangan Siap Digunakan (Aktif)</strong>
                        <span>
                            Ruangan aktif akan langsung tersedia untuk jadwal kelas dan publikasi profil sekolah.
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
                    <i class="bi bi-plus-lg me-1"></i> Simpan Ruangan
                </button>
            </div>

        </form>

    </div>
</div>
