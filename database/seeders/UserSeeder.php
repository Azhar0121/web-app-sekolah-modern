<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Password sama untuk semua akun dummy
        $password = Hash::make('password123');

        $dummyUsers = [
            ['name' => 'Admin Sistem', 'email' => 'admin@sekolah.test', 'role_slug' => 'super-admin'],
            ['name' => 'Budi Santoso, S.Pd', 'email' => 'guru@sekolah.test', 'role_slug' => 'guru'],
            ['name' => 'Ahmad Fauzi', 'email' => 'siswa@sekolah.test', 'role_slug' => 'siswa'],
            ['name' => 'Ibu Siti Aminah', 'email' => 'ortu@sekolah.test', 'role_slug' => 'ortu'],
            ['name' => 'Rina Wijaya', 'email' => 'tu@sekolah.test', 'role_slug' => 'tu'],
            ['name' => 'Dr. H. Suryanto, M.Pd', 'email' => 'kepsek@sekolah.test', 'role_slug' => 'kepsek'],
        ];

        foreach ($dummyUsers as $data) {
            $role = Role::where('slug', $data['role_slug'])->first();

            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $password,
                    'role_id' => $role?->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}