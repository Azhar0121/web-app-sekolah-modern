<header class="admin-topbar">
<link rel="stylesheet" href="{{ asset('css/admin/topbar.css') }}?v={{ filemtime(public_path('css/admin/topbar.css')) }}">
    <div class="admin-topbar-inner">

        {{-- LEFT: SIDEBAR TOGGLE & BRAND --}}
        <div class="admin-topbar-left">

            @isset($showSidebarToggle)
                <button
                    type="button"
                    class="admin-icon-btn sidebar-toggle-btn d-lg-none"
                    data-sidebar-toggle
                    aria-label="Buka atau tutup sidebar"
                >
                    <i class="bi bi-list"></i>
                </button>
            @endisset

            {{-- BRAND SEKOLAH MODERN --}}
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">

                <div class="admin-brand-logo">
                    <svg
                        viewBox="0 0 64 64"
                        xmlns="http://www.w3.org/2000/svg"
                        role="img"
                        aria-label="Logo Sekolah Modern"
                    >
                        <path
                            d="M32 3 L56 11 V29
                               C56 44 46 55 32 61
                               C18 55 8 44 8 29 V11 Z"
                            fill="#ffffff"
                        />

                        <path
                            d="M32 9 L50 15 V29
                               C50 40 42 49 32 54
                               C22 49 14 40 14 29 V15 Z"
                            fill="#0f2747"
                        />

                        <path
                            d="M32 14 L34.5 20.5
                               L41.5 20.5 L36 25
                               L38 31.5 L32 27.5
                               L26 31.5 L28 25
                               L22.5 20.5 L29.5 20.5 Z"
                            fill="#ffffff"
                        />

                        <path
                            d="M19 34
                               C23 32 27 33 32 36
                               C37 33 41 32 45 34
                               V44 C40 42 36 42 32 45
                               C28 42 24 42 19 44 Z"
                            fill="#ffffff"
                        />

                        <path
                            d="M32 36 V45"
                            stroke="#0f2747"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <div class="admin-brand-info">
                    <strong>SEKOLAH MODERN</strong>
                    <span>Modern School Management System</span>
                </div>

            </a>

            <div class="admin-topbar-divider"></div>

            {{-- LINK WEBSITE --}}
            <a
                href="{{ url('/') }}"
                target="_blank"
                rel="noopener noreferrer"
                class="admin-website-link"
            >
                <i class="bi bi-globe2"></i>
                <span>Lihat Website</span>
                <i class="bi bi-box-arrow-up-right website-external-icon"></i>
            </a>

        </div>


        {{-- RIGHT: NOTIFICATIONS & USER --}}
        <div class="admin-topbar-right">

            {{-- NOTIFIKASI --}}
            <div class="dropdown admin-notification-dropdown">

                <button
                    type="button"
                    class="admin-icon-btn notification-trigger"
                    id="notifBellDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    aria-label="Buka notifikasi"
                >
                    <i class="bi bi-bell"></i>

                    @php
                        $unreadNotifs = auth()->user()?->unreadNotifications?->count() ?? 0;
                    @endphp

                    @if ($unreadNotifs > 0)
                        <span class="notification-dot"></span>
                        <span class="visually-hidden">
                            {{ $unreadNotifs }} notifikasi belum dibaca
                        </span>
                    @endif
                </button>

                <div
                    class="dropdown-menu dropdown-menu-end admin-notification-menu"
                    aria-labelledby="notifBellDropdown"
                >
                    <div class="admin-dropdown-heading">
                        <div>
                            <strong>Notifikasi</strong>
                            <span>Informasi terbaru akun kamu</span>
                        </div>

                        @if ($unreadNotifs > 0)
                            <span class="admin-notification-count">
                                {{ $unreadNotifs }} baru
                            </span>
                        @endif
                    </div>

                    @if ($unreadNotifs > 0)
                        <div class="admin-notification-actions">
                            <form
                                action="{{ route('notifications.read-all') }}"
                                method="POST"
                            >
                                @csrf

                                <button type="submit">
                                    <i class="bi bi-check2-all"></i>
                                    Tandai semua dibaca
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="admin-notification-list">

                        @forelse (auth()->user()?->notifications()->take(5)->get() ?? [] as $notif)

                            <a
                                href="{{ route('notifications.go', $notif->id) }}"
                                class="admin-notification-item {{ is_null($notif->read_at) ? 'is-unread' : '' }}"
                            >
                                <span class="notification-item-icon">
                                    <i class="bi bi-bell"></i>
                                </span>

                                <span class="notification-item-content">
                                    <span class="notification-item-top">
                                        <strong>
                                            {{ $notif->data['title'] ?? 'Notifikasi' }}
                                        </strong>

                                        <small>
                                            {{ $notif->created_at->diffForHumans() }}
                                        </small>
                                    </span>

                                    <span class="notification-item-message">
                                        {{ $notif->data['message'] ?? '' }}
                                    </span>
                                </span>
                            </a>

                        @empty

                            <div class="admin-notification-empty">
                                <i class="bi bi-bell-slash"></i>
                                <strong>Belum ada notifikasi</strong>
                                <span>Notifikasi terbaru akan muncul di sini.</span>
                            </div>

                        @endforelse

                    </div>

                </div>
            </div>


            {{-- PROFIL PENGGUNA --}}
            <div class="dropdown admin-user-dropdown">

                <button
                    type="button"
                    class="admin-user-trigger"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <span class="admin-user-avatar">
                        <x-avatar :user="auth()->user()" :size="36" />
                    </span>

                    <span class="admin-user-info">
                        <strong>{{ auth()->user()->name }}</strong>

                        <span class="admin-user-role">
                            {{ auth()->user()->role?->name ?? 'User' }}
                        </span>
                    </span>

                    <i class="bi bi-chevron-down admin-user-chevron"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end admin-user-menu">

                    <li class="admin-user-menu-header">
                        <span class="admin-user-menu-avatar">
                            <x-avatar :user="auth()->user()" :size="44" />
                        </span>

                        <span class="admin-user-menu-details">
                            <strong>{{ auth()->user()->name }}</strong>
                            <small>{{ auth()->user()->email }}</small>

                            <span class="admin-user-role">
                                {{ auth()->user()->role?->name ?? 'User' }}
                            </span>
                        </span>
                    </li>

                    <li>
                        <a
                            href="{{ route('account.profile.edit') }}"
                            class="dropdown-item admin-user-menu-item"
                        >
                            <i class="bi bi-person-gear"></i>
                            <span>Profil & Akun Saya</span>
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form method="POST" action="{{ url('/logout') }}">
                            @csrf

                            <button
                                type="submit"
                                class="dropdown-item admin-user-menu-item admin-logout-item"
                            >
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Keluar Sistem</span>
                            </button>
                        </form>
                    </li>

                </ul>

            </div>

        </div>

    </div>
</header>
