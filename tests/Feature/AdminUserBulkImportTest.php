<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserBulkImportTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected Role $roleAdmin;
    protected Role $roleGuru;
    protected Role $roleTu;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'sekolah_modern',
        ]);

        $this->roleAdmin = Role::firstOrCreate(['slug' => 'super-admin'], ['name' => 'Super Admin']);
        $this->roleGuru = Role::firstOrCreate(['slug' => 'guru'], ['name' => 'Guru']);
        $this->roleTu = Role::firstOrCreate(['slug' => 'tu'], ['name' => 'Tata Usaha']);

        $this->admin = User::create([
            'name' => 'Admin Import Tester',
            'email' => 'admin.import.' . uniqid() . '@sekolah.test',
            'password' => Hash::make('password123'),
            'role_id' => $this->roleAdmin->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_download_import_template_csv(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.import.template'));

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename="template-import-guru-tu.csv"');

        $content = $response->streamedContent();
        $this->assertStringContainsString('nama,email,role', $content);
        $this->assertStringContainsString('guru', $content);
        $this->assertStringContainsString('tu', $content);
    }

    public function test_admin_can_bulk_import_guru_and_tu_with_random_passwords(): void
    {
        $emailGuru1 = 'guru1.' . uniqid() . '@sekolah.test';
        $emailGuru2 = 'guru2.' . uniqid() . '@sekolah.test';
        $emailTu1 = 'tu1.' . uniqid() . '@sekolah.test';

        $csvContent = implode("\n", [
            'nama,email,role',
            "Drs. H. Mulyono M.Pd,{$emailGuru1},guru",
            "Siti Aminah S.Pd,{$emailGuru2},guru",
            "Rina Wijaya A.Md,{$emailTu1},tu",
        ]);

        $file = UploadedFile::fake()->createWithContent('daftar_guru_tu.csv', $csvContent);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('import_results');

        // Verify users created in database
        $userGuru1 = User::where('email', $emailGuru1)->first();
        $this->assertNotNull($userGuru1, 'FAILED: userGuru1 is null in DB');
        $this->assertSame('Drs. H. Mulyono M.Pd', $userGuru1->name);
        $this->assertSame($this->roleGuru->id, $userGuru1->role_id);
        $this->assertTrue($userGuru1->is_active);
        $this->assertNotNull($userGuru1->email_verified_at, 'FAILED: email_verified_at is null');

        $userTu1 = User::where('email', $emailTu1)->first();
        $this->assertNotNull($userTu1, 'FAILED: userTu1 is null in DB');
        $this->assertSame($this->roleTu->id, $userTu1->role_id);

        // Verify credentials generated in session
        $credentials = session('latest_import_credentials');
        $this->assertCount(3, $credentials);

        $credGuru1 = collect($credentials)->firstWhere('email', $emailGuru1);
        $this->assertNotNull($credGuru1, 'FAILED: credGuru1 is null in session');
        $plainPassword = $credGuru1['plain_password'];

        // Password should be 8 characters, friendly format, and verify with Hash::check
        $this->assertSame(8, strlen($plainPassword));
        $this->assertTrue(Hash::check($plainPassword, $userGuru1->password));
    }

    public function test_import_handles_duplicate_emails_and_invalid_roles_gracefully(): void
    {
        $existingEmail = 'existing.' . uniqid() . '@sekolah.test';
        User::create([
            'name' => 'Existing User',
            'email' => $existingEmail,
            'password' => Hash::make('password123'),
            'role_id' => $this->roleGuru->id,
            'is_active' => true,
        ]);

        $newValidEmail = 'valid.' . uniqid() . '@sekolah.test';

        $csvContent = implode("\n", [
            'nama,email,role',
            "Guru Baru,{$newValidEmail},guru",
            "Guru Duplikat,{$existingEmail},guru",
            "Role Salah,salah.role@sekolah.test,siswa",
        ]);

        $file = UploadedFile::fake()->createWithContent('test_partial.csv', $csvContent);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(2, $errors);

        // Only valid email is created
        $this->assertDatabaseHas('users', ['email' => $newValidEmail]);
        $this->assertDatabaseMissing('users', ['email' => 'salah.role@sekolah.test']);
    }

    public function test_admin_can_export_master_rekap_and_print_slips_from_session(): void
    {
        $credentials = [
            [
                'id' => 101,
                'name' => 'Drs. H. Mulyono',
                'email' => 'mulyono@sekolah.test',
                'role_name' => 'Guru',
                'role_slug' => 'guru',
                'plain_password' => 'Muly8429',
            ],
            [
                'id' => 102,
                'name' => 'Rina Wijaya',
                'email' => 'rina@sekolah.test',
                'role_name' => 'Tata Usaha',
                'role_slug' => 'tu',
                'plain_password' => 'Rina7392',
            ],
        ];

        session(['latest_import_credentials' => $credentials]);

        // 1. Export Rekap CSV
        $rekapResponse = $this->actingAs($this->admin)
            ->get(route('admin.users.import.export-rekap'));

        $rekapResponse->assertOk();
        $rekapContent = $rekapResponse->streamedContent();
        $this->assertStringContainsString('Drs. H. Mulyono', $rekapContent);
        $this->assertStringContainsString('Muly8429', $rekapContent);
        $this->assertStringContainsString('Rina Wijaya', $rekapContent);
        $this->assertStringContainsString('Rina7392', $rekapContent);

        // 2. Print Slips View
        $slipsResponse = $this->actingAs($this->admin)
            ->get(route('admin.users.import.print-slips'));

        $slipsResponse->assertOk();
        $slipsResponse->assertSee('Drs. H. Mulyono');
        $slipsResponse->assertSee('Muly8429');
        $slipsResponse->assertSee('Rina Wijaya');
        $slipsResponse->assertSee('Rina7392');
        $slipsResponse->assertSee('Kartu Akses Portal Sekolah');
        $slipsResponse->assertSee('window.print()', false);
    }
}
