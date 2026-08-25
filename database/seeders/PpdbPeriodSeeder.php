<?php

namespace Database\Seeders;

use App\Models\PpdbPeriod;
use Illuminate\Database\Seeder;

class PpdbPeriodSeeder extends Seeder
{
    public function run(): void
    {
        PpdbPeriod::updateOrCreate(
            ['name' => 'Gelombang 1 Tahun Ajaran 2026/2027'],
            [
                'start_date' => now()->subDays(10),
                'end_date' => now()->addMonths(2),
                'quota' => 100,
                'is_active' => true,
            ]
        );
    }
}
