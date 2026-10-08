<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

@php
    $user = auth()->user();

    $menu = [
        [
            'label' => null,
            'items' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'icon' => 'bi-grid-1x2-fill', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Dashboard', 'route' => 'guru.dashboard', 'pattern' => 'guru.dashboard', 'icon' => 'bi-grid-1x2-fill', 'show' => $user->hasRole('guru')],
                ['label' => 'Dashboard', 'route' => 'tu.dashboard', 'pattern' => 'tu.dashboard', 'icon' => 'bi-grid-1x2-fill', 'show' => $user->hasRole('tu')],
                ['label' => 'Dashboard', 'route' => 'kepsek.dashboard', 'pattern' => 'kepsek.dashboard', 'icon' => 'bi-grid-1x2-fill', 'show' => $user->hasRole('kepsek')],
            ],
        ],
        [
            'label' => 'Portal Guru',
            'items' => [
                ['label' => 'Jadwal Mengajar', 'route' => 'guru.schedule.index', 'pattern' => 'guru.schedule.*', 'icon' => 'bi-calendar3', 'show' => $user->hasRole('guru')],
                ['label' => 'Presensi Kelas', 'route' => 'guru.attendance.index', 'pattern' => 'guru.attendance.*', 'icon' => 'bi-calendar2-check', 'show' => $user->hasRole('guru')],
                ['label' => 'Daftar Siswa', 'route' => 'guru.student-profile.index', 'pattern' => 'guru.student-profile.*', 'icon' => 'bi-people', 'show' => $user->hasRole('guru')],
                ['label' => 'Izin Siswa', 'route' => 'guru.leave-requests.index', 'pattern' => 'guru.leave-requests.*', 'icon' => 'bi-file-earmark-medical', 'show' => $user->hasRole('guru')],
                ['label' => 'Komunikasi Ortu', 'route' => 'guru.communication.index', 'pattern' => 'guru.communication.*', 'icon' => 'bi-chat-dots', 'show' => $user->hasRole('guru')],
            ],
        ],
        [
            'label' => 'Manajemen Akses',
            'items' => [
                ['label' => 'Kelola User', 'route' => 'admin.users.index', 'pattern' => 'admin.users.*', 'icon' => 'bi-people-fill', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Role & Permission', 'route' => 'admin.roles.index', 'pattern' => 'admin.roles.*', 'icon' => 'bi-shield-lock-fill', 'show' => $user->hasRole('super-admin')],
            ],
        ],
        [
            'label' => 'Master Data Akademik',
            'items' => [
                ['label' => 'Tahun Ajaran & Semester', 'route' => 'admin.academic-years.index', 'pattern' => 'admin.academic-years.*', 'icon' => 'bi-calendar-event-fill', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Jurusan / Keahlian', 'route' => 'admin.departments.index', 'pattern' => 'admin.departments.*', 'icon' => 'bi-mortarboard-fill', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Ruangan & Fasilitas', 'route' => 'admin.rooms.index', 'pattern' => 'admin.rooms.*', 'icon' => 'bi-building', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Mata Pelajaran', 'route' => 'admin.subjects.index', 'pattern' => 'admin.subjects.*', 'icon' => 'bi-book-half', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Kelas', 'route' => 'admin.classrooms.index', 'pattern' => 'admin.classrooms.*', 'icon' => 'bi-person-video2', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Penugasan Mengajar', 'route' => 'admin.teaching-assignments.index', 'pattern' => 'admin.teaching-assignments.*', 'icon' => 'bi-person-badge', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Penempatan Siswa', 'route' => 'admin.student-placements.index', 'pattern' => 'admin.student-placements.*', 'icon' => 'bi-person-plus', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Jadwal Pelajaran', 'route' => 'admin.schedules.index', 'pattern' => 'admin.schedules.*', 'icon' => 'bi-clock-history', 'show' => $user->hasRole('super-admin')],
            ],
        ],
        [
            'label' => 'Operasional & Tata Usaha',
            'items' => [
                ['label' => 'Pendaftaran PPDB', 'route' => 'admin.ppdb.index', 'pattern' => 'admin.ppdb.*', 'icon' => 'bi-person-lines-fill', 'show' => $user->hasRole('super-admin') || $user->hasPermission('ppdb.view') || $user->hasPermission('ppdb.manage')],
                ['label' => 'Kelola Pengumuman', 'route' => 'admin.announcements.index', 'pattern' => 'admin.announcements.*', 'icon' => 'bi-megaphone-fill', 'show' => $user->hasRole('super-admin') || $user->hasRole('kepsek') || $user->hasRole('tu')],
                ['label' => 'Persuratan Digital', 'route' => 'admin.correspondences.index', 'pattern' => 'admin.correspondences.*', 'icon' => 'bi-envelope-paper-fill', 'show' => $user->hasRole('super-admin') || $user->hasRole('tu') || $user->hasPermission('persuratan.manage')],
                ['label' => 'Biodata Siswa', 'route' => 'admin.student-profiles.index', 'pattern' => 'admin.student-profiles.*', 'icon' => 'bi-person-vcard-fill', 'show' => $user->hasRole('super-admin') || $user->hasRole('tu') || $user->hasPermission('siswa.manage')],
                ['label' => 'Tagihan Siswa', 'route' => 'admin.billing.index', 'pattern' => 'admin.billing.*', 'icon' => 'bi-credit-card-2-front-fill', 'show' => $user->hasRole('super-admin') || $user->hasRole('tu') || $user->hasPermission('billing.manage')],
            ],
        ],
        [
            'label' => 'System & Core Engine',
            'items' => [
                ['label' => 'Pengaturan Global', 'route' => 'admin.settings.index', 'pattern' => 'admin.settings.*', 'icon' => 'bi-gear-fill', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Media Library', 'route' => 'admin.media.index', 'pattern' => 'admin.media.*', 'icon' => 'bi-images', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Konfigurasi SEO', 'route' => 'admin.seo.index', 'pattern' => 'admin.seo.*', 'icon' => 'bi-globe2', 'show' => $user->hasRole('super-admin')],
                ['label' => 'Audit Log & Security', 'route' => 'admin.audit-logs.index', 'pattern' => 'admin.audit-logs.*', 'icon' => 'bi-shield-check', 'show' => $user->hasRole('super-admin') || $user->hasPermission('audit.view')],
                ['label' => 'Backup & Pemulihan', 'route' => 'admin.backups.index', 'pattern' => 'admin.backups.*', 'icon' => 'bi-database-down', 'show' => $user->hasRole('super-admin')],
            ],
        ],
    ];
@endphp          

<div class="sidebar-backdrop" data-sidebar-backdrop></div>

<aside class="app-sidebar" data-sidebar>
    <div class="d-flex flex-column">
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
                        <i class="bi {{ $item['icon'] }}"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            @endforeach
        </nav>
    </div>
</aside>
