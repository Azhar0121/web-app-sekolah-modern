
@extends(auth()->user()->hasRole('siswa') || auth()->user()->hasRole('ortu') ? 'layouts.app' : 'layouts.admin')

@section('title', 'Profil & Pengaturan Akun')

@section('content')
<link rel="stylesheet" href="{{ asset('css/account/profile.css') }}">

<div class="account-profile-page">
    <div class="account-page-header">
        <div class="account-page-heading">
            <span class="account-eyebrow">
                <i class="bi bi-person-gear"></i>
                PENGATURAN AKUN
            </span>
            <h1>Profil & Pengaturan Akun</h1>
            <p>Kelola informasi pribadi, foto profil, dan keamanan akun Anda.</p>
        </div>

        <div class="account-role-badge">
            <i class="bi bi-shield-check"></i>
            {{ $user->role->name ?? 'Pengguna' }}
        </div>
    </div>

    @if (session('success'))
        <div class="account-alert account-alert-success" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="account-alert-close"
                    onclick="this.parentElement.remove()" aria-label="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="account-alert account-alert-error" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>
                <strong>Periksa kembali data Anda.</strong>
                <p class="mb-0">Ada beberapa isian yang perlu diperbaiki.</p>
            </div>
        </div>
    @endif

    <div class="account-profile-grid">
        {{-- PROFIL PENGGUNA --}}
        <aside class="account-sidebar">
            <section class="account-card account-identity-card">
                <div class="account-identity-top">
                    <div class="account-avatar-wrap">
                        <x-avatar :user="$user" :size="112" class="account-avatar" />
                        <span class="account-online-indicator"
                              title="Akun aktif"></span>
                    </div>

                    <h2>{{ $user->name }}</h2>
                    <p class="account-user-email">{{ $user->email }}</p>

                    <span class="account-user-role">
                        <i class="bi bi-person-badge"></i>
                        {{ $user->role->name ?? 'Pengguna' }}
                    </span>
                </div>

                <div class="account-card-divider"></div>

                <div class="account-information">
                    <h3>Informasi Akun</h3>

                    <div class="account-info-row">
                        <span class="account-info-icon">
                            <i class="bi bi-check-circle"></i>
                        </span>
                        <div class="account-info-content">
                            <span>Status akun</span>
                            <strong class="account-status-active">
                                <span></span> Aktif
                            </strong>
                        </div>
                    </div>

                    <div class="account-info-row">
                        <span class="account-info-icon">
                            <i class="bi bi-calendar3"></i>
                        </span>
                        <div class="account-info-content">
                            <span>Terdaftar sejak</span>
                            <strong>
                                {{ $user->created_at?->translatedFormat('d F Y') ?? '-' }}
                            </strong>
                        </div>
                    </div>

                    <div class="account-info-row">
                        <span class="account-info-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </span>
                        <div class="account-info-content">
                            <span>Pembaruan terakhir</span>
                            <strong>
                                {{ $user->updated_at?->diffForHumans() ?? '-' }}
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="account-sidebar-note">
                    <i class="bi bi-info-circle"></i>
                    <p>Pastikan informasi akun Anda selalu benar dan terbaru.</p>
                </div>
            </section>
        </aside>

        {{-- FORM PENGATURAN --}}
        <div class="account-main-content">
            {{-- IDENTITAS DAN FOTO --}}
            <section class="account-card account-form-card">
                <div class="account-section-header">
                    <div class="account-section-icon account-icon-blue">
                        <i class="bi bi-person-vcard"></i>
                    </div>
                    <div>
                        <h2>Identitas & Foto Profil</h2>
                        <p>Perbarui informasi dasar akun Anda.</p>
                    </div>
                </div>

                <div class="account-card-body">
                    <form action="{{ route('account.profile.update') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="account-form-grid">
                            <div class="account-field">
                                <label for="name">Nama lengkap</label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    autocomplete="name"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="account-field">
                                <label for="email">Alamat email</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    autocomplete="email"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="account-field account-field-full">
                                <label for="photo">Foto profil baru</label>
                                <div class="account-file-wrap">
                                    <input
                                        type="file"
                                        name="photo"
                                        id="photo"
                                        class="form-control @error('photo') is-invalid @enderror"
                                        accept="image/png,image/jpeg,image/webp"
                                    >
                                </div>
                                <div class="account-field-help">
                                    <i class="bi bi-image"></i>
                                    JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                                    Disarankan menggunakan foto persegi (1:1).
                                </div>
                                @error('photo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                @if ($user->photo_url)
                                    <label class="account-remove-photo" for="remove_photo">
                                        <input
                                            type="checkbox"
                                            name="remove_photo"
                                            id="remove_photo"
                                            value="1"
                                            @checked(old('remove_photo'))
                                        >
                                        <span class="account-remove-icon">
                                            <i class="bi bi-trash3"></i>
                                        </span>
                                        <span>
                                            <strong>Hapus foto profil saat ini</strong>
                                            <small>Foto akan diganti dengan inisial nama.</small>
                                        </span>
                                    </label>
                                @endif
                            </div>
                        </div>

                        <div class="account-form-footer">
                            <span class="account-footer-hint">
                                <i class="bi bi-shield-check"></i>
                                Periksa kembali sebelum menyimpan.
                            </span>
                            <button type="submit" class="account-btn account-btn-primary">
                                <i class="bi bi-check2-circle"></i>
                                Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            {{-- UBAH KATA SANDI --}}
            <section class="account-card account-form-card account-password-card">
                <div class="account-section-header">
                    <div class="account-section-icon account-icon-navy">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div>
                        <h2>Ubah Kata Sandi</h2>
                        <p>Gunakan kata sandi yang kuat untuk menjaga keamanan akun.</p>
                    </div>
                </div>

                <div class="account-card-body">
                    <form action="{{ route('account.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="account-form-grid">
                            <div class="account-field account-field-full">
                                <label for="current_password">Kata sandi saat ini</label>
                                <div class="account-password-input">
                                    <input
                                        type="password"
                                        name="current_password"
                                        id="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        placeholder="Masukkan kata sandi saat ini"
                                        autocomplete="current-password"
                                        required
                                    >
                                    <button type="button" class="account-password-toggle"
                                            data-password-target="current_password"
                                            aria-label="Tampilkan kata sandi">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="account-field">
                                <label for="password">Kata sandi baru</label>
                                <div class="account-password-input">
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Minimal 8 karakter"
                                        autocomplete="new-password"
                                        required
                                    >
                                    <button type="button" class="account-password-toggle"
                                            data-password-target="password"
                                            aria-label="Tampilkan kata sandi">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="account-field">
                                <label for="password_confirmation">Konfirmasi kata sandi</label>
                                <div class="account-password-input">
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="form-control"
                                        placeholder="Ulangi kata sandi baru"
                                        autocomplete="new-password"
                                        required
                                    >
                                    <button type="button" class="account-password-toggle"
                                            data-password-target="password_confirmation"
                                            aria-label="Tampilkan kata sandi">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="account-password-note">
                            <i class="bi bi-info-circle"></i>
                            <span>Pastikan kata sandi baru mudah Anda ingat dan tidak dibagikan kepada orang lain.</span>
                        </div>

                        <div class="account-form-footer">
                            <span class="account-footer-hint">
                                <i class="bi bi-lock"></i>
                                Informasi keamanan akun
                            </span>
                            <button type="submit" class="account-btn account-btn-dark">
                                <i class="bi bi-shield-check"></i>
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-password-target]');

    if (!button) return;

    const input = document.getElementById(button.dataset.passwordTarget);
    if (!input) return;

    const showPassword = input.type === 'password';
    input.type = showPassword ? 'text' : 'password';

    const icon = button.querySelector('i');
    if (icon) {
        icon.classList.toggle('bi-eye', !showPassword);
        icon.classList.toggle('bi-eye-slash', showPassword);
    }

    button.setAttribute(
        'aria-label',
        showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'
    );
});
</script>
@endsection
