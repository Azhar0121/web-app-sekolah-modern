# Web Sekolah Modern

Aplikasi manajemen sekolah berbasis web yang sedang dikembangkan dengan Laravel. Project ini menyediakan website publik sekolah, PPDB online, serta portal berdasarkan role pengguna untuk mendukung kegiatan akademik dan administrasi sekolah.

> **Status project:** dalam pengembangan. Fitur inti untuk PPDB, master data akademik, guru, dan siswa sudah tersedia, tetapi belum seluruh fitur untuk semua role selesai. README ini mencatat kondisi implementasi saat ini agar pengembangan berikutnya tetap terarah.

## Teknologi

- PHP 8.4 atau lebih baru
- Laravel 13
- Laravel Blade
- Vite 6
- Bootstrap 5.3
- Sass
- MySQL/MariaDB atau SQLite
- PHPUnit 12

## Fitur Yang Sudah Tersedia

### Website publik dan PPDB

- Halaman beranda sekolah.
- Melihat periode PPDB yang sedang aktif.
- Pendaftaran calon siswa secara online.
- Upload dokumen pendaftaran.
- Halaman sukses dan cetak bukti pendaftaran.
- Cek status pendaftaran.
- Pemulihan nomor pendaftaran berdasarkan data pendaftar.
- Pengelolaan status pendaftar dan konfirmasi daftar ulang dari panel internal.
- Pembuatan akun siswa setelah proses PPDB sesuai alur aplikasi.

### Autentikasi dan otorisasi

- Login dan logout.
- Proteksi route berdasarkan role.
- Proteksi modul tertentu berdasarkan permission.
- Relasi user, role, dan permission menggunakan tabel database.

### Portal Super Admin (`/admin`)

- Dashboard admin.
- Kelola user dan role user.
- Kelola permission pada role.
- Kelola tahun ajaran dan semester.
- Kelola mata pelajaran.
- Kelola kelas.
- Kelola penugasan mengajar guru, kelas, dan mata pelajaran.
- Kelola penempatan siswa ke kelas.
- Kelola jadwal pelajaran.
- Akses pengelolaan PPDB melalui permission `ppdb.manage`.

### Portal Guru (`/guru`)

- Dashboard dan daftar penugasan mengajar.
- Kelola materi pembelajaran.
- Buat dan kelola tugas.
- Lihat pengumpulan tugas siswa dan memberi nilai/feedback.
- Input nilai secara batch.
- Kelola bobot penilaian.
- Melihat jadwal mengajar.
- Membuka dan menutup sesi presensi.
- Scan QR presensi serta memperbarui status kehadiran siswa.

### Portal Siswa (`/siswa`)

- Dashboard siswa.
- Melihat jadwal pelajaran.
- Melihat nilai dan rekap nilai.
- Melihat kartu pelajar dan memperbarui QR token.
- Melihat riwayat presensi.
- Mengakses dan mengunduh materi.
- Melihat tugas.
- Mengumpulkan tugas serta mengunduh lampiran dan hasil pengumpulan.

## Role Yang Tersedia

Role berikut dibuat oleh `RoleSeeder` dan dapat digunakan dalam aplikasi:

| Role | Slug | Kondisi saat ini |
| --- | --- | --- |
| Super Admin | `super-admin` | Modul administrasi utama tersedia. |
| Guru / Wali Kelas | `guru` | Materi, tugas, nilai, jadwal, dan presensi tersedia. |
| Siswa | `siswa` | Jadwal, nilai, materi, tugas, kartu pelajar, dan presensi tersedia. |
| Orang Tua / Wali | `ortu` | Route dan dashboard tersedia, isi dashboard masih placeholder. |
| Tata Usaha | `tu` | Dashboard dan ringkasan PPDB tersedia; modul administrasi lain masih dikembangkan. |
| Kepala Sekolah | `kepsek` | Route dan dashboard tersedia, laporan serta approval masih placeholder. |

## Permission Yang Terdaftar

Permission berikut sudah dibuat oleh `PermissionSeeder`. Sebagian masih menjadi fondasi untuk modul berikutnya dan belum semuanya memiliki halaman atau route khusus:

- `cms.manage` - kelola konten CMS.
- `ppdb.manage` dan `ppdb.view` - kelola dan lihat PPDB.
- `akademik.manage` - kelola master data akademik.
- `nilai.input`, `nilai.approve`, dan `nilai.view` - alur nilai.
- `presensi.manage` dan `presensi.view` - alur presensi.
- `persuratan.manage` - kelola persuratan.
- `inventaris.manage` - kelola inventaris.
- `user.manage` - kelola user dan permission.
- `audit.view` - lihat audit log.
- `settings.manage` - kelola pengaturan global.
- `dashboard.report.view` - lihat dashboard laporan.

## Fitur Yang Masih Dalam Pengembangan

Prioritas pengembangan berikut disusun berdasarkan route, view, permission, dan role yang sudah ada di source code:

1. Menyelesaikan dashboard Orang Tua/Wali untuk memantau nilai, presensi, jadwal, dan tugas anak.
2. Menyelesaikan dashboard Kepala Sekolah beserta laporan dan proses approval nilai.
3. Menambahkan modul Tata Usaha untuk persuratan dan inventaris.
4. Menambahkan CMS untuk konten website publik.
5. Menambahkan audit log dan pengaturan global.
6. Menyempurnakan pengelolaan permission agar seluruh permission benar-benar terhubung dengan modulnya.
7. Menambah validasi, notifikasi, laporan, dan pengujian untuk alur utama setiap role.
8. Meninjau kembali akses keamanan, tampilan, dan alur kerja sebelum digunakan di lingkungan produksi.

## Struktur Direktori Penting

```text
app/
	Http/Controllers/       Controller autentikasi dan setiap role
	Http/Middleware/        Middleware role dan permission
	Models/                 Model domain sekolah
	Services/               Logika layanan seperti kalkulasi nilai
database/
	migrations/             Struktur tabel database
	seeders/                Role, permission, user dummy, dan data contoh
resources/views/          Tampilan Blade publik dan portal pengguna
routes/web.php            Seluruh route web aplikasi
public/                   Entry point dan asset hasil build
```

## Persiapan Lokal

Pastikan PHP, Composer, Node.js, dan database sudah tersedia. Untuk penggunaan dengan XAMPP, aktifkan Apache dan MySQL, lalu jalankan perintah dari folder project:

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Buat tabel dan data contoh:

```bash
php artisan migrate --seed
```

Install dependency frontend dan buat asset production:

```bash
npm install
npm run build
```

## Menjalankan Aplikasi Saat Development

Jalankan server Laravel:

```bash
php artisan serve
```

Pada terminal lain, jalankan Vite agar perubahan frontend dimuat otomatis:

```bash
npm run dev
```

Alternatifnya, script Composer berikut menjalankan alur development yang sudah dikonfigurasi project:

```bash
composer run dev
```

## Akun Demo Seeder

`UserSeeder` membuat akun berikut. Semua akun demo menggunakan password `password123`.

| Role | Email |
| --- | --- |
| Super Admin | `admin@sekolah.test` |
| Guru | `guru@sekolah.test` |
| Siswa | `siswa@sekolah.test` |
| Orang Tua / Wali | `ortu@sekolah.test` |
| Tata Usaha | `tu@sekolah.test` |
| Kepala Sekolah | `kepsek@sekolah.test` |

> Akun tersebut hanya untuk development dan pengujian. Ganti password serta jangan gunakan kredensial demo di production.

## Pengujian

Jalankan pengujian PHPUnit dengan:

```bash
php artisan test
```

Saat ini folder `tests` masih berisi pengujian contoh Laravel. Pengujian feature untuk login, pembatasan role, PPDB, akademik, presensi, nilai, materi, dan tugas perlu ditambahkan secara bertahap seiring penyelesaian setiap role.

## Catatan Pengembangan

- Gunakan migration untuk setiap perubahan struktur database.
- Gunakan seeder untuk data role, permission, dan data demo yang dapat diulang.
- Pertahankan pembatasan akses melalui middleware `role` dan `permission`.
- Pastikan setiap fitur baru memiliki route, controller, view, validasi, dan pengujian yang sesuai.
- Jangan menganggap dashboard yang hanya menampilkan placeholder sebagai fitur yang sudah selesai.
- Sebelum deployment, gunakan konfigurasi production, password aman, dan lakukan pemeriksaan keamanan serta backup database.

## Lisensi

Project ini menggunakan Laravel yang dirilis dengan lisensi MIT. Lisensi dan status project aplikasi sekolah dapat disesuaikan dengan kebutuhan pemilik project.
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
