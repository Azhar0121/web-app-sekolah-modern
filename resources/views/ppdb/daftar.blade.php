<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Formulir Pendaftaran PPDB - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ppdb-form.css') }}">
</head>

<body>

    <div class="ppdb-form-page">

        {{-- HEADER --}}
        <header class="form-header">

            <a href="{{ route('ppdb.index') }}" class="back-button">
                <span>&larr;</span>
                PPDB Online
            </a>

            <div class="header-badge">
                FORMULIR PENDAFTARAN
            </div>

        </header>


        {{-- HERO --}}
        <section class="form-hero">

            <div>
                <span class="hero-label">
                    PENERIMAAN PESERTA DIDIK BARU
                </span>

                <h1>
                    Formulir Pendaftaran
                </h1>

                <p>
                    Lengkapi data di bawah ini dengan benar. Nomor pendaftaran
                    akan dikirimkan ke email yang Anda daftarkan.
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
                <div class="error-icon">!</div>

                <div>
                    <strong>Periksa kembali data yang Anda isi</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif


        <form method="POST" action="{{ route('ppdb.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- DATA CALON SISWA --}}
            <div class="form-card">

                <div class="section-heading">
                    <div class="section-number">1</div>

                    <div>
                        <span>LANGKAH 1</span>
                        <h2>Data Calon Siswa</h2>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="form-field full">
                        <label>Nama Lengkap <span>*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}"
                               placeholder="Sesuai akta lahir / ijazah" required>
                    </div>

                    <div class="form-field">
                        <label>NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}"
                               placeholder="Nomor Induk Siswa Nasional">
                    </div>

                    <div class="form-field">
                        <label>NIK</label>
                        <input type="text" name="nik" value="{{ old('nik') }}"
                               placeholder="Nomor Induk Kependudukan">
                    </div>

                    <div class="form-field">
                        <label>Jenis Kelamin <span>*</span></label>
                        <select name="gender" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" @selected(old('gender') === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('gender') === 'P')>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label>Tempat Lahir <span>*</span></label>
                        <input type="text" name="birth_place" value="{{ old('birth_place') }}"
                               placeholder="Kota tempat lahir" required>
                    </div>

                    <div class="form-field">
                        <label>Tanggal Lahir <span>*</span></label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" required>
                    </div>

                    <div class="form-field full">
                        <label>Alamat <span>*</span></label>
                        <textarea name="address" placeholder="Alamat lengkap tempat tinggal" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="form-field">
                        <label>No. HP Calon Siswa <span>*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="08xxxxxxxxxx" required>
                    </div>

                    <div class="form-field">
                        <label>Email <span>*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="email@aktif.com" required>
                    </div>

                </div>

            </div>


            {{-- DATA ORANG TUA/WALI --}}
            <div class="form-card">

                <div class="section-heading">
                    <div class="section-number">2</div>

                    <div>
                        <span>LANGKAH 2</span>
                        <h2>Data Orang Tua / Wali</h2>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="form-field">
                        <label>Nama Orang Tua/Wali <span>*</span></label>
                        <input type="text" name="parent_name" value="{{ old('parent_name') }}"
                               placeholder="Nama lengkap" required>
                    </div>

                    <div class="form-field">
                        <label>No. HP Orang Tua/Wali <span>*</span></label>
                        <input type="text" name="parent_phone" value="{{ old('parent_phone') }}"
                               placeholder="08xxxxxxxxxx" required>
                    </div>

                </div>

            </div>


            {{-- ASAL SEKOLAH --}}
            <div class="form-card">

                <div class="section-heading">
                    <div class="section-number">3</div>

                    <div>
                        <span>LANGKAH 3</span>
                        <h2>Asal Sekolah</h2>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="form-field full">
                        <label>Nama Sekolah Asal (SMP) <span>*</span></label>
                        <input type="text" name="previous_school" value="{{ old('previous_school') }}"
                               placeholder="Nama SMP/sederajat" required>
                    </div>

                    <div class="form-field">
                        <label>Nilai Rata-rata Rapor (Semester 1-5) <span>*</span></label>
                        <input type="number" name="nilai_rapor" value="{{ old('nilai_rapor') }}"
                               min="0" max="100" step="0.01" placeholder="Contoh: 85.50" required>
                    </div>

                    <div class="form-field">
                        <label>Nilai Ijazah <span>*</span></label>
                        <input type="number" name="nilai_ijazah" value="{{ old('nilai_ijazah') }}"
                               min="0" max="100" step="0.01" placeholder="Contoh: 82.00" required>
                    </div>

                </div>

            </div>


            {{-- PILIHAN JURUSAN --}}
            <div class="form-card">

                <div class="section-heading">
                    <div class="section-number">4</div>

                    <div>
                        <span>LANGKAH 4</span>
                        <h2>Pilihan Jurusan (SMA / SMK)</h2>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="form-field">
                        <label>Pilihan Jurusan 1 <span>*</span></label>
                        <select name="first_major" required>
                            <option value="">-- Pilih Jurusan Utama --</option>
                            <optgroup label="Sekolah Menengah Atas (SMA)">
                                <option value="SMA - MIPA / IPA" @selected(old('first_major') === 'SMA - MIPA / IPA')>SMA - MIPA / IPA (Matematika & IPA)</option>
                                <option value="SMA - IPS" @selected(old('first_major') === 'SMA - IPS')>SMA - IPS (Ilmu-Ilmu Sosial)</option>
                                <option value="SMA - Bahasa & Budaya" @selected(old('first_major') === 'SMA - Bahasa & Budaya')>SMA - Bahasa & Budaya</option>
                            </optgroup>
                            <optgroup label="Sekolah Menengah Kejuruan (SMK)">
                                <option value="SMK - Rekayasa Perangkat Lunak (RPL)" @selected(old('first_major') === 'SMK - Rekayasa Perangkat Lunak (RPL)')>SMK - Rekayasa Perangkat Lunak (RPL)</option>
                                <option value="SMK - Teknik Komputer & Jaringan (TKJ)" @selected(old('first_major') === 'SMK - Teknik Komputer & Jaringan (TKJ)')>SMK - Teknik Komputer & Jaringan (TKJ)</option>
                                <option value="SMK - Teknik Kendaraan Ringan (TKR)" @selected(old('first_major') === 'SMK - Teknik Kendaraan Ringan (TKR)')>SMK - Teknik Kendaraan Ringan (TKR / Otomotif)</option>
                                <option value="SMK - Akuntansi & Keuangan Lembaga (AKL)" @selected(old('first_major') === 'SMK - Akuntansi & Keuangan Lembaga (AKL)')>SMK - Akuntansi & Keuangan Lembaga (AKL)</option>
                                <option value="SMK - Manajemen Perkantoran (MPLB / OTKP)" @selected(old('first_major') === 'SMK - Manajemen Perkantoran (MPLB / OTKP)')>SMK - Manajemen Perkantoran (MPLB / OTKP)</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-field">
                        <label>Pilihan Jurusan 2 <span class="text-muted">(Opsional / Cadangan)</span></label>
                        <select name="second_major">
                            <option value="">-- Tanpa Pilihan Kedua --</option>
                            <optgroup label="Sekolah Menengah Atas (SMA)">
                                <option value="SMA - MIPA / IPA" @selected(old('second_major') === 'SMA - MIPA / IPA')>SMA - MIPA / IPA (Matematika & IPA)</option>
                                <option value="SMA - IPS" @selected(old('second_major') === 'SMA - IPS')>SMA - IPS (Ilmu-Ilmu Sosial)</option>
                                <option value="SMA - Bahasa & Budaya" @selected(old('second_major') === 'SMA - Bahasa & Budaya')>SMA - Bahasa & Budaya</option>
                            </optgroup>
                            <optgroup label="Sekolah Menengah Kejuruan (SMK)">
                                <option value="SMK - Rekayasa Perangkat Lunak (RPL)" @selected(old('second_major') === 'SMK - Rekayasa Perangkat Lunak (RPL)')>SMK - Rekayasa Perangkat Lunak (RPL)</option>
                                <option value="SMK - Teknik Komputer & Jaringan (TKJ)" @selected(old('second_major') === 'SMK - Teknik Komputer & Jaringan (TKJ)')>SMK - Teknik Komputer & Jaringan (TKJ)</option>
                                <option value="SMK - Teknik Kendaraan Ringan (TKR)" @selected(old('second_major') === 'SMK - Teknik Kendaraan Ringan (TKR)')>SMK - Teknik Kendaraan Ringan (TKR / Otomotif)</option>
                                <option value="SMK - Akuntansi & Keuangan Lembaga (AKL)" @selected(old('second_major') === 'SMK - Akuntansi & Keuangan Lembaga (AKL)')>SMK - Akuntansi & Keuangan Lembaga (AKL)</option>
                                <option value="SMK - Manajemen Perkantoran (MPLB / OTKP)" @selected(old('second_major') === 'SMK - Manajemen Perkantoran (MPLB / OTKP)')>SMK - Manajemen Perkantoran (MPLB / OTKP)</option>
                            </optgroup>
                        </select>
                    </div>

                </div>

            </div>


            {{-- DOKUMEN --}}
            <div class="form-card">

                <div class="section-heading">
                    <div class="section-number">5</div>

                    <div>
                        <span>LANGKAH 5</span>
                        <h2>Unggah Dokumen</h2>
                    </div>
                </div>

                <div class="document-info">
                    <strong>Dokumen bersifat opsional saat ini</strong>
                    <p>Format PDF/JPG/PNG, maksimal 2MB per file. Dokumen yang belum lengkap bisa disusulkan langsung ke sekolah.</p>
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Kartu Keluarga</strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB</span>
                    </div>
                    <input type="file" name="documents[kartu_keluarga]" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Akta Lahir</strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB</span>
                    </div>
                    <input type="file" name="documents[akta_lahir]" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Rapor (Semester 1-5)</strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB</span>
                    </div>
                    <input type="file" name="documents[rapor]" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Sertifikat Prestasi 1 <span class="text-muted">(Opsional)</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB</span>
                    </div>
                    <input type="file" name="documents[sertifikat_1]" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Sertifikat Prestasi 2 <span class="text-muted">(Opsional)</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB</span>
                    </div>
                    <input type="file" name="documents[sertifikat_2]" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Surat Keterangan Lulus (SKL) <span class="text-muted">(Opsional)</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB. Cukup lampirkan salah satu: SKL atau Ijazah asli kalau sudah terbit.</span>
                    </div>
                    <input type="file" name="documents[surat_keterangan_lulus]" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="form-submit">
                    <div class="submit-info">
                        <strong>Siap dikirim?</strong>
                        <span>Periksa kembali data Anda sebelum mengirim pendaftaran.</span>
                    </div>

                    <button type="submit" class="submit-button">
                        <span>Kirim Pendaftaran</span>

                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>

            </div>

        </form>


        {{-- FOOTER --}}
        <div class="form-footer">
            <div></div>
            <p>
                &copy; {{ date('Y') }} {{ config('app.name') }}
                &nbsp;•&nbsp;
                Sistem PPDB Online
            </p>
        </div>

    </div>

</body>

</html>
