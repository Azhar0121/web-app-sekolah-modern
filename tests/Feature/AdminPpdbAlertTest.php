<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\PpdbPeriod;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminPpdbAlertTest extends TestCase
{
    use DatabaseTransactions;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['slug' => 'super-admin'], ['name' => 'Super Admin']);

        $this->adminUser = User::firstOrCreate(
            ['email' => 'admin_ppdb_alert_test@test.sch.id'],
            [
                'name'      => 'Admin PPDB Alert Test',
                'password'  => bcrypt('password'),
                'role_id'   => $adminRole->id,
                'is_active' => true,
            ]
        );
    }

    public function test_ppdb_index_displays_success_alert_only_once_above_period_card(): void
    {
        $message = 'Periode PPDB berhasil diperbarui. Status pendaftaran saat ini: DIBUKA.';

        $response = $this->actingAs($this->adminUser)
            ->withSession(['success' => $message])
            ->get(route('admin.ppdb.index'));

        $response->assertStatus(200);

        $html = $response->getContent();

        // 1. Alert must appear EXACTLY once in the page
        $this->assertSame(
            1,
            substr_count($html, $message),
            'Success alert appeared more than once in the HTML output.'
        );

        // 2. Alert must be positioned below ppdb-header and right above ppdb-period-card
        $headerPos = strpos($html, 'ppdb-header');
        $alertPos  = strpos($html, $message);
        $cardPos   = strpos($html, 'ppdb-period-card');

        $this->assertNotFalse($headerPos);
        $this->assertNotFalse($alertPos);
        $this->assertNotFalse($cardPos);

        $this->assertTrue(
            $headerPos < $alertPos && $alertPos < $cardPos,
            'The success alert must be located between the PPDB header and the PPDB period card.'
        );
    }

    public function test_updating_ppdb_period_redirects_and_renders_single_alert(): void
    {
        $year = AcademicYear::firstOrCreate(
            ['name' => '2027/2028'],
            [
                'semester'   => 'ganjil',
                'is_active'  => true,
                'start_date' => now()->toDateString(),
                'end_date'   => now()->addYear()->toDateString(),
            ]
        );

        $response = $this->actingAs($this->adminUser)->put(route('admin.ppdb.period.update'), [
            'academic_year_id'      => $year->id,
            'name'                  => 'Gelombang 1 Tahun Ajaran 2027/2028',
            'start_date'            => now()->subDays(5)->toDateString(),
            'end_date'              => now()->addDays(20)->toDateString(),
            'quota'                 => 500,
            're_registration_days'  => 7,
            'is_active'             => 1,
        ]);

        $response->assertRedirect(route('admin.ppdb.index'));
        $response->assertSessionHas('success');

        $followResponse = $this->followRedirects($response);
        $followResponse->assertStatus(200);

        $html = $followResponse->getContent();
        $expectedSubstring = 'Periode PPDB berhasil diperbarui. Status pendaftaran saat ini: DIBUKA.';

        $this->assertSame(
            1,
            substr_count($html, $expectedSubstring),
            'Success alert appeared multiple times after redirect.'
        );
    }
}
