<div class="modal fade act-modal" id="createEkskulModal" tabindex="-1" aria-labelledby="createEkskulLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.student-activities.extracurriculars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <div>
                        <span class="act-modal-label">Pengembangan Bakat Siswa</span>
                        <h5 class="modal-title" id="createEkskulLabel">Tambah Ekstrakurikuler Baru</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_ekskul_name">Nama Ekstrakurikuler <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="create_ekskul_name" class="form-control" placeholder="Contoh: Robotik & Coding, Pramuka, Basket" required>
                        </div>
                        <div class="act-form-group">
                            <label for="create_ekskul_category">Kategori Peminatan <span class="text-danger">*</span></label>
                            <select name="category" id="create_ekskul_category" class="form-select" required>
                                <option value="olahraga">Olahraga & Fisik</option>
                                <option value="seni">Seni & Budaya</option>
                                <option value="teknologi">Sains & Teknologi</option>
                                <option value="kepemimpinan">Kepemimpinan & Organisasi</option>
                                <option value="keagamaan">Keagamaan & Rohani</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_ekskul_coach">Pembina / Pelatih</label>
                            <input type="text" name="coach_name" id="create_ekskul_coach" class="form-control" placeholder="Nama guru pembina atau instruktur profesional">
                        </div>
                        <div class="act-form-group">
                            <label for="create_ekskul_sched">Jadwal Latihan Rutin</label>
                            <input type="text" name="schedule_day" id="create_ekskul_sched" class="form-control" placeholder="Contoh: Setiap Jumat, 15:30 - 17:00">
                        </div>
                    </div>

                    <div class="act-form-group">
                        <label for="create_ekskul_desc">Deskripsi & Profil Kegiatan</label>
                        <textarea name="description" id="create_ekskul_desc" rows="3" class="form-control" placeholder="Jelaskan tujuan ekskul, materi latihan, dan pencapaian yang diasah..."></textarea>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_ekskul_photo">Foto Dokumentasi / Logo Ekskul</label>
                            <input type="file" name="photo" id="create_ekskul_photo" class="form-control" accept="image/png,image/jpeg,image/webp">
                            <span class="act-form-help">Format JPG/PNG/WEBP, maksimal 3MB. Disarankan foto aksi/latihan anggota.</span>
                        </div>
                        <div class="act-form-group">
                            <label for="create_ekskul_order">Urutan Tampilan</label>
                            <input type="number" name="order" id="create_ekskul_order" class="form-control" value="0" min="0">
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4 mt-2 pt-2 border-top">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="create_ekskul_active" checked>
                            <label class="form-check-label" for="create_ekskul_active">Status Aktif / Tampilkan di Web</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="act-btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="act-btn-save"><i class="bi bi-check-lg me-1"></i> Simpan Ekstrakurikuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($extracurriculars as $ekskul)
<div class="modal fade act-modal" id="editEkskulModal{{ $ekskul->id }}" tabindex="-1" aria-labelledby="editEkskulLabel{{ $ekskul->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.student-activities.extracurriculars.update', $ekskul) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <div>
                        <span class="act-modal-label">Perbarui Ekskul</span>
                        <h5 class="modal-title" id="editEkskulLabel{{ $ekskul->id }}">Edit: {{ $ekskul->name }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="edit_ekskul_name_{{ $ekskul->id }}">Nama Ekstrakurikuler <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_ekskul_name_{{ $ekskul->id }}" class="form-control" value="{{ $ekskul->name }}" required>
                        </div>
                        <div class="act-form-group">
                            <label for="edit_ekskul_category_{{ $ekskul->id }}">Kategori Peminatan <span class="text-danger">*</span></label>
                            <select name="category" id="edit_ekskul_category_{{ $ekskul->id }}" class="form-select" required>
                                <option value="olahraga" {{ $ekskul->category === 'olahraga' ? 'selected' : '' }}>Olahraga & Fisik</option>
                                <option value="seni" {{ $ekskul->category === 'seni' ? 'selected' : '' }}>Seni & Budaya</option>
                                <option value="teknologi" {{ $ekskul->category === 'teknologi' ? 'selected' : '' }}>Sains & Teknologi</option>
                                <option value="kepemimpinan" {{ $ekskul->category === 'kepemimpinan' ? 'selected' : '' }}>Kepemimpinan & Organisasi</option>
                                <option value="keagamaan" {{ $ekskul->category === 'keagamaan' ? 'selected' : '' }}>Keagamaan & Rohani</option>
                                <option value="lainnya" {{ $ekskul->category === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="edit_ekskul_coach_{{ $ekskul->id }}">Pembina / Pelatih</label>
                            <input type="text" name="coach_name" id="edit_ekskul_coach_{{ $ekskul->id }}" class="form-control" value="{{ $ekskul->coach_name }}">
                        </div>
                        <div class="act-form-group">
                            <label for="edit_ekskul_sched_{{ $ekskul->id }}">Jadwal Latihan Rutin</label>
                            <input type="text" name="schedule_day" id="edit_ekskul_sched_{{ $ekskul->id }}" class="form-control" value="{{ $ekskul->schedule_day }}">
                        </div>
                    </div>

                    <div class="act-form-group">
                        <label for="edit_ekskul_desc_{{ $ekskul->id }}">Deskripsi & Profil Kegiatan</label>
                        <textarea name="description" id="edit_ekskul_desc_{{ $ekskul->id }}" rows="3" class="form-control">{{ $ekskul->description }}</textarea>
                    </div>

                    @if ($ekskul->photo_url)
                        <div class="act-form-group">
                            <label>Foto Saat Ini</label>
                            <div class="act-photo-preview-card">
                                <img src="{{ $ekskul->photo_url }}" alt="{{ $ekskul->name }}" class="act-photo-preview-img">
                                <div>
                                    <span class="small text-muted d-block mb-1">Foto aktif di halaman kesiswaan.</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="rm_ekskul_photo_{{ $ekskul->id }}">
                                        <label class="form-check-label text-danger small" for="rm_ekskul_photo_{{ $ekskul->id }}">
                                            <i class="bi bi-trash me-1"></i>Hapus foto ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="edit_ekskul_photo_{{ $ekskul->id }}">Ganti Foto Baru</label>
                            <input type="file" name="photo" id="edit_ekskul_photo_{{ $ekskul->id }}" class="form-control" accept="image/png,image/jpeg,image/webp">
                            <span class="act-form-help">Biarkan kosong jika tetap menggunakan foto lama.</span>
                        </div>
                        <div class="act-form-group">
                            <label for="edit_ekskul_order_{{ $ekskul->id }}">Urutan Tampilan</label>
                            <input type="number" name="order" id="edit_ekskul_order_{{ $ekskul->id }}" class="form-control" value="{{ $ekskul->order }}" min="0">
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4 mt-2 pt-2 border-top">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_ekskul_act_{{ $ekskul->id }}" {{ $ekskul->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="edit_ekskul_act_{{ $ekskul->id }}">Status Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="act-btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="act-btn-save"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
