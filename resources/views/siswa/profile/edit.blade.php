@extends('layouts.app')

@section('title', 'Biodata & Keamanan Akun')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-1">Biodata & Keamanan Akun</h4>
        <p class="text-muted mb-0">
            {{ $student->name }}
            @if ($classroom)
                &middot; Kelas <strong>{{ $classroom->name }}</strong>
            @endif
        </p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('password_success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-shield-check me-2"></i>{{ session('password_success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3">

    {{-- DATA RESMI --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Data Resmi</div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Data ini dikelola oleh Tata Usaha. Kalau ada yang keliru,
                    silakan hubungi Tata Usaha untuk dikoreksi.
                </p>
                <table class="table table-borderless table-sm mb-0">
                    <tr><th class="text-muted fw-normal" style="width: 160px;">NISN</th><td>{{ $profile->nisn ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">NIK</th><td>{{ $profile->nik ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">Jenis Kelamin</th><td>{{ $profile->genderLabel() }}</td></tr>
                    <tr>
                        <th class="text-muted fw-normal">Tempat, Tgl Lahir</th>
                        <td>{{ $profile->birth_place ?? '-' }}, {{ $profile->birth_date?->translatedFormat('d F Y') ?? '-' }}</td>
                    </tr>
                    <tr><th class="text-muted fw-normal">Sekolah Asal</th><td>{{ $profile->previous_school ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">Nama Orang Tua/Wali</th><td>{{ $profile->parent_name ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">No. HP Orang Tua/Wali</th><td>{{ $profile->parent_phone ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    {{-- DATA PRIBADI --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">
                Data Pribadi <span class="text-muted small">(bisa Anda ubah)</span>
            </div>
            <div class="card-body">
                @if ($errors->any() && !$errors->has('current_password') && !$errors->has('new_password'))
                    <div class="alert alert-danger py-2">
                        @foreach ($errors->all() as $error)
                            <div class="small">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('siswa.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $profile->address) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto <span class="text-muted">(opsional)</span></label>
                        @if ($profile->hasPhoto())
                            <div class="form-text mb-1">
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($profile->photo_path) }}" target="_blank">
                                    Lihat foto saat ini
                                </a>
                            </div>
                        @endif
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Kontak Darurat</label>
                            <input type="text" name="emergency_contact_name" class="form-control"
                                   value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP Kontak Darurat</label>
                            <input type="text" name="emergency_contact_phone" class="form-control"
                                   value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone) }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    {{-- GANTI PASSWORD --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-shield-lock-fill text-warning"></i>
                <span class="fw-semibold">Ganti Password</span>
            </div>
            <div class="card-body">
                <div class="row g-4">

                    {{-- FORM --}}
                    <div class="col-lg-5">
                        @if ($errors->has('current_password') || $errors->has('new_password'))
                            <div class="alert alert-danger py-2 mb-3">
                                @error('current_password')
                                    <div class="small"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                                @error('new_password')
                                    <div class="small"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <form method="POST" action="{{ route('siswa.profile.update-password') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                                <div class="input-group">
                                    <input type="password" name="current_password" id="current_password"
                                           class="form-control @error('current_password') is-invalid @enderror"
                                           placeholder="Masukkan password lama Anda" autocomplete="current-password">
                                    <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePwd('current_password', this)" tabindex="-1">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label fw-semibold">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" name="new_password" id="new_password"
                                           class="form-control @error('new_password') is-invalid @enderror"
                                           placeholder="Minimal 8 karakter"
                                           oninput="checkStrength(this.value)" autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePwd('new_password', this)" tabindex="-1">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mt-2" id="strength-bar-wrap" style="display:none;">
                                    <div class="progress" style="height:5px; border-radius:3px;">
                                        <div id="strength-bar" class="progress-bar" role="progressbar" style="width:0%; transition: width .3s;"></div>
                                    </div>
                                    <small id="strength-label" class="text-muted"></small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                           class="form-control"
                                           placeholder="Ulangi password baru" autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePwd('new_password_confirmation', this)" tabindex="-1">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning fw-semibold">
                                <i class="bi bi-shield-lock me-1"></i>
                                Perbarui Password
                            </button>
                        </form>
                    </div>

                    {{-- TIPS --}}
                    <div class="col-lg-5 offset-lg-1">
                        <div class="bg-light rounded-3 p-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-info-circle-fill text-primary me-1"></i>
                                Tips Keamanan Password
                            </h6>
                            <ul class="list-unstyled mb-0" style="font-size:.88rem; line-height:1.9;">
                                <li><i class="bi bi-check2 text-success me-2"></i>Minimal <strong>8 karakter</strong></li>
                                <li><i class="bi bi-check2 text-success me-2"></i>Kombinasi <strong>huruf besar & kecil</strong></li>
                                <li><i class="bi bi-check2 text-success me-2"></i>Gunakan <strong>angka</strong> atau <strong>simbol</strong></li>
                                <li><i class="bi bi-x text-danger me-2"></i>Jangan gunakan nama atau tanggal lahir</li>
                                <li><i class="bi bi-x text-danger me-2"></i>Jangan bagikan password ke siapapun</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<script>
function togglePwd(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

function checkStrength(val) {
    const wrap  = document.getElementById('strength-bar-wrap');
    const bar   = document.getElementById('strength-bar');
    const label = document.getElementById('strength-label');

    wrap.style.display = val.length ? 'block' : 'none';
    if (!val.length) return;

    let score = 0;
    if (val.length >= 8)            score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[a-z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;

    const levels = [
        { pct: '20%', cls: 'bg-danger',  text: 'Sangat Lemah' },
        { pct: '40%', cls: 'bg-warning', text: 'Lemah' },
        { pct: '60%', cls: 'bg-info',    text: 'Cukup' },
        { pct: '80%', cls: 'bg-primary', text: 'Kuat' },
        { pct: '100%',cls: 'bg-success', text: 'Sangat Kuat' },
    ];

    const lvl = levels[Math.min(score - 1, 4)] ?? levels[0];
    bar.style.width = lvl.pct;
    bar.className   = 'progress-bar ' + lvl.cls;
    label.textContent = lvl.text;
}
</script>
@endsection
