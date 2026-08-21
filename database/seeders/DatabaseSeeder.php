<?php

namespace Database\Seeders;

use App\Models\Users;
use App\Models\Roles;
use App\Models\Permissions;
use App\Models\PermissionRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}