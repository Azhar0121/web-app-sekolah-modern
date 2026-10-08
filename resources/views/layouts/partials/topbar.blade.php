<header class="app-topbar bg-white border-bottom sticky-top shadow-sm px-3 py-2" style="position: sticky; top: 0; z-index: 1030; height: 62px;">
    <div class="d-flex align-items-center justify-content-between h-100">

        {{-- LEFT: TOGGLE & BRAND --}}
        <div class="d-flex align-items-center gap-3">
            @isset($showSidebarToggle)
                <button type="button" class="btn btn-sm btn-light border-0 text-secondary p-2 rounded-2 topbar-btn d-lg-none" data-sidebar-toggle aria-label="Toggle Sidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
            @endisset

            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <div class="d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                     style="width: 36px; height: 36px; border-radius: 9px; background: linear-gradient(135deg, #071b35 0%, #1769d5 100%); font-size: 1rem;">
                    {{ strtoupper(substr(config('app.name'), 0, 1)) }}
                </div>

                <div class="d-none d-sm-block lh-sm">
                    <div class="fw-bold text-dark text-truncate" style="max-width: 240px; font-size: 0.95rem;">
                        {{ config('app.name') }}
                    </div>
                    <div class="text-muted" style="font-size: 0.72rem; letter-spacing: 0.02em;">
                        Sistem Informasi Manajemen Sekolah
                    </div>
                </div>
            </a>

            <div class="vr mx-2 d-none d-md-block opacity-25" style="height: 24px;"></div>

            <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-light text-secondary d-none d-md-inline-flex align-items-center gap-2 rounded-pill px-3 py-1 border topbar-btn" style="font-size: 0.78rem;">
                <i class="bi bi-globe2 text-primary"></i>
                <span>Lihat Website</span>
                <i class="bi bi-box-arrow-up-right text-muted" style="font-size: 0.7rem;"></i>
            </a>
        </div>

        {{-- RIGHT: NOTIFICATIONS & USER PROFILE --}}
        <div class="d-flex align-items-center gap-2">

            <div class="dropdown">
                <button type="button" class="btn btn-light rounded-circle text-secondary position-relative p-2 d-flex align-items-center justify-content-center border topbar-btn"
                        id="notifBellDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 38px; height: 38px;">
                    <i class="bi bi-bell fs-6"></i>
                    @php($unreadNotifs = auth()->user()?->unreadNotifications?->count() ?? 0)
                    @if ($unreadNotifs > 0)
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">Notifikasi Baru</span>
                        </span>
                    @endif
                </button>

                <div class="dropdown-menu dropdown-menu-end shadow-lg p-0 border border-slate-200 rounded-3" style="width: 320px; max-height: 420px; overflow-y: auto;">
                    <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <strong class="small text-dark mb-0">Notifikasi</strong>
                            @if ($unreadNotifs > 0)
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;">{{ $unreadNotifs }} baru</span>
                            @endif
                        </div>
                        @if ($unreadNotifs > 0)
                            <form action="{{ route('notifications.read-all') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 text-decoration-none text-primary fw-medium" style="font-size: 0.75rem;">Tandai dibaca</button>
                            </form>
                        @endif
                    </div>

                    <div class="list-group list-group-flush small">
                        @forelse (auth()->user()?->notifications()->take(5)->get() ?? [] as $notif)
                            <a href="{{ $notif->data['url'] ?? '#' }}" class="list-group-item list-group-item-action p-3 {{ is_null($notif->read_at) ? 'bg-primary bg-opacity-10 border-start border-3 border-primary' : '' }}">
                                <div class="d-flex justify-content-between mb-1">
                                    <strong class="text-dark">{{ $notif->data['title'] ?? 'Notifikasi' }}</strong>
                                    <small class="text-muted" style="font-size:0.68rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="small text-muted mb-0">{{ $notif->data['message'] ?? '' }}</div>
                            </a>
                        @empty
                            <div class="p-4 text-center text-muted small">
                                <i class="bi bi-bell-slash fs-3 d-block mb-1 text-secondary opacity-50"></i>
                                Belum ada notifikasi baru
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 py-1 px-2 rounded-pill border bg-white topbar-user-btn"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle shadow-sm"
                          style="width: 32px; height: 32px; background: linear-gradient(135deg, #071b35, #1769d5); font-size: 0.85rem;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <span class="d-none d-md-flex flex-column align-items-start lh-sm pe-1">
                        <span class="fw-semibold text-dark text-truncate" style="max-width: 130px; font-size: 0.825rem;">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="badge" style="background-color: #eaf3ff; color: #1769d5; border: 1px solid #bfdbfe; font-size: 0.65rem; font-weight: 600;">
                            {{ auth()->user()->role->name ?? 'User' }}
                        </span>
                    </span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-lg border border-slate-200 rounded-3 mt-1 py-1" style="min-width: 220px;">
                    <li class="px-3 py-2 bg-light border-bottom">
                        <div class="fw-bold text-dark small">{{ auth()->user()->name }}</div>
                        <div class="text-muted" style="font-size: 0.72rem;">{{ auth()->user()->email }}</div>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ url('/logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item-danger d-flex align-items-center gap-2 small py-2">
                                <i class="bi bi-box-arrow-right fs-6"></i>
                                <span>Keluar Sistem</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</header>
