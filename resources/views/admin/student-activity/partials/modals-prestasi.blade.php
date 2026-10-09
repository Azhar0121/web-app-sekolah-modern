<div class="modal fade act-modal" id="createAchievementModal" tabindex="-1" aria-labelledby="createAchievementLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.student-activities.achievements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <div>
                        <span class="act-modal-label">Apresiasi & Kejuaraan</span>
                        <h5 class="modal-title" id="createAchievementLabel">Tambah Rekam Prestasi Siswa</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="act-form-group">
                        <label for="create_ach_title">Judul Prestasi / Kejuaraan <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="create_ach_title" class="form-control" placeholder="Contoh: Juara 1 Olimpiade Sains Nasional (OSN) Tingkat Nasional" required>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_ach_student">Nama Siswa / Tim <span class="text-danger">*</span></label>
                            <input type="text" name="student_name" id="create_ach_student" class="form-control" placeholder="Nama peraih prestasi" required>
                        </div>
                        <div class="act-form-group">
                            <label for="create_ach_class">Kelas</label>
                            <input type="text" name="student_class" id="create_ach_class" class="form-control" placeholder="Contoh: XII MIPA 1, X-A">
                        </div>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_ach_level">Tingkat Kejuaraan <span class="text-danger">*</span></label>
                            <select name="level" id="create_ach_level" class="form-select" required>
                                <option value="sekolah">Tingkat Sekolah</option>
                                <option value="kota">Tingkat Kota / Kabupaten</option>
                                <option value="provinsi">Tingkat Provinsi</option>
                                <option value="nasional" selected>Tingkat Nasional</option>
                                <option value="internasional">Tingkat Internasional</option>
                            </select>
                        </div>
                        <div class="act-form-group">
                            <label for="create_ach_category">Kategori Lomba <span class="text-danger">*</span></label>
                            <select name="category" id="create_ach_category" class="form-select" required>
                                <option value="akademik">Akademik & Sains</option>
                                <option value="non-akademik">Non-Akademik / Umum</option>
                                <option value="seni">Seni & Budaya</option>
                                <option value="olahraga">Olahraga</option>
                            </select>
                        </div>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_ach_year">Tahun Perolehan</label>
                            <input type="number" name="year" id="create_ach_year" class="form-control" value="{{ date('Y') }}" min="2000" max="2099">
                        </div>
                        <div class="act-form-group">
                            <label for="create_ach_organizer">Penyelenggara / Institusi</label>
                            <input type="text" name="organizer" id="create_ach_organizer" class="form-control" placeholder="Contoh: Puspresnas Kemendikbudristek">
                        </div>
                    </div>

                    <div class="act-form-group">
                        <label for="create_ach_desc">Keterangan Tambahan / Deskripsi Prestasi</label>
                        <textarea name="description" id="create_ach_desc" rows="3" class="form-control" placeholder="Rincian kompetisi, medali emas/perak, piagam penghargaan, dll..."></textarea>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_ach_photo">Foto Penghargaan / Penyerahan Trofi</label>
                            <input type="file" name="photo" id="create_ach_photo" class="form-control" accept="image/png,image/jpeg,image/webp">
                            <span class="act-form-help">Foto siswa membawa piala, sertifikat, atau medali. Maksimal 3MB.</span>
                        </div>
                        <div class="act-form-group">
                            <label for="create_ach_order">Urutan Tampilan</label>
                            <input type="number" name="order" id="create_ach_order" class="form-control" value="0" min="0">
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4 mt-2 pt-2 border-top">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="create_ach_featured" checked>
                            <label class="form-check-label" for="create_ach_featured">Prestasi Unggulan (Tampil Utama di Web)</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="create_ach_active" checked>
                            <label class="form-check-label" for="create_ach_active">Status Aktif / Publikasikan</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="act-btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="act-btn-save"><i class="bi bi-check-lg me-1"></i> Simpan Prestasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($achievements as $ach)
<div class="modal fade act-modal" id="editAchievementModal{{ $ach->id }}" tabindex="-1" aria-labelledby="editAchievementLabel{{ $ach->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.student-activities.achievements.update', $ach) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <div>
                        <span class="act-modal-label">Perbarui Data Prestasi</span>
                        <h5 class="modal-title" id="editAchievementLabel{{ $ach->id }}">Edit: {{ $ach->title }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="act-form-group">
                        <label for="edit_ach_title_{{ $ach->id }}">Judul Prestasi / Kejuaraan <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_ach_title_{{ $ach->id }}" class="form-control" value="{{ $ach->title }}" required>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="edit_ach_student_{{ $ach->id }}">Nama Siswa / Tim <span class="text-danger">*</span></label>
                            <input type="text" name="student_name" id="edit_ach_student_{{ $ach->id }}" class="form-control" value="{{ $ach->student_name }}" required>
                        </div>
                        <div class="act-form-group">
                            <label for="edit_ach_class_{{ $ach->id }}">Kelas</label>
                            <input type="text" name="student_class" id="edit_ach_class_{{ $ach->id }}" class="form-control" value="{{ $ach->student_class }}">
                        </div>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="edit_ach_level_{{ $ach->id }}">Tingkat Kejuaraan <span class="text-danger">*</span></label>
                            <select name="level" id="edit_ach_level_{{ $ach->id }}" class="form-select" required>
                                <option value="sekolah" {{ $ach->level === 'sekolah' ? 'selected' : '' }}>Tingkat Sekolah</option>
                                <option value="kota" {{ $ach->level === 'kota' ? 'selected' : '' }}>Tingkat Kota / Kabupaten</option>
                                <option value="provinsi" {{ $ach->level === 'provinsi' ? 'selected' : '' }}>Tingkat Provinsi</option>
                                <option value="nasional" {{ $ach->level === 'nasional' ? 'selected' : '' }}>Tingkat Nasional</option>
                                <option value="internasional" {{ $ach->level === 'internasional' ? 'selected' : '' }}>Tingkat Internasional</option>
                            </select>
                        </div>
                        <div class="act-form-group">
                            <label for="edit_ach_category_{{ $ach->id }}">Kategori Lomba <span class="text-danger">*</span></label>
                            <select name="category" id="edit_ach_category_{{ $ach->id }}" class="form-select" required>
                                <option value="akademik" {{ $ach->category === 'akademik' ? 'selected' : '' }}>Akademik & Sains</option>
                                <option value="non-akademik" {{ $ach->category === 'non-akademik' ? 'selected' : '' }}>Non-Akademik / Umum</option>
                                <option value="seni" {{ $ach->category === 'seni' ? 'selected' : '' }}>Seni & Budaya</option>
                                <option value="olahraga" {{ $ach->category === 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                            </select>
                        </div>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="edit_ach_year_{{ $ach->id }}">Tahun Perolehan</label>
                            <input type="number" name="year" id="edit_ach_year_{{ $ach->id }}" class="form-control" value="{{ $ach->year }}" min="2000" max="2099">
                        </div>
                        <div class="act-form-group">
                            <label for="edit_ach_organizer_{{ $ach->id }}">Penyelenggara / Institusi</label>
                            <input type="text" name="organizer" id="edit_ach_organizer_{{ $ach->id }}" class="form-control" value="{{ $ach->organizer }}">
                        </div>
                    </div>

                    <div class="act-form-group">
                        <label for="edit_ach_desc_{{ $ach->id }}">Keterangan Tambahan / Deskripsi Prestasi</label>
                        <textarea name="description" id="edit_ach_desc_{{ $ach->id }}" rows="3" class="form-control">{{ $ach->description }}</textarea>
                    </div>

                    @if ($ach->photo_url)
                        <div class="act-form-group">
                            <label>Foto Penghargaan Saat Ini</label>
                            <div class="act-photo-preview-card">
                                <img src="{{ $ach->photo_url }}" alt="{{ $ach->title }}" class="act-photo-preview-img">
                                <div>
                                    <span class="small text-muted d-block mb-1">Foto aktif di halaman kesiswaan.</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="rm_ach_photo_{{ $ach->id }}">
                                        <label class="form-check-label text-danger small" for="rm_ach_photo_{{ $ach->id }}">
                                            <i class="bi bi-trash me-1"></i>Hapus foto ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="edit_ach_photo_{{ $ach->id }}">Ganti Foto Baru</label>
                            <input type="file" name="photo" id="edit_ach_photo_{{ $ach->id }}" class="form-control" accept="image/png,image/jpeg,image/webp">
                            <span class="act-form-help">Biarkan kosong jika tidak mengubah foto penghargaan.</span>
                        </div>
                        <div class="act-form-group">
                            <label for="edit_ach_order_{{ $ach->id }}">Urutan Tampilan</label>
                            <input type="number" name="order" id="edit_ach_order_{{ $ach->id }}" class="form-control" value="{{ $ach->order }}" min="0">
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4 mt-2 pt-2 border-top">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="edit_ach_feat_{{ $ach->id }}" {{ $ach->is_featured ? 'checked' : '' }}>
                            <label class="form-check-label" for="edit_ach_feat_{{ $ach->id }}">Prestasi Unggulan</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_ach_act_{{ $ach->id }}" {{ $ach->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="edit_ach_act_{{ $ach->id }}">Status Aktif</label>
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
