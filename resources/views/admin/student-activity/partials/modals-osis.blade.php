<div class="modal fade act-modal" id="createOsisActivityModal" tabindex="-1" aria-labelledby="createOsisActivityLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.student-activities.osis.activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <div>
                        <span class="act-modal-label">Agenda & Program Kerja</span>
                        <h5 class="modal-title" id="createOsisActivityLabel">Tambah Kegiatan OSIS</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="act-form-group">
                        <label for="create_act_title">Nama / Judul Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="create_act_title" class="form-control" placeholder="Contoh: Latihan Dasar Kepemimpinan Siswa (LDKS)" required>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_act_date">Tanggal Pelaksanaan</label>
                            <input type="date" name="date" id="create_act_date" class="form-control">
                        </div>
                        <div class="act-form-group">
                            <label for="create_act_order">Urutan Tampilan</label>
                            <input type="number" name="order" id="create_act_order" class="form-control" value="0" min="0">
                        </div>
                    </div>

                    <div class="act-form-group">
                        <label for="create_act_desc">Deskripsi & Rincian Kegiatan</label>
                        <textarea name="description" id="create_act_desc" rows="3" class="form-control" placeholder="Jelaskan ringkasan tujuan, agenda, peserta, dan hasil kegiatan..."></textarea>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_act_photo">Foto Utama / Banner Kegiatan</label>
                            <input type="file" name="photo" id="create_act_photo" class="form-control" accept="image/png,image/jpeg,image/webp">
                            <span class="act-form-help">Format: JPG, PNG, WEBP. Maksimal 3MB.</span>
                        </div>
                        <div class="act-form-group">
                            <label for="create_act_gallery">Galeri Dokumentasi Foto (Banyak Foto)</label>
                            <input type="file" name="gallery[]" id="create_act_gallery" class="form-control" accept="image/png,image/jpeg,image/webp" multiple>
                            <span class="act-form-help">Bisa memilih beberapa foto sekaligus untuk slider galeri.</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4 mt-2 pt-2 border-top">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="create_act_featured">
                            <label class="form-check-label" for="create_act_featured">Tampilkan sebagai Program Unggulan</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="create_act_active" checked>
                            <label class="form-check-label" for="create_act_active">Status Aktif / Publikasikan</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="act-btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="act-btn-save"><i class="bi bi-check-lg me-1"></i> Simpan Kegiatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($osisActivities as $act)
<div class="modal fade act-modal" id="editOsisActivityModal{{ $act->id }}" tabindex="-1" aria-labelledby="editOsisActivityLabel{{ $act->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.student-activities.osis.activities.update', $act) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <div>
                        <span class="act-modal-label">Perbarui Data</span>
                        <h5 class="modal-title" id="editOsisActivityLabel{{ $act->id }}">Edit Kegiatan: {{ $act->title }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="act-form-group">
                        <label for="edit_act_title_{{ $act->id }}">Nama / Judul Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_act_title_{{ $act->id }}" class="form-control" value="{{ $act->title }}" required>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="edit_act_date_{{ $act->id }}">Tanggal Pelaksanaan</label>
                            <input type="date" name="date" id="edit_act_date_{{ $act->id }}" class="form-control" value="{{ $act->date ? $act->date->format('Y-m-d') : '' }}">
                        </div>
                        <div class="act-form-group">
                            <label for="edit_act_order_{{ $act->id }}">Urutan Tampilan</label>
                            <input type="number" name="order" id="edit_act_order_{{ $act->id }}" class="form-control" value="{{ $act->order }}" min="0">
                        </div>
                    </div>

                    <div class="act-form-group">
                        <label for="edit_act_desc_{{ $act->id }}">Deskripsi & Rincian Kegiatan</label>
                        <textarea name="description" id="edit_act_desc_{{ $act->id }}" rows="3" class="form-control">{{ $act->description }}</textarea>
                    </div>

                    {{-- Foto Utama Saat Ini --}}
                    @if ($act->photo_url)
                        <div class="act-form-group">
                            <label>Foto Utama Saat Ini</label>
                            <div class="act-photo-preview-card">
                                <img src="{{ $act->photo_url }}" alt="{{ $act->title }}" class="act-photo-preview-img">
                                <div>
                                    <span class="small text-muted d-block mb-1">Ukuran penuh sudah tersimpan di sistem.</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="rm_act_photo_{{ $act->id }}">
                                        <label class="form-check-label text-danger small" for="rm_act_photo_{{ $act->id }}">
                                            <i class="bi bi-trash me-1"></i>Hapus foto utama saat ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="act-form-group">
                        <label for="edit_act_photo_{{ $act->id }}">Ganti / Unggah Foto Utama Baru</label>
                        <input type="file" name="photo" id="edit_act_photo_{{ $act->id }}" class="form-control" accept="image/png,image/jpeg,image/webp">
                        <span class="act-form-help">Biarkan kosong jika tidak ingin mengubah foto utama.</span>
                    </div>

                    {{-- Galeri Dokumentasi Saat Ini --}}
                    @if (is_array($act->gallery) && count($act->gallery) > 0)
                        <div class="act-form-group">
                            <label>Galeri Foto Dokumentasi Saat Ini (Centang untuk menghapus foto):</label>
                            <div class="act-gallery-preview-grid">
                                @foreach ($act->gallery as $gPath)
                                    <div class="act-gallery-item-card">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($gPath) }}" alt="Galeri">
                                        <label>
                                            <input type="checkbox" name="delete_gallery[]" value="{{ $gPath }}"> Hapus
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="act-form-group">
                        <label for="edit_act_gallery_{{ $act->id }}">Tambah Foto ke Galeri</label>
                        <input type="file" name="gallery[]" id="edit_act_gallery_{{ $act->id }}" class="form-control" accept="image/png,image/jpeg,image/webp" multiple>
                        <span class="act-form-help">Foto yang dipilih akan ditambahkan ke galeri yang sudah ada.</span>
                    </div>

                    <div class="d-flex align-items-center gap-4 mt-2 pt-2 border-top">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="edit_act_feat_{{ $act->id }}" {{ $act->is_featured ? 'checked' : '' }}>
                            <label class="form-check-label" for="edit_act_feat_{{ $act->id }}">Program Unggulan</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_act_act_{{ $act->id }}" {{ $act->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="edit_act_act_{{ $act->id }}">Status Aktif</label>
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

<div class="modal fade act-modal" id="createOsisMemberModal" tabindex="-1" aria-labelledby="createOsisMemberLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.student-activities.osis.members.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <div>
                        <span class="act-modal-label">Kepengurusan OSIS</span>
                        <h5 class="modal-title" id="createOsisMemberLabel">Tambah Pengurus / Anggota OSIS</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_mem_name">Nama Siswa / Pengurus <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="create_mem_name" class="form-control" placeholder="Nama lengkap siswa" required>
                        </div>
                        <div class="act-form-group">
                            <label for="create_mem_pos">Jabatan di OSIS <span class="text-danger">*</span></label>
                            <input type="text" name="position" id="create_mem_pos" class="form-control" placeholder="Contoh: Ketua OSIS, Sekretaris 1, Anggota" required>
                        </div>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_mem_dept">Divisi / Departemen</label>
                            <input type="text" name="department" id="create_mem_dept" class="form-control" placeholder="Contoh: BPH, Sekbid Ketaqwaan, Sekbid Olahraga" value="Badan Pengurus Harian (BPH)">
                        </div>
                        <div class="act-form-group">
                            <label for="create_mem_class">Kelas</label>
                            <input type="text" name="class_name" id="create_mem_class" class="form-control" placeholder="Contoh: XI MIPA 1, X IPS 2">
                        </div>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="create_mem_period">Masa Bakti / Periode</label>
                            <input type="text" name="period" id="create_mem_period" class="form-control" value="2026/2027">
                        </div>
                        <div class="act-form-group">
                            <label for="create_mem_order">Urutan Tampilan</label>
                            <input type="number" name="order" id="create_mem_order" class="form-control" value="0" min="0">
                        </div>
                    </div>

                    <div class="act-form-group">
                        <label for="create_mem_photo">Foto Profil Pengurus</label>
                        <input type="file" name="photo" id="create_mem_photo" class="form-control" accept="image/png,image/jpeg,image/webp">
                        <span class="act-form-help">Foto formal almamater/seragam sekolah disarankan. Format JPG/PNG/WEBP maks 2MB.</span>
                    </div>

                    <div class="d-flex align-items-center gap-4 mt-2 pt-2 border-top">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="create_mem_active" checked>
                            <label class="form-check-label" for="create_mem_active">Status Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="act-btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="act-btn-save"><i class="bi bi-check-lg me-1"></i> Simpan Pengurus</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 4. MODAL EDIT PENGURUS/ANGGOTA OSIS       --}}
{{-- ========================================== --}}
@foreach ($osisMembers as $member)
<div class="modal fade act-modal" id="editOsisMemberModal{{ $member->id }}" tabindex="-1" aria-labelledby="editOsisMemberLabel{{ $member->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.student-activities.osis.members.update', $member) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <div>
                        <span class="act-modal-label">Perbarui Data</span>
                        <h5 class="modal-title" id="editOsisMemberLabel{{ $member->id }}">Edit Pengurus: {{ $member->name }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="edit_mem_name_{{ $member->id }}">Nama Siswa / Pengurus <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_mem_name_{{ $member->id }}" class="form-control" value="{{ $member->name }}" required>
                        </div>
                        <div class="act-form-group">
                            <label for="edit_mem_pos_{{ $member->id }}">Jabatan di OSIS <span class="text-danger">*</span></label>
                            <input type="text" name="position" id="edit_mem_pos_{{ $member->id }}" class="form-control" value="{{ $member->position }}" required>
                        </div>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="edit_mem_dept_{{ $member->id }}">Divisi / Departemen</label>
                            <input type="text" name="department" id="edit_mem_dept_{{ $member->id }}" class="form-control" value="{{ $member->department }}">
                        </div>
                        <div class="act-form-group">
                            <label for="edit_mem_class_{{ $member->id }}">Kelas</label>
                            <input type="text" name="class_name" id="edit_mem_class_{{ $member->id }}" class="form-control" value="{{ $member->class_name }}">
                        </div>
                    </div>

                    <div class="act-form-grid">
                        <div class="act-form-group">
                            <label for="edit_mem_period_{{ $member->id }}">Masa Bakti / Periode</label>
                            <input type="text" name="period" id="edit_mem_period_{{ $member->id }}" class="form-control" value="{{ $member->period }}">
                        </div>
                        <div class="act-form-group">
                            <label for="edit_mem_order_{{ $member->id }}">Urutan Tampilan</label>
                            <input type="number" name="order" id="edit_mem_order_{{ $member->id }}" class="form-control" value="{{ $member->order }}" min="0">
                        </div>
                    </div>

                    @if ($member->photo_url)
                        <div class="act-form-group">
                            <label>Foto Profil Saat Ini</label>
                            <div class="act-photo-preview-card">
                                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="act-photo-preview-avatar">
                                <div>
                                    <span class="small text-muted d-block mb-1">Foto telah tersimpan di sistem.</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="rm_mem_photo_{{ $member->id }}">
                                        <label class="form-check-label text-danger small" for="rm_mem_photo_{{ $member->id }}">
                                            <i class="bi bi-trash me-1"></i>Hapus foto profil saat ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="act-form-group">
                        <label for="edit_mem_photo_{{ $member->id }}">Ganti Foto Profil Baru</label>
                        <input type="file" name="photo" id="edit_mem_photo_{{ $member->id }}" class="form-control" accept="image/png,image/jpeg,image/webp">
                        <span class="act-form-help">Biarkan kosong jika tidak ingin mengubah foto.</span>
                    </div>

                    <div class="d-flex align-items-center gap-4 mt-2 pt-2 border-top">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_mem_act_{{ $member->id }}" {{ $member->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="edit_mem_act_{{ $member->id }}">Status Aktif</label>
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
