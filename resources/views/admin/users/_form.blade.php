@if ($errors->any())
    <div class="alert alert-danger py-2">
        @foreach ($errors->all() as $error)
            <div class="small">{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="mb-3">
    <label for="name" class="form-label">Nama Lengkap</label>
    <input type="text" name="name" id="name" class="form-control"
           value="{{ old('name', $user?->name) }}" required autofocus>
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control"
           value="{{ old('email', $user?->email) }}" required>
</div>

<div class="mb-3">
    <label for="role_id" class="form-label">Role</label>
    <select name="role_id" id="role_id" class="form-select" required>
        <option value="">-- Pilih Role --</option>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" @selected(old('role_id', $user?->role_id) == $role->id)>
                {{ $role->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="photo" class="form-label">Foto Profil (Guru / Staff / Pengguna)</label>
    @if ($user?->photo_url)
        <div class="d-flex align-items-center gap-3 mb-2 p-2 border rounded bg-light">
            <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="rounded-circle object-fit-cover border" style="width: 56px; height: 56px;">
            <div>
                <div class="small fw-semibold text-secondary">Foto saat ini aktif</div>
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
                    <label class="form-check-label small text-danger" for="remove_photo">
                        Hapus foto profil ini (gunakan inisial nama)
                    </label>
                </div>
            </div>
        </div>
    @endif
    <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/png,image/jpeg,image/webp">
    <div class="form-text small">Format didukung: JPG, PNG, WEBP (maks. 2MB). Disarankan foto rasio 1:1 atau portrait rapi.</div>
    @error('photo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="password" class="form-label">
        Password {{ $user ? '(kosongkan jika tidak ingin mengubah)' : '' }}
    </label>
    <input type="password" name="password" id="password" class="form-control" {{ $user ? '' : 'required' }}>
</div>

<div class="mb-3">
    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
</div>

<div class="form-check">
    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
           @checked(old('is_active', $user?->is_active ?? true))>
    <label for="is_active" class="form-check-label">Akun aktif (bisa login)</label>
</div>
