<header class="app-topbar">
    <div class="d-flex align-items-center justify-content-between">

        <div class="d-flex align-items-center gap-2">
            @isset($showSidebarToggle)
                <button type="button" class="sidebar-toggle-btn d-lg-none" data-sidebar-toggle>
                    <x-icon name="menu" :size="20" />
                </button>
            @endisset

            <div class="brand-mark">{{ strtoupper(substr(config('app.name'), 0, 1)) }}</div>

            <div class="brand-text d-none d-sm-block">
                <div class="brand-name">{{ config('app.name') }}</div>
                <div class="brand-tagline">Sistem Informasi Sekolah</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div class="dropdown">
                <button type="button" class="btn btn-link text-white position-relative p-1 text-decoration-none me-2"
                        id="notifBellDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-5"></i>
                    @php($unreadNotifs = auth()->user()?->unreadNotifications?->count() ?? 0)
                    @if ($unreadNotifs > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                            {{ $unreadNotifs }}
                        </span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow p-0 border-0" style="width: 300px; max-height: 380px; overflow-y: auto;">
                    <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <strong class="small text-dark mb-0">Pusat Notifikasi</strong>
                        @if ($unreadNotifs > 0)
                            <form action="{{ route('notifications.read-all') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 text-decoration-none text-primary" style="font-size: 0.75rem;">Tandai dibaca</button>
                            </form>
                        @endif
                    </div>
                    <div class="list-group list-group-flush small">
                        @forelse (auth()->user()?->notifications()->take(5)->get() ?? [] as $notif)
                            <a href="{{ $notif->data['url'] ?? '#' }}" class="list-group-item list-group-item-action p-3 {{ is_null($notif->read_at) ? 'bg-light border-start border-3 border-primary' : '' }}">
                                <div class="d-flex justify-content-between mb-1">
                                    <strong class="text-dark">{{ $notif->data['title'] ?? 'Notifikasi' }}</strong>
                                    <small class="text-muted" style="font-size:0.68rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="small text-muted mb-0">{{ $notif->data['message'] ?? '' }}</div>
                            </a>
                        @empty
                            <div class="p-3 text-center text-muted small">
                                <i class="bi bi-bell-slash fs-4 d-block mb-1 text-secondary"></i>
                                Belum ada notifikasi baru
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <button class="btn topbar-user-btn dropdown-toggle d-flex align-items-center gap-2" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="d-none d-md-flex flex-column align-items-start lh-sm">
                    <span class="small fw-semibold">{{ auth()->user()->name }}</span>
                    <span class="badge role-badge">{{ auth()->user()->role->name ?? '-' }}</span>
                </span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li>
                    <span class="dropdown-item-text small text-muted d-sm-none">
                        {{ auth()->user()->name }} &middot; {{ auth()->user()->role->name ?? '-' }}
                    </span>
                </li>
                <li><hr class="dropdown-divider d-sm-none"></li>
                <li>
                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2">
                            <x-icon name="log-out" :size="15" />
                            Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</header>
