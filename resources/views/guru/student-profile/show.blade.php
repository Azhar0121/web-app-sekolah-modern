@extends('layouts.admin')

@section('title', 'Biodata Siswa — ' . $student->name)

@section('content')
<link rel="stylesheet" href="{{ asset('css/guru/student-profile/show.css') }}">

<div class="guru-student-detail">

    {{-- HERO --}}
    <div class="student-detail-hero">
        <div class="student-detail-hero-content">

            <div class="student-detail-profile-row">

                <div class="student-detail-profile">

                    <div class="student-detail-avatar">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>

                    <div class="student-detail-heading">

                        <div class="student-detail-label">
                            BIODATA SISWA
                        </div>

                        <h1>
                            {{ $student->name }}
                        </h1>

                        <p>
                            <i class="bi bi-envelope"></i>
                            {{ $student->email }}

                            @if ($classroom)
                                <span class="student-detail-separator">•</span>

                                <i class="bi bi-door-open"></i>
                                Kelas {{ $classroom->name }}
                            @endif
                        </p>

                    </div>

                </div>

                <a
                    href="{{ route('guru.student-profile.index') }}"
                    class="student-detail-back"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Daftar Siswa</span>
                </a>

            </div>

        </div>

        <div class="student-detail-hero-decoration one"></div>
        <div class="student-detail-hero-decoration two"></div>
    </div>


    {{-- CONTENT --}}
    @if (! $profile)

        {{-- EMPTY STATE --}}
        <div class="student-detail-empty">

            <div class="student-detail-empty-icon">
                <i class="bi bi-person-x"></i>
            </div>

            <h2>
                Biodata Belum Tersedia
            </h2>

            <p>
                Biodata siswa ini belum tersedia di dalam sistem.
            </p>

        </div>

    @else

        {{-- DATA SISWA --}}
        <div class="student-detail-card">

            <div class="student-detail-card-header">

                <div class="student-detail-section-icon">
                    <i class="bi bi-person-vcard-fill"></i>
                </div>

                <div>
                    <div class="student-detail-eyebrow">
                        INFORMASI PRIBADI
                    </div>

                    <h2>
                        Data Siswa
                    </h2>

                    <p>
                        Informasi biodata siswa yang tersimpan di dalam sistem.
                    </p>
                </div>

            </div>


            <div class="student-detail-info">

                {{-- NISN --}}
                <div class="student-info-row">

                    <div class="student-info-label">
                        <i class="bi bi-card-text"></i>
                        <span>NISN</span>
                    </div>

                    <div class="student-info-value">
                        {{ $profile->nisn ?? '-' }}
                    </div>

                </div>


                {{-- JENIS KELAMIN --}}
                <div class="student-info-row">

                    <div class="student-info-label">
                        <i class="bi bi-gender-ambiguous"></i>
                        <span>Jenis Kelamin</span>
                    </div>

                    <div class="student-info-value">

                        <span class="student-detail-gender">
                            {{ $profile->genderLabel() }}
                        </span>

                    </div>

                </div>


                {{-- TEMPAT TANGGAL LAHIR --}}
                <div class="student-info-row">

                    <div class="student-info-label">
                        <i class="bi bi-calendar3"></i>
                        <span>Tempat, Tgl Lahir</span>
                    </div>

                    <div class="student-info-value">
                        {{ $profile->birth_place ?? '-' }},
                        {{ $profile->birth_date?->translatedFormat('d F Y') ?? '-' }}
                    </div>

                </div>


                {{-- ALAMAT --}}
                <div class="student-info-row">

                    <div class="student-info-label">
                        <i class="bi bi-geo-alt"></i>
                        <span>Alamat</span>
                    </div>

                    <div class="student-info-value">
                        {{ $profile->address ?? '-' }}
                    </div>

                </div>


                {{-- NO HP SISWA --}}
                <div class="student-info-row">

                    <div class="student-info-label">
                        <i class="bi bi-phone"></i>
                        <span>No. HP Siswa</span>
                    </div>

                    <div class="student-info-value">
                        {{ $profile->phone ?? '-' }}
                    </div>

                </div>


                {{-- ORANG TUA --}}
                <div class="student-info-row">

                    <div class="student-info-label">
                        <i class="bi bi-people"></i>
                        <span>Nama Orang Tua/Wali</span>
                    </div>

                    <div class="student-info-value">
                        {{ $profile->parent_name ?? '-' }}
                    </div>

                </div>


                {{-- HP ORANG TUA --}}
                <div class="student-info-row">

                    <div class="student-info-label">
                        <i class="bi bi-telephone"></i>
                        <span>No. HP Orang Tua/Wali</span>
                    </div>

                    <div class="student-info-value">
                        {{ $profile->parent_phone ?? '-' }}
                    </div>

                </div>


                {{-- KONTAK DARURAT --}}
                <div class="student-info-row">

                    <div class="student-info-label">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Kontak Darurat</span>
                    </div>

                    <div class="student-info-value">

                        {{ $profile->emergency_contact_name ?? '-' }}

                        <span class="student-emergency-phone">
                            ({{ $profile->emergency_contact_phone ?? '-' }})
                        </span>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>
@endsection