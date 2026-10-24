<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();

        // Data contoh hanya dibuat sekali, dan hanya kalau belum ada transaksi manual.
        if (! FinanceTransaction::whereNull('billing_record_id')->exists()) {
            $this->seedDemoTransactions();
        }
    }

    private function seedCategories(): void
    {
        // [nama, jenis, system_key, keterangan]
        $categories = [
            // PEMASUKAN
            ['Saldo Awal / Kas Awal',                'income',  'saldo_awal',     'Saldo pembukaan kas saat mulai memakai sistem'],
            ['Pembayaran Tagihan Siswa (SPP & Iuran)', 'income', 'tagihan_siswa',  'Otomatis dari modul Tagihan Siswa saat pembayaran dikonfirmasi'],
            ['Dana BOS',                             'income',  null,             'Bantuan Operasional Sekolah dari pemerintah pusat'],
            ['Dana BOP / Hibah Daerah',              'income',  null,             'Bantuan operasional / hibah dari pemerintah daerah'],
            ['Sumbangan & Donasi',                   'income',  null,             'Sumbangan komite, orang tua, alumni, atau pihak lain'],
            ['Uang Pendaftaran PPDB',                'income',  null,             'Biaya pendaftaran / daftar ulang siswa baru'],
            ['Pemasukan Kegiatan & Usaha',           'income',  null,             'Kantin, koperasi, sewa aula, bazar, dan sejenisnya'],
            ['Pemasukan Lainnya',                    'income',  null,             null],

            // PENGELUARAN
            ['Gaji & Honor',                         'expense', null,             'Gaji pegawai, honor guru, dan tunjangan'],
            ['Perbaikan & Pemeliharaan Sarana',      'expense', null,             'Perbaikan meja, kursi, AC, proyektor, atap, dan sarana lain yang rusak'],
            ['Pengadaan Barang & Peralatan',         'expense', null,             'Pembelian meja, kursi, komputer, alat lab, dan peralatan baru'],
            ['Alat Tulis Kantor & Perlengkapan',     'expense', null,             'ATK, kertas, tinta, perlengkapan kebersihan'],
            ['Listrik, Air & Internet',              'expense', null,             'Tagihan utilitas bulanan'],
            ['Kegiatan Siswa & Ekstrakurikuler',     'expense', null,             'OSIS, ekskul, lomba, dan kegiatan kesiswaan'],
            ['Kegiatan Akademik & Ujian',            'expense', null,             'Penyelenggaraan ujian, remedial, dan kegiatan belajar mengajar'],
            ['Konsumsi & Rapat',                     'expense', null,             'Konsumsi rapat, tamu, dan kegiatan sekolah'],
            ['Transportasi & Perjalanan Dinas',      'expense', null,             null],
            ['Pengeluaran Lainnya',                  'expense', null,             null],
        ];

        foreach ($categories as [$name, $type, $systemKey, $description]) {
            FinanceCategory::updateOrCreate(
                ['name' => $name, 'type' => $type],
                ['system_key' => $systemKey, 'description' => $description, 'is_active' => true]
            );
        }
    }

    private function seedDemoTransactions(): void
    {
        $adminId = User::whereHas('role', fn ($q) => $q->where('slug', 'tu'))->value('id')
            ?? User::whereHas('role', fn ($q) => $q->where('slug', 'super-admin'))->value('id');

        $yearId  = AcademicYear::active()?->id;
        $roomId  = Room::where('is_active', true)->orderBy('code')->value('id');
        $cat     = fn (string $name) => FinanceCategory::where('name', $name)->value('id');
        $year    = now()->year;

        // [bulan, hari, jenis, kategori, uraian, pihak, nominal, [barang, jumlah, harga satuan]]
        $rows = [
            [1,  3,  'income',  'Saldo Awal / Kas Awal',                 'Saldo awal kas tahun anggaran',              'Kas tahun lalu',       25000000],
            [1,  10, 'income',  'Dana BOS',                              'Dana BOS Tahap 1',                           'Kemendikdasmen',       60000000],
            [1,  25, 'expense', 'Gaji & Honor',                          'Honor guru tidak tetap bulan Januari',       'Guru honorer',         12000000],
            [2,  5,  'expense', 'Listrik, Air & Internet',               'Listrik & internet bulan Januari',           'PLN & ISP',             3500000],
            [2,  14, 'income',  'Sumbangan & Donasi',                    'Donasi komite untuk perbaikan sarana',       'Komite Sekolah',        8000000],
            [3,  8,  'expense', 'Perbaikan & Pemeliharaan Sarana',       'Perbaikan kursi siswa yang rusak',           'Bengkel Mebel Jaya',    1800000, ['Kursi siswa', 12, 150000]],
            [3,  20, 'expense', 'Alat Tulis Kantor & Perlengkapan',      'Pembelian kertas HVS dan tinta printer',     'Toko ATK Sentosa',       950000],
            [4,  2,  'expense', 'Pengadaan Barang & Peralatan',          'Pengadaan meja siswa baru',                  'CV Mebel Nusantara',    7500000, ['Meja siswa', 25, 300000]],
            [4,  18, 'income',  'Pemasukan Kegiatan & Usaha',            'Sewa aula untuk kegiatan eksternal',         'Yayasan Mitra',         2500000],
            [5,  6,  'expense', 'Kegiatan Siswa & Ekstrakurikuler',      'Dukungan lomba antar sekolah',               'OSIS',                  2200000],
            [5,  27, 'expense', 'Perbaikan & Pemeliharaan Sarana',       'Servis proyektor dan AC ruang kelas',        'Teknisi Elektronik',    2400000, ['Servis proyektor & AC', 4, 600000]],
            [6,  9,  'income',  'Dana BOP / Hibah Daerah',               'Hibah operasional daerah semester 1',        'Pemda',                15000000],
            [6,  24, 'expense', 'Kegiatan Akademik & Ujian',             'Penyelenggaraan ujian akhir semester',       'Panitia ujian',         4100000],
            [7,  7,  'expense', 'Perbaikan & Pemeliharaan Sarana',       'Perbaikan meja dan kursi ruang perpustakaan', 'Bengkel Mebel Jaya',    2100000, ['Meja & kursi baca', 7, 300000]],
            [8,  11, 'income',  'Uang Pendaftaran PPDB',                 'Biaya daftar ulang siswa baru',              'Orang tua calon siswa', 9000000],
            [9,  4,  'expense', 'Konsumsi & Rapat',                      'Konsumsi rapat awal tahun ajaran',           'Katering Bu Sari',       1250000],
            [10, 6,  'income',  'Dana BOS',                              'Dana BOS Tahap 2',                           'Kemendikdasmen',       60000000],
        ];

        foreach ($rows as $row) {
            [$month, $day, $type, $categoryName, $description, $party, $amount] = $row;
            $item = $row[7] ?? null;

            $date = Carbon::create($year, $month, $day);
            if ($date->isFuture()) {
                continue;
            }

            FinanceTransaction::create([
                'number'              => FinanceTransaction::generateNumber($type, $date),
                'type'                => $type,
                'finance_category_id' => $cat($categoryName),
                'transaction_date'    => $date->toDateString(),
                'amount'              => $amount,
                'description'         => $description,
                'party'               => $party,
                'payment_method'      => $type === 'income' ? 'transfer' : 'tunai',
                'item_name'           => $item[0] ?? null,
                'quantity'            => $item[1] ?? null,
                'unit_price'          => $item[2] ?? null,
                'room_id'             => $item ? $roomId : null,
                'academic_year_id'    => $yearId,
                'status'              => 'posted',
                'created_by'          => $adminId,
            ]);
        }
    }
}