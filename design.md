# Design System — Web Sekolah Modern

## Overview

Web Sekolah Modern adalah sistem informasi manajemen sekolah berbasis web yang dirancang dengan antarmuka yang bersih, elegan, dan intuitif. Desain aplikasi mengusung estetika **Modern Academic Navy**: perpaduan warna biru navy mendalam (`#071b35`) yang mencerminkan profesionalisme dan kewibawaan akademis, dipadukan dengan latar belakang terang bersuhu dingin (`#f5f8fc`) serta aksen warna fungsional yang tajam.

Sistem antarmuka dibangun di atas kerangka **Bootstrap 5.3** dengan pendekatan *Clean Code UI* — memanfaatkan kelas utilitas standar secara konsisten tanpa mengotori berkas view dengan CSS inline atau tag `<style>` manual. Struktur visual mengandalkan *hierarchy of elevation* yang halus, kartu berpenjuru melengkung (*rounded corners*), lencana status bergaya *soft pill*, serta tipografi modern yang mudah dibaca.

**Karakteristik Utama Desain:**
- **Palet Warna Modern Navy & Slate**: Kombinasi Navy Utama (`#071b35`), Biru Aksen (`#2563eb`), Slate Dark (`#0f172a`), dan Canvas Terang (`#f5f8fc`).
- **Tata Letak App Shell Interaktif**: Navigasi atas (Topbar 58px) dan Navigasi samping (Sidebar 225px) yang responsif dan mendukung layar seluler via *drawer backdrop*.
- **Desain Kartu & Modal Melengkung (*Rounded Cards*)**: Penggunaan sudut melengkung konsisten (`rounded-3` hingga `rounded-4`) dipadukan dengan bayangan lembut (`shadow-sm`).
- **Aksen Komponen Stadium Pill**: Penggunaan bentuk kapsul (`rounded-pill`) untuk seluruh badge status, filter tab, dan tombol aksi cepat.
- **Tipografi Plus Jakarta Sans**: Tipografi sans-serif geometris modern untuk keterbacaan tinggi pada data tabel, grafik analitik, maupun formulir input.
- **Sistem Lencana Status Berwarna (*Subtle Badges*)**: Status data (Lunas, Belum Bayar, Hadir, Izin, Sakit, Alpha, Disetujui, Ditolak) menggunakan kombinasi latar *subtle* berserta batas transparan (*subtle border*).

---

## 1. Warna (Color Palette)

### Warna Utama & Brand (Brand Colors)
- **Deep Navy** (`--sidebar-navy` — `#071b35`): Warna identitas utama sistem. Digunakan pada Sidebar, Topbar brand mark, header kartu utama, serta tombol tindakan primer.
- **Navy Secondary** (`--sidebar-navy-2` — `#0b294d`): Warna variasi navy yang lebih terang untuk kondisi *hover*, gradien header, dan elemen navigasi aktif.

### Warna Aksen & Semantik (Accent & Semantic Colors)
- **Electric Blue** (`#2563eb` / `#3b82f6`): Warna aksen utama untuk elemen interaktif, tautan, tombol fokus, serta indikator akademik (Nilai, Jadwal).
- **Emerald Green** (`#10b981` / `#16a34a`): Digunakan untuk sinyal positif/sukses — status Lunas, Disetujui, Hadir, dan konfirmasi transaksi.
- **Amber / Warning Yellow** (`#f59e0b` / `#d97706`): Digunakan untuk sinyal perhatian/menunggu — status Izin, Tagihan Tertunggak, Pengajuan Pending.
- **Rose / Danger Red** (`#ef4444` / `#dc2626`): Digunakan untuk sinyal bahaya/kritis — status Sakit, Alpha, Ditolak, Tagihan Jatuh Tempo, dan tombol Hapus.
- **Purple / Indigo** (`#6366f1` / `#4f46e5`): Digunakan untuk aksen portal siswa dan modul jadwal pelajaran.

### Warna Latar & Permukaan (Surface & Canvas)
- **App Background** (`#f5f8fc`): Latar belakang utama aplikasi (`.app-main`) yang memberikan kontras lembut terhadap kartu putih.
- **Card Canvas** (`#ffffff`): Latar belakang kartu, modal, tabel, dan formulir input.
- **Header Light Canvas** (`#f8fafc` / `#f1f5f9`): Latar belakang header tabel dan header grup accordion.
- **Border Hairline** (`#e2e8f0` / `#dbe5f0`): Garis pembatas tipis 1px untuk memisahkan antar baris data dan sekat komponen.

---

## 2. Tipografi (Typography)

### Font Families
Sistem mengombinasikan dua keluarga font (*font families*) utama untuk menciptakan kontras hierarki yang tegas dan bersih:

1. **Plus Jakarta Sans** (Primary / Display Font):
   - Font sans-serif geometris modern bertipe kontemporer yang menjadi identitas utama aplikasi.
   - Digunakan untuk seluruh judul (Display, Heading 1-3), header kartu, tombol navigasi, menu sidebar, badge status, dan komponen antarmuka utama.
2. **Sans-Serif (System Sans-Serif)**:
   - Tumpukan font standar sistem operasi (`sans-serif`, `-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial`).
   - Digunakan sebagai font sekunder, fallback keterbacaan tinggi, serta pasangan hierarki pada teks isi paragraf (*body copy*), form input, dan teks penjelas.

### Skala Hirarki Tipografi

| Kategori | Font Family | Ukuran | Weight | Line Height | Penggunaan |
|---|---|---|---|---|---|
| **Display Title** | Plus Jakarta Sans | 28px - 32px | 800 (ExtraBold) | 1.2 | Hero Banner Dashboard & Header Halaman Utama |
| **Heading 1** | Plus Jakarta Sans | 24px | 700 (Bold) | 1.25 | Judul Kartu Utama & Modal Header |
| **Heading 2** | Plus Jakarta Sans | 20px | 700 (Bold) | 1.3 | Judul Sub-seksi & Nama Siswa/Pengguna |
| **Heading 3** | Plus Jakarta Sans | 16px | 600 (SemiBold) | 1.35 | Judul Kolom Tabel & Label Grup Sidebar |
| **Body Regular** | Sans-Serif / Plus Jakarta | 14px (0.875rem) | 400 (Regular) | 1.5 | Teks Paragraf Utuh & Isi Pesan Konsultasi |
| **Body Small** | Sans-Serif / Plus Jakarta | 12px - 13px | 500 (Medium) | 1.4 | Teks Penjelas, Sub-label, Keterangan Form |
| **Monospace / Code**| Monospace / Sans-Serif | 12px - 13px | 600 (SemiBold) | 1.2 | Jam Pelajaran, Tanggal, NISN, Format Angka |
| **Badge Label** | Plus Jakarta Sans | 10px - 11px | 600 (SemiBold) | 1.0 | Lencana Status & Chip Kategori (Upper/Sentence Case) |

---

## 3. Tata Letak (Layout System)

### Layout Shell Aplikasi (App Shell)
Aplikasi dibagi menjadi dua tipe layout dasar:
1. **Layout Admin (`layouts.admin`)**:
   - Digunakan oleh `super-admin`, `guru`, `tu`, dan `kepsek`.
   - Terdiri dari Topbar (58px) tetap di bagian atas, Sidebar (225px) tetap di bagian kiri, dan area konten utama (`.app-main`) yang dapat di-*scroll* secara independen.
   - Layar Tablet & Mobile (< 992px): Sidebar otomatis tersembunyi dan dapat dibuka melalui tombol *hamburger toggle* dengan efek *backdrop blur*.

2. **Layout Public / Portal Client (`layouts.app`)**:
   - Digunakan oleh `siswa`, `ortu`, serta Halaman Publik Sekolah / PPDB.
   - Menggunakan navigasi Topbar penuh tanpa sidebar vertikal, dengan lebar wadah konten terpusat (`container` max-width 960px - 1140px).

### Breakpoint Responsif (Bootstrap 5)
- **Mobile Extra Small (< 576px)**: Formulir dan grid berorientasi 1 kolom vertikal.
- **Mobile / Tablet (576px - 768px)**: Grid aksi cepat berubah menjadi 2 kolom.
- **Tablet / Desktop Sedang (768px - 992px)**: Sidebar beralih ke mode slide-over drawer.
- **Desktop (>= 992px)**: Tampilan penuh dengan Sidebar 225px dan area utama fleksibel.

---

## 4. Komponen Visual & UI Design

### 1. Kartu (Cards & Containers)
- **Standard Card**: `card border-0 shadow-sm rounded-4 overflow-hidden`
- **Featured / Dark Card**: `card bg-dark text-white border-0 shadow-sm rounded-4 p-4 mb-4`
- **Card Header**: `card-header bg-light d-flex justify-content-between align-items-center py-3 px-4 border-bottom`

### 2. Tombol (Buttons)
- **Primary Button**: `btn btn-primary rounded-pill px-4 shadow-sm` — Digunakan untuk aksi utama (Simpan, Tambah, Kirim).
- **Secondary Button**: `btn btn-outline-secondary rounded-pill px-3` — Digunakan untuk Batal, Kembali, atau filter sekunder.
- **Action Pills**: `btn btn-sm rounded-pill` — Digunakan pada tombol filter dan tabel.

### 3. Lencana Status (Subtle Badges)
Seluruh lencana status mengusung konsep *Soft Subtlety* dengan teks gelap di atas latar terang:
- **Sukses / Lunas / Hadir**: `badge bg-success-subtle text-success border border-success-subtle rounded-pill`
- **Warning / Izin / Pending**: `badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill`
- **Danger / Sakit / Alpha / Ditolak**: `badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill`
- **Info / Akademik / Jam**: `badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill`

### 4. Tabel Data (Data Tables)
- Menggunakan skema `table table-hover align-middle mb-0` dengan header `table-light`.
- Baris tabel dilengkapi bantalan vertikal yang cukup (`py-3 px-4`) untuk mencegah kepadatan visual.
