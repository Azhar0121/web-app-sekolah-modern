<?php

namespace Database\Seeders;

use App\Models\Correspondence;
use App\Models\User;
use Illuminate\Database\Seeder;

class CorrespondenceSeeder extends Seeder
{
    public function run(): void
    {
        $tu = User::whereHas('role', fn ($q) => $q->where('slug', 'tu'))->first();

        $samples = [
            [
                'type' => 'masuk',
                'letter_date' => now()->subDays(5),
                'category' => 'Undangan',
                'subject' => 'Undangan Rapat Koordinasi Dinas Pendidikan',
                'correspondent' => 'Dinas Pendidikan Kota',
                'status' => 'diproses',
                'disposition' => 'Diteruskan ke Kepala Sekolah untuk konfirmasi kehadiran.',
            ],
            [
                'type' => 'masuk',
                'letter_date' => now()->subDays(2),
                'category' => 'Pemberitahuan',
                'subject' => 'Pemberitahuan Libur Nasional',
                'correspondent' => 'Kementerian Pendidikan',
                'status' => 'baru',
            ],
            [
                'type' => 'keluar',
                'letter_date' => now()->subDays(3),
                'category' => 'Permohonan',
                'subject' => 'Permohonan Izin Penggunaan Aula Kelurahan',
                'correspondent' => 'Kelurahan Setempat',
                'status' => 'terkirim',
            ],
            [
                'type' => 'keluar',
                'letter_date' => now()->subDay(),
                'category' => 'Edaran',
                'subject' => 'Edaran Jadwal Ujian Tengah Semester untuk Orang Tua',
                'correspondent' => 'Seluruh Wali Murid',
                'status' => 'draft',
            ],
        ];

        foreach ($samples as $sample) {
            $number = Correspondence::generateNumber($sample['type'], $sample['letter_date']);

            Correspondence::updateOrCreate(
                ['number' => $number],
                array_merge($sample, ['created_by' => $tu?->id])
            );
        }
    }
}
