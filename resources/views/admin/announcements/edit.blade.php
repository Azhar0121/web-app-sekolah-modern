@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/announcements/edit.css') }}">

<div class="announcement-edit-page">


{{-- HERO --}}
<div class="announcement-edit-hero">

    <div class="announcement-edit-hero-content">

        <div class="announcement-edit-icon">
            <i class="bi bi-pencil-square"></i>
        </div>

        <div>
            <span class="announcement-edit-eyebrow">
                MANAJEMEN PENGUMUMAN
            </span>

            <h1>Edit Pengumuman</h1>

            <p>
                Perbarui informasi pengumuman, target penerima, jadwal publikasi,
                dan status publikasi sebelum menyimpan perubahan.
            </p>
        </div>

    </div>

    <a href="{{ route('admin.announcements.index') }}" class="btn-back-announcement">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>

</div>


{{-- FORM CARD --}}
<div class="announcement-form-card">

    {{-- FORM HEADER --}}
    <div class="announcement-form-header">

        <div>
            <span class="form-section-label">
                INFORMASI PENGUMUMAN
            </span>

            <h2>Perbarui Data Pengumuman</h2>

            <p>
                Pastikan seluruh informasi sudah sesuai sebelum menyimpan perubahan.
            </p>
        </div>

    </div>


    <form
        action="{{ route('admin.announcements.update', $announcement->id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        {{-- INFORMASI UTAMA --}}
        <div class="form-section">

            <div class="section-heading">

                <div class="section-heading-icon">
                    <i class="bi bi-megaphone-fill"></i>
                </div>

                <div>
                    <h3>Informasi Utama</h3>
                    <p>Atur judul dan isi pengumuman.</p>
                </div>

            </div>


            {{-- JUDUL --}}
            <div class="form-group">

                <label for="title" class="custom-label">
                    Judul Pengumuman
                    <span>*</span>
                </label>

                <input
                    type="text"
                    class="custom-input @error('title') input-error @enderror"
                    id="title"
                    name="title"
                    value="{{ old('title', $announcement->title) }}"
                    placeholder="Masukkan judul pengumuman"
                    required
                >

                @error('title')
                    <div class="custom-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- KONTEN --}}
            <div class="form-group">

                <label for="content" class="custom-label">
                    Isi / Konten
                    <span>*</span>
                </label>

                <textarea
                    class="custom-textarea @error('content') input-error @enderror"
                    id="content"
                    name="content"
                    placeholder="Tulis isi pengumuman di sini..."
                    required
                >{{ old('content', $announcement->content) }}</textarea>

                @error('content')
                    <div class="custom-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

                <div class="field-hint">
                    <i class="bi bi-info-circle"></i>
                    <span>
                        Gunakan bahasa yang jelas dan informatif agar mudah dipahami oleh penerima pengumuman.
                    </span>
                </div>

            </div>


            {{-- PRIORITAS --}}
            <div class="form-group">

                <label for="priority" class="custom-label">
                    Prioritas
                    <span>*</span>
                </label>

                <select
                    class="custom-select @error('priority') input-error @enderror"
                    id="priority"
                    name="priority"
                    required
                >
                    <option
                        value="normal"
                        {{ old('priority', $announcement->priority) == 'normal' ? 'selected' : '' }}
                    >
                        Normal
                    </option>

                    <option
                        value="penting"
                        {{ old('priority', $announcement->priority) == 'penting' ? 'selected' : '' }}
                    >
                        Penting
                    </option>

                    <option
                        value="urgent"
                        {{ old('priority', $announcement->priority) == 'urgent' ? 'selected' : '' }}
                    >
                        Urgent
                    </option>
                </select>

                @error('priority')
                    <div class="custom-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>


        @php
            $roles = is_array($announcement->target_roles)
                ? $announcement->target_roles
                : json_decode($announcement->target_roles, true) ?? [];
        @endphp


        {{-- TARGET PENERIMA --}}
        <div class="form-section">

            <div class="section-heading">

                <div class="section-heading-icon">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div>
                    <h3>Target Penerima</h3>
                    <p>Tentukan siapa saja yang dapat melihat pengumuman.</p>
                </div>

            </div>


            <div class="role-selection">

                {{-- ALL --}}
                <label class="role-option role-all" for="role_all">

                    <input
                        class="role-checkbox"
                        type="checkbox"
                        id="role_all"
                        name="target_roles[]"
                        value="all"
                        {{ (is_array(old('target_roles', $roles)) && in_array('all', old('target_roles', $roles))) ? 'checked' : '' }}
                    >

                    <span class="role-checkmark">
                        <i class="bi bi-check"></i>
                    </span>

                    <span class="role-content">
                        <strong>Semua</strong>
                        <small>Seluruh pengguna</small>
                    </span>

                </label>


                {{-- SISWA --}}
                <label class="role-option" for="role_siswa">

                    <input
                        class="role-checkbox"
                        type="checkbox"
                        id="role_siswa"
                        name="target_roles[]"
                        value="siswa"
                        {{ (is_array(old('target_roles', $roles)) && in_array('siswa', old('target_roles', $roles))) ? 'checked' : '' }}
                    >

                    <span class="role-checkmark">
                        <i class="bi bi-check"></i>
                    </span>

                    <span class="role-content">
                        <strong>Siswa</strong>
                        <small>Peserta didik</small>
                    </span>

                </label>


                {{-- GURU --}}
                <label class="role-option" for="role_guru">

                    <input
                        class="role-checkbox"
                        type="checkbox"
                        id="role_guru"
                        name="target_roles[]"
                        value="guru"
                        {{ (is_array(old('target_roles', $roles)) && in_array('guru', old('target_roles', $roles))) ? 'checked' : '' }}
                    >

                    <span class="role-checkmark">
                        <i class="bi bi-check"></i>
                    </span>

                    <span class="role-content">
                        <strong>Guru</strong>
                        <small>Tenaga pendidik</small>
                    </span>

                </label>


                {{-- ORTU --}}
                <label class="role-option" for="role_ortu">

                    <input
                        class="role-checkbox"
                        type="checkbox"
                        id="role_ortu"
                        name="target_roles[]"
                        value="ortu"
                        {{ (is_array(old('target_roles', $roles)) && in_array('ortu', old('target_roles', $roles))) ? 'checked' : '' }}
                    >

                    <span class="role-checkmark">
                        <i class="bi bi-check"></i>
                    </span>

                    <span class="role-content">
                        <strong>Orang Tua</strong>
                        <small>Wali peserta didik</small>
                    </span>

                </label>


                {{-- TU --}}
                <label class="role-option" for="role_tu">

                    <input
                        class="role-checkbox"
                        type="checkbox"
                        id="role_tu"
                        name="target_roles[]"
                        value="tu"
                        {{ (is_array(old('target_roles', $roles)) && in_array('tu', old('target_roles', $roles))) ? 'checked' : '' }}
                    >

                    <span class="role-checkmark">
                        <i class="bi bi-check"></i>
                    </span>

                    <span class="role-content">
                        <strong>TU</strong>
                        <small>Tenaga kependidikan</small>
                    </span>

                </label>


                {{-- KEPSEK --}}
                <label class="role-option" for="role_kepsek">

                    <input
                        class="role-checkbox"
                        type="checkbox"
                        id="role_kepsek"
                        name="target_roles[]"
                        value="kepsek"
                        {{ (is_array(old('target_roles', $roles)) && in_array('kepsek', old('target_roles', $roles))) ? 'checked' : '' }}
                    >

                    <span class="role-checkmark">
                        <i class="bi bi-check"></i>
                    </span>

                    <span class="role-content">
                        <strong>Kepsek</strong>
                        <small>Kepala sekolah</small>
                    </span>

                </label>

            </div>


            @error('target_roles')
                <div class="custom-error role-error">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- JADWAL --}}
        <div class="form-section">

            <div class="section-heading">

                <div class="section-heading-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div>
                    <h3>Jadwal Publikasi</h3>
                    <p>Atur periode tampilnya pengumuman.</p>
                </div>

            </div>


            <div class="date-grid">

                <div class="form-group">

                    <label for="published_at" class="custom-label">
                        Tanggal Mulai
                        <small>Opsional</small>
                    </label>

                    <input
                        type="datetime-local"
                        class="custom-input @error('published_at') input-error @enderror"
                        id="published_at"
                        name="published_at"
                        value="{{ old('published_at', $announcement->published_at ? $announcement->published_at->format('Y-m-d\TH:i') : '') }}"
                    >

                    @error('published_at')
                        <div class="custom-error">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="form-group">

                    <label for="expired_at" class="custom-label">
                        Tanggal Kadaluarsa
                        <small>Opsional</small>
                    </label>

                    <input
                        type="datetime-local"
                        class="custom-input @error('expired_at') input-error @enderror"
                        id="expired_at"
                        name="expired_at"
                        value="{{ old('expired_at', $announcement->expired_at ? $announcement->expired_at->format('Y-m-d\TH:i') : '') }}"
                    >

                    @error('expired_at')
                        <div class="custom-error">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- PUBLISH --}}
        <div class="publish-section">

            <label class="publish-option" for="is_published">

                <input
                    type="checkbox"
                    class="publish-checkbox"
                    id="is_published"
                    name="is_published"
                    value="1"
                    {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}
                >

                <span class="publish-checkmark">
                    <i class="bi bi-check-lg"></i>
                </span>

                <span class="publish-content">
                    <strong>Publish Sekarang</strong>
                    <small>
                        Aktifkan agar pengumuman dapat langsung ditampilkan kepada target penerima.
                    </small>
                </span>

            </label>

        </div>


        {{-- ACTION --}}
        <div class="form-actions">

            <div class="form-actions-info">
                Pastikan seluruh informasi sudah benar sebelum disimpan.
            </div>

            <div class="form-actions-buttons">

                <a
                    href="{{ route('admin.announcements.index') }}"
                    class="btn-cancel"
                >
                    <i class="bi bi-x-lg"></i>
                    Batal
                </a>

                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i>
                    Simpan Perubahan
                </button>

            </div>

        </div>

    </form>

</div>


</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const roleAll = document.getElementById('role_all');
    const otherRoles = document.querySelectorAll('.role-checkbox:not(#role_all)');

    if (!roleAll) {
        return;
    }

    roleAll.addEventListener('change', function() {

        if (this.checked) {
            otherRoles.forEach(cb => cb.checked = true);
        } else {
            otherRoles.forEach(cb => cb.checked = false);
        }

    });

    otherRoles.forEach(cb => {

        cb.addEventListener('change', function() {

            if (!this.checked) {
                roleAll.checked = false;
            } else {

                const allChecked =
                    Array.from(otherRoles).every(c => c.checked);

                if (allChecked) {
                    roleAll.checked = true;
                }

            }

        });

    });

});
</script>

@endsection
