<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $guruDummy = User::where('email', 'guru@sekolah.test')->first();

        $classrooms = [
            ['name' => 'X-1', 'grade_level' => 'X', 'major' => null, 'homeroom_teacher_id' => $guruDummy?->id, 'capacity' => 36],
            ['name' => 'X-2', 'grade_level' => 'X', 'major' => null, 'capacity' => 36],
            ['name' => 'XI IPA 1', 'grade_level' => 'XI', 'major' => 'IPA', 'capacity' => 34],
            ['name' => 'XI IPS 1', 'grade_level' => 'XI', 'major' => 'IPS', 'capacity' => 34],
            ['name' => 'XII IPA 1', 'grade_level' => 'XII', 'major' => 'IPA', 'capacity' => 32],
            ['name' => 'XII IPS 1', 'grade_level' => 'XII', 'major' => 'IPS', 'capacity' => 32],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::updateOrCreate(['name' => $classroom['name']], $classroom);
        }
    }
}