<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // CMS & Website Publik
            ['name' => 'Kelola Konten CMS', 'slug' => 'cms.manage', 'module' => 'CMS'],
            ['name' => 'Kelola PPDB Online', 'slug' => 'ppdb.manage', 'module' => 'PPDB'],
            ['name' => 'Lihat Status PPDB', 'slug' => 'ppdb.view', 'module' => 'PPDB'],

            // Master Data Akademik
            ['name' => 'Kelola Master Data Akademik', 'slug' => 'akademik.manage', 'module' => 'Akademik'],

            // Akademik
            ['name' => 'Input Nilai', 'slug' => 'nilai.input', 'module' => 'Akademik'],
            ['name' => 'Approve Nilai', 'slug' => 'nilai.approve', 'module' => 'Akademik'],
            ['name' => 'Lihat Nilai', 'slug' => 'nilai.view', 'module' => 'Akademik'],

            // Presensi
            ['name' => 'Kelola Presensi (QR)', 'slug' => 'presensi.manage', 'module' => 'Presensi'],
            ['name' => 'Lihat Presensi', 'slug' => 'presensi.view', 'module' => 'Presensi'],

            // TU
            ['name' => 'Kelola Persuratan', 'slug' => 'persuratan.manage', 'module' => 'TU'],
            ['name' => 'Kelola Inventaris', 'slug' => 'inventaris.manage', 'module' => 'TU'],
            ['name' => 'Kelola Tagihan Siswa', 'slug' => 'billing.manage', 'module' => 'TU'],
            ['name' => 'Kelola Rapor Digital', 'slug' => 'rapor.manage', 'module' => 'TU'],

            // Biodata Siswa
            ['name' => 'Kelola Biodata Siswa', 'slug' => 'siswa.manage', 'module' => 'Siswa'],

            // Pengumuman Internal
            ['name' => 'Kelola Pengumuman', 'slug' => 'pengumuman.manage', 'module' => 'Pengumuman'],

            // Izin Siswa
            ['name' => 'Proses Izin Siswa', 'slug' => 'izin.manage', 'module' => 'Akademik'],

            // Super Admin
            ['name' => 'Kelola User & Permission', 'slug' => 'user.manage', 'module' => 'Admin'],
            ['name' => 'Lihat Audit Log', 'slug' => 'audit.view', 'module' => 'Admin'],
            ['name' => 'Kelola Pengaturan Global', 'slug' => 'settings.manage', 'module' => 'Admin'],

            // Kepala Sekolah
            ['name' => 'Lihat Dashboard Laporan', 'slug' => 'dashboard.report.view', 'module' => 'Laporan'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }

        // Mapping permission -> role
        $map = [
            'super-admin' => Permission::pluck('slug')->all(),
            'guru'        => ['nilai.input', 'nilai.view', 'presensi.manage', 'presensi.view', 'izin.manage'],
            'siswa'       => ['nilai.view', 'presensi.view'],
            'ortu'        => ['nilai.view', 'presensi.view'],
            'tu'          => [
                'persuratan.manage', 'inventaris.manage',
                'ppdb.manage', 'ppdb.view',
                'siswa.manage',
                'billing.manage',
                'rapor.manage',
                'pengumuman.manage',
            ],
            'kepsek'      => ['dashboard.report.view', 'nilai.approve', 'audit.view', 'ppdb.view', 'pengumuman.manage'],
        ];

        foreach ($map as $roleSlug => $permissionSlugs) {
            $role = Role::where('slug', $roleSlug)->first();
            if (! $role) {
                continue;
            }
            $permissionIds = Permission::whereIn('slug', $permissionSlugs)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }
    }
}