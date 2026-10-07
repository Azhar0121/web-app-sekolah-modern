@extends('layouts.admin')

@section('title', 'Kelola Biodata — ' . $student->name)

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/student-profiles/edit.css') }}">

<div class="student-profile-edit-page">

    {{-- HERO --}}
    <section class="student-profile-edit-hero">

        <div class="student-profile-edit-hero-content">

            <div class="student-profile-edit-hero-icon">
                <i class="bi bi-person-vcard-fill"></i>
            </div>

            <div>
                <span class="student-profile-edit-hero-label">
                    DATA SISWA
                </span>

                <h1>Kelola Biodata Siswa</h1>

                <p>
                    Perbarui dan kelola informasi biodata siswa secara lengkap.
                </p>
            </div>

        </div>

        <div class="student-profile-edit-student">

            <div class="student-profile-edit-student-icon">
                <i class="bi bi-person-fill"></i>
            </div>

            <div>
                <span>SISWA</span>
                <strong>{{ $student->name }}</strong>

                @if ($classroom)
                    <small>
                        Kelas {{ $classroom->name }}
                    </small>
                @endif
            </div>

        </div>

    </section>


    {{-- PPDB SYNC --}}
    @if ($ppdbRegistration)

        <section class="student-profile-ppdb-alert">

            <div class="student-profile-ppdb-icon">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>

            <div class="student-profile-ppdb-content">

                <div class="student-profile-ppdb-heading">
                    Data PPDB Tersedia
                </div>

                <p>
                    Siswa ini memiliki data pendaftaran PPDB dengan nomor
                    <strong>{{ $ppdbRegistration->registration_number }}</strong>.
                    Data tersebut dapat disinkronkan secara otomatis ke form biodata.
                </p>

                <form
                    method="POST"
                    action="{{ route('admin.student-profiles.sync-ppdb', $student) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="student-profile-sync-btn"
                    >
                        <i class="bi bi-arrow-repeat"></i>
                        Sinkronkan dari Data PPDB
                    </button>
                </form>

            </div>

        </section>

    @endif


    {{-- FORM --}}
    <section class="student-profile-edit-card">

        <div class="student-profile-edit-card-header">

            <div>
                <span>FORMULIR BIODATA</span>
                <h2>Informasi Siswa</h2>
                <p>
                    Lengkapi data resmi dan informasi pribadi siswa.
                </p>
            </div>

            <div class="student-profile-form-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

        </div>


        <div class="student-profile-edit-card-body">

            {{-- ERRORS --}}
            @if ($errors->any())

                <div class="student-profile-error">

                    <div class="student-profile-error-icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>

                    <div>
                        <strong>Periksa kembali data yang dimasukkan.</strong>

                        <div class="student-profile-error-list">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('admin.student-profiles.update', $student) }}"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')


                {{-- DATA RESMI --}}
                <div class="student-profile-form-section">

                    <div class="student-profile-section-heading">

                        <div class="student-profile-section-icon">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>

                        <div>
                            <span>INFORMASI UTAMA</span>
                            <h3>Data Resmi</h3>
                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="student-profile-form-label">
                                NISN
                            </label>

                            <input
                                type="text"
                                name="nisn"
                                class="form-control student-profile-input"
                                value="{{ old('nisn', $profile->nisn) }}"
                            >
                        </div>


                        <div class="col-md-6">
                            <label class="student-profile-form-label">
                                NIK
                            </label>

                            <input
                                type="text"
                                name="nik"
                                class="form-control student-profile-input"
                                value="{{ old('nik', $profile->nik) }}"
                            >
                        </div>


                        <div class="col-md-4">
                            <label class="student-profile-form-label">
                                Jenis Kelamin
                            </label>

                            <select
                                name="gender"
                                class="form-select student-profile-input"
                            >
                                <option value="">-- Pilih --</option>

                                <option
                                    value="L"
                                    @selected(old('gender', $profile->gender) === 'L')
                                >
                                    Laki-laki
                                </option>

                                <option
                                    value="P"
                                    @selected(old('gender', $profile->gender) === 'P')
                                >
                                    Perempuan
                                </option>

                            </select>
                        </div>


                        <div class="col-md-4">
                            <label class="student-profile-form-label">
                                Tempat Lahir
                            </label>

                            <input
                                type="text"
                                name="birth_place"
                                class="form-control student-profile-input"
                                value="{{ old('birth_place', $profile->birth_place) }}"
                            >
                        </div>


                        <div class="col-md-4">
                            <label class="student-profile-form-label">
                                Tanggal Lahir
                            </label>

                            <input
                                type="date"
                                name="birth_date"
                                class="form-control student-profile-input"
                                value="{{ old('birth_date', $profile->birth_date?->format('Y-m-d')) }}"
                            >
                        </div>


                        <div class="col-md-6">
                            <label class="student-profile-form-label">
                                Sekolah Asal
                            </label>

                            <input
                                type="text"
                                name="previous_school"
                                class="form-control student-profile-input"
                                value="{{ old('previous_school', $profile->previous_school) }}"
                            >
                        </div>


                        <div class="col-md-6">
                            <label class="student-profile-form-label">
                                Nama Orang Tua/Wali
                            </label>

                            <input
                                type="text"
                                name="parent_name"
                                class="form-control student-profile-input"
                                value="{{ old('parent_name', $profile->parent_name) }}"
                            >
                        </div>


                        <div class="col-md-6">
                            <label class="student-profile-form-label">
                                No. HP Orang Tua/Wali
                            </label>

                            <input
                                type="text"
                                name="parent_phone"
                                class="form-control student-profile-input"
                                value="{{ old('parent_phone', $profile->parent_phone) }}"
                            >
                        </div>

                    </div>

                </div>


                {{-- DATA PRIBADI --}}
                <div class="student-profile-form-section personal-section">

                    <div class="student-profile-section-heading">

                        <div class="student-profile-section-icon">
                            <i class="bi bi-person-lines-fill"></i>
                        </div>

                        <div>
                            <span>INFORMASI PERSONAL</span>
                            <h3>Data Pribadi</h3>
                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-12">

                            <label class="student-profile-form-label">
                                Alamat
                            </label>

                            <textarea
                                name="address"
                                class="form-control student-profile-input student-profile-textarea"
                                rows="3"
                            >{{ old('address', $profile->address) }}</textarea>

                        </div>


                        <div class="col-md-6">

                            <label class="student-profile-form-label">
                                No. HP Siswa
                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control student-profile-input"
                                value="{{ old('phone', $profile->phone) }}"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="student-profile-form-label">
                                Foto
                                <span class="student-profile-optional">
                                    Opsional
                                </span>
                            </label>

                            @if ($profile->hasPhoto())

                                <div class="student-profile-current-photo">

                                    <i class="bi bi-image-fill"></i>

                                    <a
                                        href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($profile->photo_path) }}"
                                        target="_blank"
                                    >
                                        Lihat foto saat ini
                                    </a>

                                </div>

                            @endif

                            <input
                                type="file"
                                name="photo"
                                class="form-control student-profile-input"
                                accept="image/*"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="student-profile-form-label">
                                Nama Kontak Darurat
                            </label>

                            <input
                                type="text"
                                name="emergency_contact_name"
                                class="form-control student-profile-input"
                                value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="student-profile-form-label">
                                No. HP Kontak Darurat
                            </label>

                            <input
                                type="text"
                                name="emergency_contact_phone"
                                class="form-control student-profile-input"
                                value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone) }}"
                            >

                        </div>

                    </div>

                </div>


                {{-- FORM ACTION --}}
                <div class="student-profile-form-footer">

                    <div class="student-profile-form-footer-info">

                        <i class="bi bi-info-circle-fill"></i>

                        <span>
                            Pastikan data siswa sudah benar sebelum menyimpan perubahan.
                        </span>

                    </div>

                    <div class="student-profile-form-actions">

                        <a
                            href="{{ route('admin.student-profiles.index') }}"
                            class="student-profile-cancel-btn"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="student-profile-save-btn"
                        >
                            <i class="bi bi-check2"></i>
                            Simpan Perubahan
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </section>

</div>

@endsection