<?php

namespace Database\Seeders;

use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use Illuminate\Database\Seeder;

class PpdbRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $period = PpdbPeriod::first();

        if (! $period) {
            return;
        }

        $dummyData = [
            [
                'full_name' => 'Rangga Pratama',
                'gender' => 'L',
                'birth_place' => 'Yogyakarta',
                'birth_date' => '2011-03-14',
                'address' => 'Jl. Kaliurang KM 5, Yogyakarta',
                'phone' => '081234567801',
                'parent_name' => 'Bapak Wibowo',
                'parent_phone' => '081234567802',
                'previous_school' => 'SMP Negeri 1 Yogyakarta',
                'status' => 'submitted',
            ],
            [
                'full_name' => 'Dewi Anggraini',
                'gender' => 'P',
                'birth_place' => 'Sleman',
                'birth_date' => '2011-07-22',
                'address' => 'Jl. Magelang KM 8, Sleman',
                'phone' => '081234567803',
                'parent_name' => 'Ibu Sartika',
                'parent_phone' => '081234567804',
                'previous_school' => 'SMP Negeri 2 Sleman',
                'status' => 'verified',
            ],
            [
                'full_name' => 'Fajar Nugroho',
                'gender' => 'L',
                'birth_place' => 'Bantul',
                'birth_date' => '2011-01-05',
                'address' => 'Jl. Parangtritis KM 10, Bantul',
                'phone' => '081234567805',
                'parent_name' => 'Bapak Hartono',
                'parent_phone' => '081234567806',
                'previous_school' => 'SMP Muhammadiyah Bantul',
                'status' => 'accepted',
            ],
        ];

        foreach ($dummyData as $data) {
            PpdbRegistration::updateOrCreate(
                ['full_name' => $data['full_name'], 'ppdb_period_id' => $period->id],
                array_merge($data, ['ppdb_period_id' => $period->id])
            );
        }
    }
}
