<?php

namespace Tests\Feature;

use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FinanceCategoryManagementTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'Super Administrator']
        );

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_finance_test@test.sch.id'],
            [
                'name'      => 'Test Admin Finance',
                'password'  => bcrypt('password'),
                'role_id'   => $role->id,
                'is_active' => true,
            ]
        );
    }

    public function test_can_view_finance_categories_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.finance.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Kategori Keuangan');
        $response->assertSee('Kategori Pemasukan');
        $response->assertSee('Kategori Pengeluaran');
    }

    public function test_can_create_new_finance_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.finance.categories.store'), [
            'name'        => 'Kategori Testing Baru',
            'type'        => 'income',
            'description' => 'Keterangan untuk kategori testing baru',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('finance_categories', [
            'name'        => 'Kategori Testing Baru',
            'type'        => 'income',
            'description' => 'Keterangan untuk kategori testing baru',
            'is_active'   => 1,
        ]);
    }

    public function test_cannot_create_duplicate_category_in_same_type(): void
    {
        FinanceCategory::create([
            'name'      => 'Kategori Duplikat Test',
            'type'      => 'expense',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.finance.categories.store'), [
            'name' => 'Kategori Duplikat Test',
            'type' => 'expense',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_can_update_non_system_category(): void
    {
        $category = FinanceCategory::create([
            'name'        => 'Kategori Lama',
            'type'        => 'expense',
            'description' => 'Keterangan lama',
            'is_active'   => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.finance.categories.update', $category), [
            'name'        => 'Kategori Baru Diperbarui',
            'description' => 'Keterangan sudah diperbarui',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $category->refresh();
        $this->assertEquals('Kategori Baru Diperbarui', $category->name);
        $this->assertEquals('Keterangan sudah diperbarui', $category->description);
    }

    public function test_can_update_system_category_description_without_name(): void
    {
        $systemCategory = FinanceCategory::whereNotNull('system_key')->first();
        if (! $systemCategory) {
            $systemCategory = FinanceCategory::create([
                'name'        => 'Saldo Awal Sistem',
                'type'        => 'income',
                'system_key'  => 'saldo_awal_test',
                'is_active'   => true,
            ]);
        }

        $origName = $systemCategory->name;

        $response = $this->actingAs($this->admin)->put(route('admin.finance.categories.update', $systemCategory), [
            'description' => 'Deskripsi sistem diperbarui',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $systemCategory->refresh();
        $this->assertEquals($origName, $systemCategory->name);
        $this->assertEquals('Deskripsi sistem diperbarui', $systemCategory->description);
    }

    public function test_can_toggle_category_active_status(): void
    {
        $category = FinanceCategory::create([
            'name'      => 'Kategori Toggle Test',
            'type'      => 'income',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.finance.categories.toggle', $category));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $category->refresh();
        $this->assertFalse($category->is_active);

        $response2 = $this->actingAs($this->admin)->patch(route('admin.finance.categories.toggle', $category));
        $category->refresh();
        $this->assertTrue($category->is_active);
    }

    public function test_cannot_delete_system_category(): void
    {
        $systemCategory = FinanceCategory::whereNotNull('system_key')->first();
        if (! $systemCategory) {
            $systemCategory = FinanceCategory::create([
                'name'        => 'Kategori Sistem Penting',
                'type'        => 'income',
                'system_key'  => 'test_system_key',
                'is_active'   => true,
            ]);
        }

        $response = $this->actingAs($this->admin)->delete(route('admin.finance.categories.destroy', $systemCategory));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('finance_categories', ['id' => $systemCategory->id]);
    }

    public function test_can_delete_category_with_zero_transactions(): void
    {
        $category = FinanceCategory::create([
            'name'        => 'Kategori Nol Transaksi',
            'type'        => 'expense',
            'description' => 'Siap dihapus',
            'is_active'   => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.finance.categories.destroy', $category));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('finance_categories', ['id' => $category->id]);
    }

    public function test_can_delete_category_with_transactions_and_reassigns_to_fallback(): void
    {
        // Pastikan ada fallback kategori
        $fallback = FinanceCategory::firstOrCreate(
            ['name' => 'Pengeluaran Lainnya', 'type' => 'expense'],
            ['description' => 'Fallback pengeluaran', 'is_active' => true]
        );

        $category = FinanceCategory::create([
            'name'        => 'Kategori Hapus Ada Transaksi',
            'type'        => 'expense',
            'description' => 'Akan dihapus dengan migrasi transaksi',
            'is_active'   => true,
        ]);

        // Buat transaksi dengan kategori ini
        $tx = FinanceTransaction::create([
            'number'              => 'EXP-TEST-' . uniqid(),
            'type'                => 'expense',
            'finance_category_id' => $category->id,
            'transaction_date'    => now()->toDateString(),
            'amount'              => 500000,
            'description'         => 'Beli perlengkapan test',
            'status'              => 'posted',
            'payment_method'      => 'tunai',
        ]);

        $this->assertEquals($category->id, $tx->finance_category_id);

        $response = $this->actingAs($this->admin)->delete(route('admin.finance.categories.destroy', $category));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Kategori lama sudah terhapus
        $this->assertDatabaseMissing('finance_categories', ['id' => $category->id]);

        // Transaksi tidak hilang, namun telah dialihkan ke fallback kategori
        $tx->refresh();
        $this->assertNotEquals($category->id, $tx->finance_category_id);
        $this->assertNotNull($tx->finance_category_id);
    }

    public function test_ajax_delete_returns_json_response(): void
    {
        $category = FinanceCategory::create([
            'name'      => 'Kategori Ajax Delete',
            'type'      => 'income',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->deleteJson(route('admin.finance.categories.destroy', $category));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseMissing('finance_categories', ['id' => $category->id]);
    }
}
