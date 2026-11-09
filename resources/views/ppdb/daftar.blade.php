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

        {{-- HERO --}}
        <section class="form-hero">

            <div class="hero-content">

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

                <a href="{{ route('ppdb.index') }}" class="back-button">
                    <span>&larr;</span>
                    Kembali ke Informasi PPDB
                </a>

            </div>

            <div class="hero-period">
                <span>PERIODE PENDAFTARAN</span>
                <strong>{{ $activePeriod->name }}</strong>
            </div>

        </section>

        {{-- ERROR ALERT --}}
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

        <form method="POST" action="{{ route('ppdb.store') }}" enctype="multipart/form-data" id="ppdbRegistrationForm">
            @csrf

            {{-- =========================================================
                 LANGKAH 1: DATA CALON SISWA
                 ========================================================= --}}
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
                        <label>Alamat Tempat Tinggal <span>*</span></label>
                        <textarea name="address" placeholder="Alamat lengkap tempat tinggal saat ini" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="form-field">
                        <label>No. HP Calon Siswa <span>*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="08xxxxxxxxxx" required>
                    </div>

                    <div class="form-field">
                        <label>Email Calon Siswa <span>*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="email@aktif.com" required>
                    </div>

                </div>

            </div>

            {{-- =========================================================
                 LANGKAH 2: DATA ORANG TUA / WALI
                 ========================================================= --}}
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
                               placeholder="Nama lengkap orang tua/wali" required>
                    </div>

                    <div class="form-field">
                        <label>No. HP Orang Tua/Wali <span>*</span></label>
                        <input type="text" name="parent_phone" value="{{ old('parent_phone') }}"
                               placeholder="08xxxxxxxxxx" required>
                    </div>

                    <div class="form-field">
                        <label>Email Orang Tua/Wali <span>*</span></label>
                        <input type="email" name="parent_email" value="{{ old('parent_email') }}"
                               placeholder="email.ortu@aktif.com" required>
                    </div>

                </div>

            </div>

            {{-- =========================================================
                 LANGKAH 3: ASAL SEKOLAH & NILAI
                 ========================================================= --}}
            <div class="form-card">

                <div class="section-heading">
                    <div class="section-number">3</div>

                    <div>
                        <span>LANGKAH 3</span>
                        <h2>Asal Sekolah & Nilai Akademik</h2>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="form-field full">
                        <label>Nama Sekolah Asal (SMP / MTs) <span>*</span></label>
                        <input type="text" name="previous_school" value="{{ old('previous_school') }}"
                               placeholder="Nama SMP/MTs/sederajat asal" required>
                    </div>

                    <div class="form-field">
                        <label>Nilai Rata-rata Rapor (Semester 1-5) <span>*</span></label>
                        <input type="number" name="nilai_rapor" value="{{ old('nilai_rapor') }}"
                               min="0" max="100" step="0.01" placeholder="Contoh: 85.50" required>
                    </div>

                    <div class="form-field">
                        <label>Nilai Ujian / Ijazah / SKL <span class="text-muted">(Opsional / dapat disusulkan)</span></label>
                        <input type="number" name="nilai_ijazah" value="{{ old('nilai_ijazah') }}"
                               min="0" max="100" step="0.01" placeholder="Contoh: 82.00 (Kosongkan jika belum terbit)">
                    </div>

                </div>

            </div>

            {{-- =========================================================
                 LANGKAH 4: PILIHAN JURUSAN (SMA / SMK)
                 ========================================================= --}}
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
                                <option value="SMK - Desain Komunikasi Visual (DKV)" @selected(old('first_major') === 'SMK - Desain Komunikasi Visual (DKV)')>SMK - Desain Komunikasi Visual (DKV)</option>
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
                                <option value="SMK - Desain Komunikasi Visual (DKV)" @selected(old('second_major') === 'SMK - Desain Komunikasi Visual (DKV)')>SMK - Desain Komunikasi Visual (DKV)</option>
                                <option value="SMK - Teknik Kendaraan Ringan (TKR)" @selected(old('second_major') === 'SMK - Teknik Kendaraan Ringan (TKR)')>SMK - Teknik Kendaraan Ringan (TKR / Otomotif)</option>
                                <option value="SMK - Akuntansi & Keuangan Lembaga (AKL)" @selected(old('second_major') === 'SMK - Akuntansi & Keuangan Lembaga (AKL)')>SMK - Akuntansi & Keuangan Lembaga (AKL)</option>
                                <option value="SMK - Manajemen Perkantoran (MPLB / OTKP)" @selected(old('second_major') === 'SMK - Manajemen Perkantoran (MPLB / OTKP)')>SMK - Manajemen Perkantoran (MPLB / OTKP)</option>
                            </optgroup>
                        </select>
                    </div>

                </div>

            </div>

            {{-- =========================================================
                 LANGKAH 5: UNGGAH DOKUMEN BERKAS
                 ========================================================= --}}
            <div class="form-card">

                <div class="section-heading">
                    <div class="section-number">5</div>

                    <div>
                        <span>LANGKAH 5</span>
                        <h2>Unggah Dokumen Berkas</h2>
                    </div>
                </div>

                <div class="document-info">
                    <strong>Ketentuan Unggah Dokumen Berkas PPDB</strong>
                    <p>Format yang diterima: PDF, JPG, atau PNG dengan ukuran maksimal 2MB per file. Dokumen bertanda bintang (<span style="color: #dc2626; font-weight: bold;">*</span>) bersifat <strong>wajib diunggah</strong> untuk kelengkapan administrasi dan verifikasi data pendaftar.</p>
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Pas Foto Calon Siswa (3x4) <span style="color: #dc2626;">*</span></strong>
                        <span>JPG, JPEG, atau PNG — maks. 2MB (Pas foto resmi terbaru berlatar belakang merah/biru untuk verifikasi wajah saat daftar ulang offline)</span>
                    </div>
                    <input type="file" name="documents[foto]" accept=".jpg,.jpeg,.png" required>
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Kartu Keluarga (KK) <span style="color: #dc2626;">*</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB</span>
                    </div>
                    <input type="file" name="documents[kartu_keluarga]" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Akta Kelahiran <span style="color: #dc2626;">*</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB</span>
                    </div>
                    <input type="file" name="documents[akta_lahir]" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Buku Rapor Semester 1 <span style="color: #dc2626;">*</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB (Lembar nilai rapor semester 1)</span>
                    </div>
                    <input type="file" name="documents[rapor_1]" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Buku Rapor Semester 2 <span style="color: #dc2626;">*</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB (Lembar nilai rapor semester 2)</span>
                    </div>
                    <input type="file" name="documents[rapor_2]" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Buku Rapor Semester 3 <span style="color: #dc2626;">*</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB (Lembar nilai rapor semester 3)</span>
                    </div>
                    <input type="file" name="documents[rapor_3]" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Buku Rapor Semester 4 <span style="color: #dc2626;">*</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB (Lembar nilai rapor semester 4)</span>
                    </div>
                    <input type="file" name="documents[rapor_4]" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Buku Rapor Semester 5 <span style="color: #dc2626;">*</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB (Lembar nilai rapor semester 5)</span>
                    </div>
                    <input type="file" name="documents[rapor_5]" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Sertifikat / Piagam Prestasi 1 <span class="text-muted" style="color: #64748b; font-weight: normal;">(Opsional)</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB</span>
                    </div>
                    <input type="file" name="documents[sertifikat_1]" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Sertifikat / Piagam Prestasi 2 <span class="text-muted" style="color: #64748b; font-weight: normal;">(Opsional)</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB</span>
                    </div>
                    <input type="file" name="documents[sertifikat_2]" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="file-field">
                    <div class="file-info">
                        <strong>Surat Keterangan Lulus (SKL) / Ijazah Asli <span class="text-muted" style="color: #64748b; font-weight: normal;">(Opsional)</span></strong>
                        <span>PDF, JPG, atau PNG — maks. 2MB (lampirkan salah satu bila sudah terbit)</span>
                    </div>
                    <input type="file" name="documents[surat_keterangan_lulus]" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="form-submit">
                    <div class="submit-info">
                        <strong>Siap mengirim pendaftaran?</strong>
                        <span>Pastikan data diri, nomor HP aktif, dan pilihan jurusan sudah diperiksa dengan teliti.</span>
                    </div>

                    <button type="submit" class="submit-button" id="ppdbSubmitBtn">
                        <span class="btn-text">Kirim Pendaftaran</span>

                        <svg class="btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
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
                Sistem PPDB Online Terpadu
            </p>
        </div>

    </div>

    <div id="ppdbLoadingOverlay" class="ppdb-loading-overlay" aria-hidden="true">
        <div class="ppdb-loading-card">
            <div class="ppdb-spinner-wrap">
                <div class="ppdb-loading-spinner"></div>
            </div>
            <h3>Mengirim Berkas Pendaftaran...</h3>
            <p>
                Mohon tunggu beberapa detik, berkas dokumen Anda sedang diunggah dan diverifikasi secara aman ke server.
            </p>
            <div class="ppdb-loading-tip">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>Mohon jangan menyegarkan (refresh), menutup, atau menekan tombol kembali pada halaman ini sampai proses selesai.</span>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('ppdbRegistrationForm');
        const submitBtn = document.getElementById('ppdbSubmitBtn');
        const overlay = document.getElementById('ppdbLoadingOverlay');
        const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB dalam bytes
        const ALLOWED_EXTS = ['pdf', 'jpg', 'jpeg', 'png'];
        let isSubmitting = false;

        const fileInputs = form.querySelectorAll('input[type="file"][name^="documents"]');

        fileInputs.forEach(input => {
            input.addEventListener('change', function () {
                const fileField = this.closest('.file-field');
                let existingPill = fileField.querySelector('.file-status-pill');
                if (existingPill) existingPill.remove();
                fileField.classList.remove('has-error');

                const file = this.files[0];
                if (!file) return;

                const ext = file.name.split('.').pop().toLowerCase();
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);

                // Cek ekstensi
                if (!ALLOWED_EXTS.includes(ext)) {
                    this.value = '';
                    fileField.classList.add('has-error');
                    const pill = document.createElement('div');
                    pill.className = 'file-status-pill invalid';
                    pill.innerHTML = `Format .${ext} tidak didukung. Harap pilih file PDF, JPG, atau PNG.`;
                    fileField.appendChild(pill);
                    return;
                }

                // Cek ukuran maksimal 2MB
                if (file.size > MAX_FILE_SIZE) {
                    this.value = '';
                    fileField.classList.add('has-error');
                    const pill = document.createElement('div');
                    pill.className = 'file-status-pill invalid';
                    pill.innerHTML = `Ukuran file (${fileSizeMB} MB) melebihi batas 2MB. Silakan kompres atau pilih file lain.`;
                    fileField.appendChild(pill);
                    return;
                }

                // File valid
                const pill = document.createElement('div');
                pill.className = 'file-status-pill valid';
                pill.innerHTML = `&#10003; ${file.name} (${fileSizeMB} MB)`;
                fileField.appendChild(pill);
            });
        });

        form.addEventListener('submit', function (e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Cek ulang apakah ada file yang melebihi ukuran
            let hasOversized = false;
            fileInputs.forEach(input => {
                if (input.files && input.files[0] && input.files[0].size > MAX_FILE_SIZE) {
                    hasOversized = true;
                    input.closest('.file-field').classList.add('has-error');
                }
            });

            if (hasOversized) {
                e.preventDefault();
                alert('Terdapat file yang melebihi ukuran maksimal 2MB. Harap periksa kembali berkas Anda.');
                return;
            }

            // Kunci tombol & tampilkan layar proses
            isSubmitting = true;
            submitBtn.disabled = true;

            const btnText = submitBtn.querySelector('.btn-text');
            if (btnText) {
                btnText.textContent = 'Sedang Mengirim...';
            }

            if (overlay) {
                overlay.classList.add('is-active');
                overlay.setAttribute('aria-hidden', 'false');
            }
        });
    });
    </script>

</body>

</html>
