<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isKepsek = $user && $user->hasRole('kepsek');

        $eventFilter = $request->query('event');
        $roleFilter  = $request->query('role');
        $search      = $request->string('search')->trim()->toString();

        // Ambil daftar role untuk dropdown filter
        $rolesQuery = Role::query();
        if ($isKepsek) {
            // Pada akun/halaman Kepala Sekolah, role siswa tidak ditampilkan
            $rolesQuery->where('slug', '!=', 'siswa');
            if ($roleFilter === 'siswa') {
                $roleFilter = null;
            }
        }
        $roles = $rolesQuery->orderBy('id')->get();

        $logs = AuditLog::with(['user.role'])
            // Pada akun/halaman Kepala Sekolah, log aktivitas role siswa tidak tampil
            ->when($isKepsek, function ($q) {
                $q->whereDoesntHave('user.role', fn ($sub) => $sub->where('slug', 'siswa'));
            })
            ->when($eventFilter, fn ($q) => $q->where('event', $eventFilter))
            ->when($roleFilter, function ($q) use ($roleFilter) {
                $q->whereHas('user.role', fn ($sub) => $sub->where('slug', $roleFilter));
            })
            ->when($search, fn ($q) => $q->where(function ($sub) use ($search) {
                $sub->where('user_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.audit-logs.index', compact(
            'logs',
            'roles',
            'eventFilter',
            'roleFilter',
            'search',
            'isKepsek'
        ));
    }
}
