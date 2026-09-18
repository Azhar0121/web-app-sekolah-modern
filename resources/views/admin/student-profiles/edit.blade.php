@extends('layouts.admin')

@section('title', 'Kelola Biodata — ' . $student->name)

@section('content')
<h4 class="fw-bold mb-1">Kelola Biodata Siswa</h4>
<p class="text-muted mb-4">
    {{ $student->name }} ({{ $student->email }})
    @if ($classroom)
        &middot; Kelas <strong>{{ $classroom->name }}</strong>
    @endif
</p>

<div class="card border-0 shadow-sm" style="max-width: 760px;">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger py-2">
                @foreach ($errors->all() as $error)
                    <div class="small">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.student-profiles.update', $student) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h6 class="fw-bold text-uppercase text-muted small mb-3">Data Resmi</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $profile->nisn) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">NIK</label>
                    <input type="text" name="nik" class="form-control" value="{{ old('nik', $profile->nik) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="gender" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="L" @selected(old('gender', $profile->gender) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('gender', $profile->gender) === 'P')>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place', $profile->birth_place) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="birth_date" class="form-control"
                           value="{{ old('birth_date', $profile->birth_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sekolah Asal</label>
                    <input type="text" name="previous_school" class="form-control" value="{{ old('previous_school', $profile->previous_school) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Orang Tua/Wali</label>
                    <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', $profile->parent_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP Orang Tua/Wali</label>
                    <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone', $profile->parent_phone) }}">
                </div>
            </div>

            <h6 class="fw-bold text-uppercase text-muted small mb-3">Data Pribadi</h6>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $profile->address) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP Siswa</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone) }}">
                </div>
                <div class="col-md-6">
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
                <div class="col-md-6">
                    <label class="form-label">Nama Kontak Darurat</label>
                    <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP Kontak Darurat</label>
                    <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone) }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.student-profiles.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
