<?php

namespace Tests\Feature;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected InventoryCategory $category;
    protected Room $room1;
    protected Room $room2;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'sekolah_modern',
        ]);

        $role = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'Super Administrator']
        );

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_inventory_test@test.sch.id'],
            [
                'name'      => 'Test Admin Inventory',
                'password'  => bcrypt('password'),
                'role_id'   => $role->id,
                'is_active' => true,
            ]
        );

        $this->category = InventoryCategory::firstOrCreate(
            ['slug' => 'mebel-furniture'],
            ['name' => 'Mebel & Kursi Meja', 'description' => 'Aset mebeler']
        );

        $this->room1 = Room::firstOrCreate(
            ['code' => 'R-TEST-1'],
            ['name' => 'Ruang Kelas 10-A', 'is_active' => true]
        );

        $this->room2 = Room::firstOrCreate(
            ['code' => 'R-TEST-2'],
            ['name' => 'Lab Komputer 1', 'is_active' => true]
        );
    }

    public function test_guest_cannot_access_inventory(): void
    {
        $response = $this->get(route('admin.inventory.index'));
        $response->assertRedirect('/login');
    }

    public function test_authorized_user_can_view_inventory_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.inventory.index'));

        $response->assertStatus(200);
        $response->assertSee('Inventaris &amp; Sarana Prasarana', false);
        $response->assertSee('Tambah Barang Baru');
    }

    public function test_authorized_user_can_create_inventory_item(): void
    {
        $payload = [
            'name'                  => 'Kursi Guru Kayu Jati',
            'inventory_category_id' => $this->category->id,
            'room_id'               => $this->room1->id,
            'quantity'              => 5,
            'unit'                  => 'unit',
            'condition'             => 'good',
            'source_of_fund'        => 'BOS 2026',
            'procurement_year'      => 2026,
            'price_per_unit'        => 350000,
            'notes'                 => 'Pengadaan tahap awal tahun ajaran',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.inventory.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inventory_items', [
            'name'                  => 'Kursi Guru Kayu Jati',
            'inventory_category_id' => $this->category->id,
            'room_id'               => $this->room1->id,
            'quantity'              => 5,
            'unit'                  => 'unit',
            'condition'             => 'good',
        ]);

        $item = InventoryItem::where('name', 'Kursi Guru Kayu Jati')->first();
        $this->assertNotNull($item);
        $this->assertMatchesRegularExpression('/^INV-\d{4}-\d{4}$/', $item->code);
    }

    public function test_authorized_user_can_view_inventory_detail(): void
    {
        $item = InventoryItem::create([
            'code'                  => InventoryItem::generateCode(),
            'name'                  => 'Proyektor Epson EB-X500',
            'inventory_category_id' => $this->category->id,
            'room_id'               => $this->room1->id,
            'quantity'              => 1,
            'unit'                  => 'unit',
            'condition'             => 'good',
            'source_of_fund'        => 'BOS',
            'procurement_year'      => 2026,
            'price_per_unit'        => 5500000,
            'created_by'            => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.inventory.show', $item));

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee($item->code);
        $response->assertSee('Mutasi Ruangan');
        $response->assertSee('Ubah Data');
        $response->assertSee('Hapus');
    }

    public function test_authorized_user_can_update_inventory_item(): void
    {
        $item = InventoryItem::create([
            'code'                  => InventoryItem::generateCode(),
            'name'                  => 'Whiteboard Portable',
            'inventory_category_id' => $this->category->id,
            'room_id'               => $this->room1->id,
            'quantity'              => 2,
            'unit'                  => 'buah',
            'condition'             => 'good',
            'created_by'            => $this->admin->id,
        ]);

        $updateData = [
            'name'                  => 'Whiteboard Portable Premium',
            'inventory_category_id' => $this->category->id,
            'room_id'               => $this->room1->id,
            'quantity'              => 3,
            'unit'                  => 'buah',
            'condition'             => 'light_damage',
            'source_of_fund'        => 'Komite Sekolah',
            'procurement_year'      => 2026,
            'price_per_unit'        => 850000,
            'notes'                 => 'Roda sedikit longgar',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.inventory.update', $item), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $item->refresh();
        $this->assertEquals('Whiteboard Portable Premium', $item->name);
        $this->assertEquals(3, $item->quantity);
        $this->assertEquals('light_damage', $item->condition);
    }

    public function test_authorized_user_can_mutate_inventory_item(): void
    {
        $item = InventoryItem::create([
            'code'                  => InventoryItem::generateCode(),
            'name'                  => 'Meja Guru',
            'inventory_category_id' => $this->category->id,
            'room_id'               => $this->room1->id,
            'quantity'              => 1,
            'unit'                  => 'unit',
            'condition'             => 'good',
            'notes'                 => 'Kondisi mulus awal beli',
            'created_by'            => $this->admin->id,
        ]);

        $mutateData = [
            'room_id'   => $this->room2->id,
            'condition' => 'light_damage',
            'notes'     => 'Dipindahkan ke Lab Komputer untuk instruktur',
        ];

        $response = $this->actingAs($this->admin)->patch(route('admin.inventory.mutate', $item), $mutateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $item->refresh();
        $this->assertEquals($this->room2->id, $item->room_id);
        $this->assertEquals('light_damage', $item->condition);
        $this->assertStringContainsString('Dipindahkan ke Lab Komputer untuk instruktur', $item->notes);
        $this->assertStringContainsString('[Catatan Mutasi', $item->notes);
    }

    public function test_authorized_user_can_delete_inventory_item(): void
    {
        $item = InventoryItem::create([
            'code'                  => InventoryItem::generateCode(),
            'name'                  => 'Aset Rusak Total Untuk Dihapus',
            'inventory_category_id' => $this->category->id,
            'room_id'               => null,
            'quantity'              => 1,
            'unit'                  => 'unit',
            'condition'             => 'heavy_damage',
            'created_by'            => $this->admin->id,
        ]);

        $itemId = $item->id;

        $response = $this->actingAs($this->admin)->delete(route('admin.inventory.destroy', $item));

        $response->assertRedirect(route('admin.inventory.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('inventory_items', [
            'id' => $itemId,
        ]);
    }

    public function test_authorized_user_can_quick_store_category(): void
    {
        $uniqueName = 'Sarana Kebersihan ' . uniqid();
        $response = $this->actingAs($this->admin)->postJson(route('admin.inventory.categories.quick-store'), [
            'name' => $uniqueName,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'is_new'  => true,
            'data'    => [
                'name' => $uniqueName,
            ],
        ]);

        $this->assertDatabaseHas('inventory_categories', [
            'name' => $uniqueName,
        ]);
    }

    public function test_quick_store_category_returns_existing_if_duplicate(): void
    {
        $response = $this->actingAs($this->admin)->postJson(route('admin.inventory.categories.quick-store'), [
            'name' => 'Mebel & Kursi Meja',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_new'  => false,
            'data'    => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ],
        ]);
    }

    public function test_authorized_user_can_quick_store_room_with_custom_code(): void
    {
        $uniqueCode = 'R-TEST-' . rand(1000, 9999);
        $uniqueName = 'Gudang Sarpras ' . rand(100, 999);

        $response = $this->actingAs($this->admin)->postJson(route('admin.inventory.rooms.quick-store'), [
            'name' => $uniqueName,
            'code' => $uniqueCode,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'is_new'  => true,
            'data'    => [
                'name'  => $uniqueName,
                'code'  => $uniqueCode,
                'label' => "{$uniqueName} ({$uniqueCode})",
            ],
        ]);

        $this->assertDatabaseHas('rooms', [
            'name' => $uniqueName,
            'code' => $uniqueCode,
        ]);
    }

    public function test_authorized_user_can_quick_store_room_with_auto_generated_code(): void
    {
        $uniqueName = 'Ruang Robotika ' . rand(100, 999);

        $response = $this->actingAs($this->admin)->postJson(route('admin.inventory.rooms.quick-store'), [
            'name' => $uniqueName,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'is_new'  => true,
        ]);

        $room = Room::where('name', $uniqueName)->first();
        $this->assertNotNull($room);
        $this->assertStringStartsWith('R-', $room->code);
    }
}
