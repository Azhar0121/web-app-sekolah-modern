<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::updateOrCreate(
            ['name' => '2026/2027'],
            [
                'start_date' => '2026-07-01',
                'end_date' => '2027-06-30',
                'is_active' => true,
            ]
        );

        $ganjil = $year->semesters()->updateOrCreate(
            ['name' => 'Ganjil'],
            ['is_active' => true]
        );

        $year->semesters()->updateOrCreate(
            ['name' => 'Genap'],
            ['is_active' => false]
        );

        // Pastikan hanya semester Ganjil 2026/2027 yang aktif (jaga-jaga kalau seeder dijalankan ulang)
        $year->semesters()->where('id', '!=', $ganjil->id)->update(['is_active' => false]);
    }
}
