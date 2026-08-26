<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar PPDB - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ppdb-form.css') }}">
</head>

<body>

    <div class="ppdb-form-page">

        {{-- HEADER --}}
        <header class="form-header">

            <a href="{{ route('ppdb.index') }}" class="back-button">
                <span>&larr;</span>
                Kembali ke PPDB
            </a>

            <div class="header-badge">
                PPDB ONLINE
            </div>

        </header>


        {{-- HERO --}}
        <section class="form-hero">

            <div>
                <span class="hero-label">
                    FORMULIR PENDAFTARAN
                </span>

                <h1>
                    Daftar PPDB
                </h1>

                <p>
                    Lengkapi data calon siswa dengan benar untuk
                    melanjutkan proses pendaftaran.
                </p>
            </div>

            <div class="hero-period">
                <span>PERIODE PENDAFTARAN</span>
                <strong>{{ $activePeriod->name }}</strong>
            </div>

        </section>


        {{-- ERROR --}}
        @if ($errors->any())
            <div class="error-box">

                <div class="error-icon">
                    !
                </div>

                <div>
                    <strong>
                        Periksa kembali data yang diisi
                    </strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>
        @endif


        {{-- FORM --}}
        <form
            method="POST"
            action="{{ route('ppdb.store') }}"
            enctype="multipart/form-data"
        >

            @csrf


            {{-- DATA CALON SISWA --}}
            <section class="form-card">

                <div class="section-heading">

                    <div class="section-number">
                        01
                    </div>

                    <div>
                        <span>INFORMASI UTAMA</span>
                        <h2>Data Calon Siswa</h2>
                    </div>

                </div>


                <div class="form-grid">

                    {{-- NAMA --}}
                    <div class="form-field full">
                        <label for="full_name">
                            Nama Lengkap
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            value="{{ old('full_name') }}"
                            placeholder="Masukkan nama lengkap"
                            required
                        >
                    </div>


                    {{-- NISN --}}
                    <div class="form-field">
                        <label for="nisn">
                            NISN
                        </label>

                        <input
                            type="text"
                            id="nisn"
                            name="nisn"
                            value="{{ old('nisn') }}"
                            placeholder="Masukkan NISN"
                        >
                    </div>


                    {{-- NIK --}}
                    <div class="form-field">
                        <label for="nik">
                            NIK
                        </label>

                        <input
                            type="text"
                            id="nik"
                            name="nik"
                            value="{{ old('nik') }}"
                            placeholder="Masukkan NIK"
                        >
                    </div>


                    {{-- JENIS KELAMIN --}}
                    <div class="form-field">
                        <label for="gender">
                            Jenis Kelamin
                            <span>*</span>
                        </label>

                        <select
                            name="gender"
                            id="gender"
                            required
                        >
                            <option value="">
                                -- Pilih Jenis Kelamin --
                            </option>

                            <option
                                value="L"
                                @selected(old('gender') === 'L')
                            >
                                Laki-laki
                            </option>

                            <option
                                value="P"
                                @selected(old('gender') === 'P')
                            >
                                Perempuan
                            </option>
                        </select>
                    </div>


                    {{-- TEMPAT LAHIR --}}
                    <div class="form-field">
                        <label for="birth_place">
                            Tempat Lahir
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="birth_place"
                            name="birth_place"
                            value="{{ old('birth_place') }}"
                            placeholder="Contoh: Semarang"
                            required
                        >
                    </div>


                    {{-- TANGGAL LAHIR --}}
                    <div class="form-field">
                        <label for="birth_date">
                            Tanggal Lahir
                            <span>*</span>
                        </label>

                        <input
                            type="date"
                            id="birth_date"
                            name="birth_date"
                            value="{{ old('birth_date') }}"
                            required
                        >
                    </div>


                    {{-- ALAMAT --}}
                    <div class="form-field full">
                        <label for="address">
                            Alamat
                            <span>*</span>
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            rows="4"
                            placeholder="Masukkan alamat lengkap"
                            required
                        >{{ old('address') }}</textarea>
                    </div>


                    {{-- PHONE --}}
                    <div class="form-field">
                        <label for="phone">
                            No. HP Calon Siswa
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="Contoh: 081234567890"
                            required
                        >
                    </div>

                </div>

            </section>


            {{-- DATA ORANG TUA --}}
            <section class="form-card">

                <div class="section-heading">

                    <div class="section-number">
                        02
                    </div>

                    <div>
                        <span>INFORMASI KELUARGA</span>
                        <h2>Data Orang Tua/Wali</h2>
                    </div>

                </div>


                <div class="form-grid">

                    <div class="form-field">
                        <label for="parent_name">
                            Nama Orang Tua/Wali
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="parent_name"
                            name="parent_name"
                            value="{{ old('parent_name') }}"
                            placeholder="Masukkan nama orang tua/wali"
                            required
                        >
                    </div>


                    <div class="form-field">
                        <label for="parent_phone">
                            No. HP Orang Tua/Wali
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="parent_phone"
                            name="parent_phone"
                            value="{{ old('parent_phone') }}"
                            placeholder="Contoh: 081234567890"
                            required
                        >
                    </div>

                </div>

            </section>


            {{-- ASAL SEKOLAH --}}
            <section class="form-card">

                <div class="section-heading">

                    <div class="section-number">
                        03
                    </div>

                    <div>
                        <span>INFORMASI PENDIDIKAN</span>
                        <h2>Asal Sekolah</h2>
                    </div>

                </div>


                <div class="form-grid">

                    <div class="form-field full">

                        <label for="previous_school">
                            Nama Sekolah Asal (SMP)
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="previous_school"
                            name="previous_school"
                            value="{{ old('previous_school') }}"
                            placeholder="Masukkan nama sekolah asal"
                            required
                        >

                    </div>

                </div>

            </section>


            {{-- DOKUMEN --}}
            <section class="form-card">

                <div class="section-heading">

                    <div class="section-number">
                        04
                    </div>

                    <div>
                        <span>DOKUMEN PENDUKUNG</span>
                        <h2>Dokumen</h2>
                    </div>

                </div>


                <div class="document-info">
                    <strong>Dokumen bersifat opsional</strong>

                    <p>
                        Dokumen dapat dilengkapi menyusul jika belum tersedia.
                    </p>
                </div>


                {{-- KK --}}
                <div class="file-field">

                    <div class="file-info">
                        <strong>Kartu Keluarga</strong>
                        <span>Format dokumen sesuai ketentuan sistem</span>
                    </div>

                    <input
                        type="file"
                        name="documents[]"
                    >

                    <input
                        type="hidden"
                        name="document_types[]"
                        value="kartu_keluarga"
                    >

                </div>


                {{-- AKTA --}}
                <div class="file-field">

                    <div class="file-info">
                        <strong>Akta Lahir</strong>
                        <span>Dokumen akta kelahiran calon siswa</span>
                    </div>

                    <input
                        type="file"
                        name="documents[]"
                    >

                    <input
                        type="hidden"
                        name="document_types[]"
                        value="akta_lahir"
                    >

                </div>


                {{-- RAPOR --}}
                <div class="file-field">

                    <div class="file-info">
                        <strong>Rapor</strong>
                        <span>Dokumen rapor calon siswa</span>
                    </div>

                    <input
                        type="file"
                        name="documents[]"
                    >

                    <input
                        type="hidden"
                        name="document_types[]"
                        value="rapor"
                    >

                </div>

            </section>


            {{-- SUBMIT --}}
            <div class="form-submit">

                <div class="submit-info">
                    <strong>Sudah yakin dengan data kamu?</strong>

                    <span>
                        Pastikan seluruh data yang wajib telah diisi dengan benar.
                    </span>
                </div>

                <button type="submit" class="submit-button">

                    <span>Kirim Pendaftaran</span>

                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>

                </button>

            </div>

        </form>


        {{-- FOOTER --}}
        <footer class="form-footer">

            <div></div>

            <p>
                &copy; {{ date('Y') }} {{ config('app.name') }}
                &nbsp;•&nbsp;
                Sistem PPDB Online
            </p>

        </footer>

    </div>

</body>
</html>