<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\UserImportBatch;
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

    public function test_admin_can_clear_latest_import_banner(): void
    {
        session(['latest_import_credentials' => [['name' => 'Guru Test']]]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.import.clear-latest'));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertFalse(session()->has('latest_import_credentials'));
    }

    public function test_import_credentials_persist_across_session_loss_via_database_setting(): void
    {
        $credentials = [
            [
                'id' => 999,
                'name' => 'Guru Persisten',
                'email' => 'persisten@sekolah.test',
                'role_name' => 'Guru',
                'role_slug' => 'guru',
                'plain_password' => 'Guru1234',
            ],
        ];

        UserImportBatch::create([
            'batch_code' => 'BATCH-PERSIST-' . uniqid(),
            'file_name' => 'persist_test.csv',
            'total_accounts' => count($credentials),
            'credentials' => $credentials,
            'created_by' => $this->admin->id,
        ]);

        \App\Models\Setting::updateOrCreate(
            ['key' => 'latest_import_credentials'],
            ['value' => json_encode($credentials)]
        );
        \App\Models\Setting::clearCache();

        // Pastikan session kosong (seperti skenario setelah logout)
        session()->forget('latest_import_credentials');

        // Buka index dan pastikan tombol muncul dengan count
        $indexResponse = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Cetak Slip Terbaru');

        // Buka cetak slip
        $slipResponse = $this->actingAs($this->admin)->get(route('admin.users.import.print-slips'));
        $slipResponse->assertOk();
        $slipResponse->assertSee('Guru Persisten');
        $slipResponse->assertSee('Guru1234');
    }

    public function test_admin_can_delete_user_via_ajax_without_page_reload(): void
    {
        $userToDelete = User::create([
            'name' => 'User To Delete',
            'email' => 'delete.me.' . uniqid() . '@sekolah.test',
            'password' => Hash::make('password123'),
            'role_id' => $this->roleGuru->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.users.destroy', $userToDelete));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.users.destroy', $this->admin));

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Anda tidak bisa menghapus akun Anda sendiri.',
        ]);

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_deleting_already_deleted_user_returns_graceful_response(): void
    {
        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.users.destroy', ['user' => 999999]));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_admin_can_filter_users_by_role(): void
    {
        $uniqueTag = uniqid();
        $guruUser = User::create([
            'name' => "AAA Guru Spesifik {$uniqueTag}",
            'email' => "guru.filter.{$uniqueTag}@sekolah.test",
            'password' => Hash::make('password123'),
            'role_id' => $this->roleGuru->id,
            'is_active' => true,
        ]);

        $tuUser = User::create([
            'name' => "AAA TU Spesifik {$uniqueTag}",
            'email' => "tu.filter.{$uniqueTag}@sekolah.test",
            'password' => Hash::make('password123'),
            'role_id' => $this->roleTu->id,
            'is_active' => true,
        ]);

        // Filter role guru
        $responseGuru = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['role' => 'guru']), ['X-Requested-With' => 'XMLHttpRequest']);

        $responseGuru->assertOk();
        $responseGuru->assertSee("AAA Guru Spesifik {$uniqueTag}");
        $responseGuru->assertDontSee("AAA TU Spesifik {$uniqueTag}");

        // Filter role tu
        $responseTu = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['role' => 'tu']), ['X-Requested-With' => 'XMLHttpRequest']);

        $responseTu->assertOk();
        $responseTu->assertSee("AAA TU Spesifik {$uniqueTag}");
        $responseTu->assertDontSee("AAA Guru Spesifik {$uniqueTag}");

        // Filter role all
        $responseAll = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['role' => 'all']), ['X-Requested-With' => 'XMLHttpRequest']);

        $responseAll->assertOk();
        $responseAll->assertSee("AAA Guru Spesifik {$uniqueTag}");
        $responseAll->assertSee("AAA TU Spesifik {$uniqueTag}");
    }

    public function test_admin_can_search_users(): void
    {
        $uniqueTag = uniqid();
        $targetUser = User::create([
            'name' => "Budi Santoso {$uniqueTag}",
            'email' => "budi.santoso.{$uniqueTag}@sekolah.test",
            'password' => Hash::make('password123'),
            'role_id' => $this->roleGuru->id,
            'is_active' => true,
        ]);

        $otherUser = User::create([
            'name' => "Joko Anwar {$uniqueTag}",
            'email' => "joko.anwar.{$uniqueTag}@sekolah.test",
            'password' => Hash::make('password123'),
            'role_id' => $this->roleGuru->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => "Budi Santoso {$uniqueTag}"]), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertOk();
        $response->assertSee("Budi Santoso {$uniqueTag}");
        $response->assertDontSee("Joko Anwar {$uniqueTag}");
    }

    public function test_admin_can_view_import_history_and_batch_slips(): void
    {
        $batch = UserImportBatch::create([
            'batch_code' => 'BATCH-TEST-999',
            'file_name' => 'test_data.csv',
            'total_accounts' => 1,
            'credentials' => [
                [
                    'id' => 888,
                    'name' => 'Guru Arsip Testing',
                    'email' => 'arsip@sekolah.test',
                    'role_name' => 'Guru',
                    'role_slug' => 'guru',
                    'plain_password' => 'ArsipPass99',
                ],
            ],
            'created_by' => $this->admin->id,
        ]);

        // 1. History Page
        $historyResponse = $this->actingAs($this->admin)
            ->get(route('admin.users.import.history'));

        $historyResponse->assertOk();
        $historyResponse->assertSee('BATCH-TEST-999');
        $historyResponse->assertSee('test_data.csv');
        $historyResponse->assertSee('Riwayat & Arsip Import Akun');

        // 2. Specific Batch Slips
        $slipResponse = $this->actingAs($this->admin)
            ->get(route('admin.users.import.print-slips', $batch));

        $slipResponse->assertOk();
        $slipResponse->assertSee('Guru Arsip Testing');
        $slipResponse->assertSee('ArsipPass99');

        // 3. Specific Batch CSV Rekap
        $rekapResponse = $this->actingAs($this->admin)
            ->get(route('admin.users.import.export-rekap', $batch));

        $rekapResponse->assertOk();
        $rekapContent = $rekapResponse->streamedContent();
        $this->assertStringContainsString('Guru Arsip Testing', $rekapContent);
        $this->assertStringContainsString('ArsipPass99', $rekapContent);
    }

    public function test_admin_can_delete_import_batch_without_affecting_users(): void
    {
        $user = User::create([
            'name' => 'User Tetap Aman',
            'email' => 'aman.' . uniqid() . '@sekolah.test',
            'password' => Hash::make('password123'),
            'role_id' => $this->roleGuru->id,
            'is_active' => true,
        ]);

        $batch = UserImportBatch::create([
            'batch_code' => 'BATCH-TO-DELETE',
            'file_name' => 'delete_me.csv',
            'total_accounts' => 1,
            'credentials' => [
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_name' => 'Guru',
                    'role_slug' => 'guru',
                    'plain_password' => 'TempSecret123',
                ],
            ],
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.users.import.batches.destroy', $batch));

        $response->assertRedirect(route('admin.users.import.history'));
        $response->assertSessionHas('success');

        // Batch is deleted from archives
        $this->assertDatabaseMissing('user_import_batches', ['id' => $batch->id]);

        // But user account still exists in users table!
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
