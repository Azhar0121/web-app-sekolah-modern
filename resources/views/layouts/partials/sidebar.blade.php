@php
    $user = auth()->user();

    $menu = [
        [
            'label' => null,
            'items' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'icon' => 'dashboard', 'show' => $user->hasRole('super-admin')],
            ],
        ],
        [
            'label' => 'Manajemen Akses',
            'items' => [
                ['label' => 'Kelola User', 'route' => 'admin.users.index', 'pattern' => 'admin.users.*', 'icon' => 'users', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Role & Permission', 'route' => 'admin.roles.index', 'pattern' => 'admin.roles.*', 'icon' => 'shield', 'show' => $user->hasRole('super-admin')],
            ],
        ],
        [
            'label' => 'Master Data Akademik',
            'items' => [
                ['label' => 'Tahun Ajaran & Semester', 'route' => 'admin.academic-years.index', 'pattern' => 'admin.academic-years.*', 'icon' => 'calendar', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Mata Pelajaran', 'route' => 'admin.subjects.index', 'pattern' => 'admin.subjects.*', 'icon' => 'book', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Kelas', 'route' => 'admin.classrooms.index', 'pattern' => 'admin.classrooms.*', 'icon' => 'building', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Penugasan Mengajar', 'route' => 'admin.teaching-assignments.index', 'pattern' => 'admin.teaching-assignments.*', 'icon' => 'user-check', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Penempatan Siswa', 'route' => 'admin.student-placements.index', 'pattern' => 'admin.student-placements.*', 'icon' => 'user-group', 'show' => $user->hasRole('super-admin')],
            ],
        ],
        [
            'label' => 'PPDB',
            'items' => [
                ['label' => 'Kelola Pendaftaran', 'route' => 'admin.ppdb.index', 'pattern' => 'admin.ppdb.*', 'icon' => 'file-text', 'show' => $user->hasPermission('ppdb.manage')],
            ],
        ],
    ];
@endphp

<div class="sidebar-backdrop" data-sidebar-backdrop></div>

<aside class="app-sidebar" data-sidebar>
    <nav class="d-flex flex-column gap-1">
        @foreach ($menu as $group)
            @php($visibleItems = collect($group['items'])->where('show', true))
            @continue($visibleItems->isEmpty())

            @if ($group['label'])
                <div class="sidebar-group-label">{{ $group['label'] }}</div>
            @endif

            @foreach ($visibleItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="sidebar-link {{ request()->routeIs($item['pattern']) ? 'active' : '' }}">
                    <x-icon :name="$item['icon']" :size="17" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        @endforeach
    </nav>
</aside>
