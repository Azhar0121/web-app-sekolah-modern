<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'code' => 'RPL',
                'name' => 'Rekayasa Perangkat Lunak',
                'description' => 'Fokus pada pengembangan aplikasi web, mobile, komputasi awan, dan rekayasa kecerdasan buatan.',
                'is_active' => true,
            ],
            [
                'code' => 'TKJ',
                'name' => 'Teknik Komputer & Jaringan',
                'description' => 'Fokus pada perancangan infrastruktur jaringan modern, sistem administrasi server, keamanan siber, dan cloud computing.',
                'is_active' => true,
            ],
            [
                'code' => 'DKV',
                'name' => 'Desain Komunikasi Visual',
                'description' => 'Fokus pada desain grafis multimedia, animasi 2D/3D, videografi kreatif, serta UI/UX prototyping.',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $data) {
            Department::updateOrCreate(['code' => $data['code']], $data);
        }

        // Tautkan atau buat contoh mapel produktif/kejuruan
        $rpl = Department::where('code', 'RPL')->first();
        $tkj = Department::where('code', 'TKJ')->first();
        $dkv = Department::where('code', 'DKV')->first();

        if ($rpl) {
            Subject::updateOrCreate(
                ['code' => 'PWEB'],
                [
                    'name' => 'Pemrograman Web & Perangkat Bergerak',
                    'department_id' => $rpl->id,
                ]
            );
            Subject::updateOrCreate(
                ['code' => 'BDAT'],
                [
                    'name' => 'Basis Data & Pemodelan Perangkat Lunak',
                    'department_id' => $rpl->id,
                ]
            );
        }

        if ($tkj) {
            Subject::updateOrCreate(
                ['code' => 'AIJ'],
                [
                    'name' => 'Administrasi Infrastruktur Jaringan',
                    'department_id' => $tkj->id,
                ]
            );
            Subject::updateOrCreate(
                ['code' => 'ASJ'],
                [
                    'name' => 'Administrasi Sistem Jaringan & Cloud',
                    'department_id' => $tkj->id,
                ]
            );
        }

        if ($dkv) {
            Subject::updateOrCreate(
                ['code' => 'DGRAF'],
                [
                    'name' => 'Desain Grafis Percetakan & Vektor',
                    'department_id' => $dkv->id,
                ]
            );
            Subject::updateOrCreate(
                ['code' => 'ANIM'],
                [
                    'name' => 'Animasi 2D & Video Editing',
                    'department_id' => $dkv->id,
                ]
            );
        }
    }
}
