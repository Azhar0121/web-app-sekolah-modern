# Web Sekolah Modern

Aplikasi Sistem Informasi Manajemen Sekolah dan Portal Akademik Terpadu berbasis web yang dibangun menggunakan framework **Laravel 13**. Aplikasi ini menyediakan website publik sekolah, portal pendaftaran siswa baru (PPDB Online), serta 6 portal khusus berbasis peran (*role-based access control*) untuk mendukung kegiatan akademik, keuangan, persuratan, dan komunikasi sekolah secara digital.

---

## Teknologi Utama

- **Framework**: Laravel 13.x (PHP 8.4+)
- **Template Engine**: Laravel Blade & Bootstrap 5.3
- **Asset Bundler**: Vite 6 & Sass
- **Database**: MySQL / MariaDB (support Eloquent ORM)
- **Visualisasi Data**: Chart.js (Grafik Performa Nilai)
- **Ikonografi**: Bootstrap Icons v1.11
- **Testing**: PHPUnit 12

---

## Fitur Yang Sudah Tersedia (Implemented Features)

Semua fitur yang tercantum di bawah ini telah selesai diimplementasikan secara penuh pada codebase:

### 1. Website Publik & PPDB Online (`/` & `/ppdb`)
- **Beranda Sekolah**: Tampilan publik profil sekolah dan pengumuman resmi.
- **Pendaftaran Siswa Baru (PPDB)**: Formulir pendaftaran calon siswa online lengkap (data diri, data orang tua, nilai rapor, sekolah asal, dan pilihan jurusan).
- **Pengunggahan Berkas Digital**: Unggah berkas persyaratan (Kartu Keluarga, Akta Kelahiran, Rapor, Ijazah, dan Sertifikat Prestasi).
- **Cek Status & Lupa Nomor Pendaftaran**: Pelacakan status seleksi dan fitur pemulihan nomor pendaftaran berdasarkan NIK/nama.
- **Cetak Bukti Pendaftaran**: Cetak kartu bukti pendaftaran online siap simpan/print.
- **Panel Pengelolaan PPDB (Admin/TU)**: Verifikasi dokumen pendaftar, pembaruan status (Diterima / Ditolak / Menunggu), konfirmasi daftar ulang, dan pembuatan akun siswa otomatis.

### 2. Autentikasi & Otorisasi Hak Akses
- **Multi-Role Authentication**: Mendukung 6 peran pengguna (`super-admin`, `guru`, `siswa`, `ortu`, `tu`, dan `kepsek`).
- **Middleware Proteksi Route**: Pengamanan route menggunakan `auth`, `role:*`, dan `permission:*`.
- **Manajemen User, Role & Permission**: Pengelolaan pengguna, penetapan peran, dan konfigurasi izin akses modul (`/admin/users`, `/admin/roles`).

### 3. Portal Guru (`/guru`)
- **Dashboard Guru**: Ringkasan penugasan mengajar aktif dan pengumuman sekolah.
- **Manajemen Jadwal Mengajar**: Jadwal mengajar pribadi per hari dan jam pelajaran.
- **Presensi Kelas & Pemindaian QR Code**: Pembuatan sesi presensi, pemindaian QR Code Kartu Pelajar siswa secara *real-time*, penyesuaian manual status (Hadir, Sakit, Izin, Alpha), serta buka/tutup sesi.
- **Input Nilai Akademik Batch**: Panel input nilai massal (Tugas, UH, UTS, UAS), pengaturan bobot persentase penilaian, dan kalkulasi nilai akhir dinamis via `GradeCalculator`.
- **Upload & Manajemen Materi**: Unggah dokumen pembelajaran (PDF, Word, PPT, ZIP) dan tautan video pembelajaran.
- **Modul Tugas & Koreksi Digital**: Pembuatan tugas digital, pengunduhan berkas jawaban siswa, serta pemberian nilai dan catatan umpan balik (*feedback*).
- **Verifikasi & Persetujuan Izin Siswa**: Peninjauan dan pemrosesan pengajuan izin/sakit siswa perwalian dari orang tua (disertai lampiran surat dokter).
- **Ruang Komunikasi Terarah Ortu**: Modul percakapan terurai (*threaded chat*) antara guru dengan orang tua siswa (kategori akademik, kedisiplinan, kehadiran), pengiriman lampiran, dan status penyelesaian.

### 4. Portal Siswa (`/siswa`)
- **Dashboard Personal Siswa**: Ringkasan jadwal hari ini, tugas aktif, dan kartu QR pelajar.
- **Kelola Biodata Diri & Keamanan Akun**: Pembaruan kontak/foto profil dan fitur ganti password mandiri (dengan verifikasi password lama & indikator kekuatan password).
- **Jadwal Pelajaran Harian**: Tampilan jadwal pelajaran terurut per hari.
- **Kartu Pelajar Digital & Dynamic TOTP QR Code**: Kartu pelajar digital dengan kode QR dinamis berbasis TOTP (TTL 25 detik + auto-refresh AJAX) untuk mencegah pemalsuan presensi.
- **Akses & Unduh Materi Belajar**: Pengunduhan dokumen materi pembelajaran dari guru.
- **Pengumpulan Tugas Digital**: Pengunggahan berkas jawaban tugas, pengunduhan lampiran soal, serta pemantauan nilai dan feedback guru.
- **Rekapitulasi Nilai & Grafik Akademik**: Rekapitulasi nilai rapor per semester dan visualisasi grafik batang interaktif (Chart.js).
- **Riwayat Presensi Harian**: Pemantauan statistik dan log kehadiran harian per mata pelajaran.

### 5. Portal Orang Tua / Wali (`/ortu`)
- **Dashboard Hub Orang Tua**: Dasbor berbasis kartu aksi cepat untuk setiap anak yang tertaut dengan akun orang tua.
- **Pemantauan Grafik Nilai Akademik Anak**: Visualisasi grafik performa nilai per mata pelajaran dan tabel rekapitulasi nilai komponen.
- **Pelacakan Riwayat Kehadiran Anak**: Indikator total Hadir, Sakit, Izin, Alpha dan log riwayat presensi harian anak.
- **Jadwal Pelajaran Anak**: Tampilan jadwal pelajaran kelas anak terkelompok per hari.
- **Status Tagihan Biaya Sekolah**: Pemantauan tagihan administrasi sekolah (Lunas / Tertunggak / Rincian nominal).
- **Pengajuan Surat Izin Anak**: Formulir pengajuan izin ketidakhadiran (Sakit/Izin) beserta pengunggahan berkas surat keterangan dokter.
- **Unduh Salinan Rapor Digital**: Pengunduhan berkas PDF rapor digital anak per semester.
- **Ruang Konsultasi Terarah dengan Guru**: Diskusi interaktif dengan Wali Kelas atau Guru Mapel perihal perkembangan anak.

### 6. Portal Tata Usaha / TU (`/tu` & `/admin`)
- **Modul Persuratan Digital**: Pengelolaan Surat Masuk dan Surat Keluar, penomoran otomatis berformat dinamis (`SM-` / kode sekolah), pengunggahan berkas scan surat, dan lembar disposisi.
- **Kelola Biodata Siswa**: Pengelolaan profil siswa terintegrasi dengan data hasil PPDB (`/admin/siswa-profiles`).
- **Pencatatan & Manajemen Tagihan Siswa**: Penambahan tagihan per siswa, konfirmasi pembayaran (manual/bukti bayar), dan pengunggahan bukti bayar (`/admin/tagihan`).
- **Upload Salinan Rapor Digital**: Pengunggahan berkas PDF rapor siswa per semester (`/admin/siswa-profiles/{id}/rapor`).

### 7. Modul Pengumuman Internal (`/admin/pengumuman`)
- Pembuatan pengumuman resmi sekolah oleh Super Admin, TU, atau Kepala Sekolah.
- Penargetan granular per peran (Semua / Siswa / Guru / Ortu / TU / Kepsek).
- Pengaturan prioritas (Normal, Penting, Urgent/Mendesak).
- Banner pengumuman otomatis pada bagian atas dashboard pengguna.

### 8. Master Data Akademik (`/admin`)
- Pengelolaan Tahun Ajaran & Semester aktif.
- Pengelolaan Mata Pelajaran & Kurikulum.
- Pengelolaan Kelas & Penetapan Wali Kelas.
- Penugasan Mengajar Guru ke Kelas & Mapel.
- Penempatan / Plotting Siswa ke Kelas.
- Pengaturan Jadwal Pelajaran Sekolah.

---

## 👥 Peran Pengguna (User Roles)

Aplikasi memiliki 6 peran pengguna yang sudah terkonfigurasi pada `RoleSeeder`:

| Role | Slug | Deskripsi Akses |
| --- | --- | --- |
| **Super Admin** | `super-admin` | Akses penuh ke seluruh modul sistem, kelola user, role, permission, dan master data. |
| **Guru / Wali Kelas** | `guru` | Akses modul jadwal, presensi QR, nilai, materi, tugas, persetujuan izin, dan komunikasi ortu. |
| **Siswa** | `siswa` | Akses portal personal: jadwal, presensi QR, materi, tugas digital, nilai & grafik, dan profil. |
| **Orang Tua / Wali** | `ortu` | Akses pemantauan anak: nilai & grafik, presensi, jadwal, tagihan, pengajuan izin, rapor, dan konsultasi guru. |
| **Tata Usaha (TU)** | `tu` | Akses modul persuratan digital, tagihan keuangan, upload rapor PDF, biodata siswa, dan PPDB. |
| **Kepala Sekolah** | `kepsek` | Akses pengawasan pengumuman sekolah dan pemantauan sistem. |

---

## Kredensial Demo (Seeder)

`UserSeeder` menyediakan akun demo berikut untuk pengujian lokal. Seluruh akun menggunakan kata sandi: `password123`.

| Peran Pengguna | Email Login |
| --- | --- |
| Super Admin | `admin@sekolah.test` |
| Guru / Wali Kelas | `guru@sekolah.test` |
| Siswa | `siswa@sekolah.test` |
| Orang Tua / Wali | `ortu@sekolah.test` |
| Tata Usaha (TU) | `tu@sekolah.test` |
| Kepala Sekolah | `kepsek@sekolah.test` |

---

## Panduan Instalasi & Jalankan Aplikasi

### 1. Prasyarat Sistem
- PHP >= 8.4
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL / MariaDB

### 2. Langkah Instalasi
Kloning repositori dan masuk ke direktori proyek:
```bash
cd web-sekolah-modern
```

Install dependensi PHP & Node.js:
```bash
composer install
npm install
```

Salin berkas lingkungan `.env` dan jalankan key generator:
```bash
cp .env.example .env
php artisan key:generate
```

Konfigurasikan koneksi database pada berkas `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), lalu jalankan migrasi dan seeder:
```bash
php artisan migrate --seed
```

Buat symbolic link untuk penyimpanan berkas publik (upload lampiran, foto, & dokumen):
```bash
php artisan storage:link
```

### 3. Menjalankan Server Pengembangan
Jalankan server aplikasi Laravel:
```bash
php artisan serve
```

Pada terminal terpisah, jalankan Vite bundler:
```bash
npm run dev
```

Buka peramban dan akses alamat: `http://127.0.0.1:8000`.

---

## Struktur Direktori Proyek

```text
web-sekolah-modern/
├── app/
│   ├── Http/
│   │   ├── Controllers/   # Controller untuk Auth, Admin, Guru, Siswa, Ortu, PPDB
│   │   └── Middleware/    # Middleware autentikasi, role, dan permission
│   ├── Models/            # Model Eloquent domain sekolah & relasi
│   └── Services/          # Service layer (GradeCalculator, dll)
├── database/
│   ├── migrations/        # Struktur tabel database
│   └── seeders/           # Seeder role, permission, user demo, & data awal
├── public/
│   └── css/               # Berkas CSS styling halaman & komponen
├── resources/
│   ├── views/             # Tampilan Blade (Admin, Guru, Siswa, Ortu, PPDB, Layouts)
│   └── sass/              # Berkas SCSS Bootstrap 5
├── routes/
│   └── web.php            # Seluruh pendaftaran route web aplikasi
└── design.md              # Panduan Sistem Desain & Antarmuka Aplikasi
```

---

## Lisensi
Proyek ini dikembangkan di atas framework [Laravel](https://laravel.com) yang dirilis di bawah lisensi [MIT License](https://opensource.org/licenses/MIT).
