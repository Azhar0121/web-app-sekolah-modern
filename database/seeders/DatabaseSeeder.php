<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            AcademicYearSeeder::class,
            SubjectSeeder::class,
            ClassroomSeeder::class,
            TeachingAssignmentSeeder::class,
            ClassroomStudentSeeder::class,
            PpdbPeriodSeeder::class,
            PpdbRegistrationSeeder::class,
        ]);
    }
}