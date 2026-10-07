@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/announcements/create.css') }}">

<div class="announcement-create-page">


{{-- HERO --}}
<div class="announcement-create-hero">

    <div class="announcement-create-hero-content">

        <div class="announcement-create-icon">
            <i class="bi bi-megaphone-fill"></i>
        </div>

        <div>
            <span class="announcement-create-eyebrow">
                MANAJEMEN INFORMASI
            </span>

            <h1>Buat Pengumuman Baru</h1>

            <p>
                Tambahkan informasi atau pengumuman baru untuk pengguna sekolah.
            </p>
        </div>

    </div>

    <a
        href="{{ route('admin.announcements.index') }}"
        class="btn-back-announcement"
    >
        <i class="bi bi-arrow-left"></i>
        <span>Kembali</span>
    </a>

</div>


{{-- FORM CARD --}}
<div class="announcement-form-card">

    <div class="announcement-form-header">
        <div>
            <span class="form-section-label">FORM PENGUMUMAN</span>
            <h2>Informasi Pengumuman</h2>
            <p>
                Lengkapi informasi berikut sebelum menyimpan pengumuman.
            </p>
        </div>
    </div>


    <form action="{{ route('admin.announcements.store') }}" method="POST">

        @csrf

        {{-- JUDUL --}}
        <div class="form-section">

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
                    value="{{ old('title') }}"
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


            {{-- ISI --}}
            <div class="form-group">

                <label for="content" class="custom-label">
                    Isi / Konten
                    <span>*</span>
                </label>

                <textarea
                    class="custom-textarea @error('content') input-error @enderror"
                    id="content"
                    name="content"
                    rows="7"
                    placeholder="Tuliskan isi pengumuman di sini..."
                    required
                >{{ old('content') }}</textarea>

                <div class="field-hint">
                    <i class="bi bi-info-circle"></i>
                    Gunakan bahasa yang jelas dan mudah dipahami oleh penerima pengumuman.
                </div>

                @error('content')
                    <div class="custom-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>


        {{-- DETAIL --}}
        <div class="form-section">

            <div class="section-heading">
                <div class="section-heading-icon">
                    <i class="bi bi-sliders"></i>
                </div>

                <div>
                    <h3>Pengaturan Pengumuman</h3>
                    <p>Tentukan prioritas dan target penerima pengumuman.</p>
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
                    <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>
                        Normal
                    </option>

                    <option value="penting" {{ old('priority') == 'penting' ? 'selected' : '' }}>
                        Penting
                    </option>

                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>
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


            {{-- TARGET ROLE --}}
            <div class="form-group">

                <label class="custom-label">
                    Target Role
                </label>

                <div class="role-selection">

                    <label class="role-option role-all" for="role_all">

                        <input
                            class="role-checkbox"
                            type="checkbox"
                            id="role_all"
                            name="target_roles[]"
                            value="all"
                            {{ (is_array(old('target_roles')) && in_array('all', old('target_roles'))) ? 'checked' : '' }}
                        >

                        <span class="role-checkmark">
                            <i class="bi bi-check-lg"></i>
                        </span>

                        <span class="role-content">
                            <strong>Semua</strong>
                            <small>Semua pengguna</small>
                        </span>

                    </label>


                    <label class="role-option" for="role_siswa">

                        <input
                            class="role-checkbox"
                            type="checkbox"
                            id="role_siswa"
                            name="target_roles[]"
                            value="siswa"
                            {{ (is_array(old('target_roles')) && in_array('siswa', old('target_roles'))) ? 'checked' : '' }}
                        >

                        <span class="role-checkmark">
                            <i class="bi bi-check-lg"></i>
                        </span>

                        <span class="role-content">
                            <strong>Siswa</strong>
                            <small>Peserta didik</small>
                        </span>

                    </label>


                    <label class="role-option" for="role_guru">

                        <input
                            class="role-checkbox"
                            type="checkbox"
                            id="role_guru"
                            name="target_roles[]"
                            value="guru"
                            {{ (is_array(old('target_roles')) && in_array('guru', old('target_roles'))) ? 'checked' : '' }}
                        >

                        <span class="role-checkmark">
                            <i class="bi bi-check-lg"></i>
                        </span>

                        <span class="role-content">
                            <strong>Guru</strong>
                            <small>Tenaga pengajar</small>
                        </span>

                    </label>


                    <label class="role-option" for="role_ortu">

                        <input
                            class="role-checkbox"
                            type="checkbox"
                            id="role_ortu"
                            name="target_roles[]"
                            value="ortu"
                            {{ (is_array(old('target_roles')) && in_array('ortu', old('target_roles'))) ? 'checked' : '' }}
                        >

                        <span class="role-checkmark">
                            <i class="bi bi-check-lg"></i>
                        </span>

                        <span class="role-content">
                            <strong>Orang Tua</strong>
                            <small>Wali siswa</small>
                        </span>

                    </label>


                    <label class="role-option" for="role_tu">

                        <input
                            class="role-checkbox"
                            type="checkbox"
                            id="role_tu"
                            name="target_roles[]"
                            value="tu"
                            {{ (is_array(old('target_roles')) && in_array('tu', old('target_roles'))) ? 'checked' : '' }}
                        >

                        <span class="role-checkmark">
                            <i class="bi bi-check-lg"></i>
                        </span>

                        <span class="role-content">
                            <strong>TU</strong>
                            <small>Tata usaha</small>
                        </span>

                    </label>


                    <label class="role-option" for="role_kepsek">

                        <input
                            class="role-checkbox"
                            type="checkbox"
                            id="role_kepsek"
                            name="target_roles[]"
                            value="kepsek"
                            {{ (is_array(old('target_roles')) && in_array('kepsek', old('target_roles'))) ? 'checked' : '' }}
                        >

                        <span class="role-checkmark">
                            <i class="bi bi-check-lg"></i>
                        </span>

                        <span class="role-content">
                            <strong>Kepsek</strong>
                            <small>Kepala sekolah</small>
                        </span>

                    </label>

                </div>

                @error('target_roles')
                    <div class="custom-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>


        {{-- PERIODE --}}
        <div class="form-section">

            <div class="section-heading">
                <div class="section-heading-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div>
                    <h3>Periode Pengumuman</h3>
                    <p>Atur waktu mulai dan berakhirnya pengumuman jika diperlukan.</p>
                </div>
            </div>


            <div class="date-grid">

                <div class="form-group">

                    <label for="published_at" class="custom-label">
                        Tanggal Mulai
                        <small>(Opsional)</small>
                    </label>

                    <input
                        type="datetime-local"
                        class="custom-input @error('published_at') input-error @enderror"
                        id="published_at"
                        name="published_at"
                        value="{{ old('published_at') }}"
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
                        <small>(Opsional)</small>
                    </label>

                    <input
                        type="datetime-local"
                        class="custom-input @error('expired_at') input-error @enderror"
                        id="expired_at"
                        name="expired_at"
                        value="{{ old('expired_at') }}"
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
                    {{ old('is_published') ? 'checked' : '' }}
                >

                <span class="publish-checkmark">
                    <i class="bi bi-check-lg"></i>
                </span>

                <span class="publish-content">
                    <strong>Publish Sekarang</strong>
                    <small>Pengumuman akan langsung ditampilkan kepada target penerima.</small>
                </span>

            </label>

        </div>


        {{-- ACTION --}}
        <div class="form-actions">

            <a
                href="{{ route('admin.announcements.index') }}"
                class="btn-cancel"
            >
                <i class="bi bi-x-lg"></i>
                Batal
            </a>

            <button
                type="submit"
                class="btn-save"
            >
                <i class="bi bi-check-lg"></i>
                Simpan Pengumuman
            </button>

        </div>

    </form>

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
