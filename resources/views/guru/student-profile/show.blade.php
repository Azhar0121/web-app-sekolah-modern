@extends('layouts.admin')

@section('title', 'Biodata Siswa — ' . $student->name)

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-1">{{ $student->name }}</h4>
        <p class="text-muted mb-0">
            {{ $student->email }}
            @if ($classroom)
                &middot; Kelas <strong>{{ $classroom->name }}</strong>
            @endif
        </p>
    </div>
</div>

@if (! $profile)
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center text-muted py-5">
            Biodata siswa ini belum tersedia.
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-borderless table-sm mb-0">
                <tr><th class="text-muted fw-normal" style="width: 200px;">NISN</th><td>{{ $profile->nisn ?? '-' }}</td></tr>
                <tr><th class="text-muted fw-normal">Jenis Kelamin</th><td>{{ $profile->genderLabel() }}</td></tr>
                <tr><th class="text-muted fw-normal">Tempat, Tgl Lahir</th>
                    <td>{{ $profile->birth_place ?? '-' }}, {{ $profile->birth_date?->translatedFormat('d F Y') ?? '-' }}</td></tr>
                <tr><th class="text-muted fw-normal">Alamat</th><td>{{ $profile->address ?? '-' }}</td></tr>
                <tr><th class="text-muted fw-normal">No. HP Siswa</th><td>{{ $profile->phone ?? '-' }}</td></tr>
                <tr><th class="text-muted fw-normal">Nama Orang Tua/Wali</th><td>{{ $profile->parent_name ?? '-' }}</td></tr>
                <tr><th class="text-muted fw-normal">No. HP Orang Tua/Wali</th><td>{{ $profile->parent_phone ?? '-' }}</td></tr>
                <tr><th class="text-muted fw-normal">Kontak Darurat</th>
                    <td>{{ $profile->emergency_contact_name ?? '-' }} ({{ $profile->emergency_contact_phone ?? '-' }})</td></tr>
            </table>
        </div>
    </div>
@endif

<div class="mt-3">
    <a href="{{ route('guru.student-profile.index') }}" class="text-decoration-none">&larr; Kembali ke Daftar Siswa</a>
</div>
@endsection
