<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KepsekAcademicDashboardTest extends TestCase
{
    use DatabaseTransactions;

    protected User $kepsekUser;

    protected function setUp(): void
    {
        parent::setUp();

        $kepsekRole = Role::firstOrCreate(['slug' => 'kepsek'], ['name' => 'Kepala Sekolah']);

        $this->kepsekUser = User::firstOrCreate(
            ['email' => 'kepsek_academic_test@test.sch.id'],
            [
                'name'      => 'Kepsek Academic Test',
                'password'  => bcrypt('password'),
                'role_id'   => $kepsekRole->id,
                'is_active' => true,
            ]
        );
    }

    public function test_kepsek_dashboard_academic_tab_renders_successfully(): void
    {
        $response = $this->actingAs($this->kepsekUser)->get('/kepsek/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Kepala Sekolah');
        $response->assertSee('Distribusi Predikat Nilai');
        $response->assertSee('Rata-Rata Nilai per Mata Pelajaran');
        $response->assertSee('Perbandingan Nilai Rata-Rata per Kelas');
        $response->assertSee('grade-predicate-card', false);
        $response->assertSee('gradeDistChart');
        $response->assertSee('subjectChart');
        $response->assertSee('classAvgChart');
    }
}
