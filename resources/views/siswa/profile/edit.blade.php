@extends('layouts.app')

@section('title', 'Biodata & Keamanan Akun')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/siswa/profile/edit.css') }}">

<div class="student-profile-page">

    <div class="student-profile-wrapper">

        {{-- HEADER --}}
        <div class="student-profile-header">

            <div class="student-profile-header-content">
                <span class="student-profile-eyebrow">
                    PROFIL SISWA
                </span>

                <h1>Biodata & Keamanan Akun</h1>

                <p>
                    Kelola informasi pribadi dan keamanan akun kamu.
                </p>
            </div>

            <a href="{{ route('siswa.dashboard') }}" class="student-profile-back">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>

        </div>


        {{-- SUCCESS ALERT --}}
        @if (session('success'))
            <div class="student-profile-alert success">
                <i class="bi bi-check-circle-fill"></i>

                <span>{{ session('success') }}</span>

                <button
                    type="button"
                    class="student-profile-alert-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                >
                    <i class="bi bi-x"></i>
                </button>
            </div>
        @endif

        @if (session('password_success'))
            <div class="student-profile-alert success">
                <i class="bi bi-shield-check"></i>

                <span>{{ session('password_success') }}</span>

                <button
                    type="button"
                    class="student-profile-alert-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                >
                    <i class="bi bi-x"></i>
                </button>
            </div>
        @endif


        {{-- PROFILE SUMMARY --}}
        <div class="student-profile-summary">

            <div class="student-profile-avatar">
                {{ mb_strtoupper(mb_substr($student->name, 0, 1)) }}
            </div>

            <div class="student-profile-summary-info">
                <span>IDENTITAS SISWA</span>

                <strong>{{ $student->name }}</strong>

                @if ($classroom)
                    <small>
                        <i class="bi bi-building"></i>
                        Kelas {{ $classroom->name }}
                    </small>
                @else
                    <small>
                        <i class="bi bi-building"></i>
                        Kelas belum ditentukan
                    </small>
                @endif
            </div>

            <div class="student-profile-status">
                <span></span>
                Aktif
            </div>

        </div>


        {{-- DATA SECTION --}}
        <div class="student-profile-grid">

            {{-- DATA RESMI --}}
            <div class="student-profile-card">

                <div class="student-profile-card-header">

                    <div class="student-profile-card-icon blue">
                        <i class="bi bi-person-vcard-fill"></i>
                    </div>

                    <div>
                        <span>INFORMASI SISWA</span>
                        <h2>Data Resmi</h2>
                    </div>

                </div>

                <div class="student-profile-card-body">

                    <div class="student-profile-info-note">
                        <i class="bi bi-info-circle-fill"></i>

                        <p>
                            Data ini dikelola oleh Tata Usaha.
                            Jika terdapat kesalahan, silakan hubungi
                            Tata Usaha untuk melakukan koreksi.
                        </p>
                    </div>

                    <div class="student-profile-data-list">

                        <div class="student-profile-data-row">
                            <span>NISN</span>
                            <strong>{{ $profile->nisn ?? '-' }}</strong>
                        </div>

                        <div class="student-profile-data-row">
                            <span>NIK</span>
                            <strong>{{ $profile->nik ?? '-' }}</strong>
                        </div>

                        <div class="student-profile-data-row">
                            <span>Jenis Kelamin</span>
                            <strong>{{ $profile->genderLabel() }}</strong>
                        </div>

                        <div class="student-profile-data-row">
                            <span>Tempat, Tgl Lahir</span>
                            <strong>
                                {{ $profile->birth_place ?? '-' }},
                                {{ $profile->birth_date?->translatedFormat('d F Y') ?? '-' }}
                            </strong>
                        </div>

                        <div class="student-profile-data-row">
                            <span>Sekolah Asal</span>
                            <strong>{{ $profile->previous_school ?? '-' }}</strong>
                        </div>

                        <div class="student-profile-data-row">
                            <span>Nama Orang Tua/Wali</span>
                            <strong>{{ $profile->parent_name ?? '-' }}</strong>
                        </div>

                        <div class="student-profile-data-row">
                            <span>No. HP Orang Tua/Wali</span>
                            <strong>{{ $profile->parent_phone ?? '-' }}</strong>
                        </div>

                    </div>

                </div>

            </div>


            {{-- DATA PRIBADI --}}
            <div class="student-profile-card">

                <div class="student-profile-card-header">

                    <div class="student-profile-card-icon blue">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                    <div>
                        <span>PEMBARUAN DATA</span>
                        <h2>Data Pribadi</h2>
                    </div>

                </div>

                <div class="student-profile-card-body">

                    <div class="student-profile-edit-label">
                        <i class="bi bi-pencil"></i>
                        <span>Data berikut dapat kamu ubah.</span>
                    </div>

                    @if ($errors->any() && !$errors->has('current_password') && !$errors->has('new_password'))

                        <div class="student-profile-error">

                            <i class="bi bi-exclamation-circle-fill"></i>

                            <div>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('siswa.profile.update') }}"
                        enctype="multipart/form-data"
                    >

                        @csrf
                        @method('PUT')


                        <div class="student-profile-form-group">

                            <label for="address">
                                Alamat
                            </label>

                            <textarea
                                name="address"
                                id="address"
                                class="student-profile-input"
                                rows="3"
                                placeholder="Masukkan alamat lengkap"
                            >{{ old('address', $profile->address) }}</textarea>

                        </div>


                        <div class="student-profile-form-group">

                            <label for="phone">
                                No. HP
                            </label>

                            <input
                                type="text"
                                name="phone"
                                id="phone"
                                class="student-profile-input"
                                value="{{ old('phone', $profile->phone) }}"
                                placeholder="Masukkan nomor HP"
                            >

                        </div>


                        <div class="student-profile-form-group">

                            <label for="photo">
                                Foto
                                <span>(opsional)</span>
                            </label>

                            @if ($profile->hasPhoto())

                                <div class="student-profile-current-photo">

                                    <div class="student-profile-current-photo-icon">
                                        <i class="bi bi-image"></i>
                                    </div>

                                    <div>
                                        <span>Foto saat ini tersedia</span>

                                        <a
                                            href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($profile->photo_path) }}"
                                            target="_blank"
                                        >
                                            Lihat foto
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </div>

                                </div>

                            @endif

                            <input
                                type="file"
                                name="photo"
                                id="photo"
                                class="student-profile-input"
                                accept="image/*"
                            >

                            <small class="student-profile-help">
                                Format gambar yang didukung sesuai validasi sistem.
                            </small>

                        </div>


                        <div class="student-profile-form-grid">

                            <div class="student-profile-form-group">

                                <label for="emergency_contact_name">
                                    Nama Kontak Darurat
                                </label>

                                <input
                                    type="text"
                                    name="emergency_contact_name"
                                    id="emergency_contact_name"
                                    class="student-profile-input"
                                    value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}"
                                    placeholder="Nama kontak darurat"
                                >

                            </div>


                            <div class="student-profile-form-group">

                                <label for="emergency_contact_phone">
                                    No. HP Kontak Darurat
                                </label>

                                <input
                                    type="text"
                                    name="emergency_contact_phone"
                                    id="emergency_contact_phone"
                                    class="student-profile-input"
                                    value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone) }}"
                                    placeholder="Nomor HP kontak darurat"
                                >

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="student-profile-submit"
                        >
                            <i class="bi bi-check2"></i>
                            Simpan Perubahan
                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- PASSWORD --}}
        <div class="student-profile-card student-profile-password-card">

            <div class="student-profile-card-header">

                <div class="student-profile-card-icon security">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <div>
                    <span>KEAMANAN AKUN</span>
                    <h2>Ganti Password</h2>
                </div>

            </div>


            <div class="student-profile-password-body">

                {{-- FORM PASSWORD --}}
                <div class="student-profile-password-form">

                    @if ($errors->has('current_password') || $errors->has('new_password'))

                        <div class="student-profile-error">

                            <i class="bi bi-exclamation-circle-fill"></i>

                            <div>

                                @error('current_password')
                                    <div>{{ $message }}</div>
                                @enderror

                                @error('new_password')
                                    <div>{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('siswa.profile.update-password') }}"
                    >

                        @csrf


                        <div class="student-profile-form-group">

                            <label for="current_password">
                                Password Saat Ini
                            </label>

                            <div class="student-profile-password-input">

                                <input
                                    type="password"
                                    name="current_password"
                                    id="current_password"
                                    class="student-profile-input @error('current_password') is-invalid @enderror"
                                    placeholder="Masukkan password lama Anda"
                                    autocomplete="current-password"
                                >

                                <button
                                    type="button"
                                    onclick="togglePwd('current_password', this)"
                                    tabindex="-1"
                                    aria-label="Tampilkan password"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                        </div>


                        <div class="student-profile-form-group">

                            <label for="new_password">
                                Password Baru
                            </label>

                            <div class="student-profile-password-input">

                                <input
                                    type="password"
                                    name="new_password"
                                    id="new_password"
                                    class="student-profile-input @error('new_password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter"
                                    oninput="checkStrength(this.value)"
                                    autocomplete="new-password"
                                >

                                <button
                                    type="button"
                                    onclick="togglePwd('new_password', this)"
                                    tabindex="-1"
                                    aria-label="Tampilkan password"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>


                            <div
                                class="student-profile-strength"
                                id="strength-bar-wrap"
                                style="display:none;"
                            >

                                <div class="student-profile-strength-bar">
                                    <div
                                        id="strength-bar"
                                        class="student-profile-strength-progress"
                                    ></div>
                                </div>

                                <small id="strength-label"></small>

                            </div>

                        </div>


                        <div class="student-profile-form-group password-confirm-group">

                            <label for="new_password_confirmation">
                                Konfirmasi Password Baru
                            </label>

                            <div class="student-profile-password-input">

                                <input
                                    type="password"
                                    name="new_password_confirmation"
                                    id="new_password_confirmation"
                                    class="student-profile-input"
                                    placeholder="Ulangi password baru"
                                    autocomplete="new-password"
                                >

                                <button
                                    type="button"
                                    onclick="togglePwd('new_password_confirmation', this)"
                                    tabindex="-1"
                                    aria-label="Tampilkan password"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="student-profile-password-submit"
                        >
                            <i class="bi bi-shield-lock"></i>
                            Perbarui Password
                        </button>

                    </form>

                </div>


                {{-- TIPS --}}
                <div class="student-profile-security-tips">

                    <div class="student-profile-tips-header">

                        <div class="student-profile-tips-icon">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>

                        <div>
                            <span>KEAMANAN</span>
                            <strong>Tips Password</strong>
                        </div>

                    </div>


                    <div class="student-profile-tips-list">

                        <div>
                            <i class="bi bi-check2"></i>
                            <span>Minimal <strong>8 karakter</strong></span>
                        </div>

                        <div>
                            <i class="bi bi-check2"></i>
                            <span>
                                Kombinasi <strong>huruf besar & kecil</strong>
                            </span>
                        </div>

                        <div>
                            <i class="bi bi-check2"></i>
                            <span>
                                Gunakan <strong>angka</strong> atau <strong>simbol</strong>
                            </span>
                        </div>

                        <div class="warning">
                            <i class="bi bi-x"></i>
                            <span>Jangan gunakan nama atau tanggal lahir</span>
                        </div>

                        <div class="warning">
                            <i class="bi bi-x"></i>
                            <span>Jangan bagikan password ke siapapun</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="student-profile-footer">

            <i class="bi bi-shield-check"></i>

            <span>
                Pastikan data yang kamu masukkan sudah benar sebelum menyimpan perubahan.
            </span>

        </div>

    </div>

</div>


<script>
function togglePwd(fieldId, btn) {

    const input = document.getElementById(fieldId);
    const icon = btn.querySelector('i');

    if (input.type === 'password') {

        input.type = 'text';
        icon.className = 'bi bi-eye-slash';

    } else {

        input.type = 'password';
        icon.className = 'bi bi-eye';

    }

}


function checkStrength(val) {

    const wrap = document.getElementById('strength-bar-wrap');
    const bar = document.getElementById('strength-bar');
    const label = document.getElementById('strength-label');

    wrap.style.display = val.length ? 'block' : 'none';

    if (!val.length) {
        return;
    }

    let score = 0;

    if (val.length >= 8) {
        score++;
    }

    if (/[A-Z]/.test(val)) {
        score++;
    }

    if (/[a-z]/.test(val)) {
        score++;
    }

    if (/[0-9]/.test(val)) {
        score++;
    }

    if (/[^A-Za-z0-9]/.test(val)) {
        score++;
    }

    const levels = [
        {
            pct: '20%',
            cls: 'very-weak',
            text: 'Sangat Lemah'
        },
        {
            pct: '40%',
            cls: 'weak',
            text: 'Lemah'
        },
        {
            pct: '60%',
            cls: 'medium',
            text: 'Cukup'
        },
        {
            pct: '80%',
            cls: 'strong',
            text: 'Kuat'
        },
        {
            pct: '100%',
            cls: 'very-strong',
            text: 'Sangat Kuat'
        }
    ];

    const lvl = levels[Math.min(score - 1, 4)] ?? levels[0];

    bar.style.width = lvl.pct;
    bar.className = 'student-profile-strength-progress ' + lvl.cls;

    label.textContent = lvl.text;

}
</script>

@endsection