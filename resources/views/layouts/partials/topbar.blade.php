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

        <div class="dropdown">
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
