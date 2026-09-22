@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Buat Pengumuman Baru</h2>

    <div class="card mt-4">
        <div class="card-body">
            <form action="{{ route('admin.announcements.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">Judul</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Isi / Konten</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6" required>{{ old('content') }}</textarea>
                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="priority" class="form-label">Prioritas</label>
                    <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="penting" {{ old('priority') == 'penting' ? 'selected' : '' }}>Penting</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Target Role</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input role-checkbox" type="checkbox" id="role_all" name="target_roles[]" value="all" {{ (is_array(old('target_roles')) && in_array('all', old('target_roles'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_all">Semua (All)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input role-checkbox" type="checkbox" id="role_siswa" name="target_roles[]" value="siswa" {{ (is_array(old('target_roles')) && in_array('siswa', old('target_roles'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_siswa">Siswa</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input role-checkbox" type="checkbox" id="role_guru" name="target_roles[]" value="guru" {{ (is_array(old('target_roles')) && in_array('guru', old('target_roles'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_guru">Guru</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input role-checkbox" type="checkbox" id="role_ortu" name="target_roles[]" value="ortu" {{ (is_array(old('target_roles')) && in_array('ortu', old('target_roles'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_ortu">Orang Tua</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input role-checkbox" type="checkbox" id="role_tu" name="target_roles[]" value="tu" {{ (is_array(old('target_roles')) && in_array('tu', old('target_roles'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_tu">TU</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input role-checkbox" type="checkbox" id="role_kepsek" name="target_roles[]" value="kepsek" {{ (is_array(old('target_roles')) && in_array('kepsek', old('target_roles'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_kepsek">Kepsek</label>
                        </div>
                    </div>
                    @error('target_roles')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="published_at" class="form-label">Tanggal Mulai (Opsional)</label>
                        <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at') }}">
                        @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="expired_at" class="form-label">Tanggal Kadaluarsa (Opsional)</label>
                        <input type="datetime-local" class="form-control @error('expired_at') is-invalid @enderror" id="expired_at" name="expired_at" value="{{ old('expired_at') }}">
                        @error('expired_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="is_published">Publish Sekarang</label>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleAll = document.getElementById('role_all');
    const otherRoles = document.querySelectorAll('.role-checkbox:not(#role_all)');
    
    roleAll.addEventListener('change', function() {
        if(this.checked) {
            otherRoles.forEach(cb => cb.checked = true);
        } else {
            otherRoles.forEach(cb => cb.checked = false);
        }
    });

    otherRoles.forEach(cb => {
        cb.addEventListener('change', function() {
            if(!this.checked) {
                roleAll.checked = false;
            } else {
                const allChecked = Array.from(otherRoles).every(c => c.checked);
                if(allChecked) roleAll.checked = true;
            }
        });
    });
});
</script>
@endsection
