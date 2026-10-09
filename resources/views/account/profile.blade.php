@extends(auth()->user()->hasRole('siswa') || auth()->user()->hasRole('ortu') ? 'layouts.app' : 'layouts.admin')

@section('title', 'Profil & Pengaturan Akun')

@section('content')
<div class="container-fluid px-0 px-md-3 py-3">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-person-circle me-2 text-primary"></i>Profil & Pengaturan Akun
            </h1>
            <p class="text-muted small mb-0">Kelola identitas akun Anda, perbarui foto profil, dan ubah kata sandi.</p>
        </div>
        <div>
            <span class="badge px-3 py-2 rounded-pill" style="background-color: #eaf3ff; color: #1769d5; border: 1px solid #bfdbfe; font-size: 0.8rem;">
                <i class="bi bi-shield-check me-1"></i>{{ $user->role->name ?? 'Pengguna' }}
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center p-4">
                    <div class="position-relative d-inline-block mb-3">
                        <x-avatar :user="$user" :size="120" class="border border-4 border-white shadow" />
                        <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-white rounded-circle shadow-sm" title="Akun Aktif">
                            <span class="visually-hidden">Aktif</span>
                        </span>
                    </div>

                    <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill mb-4">
                        Role: {{ $user->role->name ?? '-' }}
                    </span>

                    <div class="border-top pt-3 text-start small">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Status Akun</span>
                            <span class="badge bg-success-subtle text-success">Aktif</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Terdaftar Sejak</span>
                            <span class="fw-semibold">{{ $user->created_at?->translatedFormat('d F Y') ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Pembaruan Terakhir</span>
                            <span class="fw-semibold">{{ $user->updated_at?->diffForHumans() ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-camera me-2 text-primary"></i>Identitas & Foto Profil
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold small">Nama Lengkap</label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold small">Alamat Email</label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-3">
                                <label for="photo" class="form-label fw-semibold small">Foto Profil Baru</label>
                                <input type="file"
                                       name="photo"
                                       id="photo"
                                       class="form-control @error('photo') is-invalid @enderror"
                                       accept="image/png,image/jpeg,image/webp">
                                <div class="form-text small">
                                    Format didukung: JPG, JPEG, PNG, WEBP. Maksimal ukuran 2MB. Disarankan foto portrait resmi atau rasio 1:1.
                                </div>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($user->photo_url)
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
                                        <label class="form-check-label text-danger small fw-semibold" for="remove_photo">
                                            Hapus foto profil saya saat ini (kembali ke inisial nama)
                                        </label>
                                    </div>
                                @endif
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan Profil
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-key me-2 text-warning"></i>Ubah Kata Sandi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('account.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="current_password" class="form-label fw-semibold small">Kata Sandi Saat Ini</label>
                                <input type="password"
                                       name="current_password"
                                       id="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       placeholder="Masukkan kata sandi lama Anda"
                                       required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold small">Kata Sandi Baru</label>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimal 8 karakter"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold small">Konfirmasi Kata Sandi Baru</label>
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control"
                                       placeholder="Ulangi kata sandi baru"
                                       required>
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-outline-dark px-4 rounded-3">
                                    <i class="bi bi-lock me-1"></i> Perbarui Kata Sandi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
