# Design System & UI/UX Architecture — Web Sekolah Modern

## 1. Ikhtisar Filosofi Desain (Design Philosophy)

**Web Sekolah Modern** dibangun dengan paradigma estetika **Modern Academic Navy & Clean Slate UI**. Konsep ini menggabungkan kewibawaan dan profesionalisme institusi pendidikan melalui spektrum warna Deep Navy (`#071b35`) yang kokoh, dipadukan dengan atmosfer digital kontemporer melalui latar kanvas sejuk (`#f5f8fc`), kartu melengkung halus (*soft rounded elevation*), tipografi sans-serif modern, serta hierarki informasi yang sangat efisien dan bebas redundansi.

### Prinsip Desain Inti:
1. **Purpose-Driven Layout (Berbasis Tujuan & Bebas Duplikasi)**: Setiap komponen visual memiliki fungsi yang spesifik. Tidak ada elemen atau tombol navigasi yang diulang secara tidak perlu (sebagaimana diterapkan pada pemisahan layanan orang tua vs kartu portofolio mandiri anak).
2. **Elevated Visual Hierarchy**: Informasi penting (seperti status tagihan, absensi, jadwal aktif, notifikasi pesan) dipertegas menggunakan lencana status berkontras lembut (*subtle pill badges*) dan aksen warna semantik.
3. **Responsive by Default**: Seluruh antarmuka—dari portal publik, halaman login, dashboard siswa/ortu, hingga panel administrasi—beradaptasi secara mulus di berbagai resolusi layar (desktop, tablet, dan smartphone).
4. **Accessible & Intuitive**: Jarak sentuh (*touch target*) minimal 42px untuk tombol seluler, kontras warna yang memenuhi standar WCAG 2.1 AA, serta dukungan fitur bantuan input seperti toggle lihat/sembunyikan kata sandi.

---

## 2. Palet Warna (Color Palette)

### A. Warna Identitas Utama (Brand Colors)
- **Primary Navy** (`--navy`: `#071b35`): Warna identitas utama sekolah. Digunakan pada Sidebar admin, navbar brand, hero badge, dan judul utama.
- **Secondary Navy** (`--navy-2`: `#0b294d`): Variasi navy untuk efek hover navigasi aktif, gradien latar belakang, dan tombol primer.
- **Academic Royal Blue** (`--blue`: `#1769d5` / `#2563eb`): Warna interaktif utama untuk tautan, status aktif, fokus formulir, dan indikator progres.
- **Soft Light Blue** (`--blue-light`: `#eaf3ff`): Latar belakang lencana kategori, sorotan menu, dan kontainer ikon sekunder.

### B. Warna Semantik & Indikator Status (Semantic Colors)
Sistem menggunakan konsep **Soft-Subtle Tone** (latar belakang cerah lembut dengan warna teks pekat) agar tidak menyilaukan mata pengguna:
- **Success / Positif** (`#16a34a` / latar `#e9f8ef`): Menandakan status *Lunas*, *Hadir*, *Disetujui*, atau *Aktif*.
- **Warning / Perhatian** (`#d97706` / latar `#fff5dc`): Menandakan status *Izin*, *Menunggu Verifikasi*, *Tagihan Belum Lunas*, atau *Prioritas Penting*.
- **Danger / Kritis** (`#dc2626` / latar `#fef2f2`): Menandakan status *Alpha*, *Sakit*, *Ditolak*, *Jatuh Tempo*, atau *Prioritas Urgent/Mendesak*.
- **Info / Akademik** (`#087fa5` / latar `#e8f8fb`): Menandakan informasi mata pelajaran, jam pelajaran, dan pengumuman umum.

### C. Warna Latar Belakang & Permukaan (Surfaces)
- **Page Canvas** (`#f5f8fd`): Latar belakang utama area aplikasi.
- **Card Surface** (`#ffffff`): Latar belakang kartu konten, modal popup, dan formulir input.
- **Hairline Border** (`#e2e8f0` / `#dce5ef`): Garis pembatas tipis 1px untuk memisahkan baris data dan sekat komponen.
- **Text Primary** (`#10223d` / `#172b49`): Warna teks judul dan konten utama dengan tingkat keterbacaan tinggi.
- **Text Muted** (`#64748b` / `#718096`): Warna teks penjelas, tanggal, dan sub-label.

---

## 3. Tipografi (Typography)

Sistem menggunakan font geometris modern **Plus Jakarta Sans** sebagai tipe tampilan utama (*primary display font*) yang dipadukan dengan tumpukan font sans-serif sistem standar:

```css
font-family: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
```

### Skala Hierarki Tipografi:

| Tingkat Hierarki | Ukuran | Weight | Line Height | Penggunaan |
| :--- | :--- | :--- | :--- | :--- |
| **Hero Display** | 32px – 40px | 800 (ExtraBold) | 1.15 | Hero banner beranda & judul utama selamat datang |
| **Heading 1** | 24px – 26px | 800 (ExtraBold) | 1.25 | Judul halaman portal, judul section dashboard |
| **Heading 2** | 20px – 22px | 700 (Bold) | 1.30 | Judul kartu modul, nama siswa, header modal |
| **Heading 3** | 16px – 18px | 600 (SemiBold) | 1.35 | Sub-header tabel, label kelompok data |
| **Body Regular** | 13px – 14px | 400 (Regular) | 1.55 | Teks isi paragraf, narasi pengumuman, deskripsi tugas |
| **Body Small** | 11px – 12px | 500 (Medium) | 1.45 | Keterangan form, sub-teks menu, waktu dibuat |
| **Monospace / Code** | 11px – 12px | 600 (SemiBold) | 1.20 | NISN, nomor pendaftaran PPDB, kode kelas, format nominal |
| **Badge Label** | 10px – 11px | 700 (Bold) | 1.00 | Label status lencana kapsul (Lunas, Hadir, Urgent) |

---

## 4. Tata Letak Aplikasi (Layout Architecture)

Aplikasi memiliki 3 varian shell tata letak yang dirancang sesuai fungsi perannya:

### 1. Layout Administrasi & Manajemen (`layouts.admin`)
- **Digunakan oleh**: Super Admin, Guru, Staf TU, Kepala Sekolah.
- **Struktur**:
  - **Topbar (Tinggi 58px)**: Menampilkan brand logo sekolah, tautan cepat *"Lihat Website"*, dropdown profil, dan pusat notifikasi terintegrasi.
  - **Sidebar (Lebar 225px)**: Navigasi vertikal terstruktur dengan pengelompokan menu: *Dashboard*, *Portal Guru*, *Manajemen Akses*, *Master Data Akademik*, *Operasional & Tata Usaha*, dan *System Engine*. Dilengkapi fitur penyimpanan posisi scroll (*session storage*) dan badge indikator pesan belum dibaca.
  - **Main Content Area**: Wadah konten fleksibel dengan margin teratur dan dukungan notifikasi flash message (*alert success/error/warning*).
  - **Dukungan Seluler**: Sidebar otomatis beralih menjadi drawer tersembunyi dengan tombol *hamburger toggle* dan *backdrop blur*.

### 2. Layout Portal Mandiri (`layouts.app`)
- **Digunakan oleh**: Siswa, Orang Tua / Wali Murid.
- **Struktur**:
  - Navigasi atas yang bersih dengan fokus pada pengalaman pengguna yang ramah dan tidak intimidating.
  - Lebar wadah terpusat (`container` maksimal 1240px) dengan margin adaptif.
  - Banner pengumuman dinamis otomatis muncul di posisi teratas ketika ada informasi penting/mendesak dari sekolah.

### 3. Layout Tamu / Autentikasi (`layouts.guest`)
- **Digunakan oleh**: Halaman Login (`/login`).
- **Struktur**:
  - Mengadopsi sistem *split-screen card* (sisi visual foto sekolah di kiri, form autentikasi di kanan).
  - **Pemusatan Presisi (True Center View)**: Menggunakan flexbox `min-height: 100vh; align-items: center; justify-content: center;` dengan `margin: auto` pada kartu. Diproteksi agar tidak terdorong ke atas pada resolusi tinggi terbatas maupun zoom browser 100% dan 90%.

---

## 5. Pola Desain Komponen UI (UI Component Patterns)

### A. Kartu & Kontainer (Cards & Panels)
- **Standard Card**: Sudut membulat modern (`border-radius: 12px` - `16px`), border halus 1px (`#e2e8f0`), latar putih bersih, serta bayangan lembut (`box-shadow: 0 4px 18px rgba(7, 27, 53, 0.05)`).
- **Hero Welcome Card**: Gradien linear biru (`linear-gradient(115deg, #104ca0, #1769d5, #168fce)`) dengan dekorasi motif geometri sirkular transparan di latar belakang.

### B. Tombol & Elemen Interaktif (Buttons)
- **Primary Action Button**: Gradien navy-blue dengan bayangan elevasi halus saat normal dan sedikit terangkat (*translateY(-2px)*) saat di-*hover*.
- **Outline Navigation Button**: Tombol bergaris luar halus dengan ikon di sisi kiri (misal: tombol *"Kembali ke Beranda Utama"* pada form login).
- **Floating / Toggle Button**: Tombol ikon tanpa batas di dalam input field (misal: tombol toggle lihat/sembunyikan password dengan ikon SVG mata terbuka/tertutup).

### C. Lencana Status Kapsul (Status Badges)
Semua status data ditampilkan dalam bentuk pil melengkung (`rounded-pill`):
```html
<!-- Sukses / Lunas -->
<span class="status-badge status-success">
    <i class="bi bi-check-circle"></i> Tagihan Lunas
</span>

<!-- Menunggu / Warning -->
<span class="status-badge status-warning">
    <i class="bi bi-exclamation-circle"></i> Menunggu Verifikasi
</span>
```

### D. Form Input & Pengalaman Pengguna (Form Controls)
- Input memiliki tinggi proporsional (40px - 44px) dengan border halus `#dce5ef`.
- Keadaan fokus (*focus state*) dilengkapi dengan efek cincin cahaya biru lembut: `box-shadow: 0 0 0 3px rgba(23, 105, 213, 0.12)`.
- Validasi kesalahan (*validation error*) menampilkan border merah terang dengan pesan penjelasan di bawah field.

---

## 6. Desain Khusus: Portal Orang Tua (Multi-Child Architecture)

Untuk mencegah kebingungan dan tampilan "menu ganda", arsitektur visual Portal Orang Tua dibagi menjadi dua zona terpisah:

```
┌────────────────────────────────────────────────────────────────────────┐
│ 👤 HERO BANNER ORANG TUA: Ringkasan anak terdaftar & status perizinan  │
└────────────────────────────────────────────────────────────────────────┘

 [ ZONA 1: AKSES LAYANAN UTAMA ORANG TUA ] (Global Context)
 ┌──────────────────────┐  ┌──────────────────────┐  ┌──────────────────────┐
 │ 📝 Ajukan Surat Izin │  │ 💬 Konsultasi Guru   │  │ 📑 Riwayat Perizinan │
 └──────────────────────┘  └──────────────────────┘  └──────────────────────┘

 [ ZONA 2: PORTOFOLIO MANDIRI PER ANAK ] (Student Context)
 ┌──────────────────────────────────────────────────────────────────────┐
 │ 🎓 Anak 1: Ahmad Dahlan — Kelas X RPL 1        [Badge: Tagihan Lunas]│
 │    [📅 Jadwal Mapel] [⏱️ Presensi] [📊 Nilai] [💳 SPP] [📄 Rapor PDF]│
 └──────────────────────────────────────────────────────────────────────┘
 ┌──────────────────────────────────────────────────────────────────────┐
 │ 🎓 Anak 2: Siti Aisyah  — Kelas XII AKL 2      [Badge: 1 Tagihan Baru]│
 │    [📅 Jadwal Mapel] [⏱️ Presensi] [📊 Nilai] [💳 SPP] [📄 Rapor PDF]│
 └──────────────────────────────────────────────────────────────────────┘
```

**Kelebihan Pendekatan Ini:**
- **Bebas Redundansi**: Tidak ada tombol yang tampil berulang tanpa makna.
- **Konteks Data Akurat**: Tautan jadwal, presensi, dan nilai melekat langsung ke kartu anak masing-masing sehingga tidak pernah tertukar datanya.
- **Skalabilitas Penuh**: Bekerja sempurna baik bagi orang tua dengan 1 anak maupun orang tua yang memiliki 2, 3, atau lebih anak di sekolah.

---

## 7. Responsivitas & Standar Breakpoint

Sistem dioptimalkan secara ketat pada rentang breakpoint Bootstrap 5:
- **Mobile (< 576px)**: Tata letak 1 kolom, padding kontainer 16px, tombol berukuran penuh (*w-100*), navigasi drawer aktif.
- **Tablet (576px - 991px)**: Grid 2 kolom, adaptasi kartu dengan padding medium, navigasi slide-over.
- **Desktop (>= 992px)**: Sidebar 225px tampil permanen, grid 3–4 kolom pada modul kartu layanan, tata letak data table penuh.
- **Height-Constrained Displays (Layar Laptop < 700px Tinggi)**: Penskalaan proporsional pada elemen kartu login dan dashboard agar tidak overflow dan tetap terpusat di layar.
