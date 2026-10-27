<?php

namespace Database\Seeders;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Mebel & Perlengkapan Kelas',
                'slug'        => 'mebel-dan-perlengkapan-kelas',
                'description' => 'Meja siswa, kursi siswa, meja guru, papan tulis, lemari kelas, dan podium.',
            ],
            [
                'name'        => 'Komputer & Perangkat IT',
                'slug'        => 'komputer-dan-perangkat-it',
                'description' => 'Komputer PC, laptop, monitor, proyektor LCD, printer, switch hub, dan UPS.',
            ],
            [
                'name'        => 'Peralatan Laboratorium',
                'slug'        => 'peralatan-laboratorium',
                'description' => 'Mikroskop, tabung reaksi, alat ukur listrik, osiloskop, dan kit praktikum sains.',
            ],
            [
                'name'        => 'Mesin & Alat Praktik Kejuruan',
                'slug'        => 'mesin-dan-alat-praktik-kejuruan',
                'description' => 'Mesin bubut, trainer otomotif, perlengkapan las, obeng set, dan perkakas bengkel.',
            ],
            [
                'name'        => 'Elektronik & Pendingin Ruangan',
                'slug'        => 'elektronik-dan-pendingin-ruangan',
                'description' => 'AC split, kipas angin, sound system aula, amplifier, dan televisi presentasi.',
            ],
            [
                'name'        => 'Sarana Olahraga & Ekstrakurikuler',
                'slug'        => 'sarana-olahraga-dan-ekstrakurikuler',
                'description' => 'Bola basket, bola voli, matras senam, raket bulutangkis, dan peralatan musik.',
            ],
        ];

        foreach ($categories as $cat) {
            InventoryCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name'], 'description' => $cat['description'], 'is_active' => true]
            );
        }

        // Tambahkan beberapa contoh data barang awal jika tabel masih kosong
        if (InventoryItem::count() === 0) {
            $admin = User::whereHas('role', fn ($q) => $q->whereIn('slug', ['tu', 'super-admin']))->first();
            $mebelCat = InventoryCategory::where('slug', 'mebel-dan-perlengkapan-kelas')->first();
            $itCat = InventoryCategory::where('slug', 'komputer-dan-perangkat-it')->first();
            $room = Room::first();

            if ($mebelCat && $room) {
                InventoryItem::create([
                    'code'                  => 'INV-2026-0001',
                    'name'                  => 'Meja Siswa Kayu Jati',
                    'inventory_category_id' => $mebelCat->id,
                    'room_id'               => $room->id,
                    'quantity'              => 36,
                    'unit'                  => 'unit',
                    'condition'             => 'good',
                    'source_of_fund'        => 'Dana BOS',
                    'procurement_year'      => 2025,
                    'price_per_unit'        => 350000,
                    'notes'                 => 'Standar ukuran siswa SMA/SMK dengan laci buku',
                    'created_by'            => $admin?->id,
                ]);

                InventoryItem::create([
                    'code'                  => 'INV-2026-0002',
                    'name'                  => 'Kursi Siswa Besi & Kayu',
                    'inventory_category_id' => $mebelCat->id,
                    'room_id'               => $room->id,
                    'quantity'              => 36,
                    'unit'                  => 'unit',
                    'condition'             => 'good',
                    'source_of_fund'        => 'Dana BOS',
                    'procurement_year'      => 2025,
                    'price_per_unit'        => 185000,
                    'notes'                 => 'Rangka besi pipa cat hitam doff',
                    'created_by'            => $admin?->id,
                ]);
            }

            if ($itCat && $room) {
                InventoryItem::create([
                    'code'                  => 'INV-2026-0003',
                    'name'                  => 'Proyektor Epson EB-X400 3300 Lumens',
                    'inventory_category_id' => $itCat->id,
                    'room_id'               => $room->id,
                    'quantity'              => 1,
                    'unit'                  => 'unit',
                    'condition'             => 'good',
                    'source_of_fund'        => 'Dana BOS Kinerja',
                    'procurement_year'      => 2024,
                    'price_per_unit'        => 5800000,
                    'notes'                 => 'Terpasang di bracket gantung plafon ruang kelas',
                    'created_by'            => $admin?->id,
                ]);
            }
        }
    }
}
