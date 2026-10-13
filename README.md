# Web Sekolah Modern

Aplikasi Sistem Informasi Manajemen Sekolah, Portal Akademik Terpadu, dan Administrasi Digital berbasis web modern yang dibangun menggunakan framework **Laravel 11 / 12** (PHP 8.2+). Aplikasi ini menyediakan website publik sekolah yang informatif, portal pendaftaran siswa baru (PPDB Online), serta 6 portal khusus berbasis peran (*Role-Based Access Control*) untuk mendukung operasional akademik, kesiswaan, keuangan, persuratan, dan komunikasi sekolah secara digital.

---

## Nilai Utama & Keunggulan Sistem

1. **Website Publik Elegan & Informatif**: Beranda dinamis (*hero slider*, visi-misi, fasilitas, ekstrakurikuler, OSIS, prestasi), profil sekolah, daftar tenaga pendidik & staf, kurikulum akademik per jurusan, kesiswaan, dan formulir layanan pesan kontak publik.
2. **PPDB Online Terintegrasi**: Pendaftaran mandiri, upload berkas digital berjenjang, pelacakan status seleksi real-time, cetak kartu bukti pendaftaran, hingga konfirmasi daftar ulang dan pembuatan akun siswa otomatis.
3. **Presensi Digital Pintar Berbasis QR Code**: Presensi kelas real-time dengan kode QR dinamis berbasis TOTP (Time-Based One-Time Password dengan masa kedaluwarsa 25 detik dan auto-refresh AJAX) untuk mencegah pemalsuan presensi antar siswa.
4. **Portal Orang Tua Tanpa Redundansi (Multi-Child Ready)**: Akses terpisah antara layanan mandiri wali murid (pengajuan izin sakit/keperluan keluarga, konsultasi guru) dengan kartu portofolio perkembangan masing-masing anak (jadwal, presensi, grafik nilai, tagihan SPP, rapor digital).
5. **Manajemen Operasional & Persuratan Lengkap**: Penomoran surat otomatis, registrasi surat masuk/keluar, disposisi digital, penagihan SPP siswa dengan konfirmasi bukti bayar, dan kotak masuk pesan publik dengan notifikasi badge serta integrasi balas pesan via WhatsApp resmi.
6. **Keamanan & Standar Enterprise**: Proteksi CSRF, sanitasi XSS otomatis, pencegahan SQL Injection via Eloquent ORM, otorisasi RBAC granular, Audit Log forensik digital, serta modul Backup & Pemulihan database.

---

## Tumpukan Teknologi (Tech Stack)

- **Framework**: [Laravel 11 / 12](https://laravel.com/) (PHP 8.2+)
- **Template Engine**: Laravel Blade & Bootstrap 5.3
- **Asset Bundler**: Vite 6 & Sass / CSS3 Murni
- **Database**: MySQL / MariaDB (dukungan penuh Eloquent ORM & 61 Database Migrations)
- **Visualisasi Data**: Chart.js (Grafik Performa Nilai Siswa)
- **Ikonografi**: Bootstrap Icons v1.11 & SVG Icons
- **Pengujian (Testing)**: PHPUnit (100% Automated Test Suite Passing)
- **Penyimpanan Berkas**: Laravel Storage Disk (dengan Symbolic Link `public/storage`)

---

## Fitur yang Sudah Diimplementasikan

Semua fitur di bawah ini telah terpasang, teruji, dan aktif di dalam sistem:

### 1. Website Publik Sekolah (`/`)
- **Beranda Sekolah**: Hero slider beranda, sambutan kepala sekolah, statistik sekolah, fasilitas ruangan, ekstrakurikuler unggulan, prestasi siswa, dan galeri kegiatan OSIS.
- **Profil Sekolah (`/profil`)**: Sejarah, visi, misi, nilai budaya, struktur organisasi, dan fasilitas sarana prasarana.
- **Guru & Tenaga Kependidikan (`/pengajar`)**: Direktori profil guru, staf tata usaha, mata pelajaran yang diampu, dan riwayat pendidikan.
- **Kurikulum & Akademik (`/akademik`)**: Daftar kompetensi keahlian/jurusan, kalender akademik, dan daftar mata pelajaran yang tersusun rapi berdasarkan jurusan untuk kenyamanan pembaca.
- **Kesiswaan (`/kesiswaan`)**: Dokumentasi program OSIS, agenda kegiatan kesiswaan, dan portofolio ekstrakurikuler.
- **Layanan Kontak (`/kontak`)**: Informasi alamat, jam operasional, peta lokasi, serta formulir pesan masuk publik yang langsung terhubung ke panel administrasi sekolah.

### 2. PPDB Online Mandiri (`/ppdb`)
- Formulir pendaftaran siswa baru secara daring (Biodata siswa, data orang tua/wali, sekolah asal, nilai rapor semester 1–5, dan pilihan kompetensi keahlian).
- Pengunggahan berkas digital (Kartu Keluarga, Akta Kelahiran, Rapor, Ijazah, dan Sertifikat Prestasi).
- Pelacakan status seleksi calon siswa (`/ppdb/cek-status`) dan fitur lupa nomor pendaftaran (`/ppdb/lupa-nomor`).
- Cetak kartu tanda bukti pendaftaran online siap simpan PDF / print (`/ppdb/cetak/{nomor}`).
- Panel verifikasi berkas PPDB oleh Admin/TU, pengumuman status kelulusan seleksi, konfirmasi daftar ulang, hingga penempatan kelas otomatis.

### 3. Autentikasi, Keamanan & Halaman Login (`/login`)
- **Desain Login Modern & Responsif**: Terpusat sempurna (*vertically centered*) di seluruh tingkat zoom browser (100%, 90%, dan resolusi lainnya).
- **Fitur Lihat/Sembunyikan Password**: Tombol interaktif toggle mata (SVG) untuk memudahkan pengguna memverifikasi ketikan kata sandi.
- **Navigasi Cepat**: Tombol kembali ke Beranda Utama langsung dari form login.
- **Proteksi Akun**: Pemeriksaan status aktif akun (`is_active`), regenerasi sesi login, dan pencatatan riwayat login ke modul Audit Log.

### 4. Portal Guru & Wali Kelas (`/guru`)
- **Dashboard Guru**: Ringkasan jadwal mengajar aktif hari ini, tugas yang perlu dinilai, dan banner pengumuman internal sekolah.
- **Jadwal Mengajar Pribadi**: Pengorganisasian jadwal mengajar per hari, kelas, dan jam pelajaran.
- **Presensi Kelas & Pemindaian QR Code**: Pembuatan sesi absensi per pertemuan kelas, pemindaian QR code dinamis siswa, dan penyesuaian manual status kehadiran (Hadir, Sakit, Izin, Alpha).
- **Input Nilai Akademik Batch**: Pengisian nilai massal untuk Tugas, Ulangan Harian, PTS, dan PAS, disertai pengaturan bobot persentase penilaian dan kalkulasi otomatis.
- **Manajemen Materi & Tugas Digital**: Unggah materi belajar (PDF, Word, PPT, link video) dan pemberian tugas digital dengan tenggat waktu pengumpulan.
- **Koreksi Tugas Siswa**: Pemeriksaan berkas tugas siswa, pemberian nilai langsung, dan catatan feedback evaluasi guru.
- **Verifikasi Izin Siswa**: Pemrosesan persetujuan atau penolakan surat izin sakit/keperluan keluarga yang diajukan oleh orang tua siswa.
- **Komunikasi Wali Murid**: Ruang konsultasi terarah (*threaded discussion*) dengan orang tua perihal akademik maupun kedisiplinan siswa.

### 5. Portal Siswa (`/siswa`)
- **Dashboard Siswa**: Kartu pelajar digital, jadwal pelajaran hari ini, dan notifikasi tugas aktif.
- **Kartu Pelajar Digital & Dynamic QR Code**: Kode QR presensi berbasis TOTP yang selalu berganti secara dinamis tiap 25 detik untuk menjamin kejujuran absensi.
- **Jadwal Pelajaran & Riwayat Kehadiran**: Pemantauan jadwal mingguan dan statistik persentase kehadiran per mata pelajaran.
- **Materi & Tugas Digital**: Akses materi yang diunggah guru dan modul pengumpulan tugas dengan upload dokumen digital.
- **Rekap Nilai & Grafik Akademik**: Tampilan nilai tugas, ujian, dan visualisasi grafik performa capaian kompetensi per mata pelajaran via Chart.js.
- **Biodata Diri & Keamanan**: Pembaruan kontak pribadi dan penggantian password mandiri dengan verifikasi password lama.

### 6. Portal Orang Tua / Wali Murid (`/ortu`)
- **Dashboard Bebas Redundansi**: Tata letak yang membedakan secara tegas antara layanan orang tua dan pemantauan siswa.
- **Layanan Mandiri Wali Murid**: Akses cepat pengajuan surat izin sakit/izin, riwayat status verifikasi izin oleh guru, dan konsultasi dua arah dengan wali kelas.
- **Kartu Portofolio Mandiri per Anak**: Mendukung orang tua dengan 1, 2, atau banyak anak sekaligus. Setiap anak memiliki panel mandiri untuk jadwal pelajaran, absensi, nilai akademik, status pelunasan SPP/tagihan, dan unduh rapor digital.

### 7. Portal Tata Usaha / TU (`/tu` & `/admin`)
- **Persuratan Digital**: Manajemen surat masuk dan surat keluar, penomoran otomatis dengan kode dinamis (`SM-` / format instansi), unggah scan berkas, dan lembar disposisi.
- **Kotak Masuk Pesan Publik**: Manajemen pesan dari halaman kontak publik, indikator belum dibaca (*unread badge*) di sidebar, serta tombol balas langsung via WhatsApp dengan format pesan otomatis.
- **Pencatatan & Verifikasi Tagihan (SPP)**: Pembuatan tagihan per siswa atau massal, konfirmasi pembayaran, dan pengunggahan berkas bukti transfer.
- **Pengunggahan Rapor Digital**: Distribusi salinan berkas PDF rapor resmi siswa per semester.
- **Manajemen Biodata Siswa**: Sinkronisasi data siswa dari PPDB ke buku induk profil siswa.

### 8. Portal Kepala Sekolah (`/kepsek`)
- **Dashboard Eksekutif**: Pemantauan indikator kinerja sekolah, jumlah siswa aktif, grafik kehadiran sekolah, dan pengumuman kedinasan.
- **Penerbitan Pengumuman Resmi**: Pembuatan pengumuman internal sekolah dengan penargetan peran dan prioritas mendesak (*urgent*).

### 9. Panel Super Admin & Sistem Inti (`/admin`)
- **Manajemen User, Role & Permission**: Kontrol penuh atas seluruh pengguna sistem, penetapan peran, dan hak akses izin per modul.
- **Master Data Akademik**: Pengelolaan Tahun Ajaran, Semester, Jurusan/Kompetensi Keahlian, Ruangan/Gedung, Mata Pelajaran, Kelas, Penugasan Guru, Penempatan Siswa, dan Jadwal Pelajaran.
- **Pengaturan Global & SEO**: Konfigurasi identitas sekolah, favicon, meta description, robots.txt, dan generator sitemap.xml otomatis.
- **Audit Log & Keamanan**: Pencatatan riwayat setiap aksi penting di sistem (login, logout, modifikasi data) beserta alamat IP dan user agent.
- **Backup & Pemulihan**: Cadangan data otomatis dan manual untuk keandalan arsip sistem.

---

## Matriks Hak Akses Peran Pengguna

| Peran (Role) | Slug | Tanggung Jawab Utama |
| :--- | :--- | :--- |
| **Super Admin** | `super-admin` | Kontrol penuh sistem, master data, user & role, izin, konfigurasi SEO, audit log, dan backup. |
| **Guru / Wali Kelas** | `guru` | Mengajar, presensi QR, input nilai, materi & tugas, verifikasi izin, dan komunikasi ortu. |
| **Siswa** | `siswa` | Pembelajaran daring, kumpul tugas, pantau nilai, cek jadwal, dan presensi via QR kartu pelajar. |
| **Orang Tua / Wali** | `ortu` | Pengajuan izin anak, komunikasi guru, pantau kehadiran, nilai, status SPP, dan unduh rapor. |
| **Tata Usaha (TU)** | `tu` | Administrasi persuratan digital, tagihan keuangan siswa, upload rapor, PPDB, dan kontak masuk. |
| **Kepala Sekolah** | `kepsek` | Pengawasan dashboard eksekutif, rekapitulasi sekolah, dan pengumuman penting. |

---

## Panduan Instalasi Lokal (Development Setup)

### Prasyarat Sistem
- PHP >= 8.2 (ekstensi: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo, GD)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL / MariaDB Server

### Langkah-Langkah Instalasi:
1. **Pasang dependensi PHP & JavaScript**:
   ```bash
   composer install
   npm install
   ```

2. **Salin konfigurasi environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Konfigurasi Database di `.env`**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=web_sekolah_modern
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Jalankan Migrasi Database & Seeder**:
   ```bash
   php artisan migrate --seed
   ```

5. **Hubungkan Symlink Penyimpanan Berkas**:
   ```bash
   php artisan storage:link
   ```

6. **Jalankan Aplikasi**:
   ```bash
   # Terminal 1 - Asset Watcher
   npm run dev

   # Terminal 2 - Local Web Server
   php artisan serve
   ```
   Aplikasi siap diakses melalui browser di `http://127.0.0.1:8000`.

---

## Panduan Publikasi ke Server Produksi (Production Deployment)

Sebelum merilis aplikasi ke server hosting/VPS publik:

1. **Konfigurasi `.env` Produksi**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://sekolahanda.sch.id
   ```
2. **Kompilasi Aset Frontend**:
   ```bash
   npm run build
   ```
3. **Optimasi Cache Laravel**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
4. **Pastikan Symlink Aktif**:
   ```bash
   php artisan storage:link
   ```
5. **Gunakan SSL / HTTPS**: Pastikan sertifikat SSL (Let's Encrypt / Comodo) aktif agar transmisi kata sandi dan token QR terlindungi secara aman.

---

## Pengujian Otomatis (Automated Testing)

Untuk memverifikasi integritas rute dan logika sistem:
```bash
php artisan test
```

---

## Lisensi
Sistem Informasi Web Sekolah Modern ini dilisensikan di bawah [MIT License](LICENSE).
