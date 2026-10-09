<?php

namespace Database\Seeders;

use App\Models\Extracurricular;
use App\Models\OsisActivity;
use App\Models\OsisMember;
use App\Models\StudentAchievement;
use Illuminate\Database\Seeder;

class StudentActivitySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ekstrakurikuler
        $extracurriculars = [
            [
                'name'         => 'Pramuka Wajib',
                'category'     => 'kepemimpinan',
                'description'  => 'Kegiatan kepanduan wajib untuk melatih kemandirian, kedisiplinan, kecintaan alam, dan jiwa gotong royong.',
                'coach_name'   => 'Drs. H. Mulyadi',
                'schedule_day' => 'Jumat, 14:00 - 16:30 WIB',
                'photo'        => null,
                'order'        => 1,
            ],
            [
                'name'         => 'PMR (Palang Merah Remaja)',
                'category'     => 'kepemimpinan',
                'description'  => 'Pelatihan pertolongan pertama, donor darah, kesiapsiagaan bencana, dan kepedulian kemanusiaan di lingkungan sekolah.',
                'coach_name'   => 'dr. Retno Wulandari',
                'schedule_day' => 'Sabtu, 08:30 - 11:00 WIB',
                'photo'        => null,
                'order'        => 2,
            ],
            [
                'name'         => 'Paskibra Sekolah',
                'category'     => 'kepemimpinan',
                'description'  => 'Pelatihan formasi baris-berbaris (PBB), kepemimpinan regu, dan petugas upacara bendera tingkat sekolah hingga kabupaten.',
                'coach_name'   => 'Kapten (Purn) Sudirman',
                'schedule_day' => 'Selasa & Kamis, 15:30 - 17:00 WIB',
                'photo'        => null,
                'order'        => 3,
            ],
            [
                'name'         => 'Coding & Tech Club',
                'category'     => 'teknologi',
                'description'  => 'Eksplorasi pembuatan aplikasi web, kecerdasan buatan (AI), desain UI/UX, dan kompetisi hackathon pelajar.',
                'coach_name'   => 'Irfan Maulana, S.Kom',
                'schedule_day' => 'Rabu, 15:30 - 17:30 WIB',
                'photo'        => null,
                'order'        => 4,
            ],
            [
                'name'         => 'Bola Basket',
                'category'     => 'olahraga',
                'description'  => 'Pembinaan tim basket putra dan putri, pengasahan teknik fundamental, strategi tim, dan kejuaraan DBL tingkat pelajar.',
                'coach_name'   => 'Coach Denny Santoso',
                'schedule_day' => 'Senin & Kamis, 15:30 - 17:30 WIB',
                'photo'        => null,
                'order'        => 5,
            ],
            [
                'name'         => 'Futsal & Sepakbola',
                'category'     => 'olahraga',
                'description'  => 'Latihan fisik, taktik sepak bola modern, dan keikutsertaan dalam kompetisi liga pelajar regional.',
                'coach_name'   => 'Coach Bambang Irawan',
                'schedule_day' => 'Selasa & Jumat, 15:30 - 17:30 WIB',
                'photo'        => null,
                'order'        => 6,
            ],
            [
                'name'         => 'Paduan Suara & Band Musik',
                'category'     => 'seni',
                'description'  => 'Olah vokal, aransemen paduan suara harmoni, serta grup band sekolah untuk pentas seni dan festival FLS2N.',
                'coach_name'   => 'Yuliana Sari, S.Pd',
                'schedule_day' => 'Sabtu, 09:00 - 12:00 WIB',
                'photo'        => null,
                'order'        => 7,
            ],
            [
                'name'         => 'English Debate & Speech',
                'category'     => 'seni',
                'description'  => 'Pelatihan public speaking, debat bahasa Inggris sistem Parliamentary, dan kompetisi National Schools Debating Championship.',
                'coach_name'   => 'Sarah Anggraini, M.Pd',
                'schedule_day' => 'Kamis, 15:30 - 17:00 WIB',
                'photo'        => null,
                'order'        => 8,
            ],
        ];

        foreach ($extracurriculars as $data) {
            Extracurricular::firstOrCreate(['name' => $data['name']], $data);
        }

        // 2. Prestasi Siswa
        $achievements = [
            [
                'title'         => 'Juara 1 LKS Tingkat Nasional Bidang Web Technologies',
                'category'      => 'akademik',
                'level'         => 'nasional',
                'year'          => 2025,
                'organizer'     => 'Kemendikbudristek Republik Indonesia',
                'student_name'  => 'Muhammad Farhan',
                'student_class' => 'Kelas XII RPL 1',
                'description'   => 'Meraih medali emas nasional setelah melalui seleksi ketat dan membangun arsitektur web modern full-stack berstandar internasional.',
                'photo'         => null,
                'is_featured'   => true,
                'order'         => 1,
            ],
            [
                'title'         => 'Medali Emas OSN Tingkat Provinsi Bidang Informatika',
                'category'      => 'akademik',
                'level'         => 'provinsi',
                'year'          => 2025,
                'organizer'     => 'Balai Pengembangan Talenta Indonesia (BPTI)',
                'student_name'  => 'Siti Nurhaliza',
                'student_class' => 'Kelas XI MIPA 2',
                'description'   => 'Menyelesaikan persoalan algoritma dan struktur data kompetitif dengan poin tertinggi tingkat provinsi.',
                'photo'         => null,
                'is_featured'   => false,
                'order'         => 2,
            ],
            [
                'title'         => 'Juara Umum Festival Seni & FLS2N Tingkat Kabupaten',
                'category'      => 'seni',
                'level'         => 'kota',
                'year'          => 2025,
                'organizer'     => 'Dinas Pendidikan Kabupaten',
                'student_name'  => 'Tim Paduan Suara Voice of Modern',
                'student_class' => 'Grup Siswa Lintas Kelas',
                'description'   => 'Kategori Cipta Lagu & Paduan Suara Pelajar Terbaik dengan interpretasi lagu daerah yang memukau dewan juri.',
                'photo'         => null,
                'is_featured'   => false,
                'order'         => 3,
            ],
        ];

        foreach ($achievements as $data) {
            StudentAchievement::firstOrCreate(['title' => $data['title']], $data);
        }

        // 3. Pengurus OSIS
        $members = [
            [
                'name'       => 'Rizky Pratama',
                'position'   => 'Ketua OSIS',
                'department' => 'Badan Pengurus Harian (BPH)',
                'class_name' => 'XI MIPA 1',
                'period'     => '2026/2027',
                'order'      => 1,
            ],
            [
                'name'       => 'Amanda Putri Lestari',
                'position'   => 'Wakil Ketua OSIS',
                'department' => 'Badan Pengurus Harian (BPH)',
                'class_name' => 'XI IPS 2',
                'period'     => '2026/2027',
                'order'      => 2,
            ],
            [
                'name'       => 'Nabila Zahra Khairunnisa',
                'position'   => 'Sekretaris Umum',
                'department' => 'Badan Pengurus Harian (BPH)',
                'class_name' => 'X RPL 1',
                'period'     => '2026/2027',
                'order'      => 3,
            ],
            [
                'name'       => 'Fajar Setiawan',
                'position'   => 'Bendahara Umum',
                'department' => 'Badan Pengurus Harian (BPH)',
                'class_name' => 'XI AKL 1',
                'period'     => '2026/2027',
                'order'      => 4,
            ],
            [
                'name'       => 'Bintang Ramadhan',
                'position'   => 'Koordinator Divisi IT & Multimedia',
                'department' => 'Divisi Teknologi Informasi',
                'class_name' => 'XI RPL 2',
                'period'     => '2026/2027',
                'order'      => 5,
            ],
            [
                'name'       => 'Clarissa Maheswari',
                'position'   => 'Koordinator Divisi Seni & Budaya',
                'department' => 'Divisi Apresiasi Seni',
                'class_name' => 'X DKV 1',
                'period'     => '2026/2027',
                'order'      => 6,
            ],
        ];

        foreach ($members as $data) {
            OsisMember::firstOrCreate(['name' => $data['name'], 'period' => $data['period']], $data);
        }

        // 4. Program & Kegiatan OSIS
        $activities = [
            [
                'title'       => 'Pentas Seni & Classmeet Spektakuler',
                'date'        => '2026-12-18',
                'description' => 'Ajang tahunan unjuk ekspresi kreativitas, tari tradisional, festival musik band, dan kompetisi olahraga antar kelas setelah masa ujian semester.',
                'photo'       => null,
                'gallery'     => null,
                'is_featured' => true,
                'order'       => 1,
            ],
            [
                'title'       => 'Bakti Sosial & Aksi Peduli Sesama',
                'date'        => '2026-10-15',
                'description' => 'Penggalangan dana bantuan kemanusiaan, pembagian sembako warga sekitar sekolah, dan aksi bersih lingkungan bersama masyarakat.',
                'photo'       => null,
                'gallery'     => null,
                'is_featured' => false,
                'order'       => 2,
            ],
            [
                'title'       => 'Tech Fest & Hackathon Pelajar',
                'date'        => '2026-11-05',
                'description' => 'Kompetisi pembuatan aplikasi web edukatif, desain UI/UX, dan pameran proyek inovasi teknologi siswa bekerjasama dengan mitra industri.',
                'photo'       => null,
                'gallery'     => null,
                'is_featured' => false,
                'order'       => 3,
            ],
            [
                'title'       => 'Majalah Dinding & Buletin Jurnalistik Digital',
                'date'        => '2026-09-20',
                'description' => 'Publikasi karya literasi siswa, liputan video kegiatan sekolah, serta buletin berkala yang didistribusikan secara daring.',
                'photo'       => null,
                'gallery'     => null,
                'is_featured' => false,
                'order'       => 4,
            ],
        ];

        foreach ($activities as $data) {
            OsisActivity::firstOrCreate(['title' => $data['title']], $data);
        }
    }
}