<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Akses penuh ke seluruh sistem'],
            ['name' => 'Guru / Wali Kelas', 'slug' => 'guru', 'description' => 'Input nilai, presensi, materi kelas'],
            ['name' => 'Siswa', 'slug' => 'siswa', 'description' => 'Lihat nilai, presensi, materi'],
            ['name' => 'Orang Tua / Wali', 'slug' => 'ortu', 'description' => 'Pantau progres anak'],
            ['name' => 'Tata Usaha', 'slug' => 'tu', 'description' => 'Persuratan, inventaris, administrasi'],
            ['name' => 'Kepala Sekolah', 'slug' => 'kepsek', 'description' => 'Dashboard laporan & approval tingkat sekolah'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
