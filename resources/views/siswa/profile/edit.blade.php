@extends('layouts.app')

@section('title', 'Biodata Saya')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-1">Biodata Saya</h4>
        <p class="text-muted mb-0">
            {{ $student->name }}
            @if ($classroom)
                &middot; Kelas <strong>{{ $classroom->name }}</strong>
            @endif
        </p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-3">
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
                    <tr><th class="text-muted fw-normal">Tempat, Tgl Lahir</th>
                        <td>{{ $profile->birth_place ?? '-' }}, {{ $profile->birth_date?->translatedFormat('d F Y') ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">Sekolah Asal</th><td>{{ $profile->previous_school ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">Nama Orang Tua/Wali</th><td>{{ $profile->parent_name ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">No. HP Orang Tua/Wali</th><td>{{ $profile->parent_phone ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Data Pribadi <span class="text-muted small">(bisa Anda ubah)</span></div>
            <div class="card-body">
                @if ($errors->any())
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
</div>
@endsection
