<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Setting;
use App\Models\Subject;
use Illuminate\View\View;

class AcademicController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key')->all();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $subjects = Subject::with('department')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $academicDocuments = [
            [
                'title'       => 'Buku Pedoman Akademik & Tata Tertib Siswa TP 2026/2027',
                'category'    => 'Pedoman Akademik',
                'description' => 'Memuat standar operasional KBM, hak & kewajiban siswa, sistem penilaian, aturan kenaikan kelas, dan tata tertib sekolah.',
                'file_name'   => 'Buku-Pedoman-Akademik-2026-2027.pdf',
                'file_size'   => '2.4 MB',
                'updated_at'  => 'Juli 2026',
                'icon'        => 'bi-journal-bookmark-fill',
                'color'       => 'primary',
            ],
            [
                'title'       => 'Kalender Pendidikan Resmi Sekolah TP 2026/2027',
                'category'    => 'Kalender Pendidikan',
                'description' => 'Jadwal pekan efektif belajar, agenda Penilaian Tengah Semester (PTS), PAS/PAT, asesmen sekolah, serta libur resmi semester.',
                'file_name'   => 'Kalender-Pendidikan-2026-2027.pdf',
                'file_size'   => '1.1 MB',
                'updated_at'  => 'Juli 2026',
                'icon'        => 'bi-calendar-check-fill',
                'color'       => 'success',
            ],
            [
                'title'       => 'Silabus Kurikulum Merdeka & Capaian Pembelajaran (CP)',
                'category'    => 'Silabus Nasional',
                'description' => 'Kerangka struktur mata pelajaran Fase E (Kelas X) & Fase F (Kelas XI-XII), alokasi jam belajar, serta panduan Projek P5.',
                'file_name'   => 'Silabus-Kurikulum-Merdeka-Fase-E-F.pdf',
                'file_size'   => '3.2 MB',
                'updated_at'  => 'Agustus 2026',
                'icon'        => 'bi-file-earmark-text-fill',
                'color'       => 'warning',
            ],
            [
                'title'       => 'Kurikulum Operasional Satuan Pendidikan (KOSP) & Panduan PKL',
                'category'    => 'Program Keahlian',
                'description' => 'Kompetensi kejuruan (RPL, TKJ, DKV), skema sertifikasi industri (LSP/BNSP), pedoman Praktik Kerja Lapangan (PKL), dan UKK.',
                'file_name'   => 'Panduan-KOSP-Kejuruan-SMK.pdf',
                'file_size'   => '4.0 MB',
                'updated_at'  => 'Agustus 2026',
                'icon'        => 'bi-briefcase-fill',
                'color'       => 'info',
            ],
            [
                'title'       => 'Petunjuk Teknis Asesmen & Ujian Berbasis Komputer (CBT)',
                'category'    => 'Panduan Asesmen',
                'description' => 'Prosedur teknis tata tertib pelaksanaan evaluasi berkala dan ujian daring mandiri melalui platform CBT sekolah.',
                'file_name'   => 'Petunjuk-Teknis-CBT-Sekolah.pdf',
                'file_size'   => '1.5 MB',
                'updated_at'  => 'September 2026',
                'icon'        => 'bi-laptop-fill',
                'color'       => 'secondary',
            ],
        ];

        return view('public.academic', compact('settings', 'departments', 'subjects', 'academicDocuments'));
    }
}