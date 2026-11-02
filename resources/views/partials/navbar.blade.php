<nav class="school-navbar" id="schoolNavbar">
    <div class="navbar-container">

        @php
            $navSettings = $settings ?? $schoolSettings ?? \App\Models\Setting::allKeyed();
            $navName = $navSettings['school_name'] ?? config('app.name', 'SEKOLAH MODERN');
            $navAbbr = $navSettings['school_abbreviation'] ?? 'Modern School Management System';
            $navLogo = $navSettings['logo_path'] ?? null;
        @endphp

        <a href="{{ url('/') }}" class="school-brand">

            {{-- LOGO SEKOLAH --}}
            <div class="brand-logo">
                @if(!empty($navLogo) && \Illuminate\Support\Facades\Storage::disk('public')->exists($navLogo))
                    <img src="{{ asset('storage/' . $navLogo) }}" alt="Logo {{ $navName }}" style="width: 34px; height: 34px; object-fit: contain; display: block;">
                @else
                    <svg
                        viewBox="0 0 64 64"
                        xmlns="http://www.w3.org/2000/svg"
                        aria-label="Logo Sekolah"
                    >
                        {{-- Shield --}}
                        <path
                            d="M32 4
                               L55 12
                               V29
                               C55 44 45 54 32 60
                               C19 54 9 44 9 29
                               V12
                               Z"
                            fill="#ffffff"
                        />

                        {{-- Inner shield --}}
                        <path
                            d="M32 10
                               L49 16
                               V29
                               C49 40 42 48 32 53
                               C22 48 15 40 15 29
                               V16
                               Z"
                            fill="#0f2747"
                        />

                        {{-- Star --}}
                        <path
                            d="M32 15
                               L34.5 21
                               L41 21
                               L36 25
                               L38 31
                               L32 27
                               L26 31
                               L28 25
                               L23 21
                               L29.5 21
                               Z"
                            fill="#ffffff"
                        />

                        {{-- Book --}}
                        <path
                            d="M19 34
                               C23 32 27 33 32 36
                               C37 33 41 32 45 34
                               V44
                               C41 42 37 42 32 45
                               C27 42 23 42 19 44
                               Z"
                            fill="#ffffff"
                        />

                        {{-- Book center --}}
                        <path
                            d="M32 36 V45"
                            stroke="#0f2747"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                    </svg>
                @endif
            </div>

            {{-- NAMA SEKOLAH --}}
            <div class="brand-info">
                <strong>{{ $navName }}</strong>
                <span>{{ $navAbbr }}</span>
            </div>

        </a>


        {{-- MENU DESKTOP --}}
        <ul class="nav-menu">

            <li>
                <a href="{{ url('/') }}"
                   class="{{ request()->is('/') ? 'active' : '' }}">
                    Beranda
                </a>
            </li>

            <li>
                <a href="{{ route('profile.show') }}"
                   class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    Profil Sekolah
                </a>
            </li>

            <li>
                <a href="{{ route('teachers.show') }}"
                   class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                    Pengajar & Staf
                </a>
            </li>

            <li>
                <a href="{{ route('academic.show') }}"
                   class="{{ request()->routeIs('academic.*') ? 'active' : '' }}">
                    Akademik
                </a>
            </li>

            <li>
                <a href="{{ route('student-activity.show') }}"
                   class="{{ request()->routeIs('student-activity.*') ? 'active' : '' }}">
                    Kesiswaan
                </a>
            </li>

            <li>
                <a href="{{ route('ppdb.index') }}"
                   class="{{ request()->routeIs('ppdb.*') ? 'active' : '' }}">
                    PPDB Online
                </a>
            </li>

            <li>
                <a href="{{ route('contact.show') }}"
                   class="{{ request()->routeIs('contact.*') ? 'active' : '' }}">
                    Media & Kontak
                </a>
            </li>

        </ul>


        {{-- ACTIONS KANAN: LOGIN (DESKTOP) & HAMBURGER TOGGLE (MOBILE) --}}
        <div class="nav-actions">
            @auth
                <a href="{{ auth()->user()->dashboardUrl() }}" class="nav-login">
                    <span>Dashboard {{ auth()->user()->role?->name ?? '' }}</span>
                    <span class="nav-arrow">&rarr;</span>
                </a>
            @else
                <a href="{{ url('/login') }}" class="nav-login">
                    <span>Masuk ke Portal</span>
                    <span class="nav-arrow">&rarr;</span>
                </a>
            @endauth

            {{-- Tombol Hamburger Mobile --}}
            <button type="button" class="nav-toggle" id="navToggleBtn" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="navMobileMenu">
                <span class="nav-toggle-bar"></span>
                <span class="nav-toggle-bar"></span>
                <span class="nav-toggle-bar"></span>
            </button>
        </div>

    </div>

    {{-- MENU MOBILE DROPDOWN --}}
    <div class="nav-mobile-menu" id="navMobileMenu">
        <ul class="nav-mobile-list">
            <li>
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>Beranda</span>
                </a>
            </li>
            <li>
                <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    <span>Profil Sekolah</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teachers.show') }}" class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Pengajar & Staf</span>
                </a>
            </li>
            <li>
                <a href="{{ route('academic.show') }}" class="{{ request()->routeIs('academic.*') ? 'active' : '' }}">
                    <i class="bi bi-mortarboard"></i>
                    <span>Akademik</span>
                </a>
            </li>
            <li>
                <a href="{{ route('student-activity.show') }}" class="{{ request()->routeIs('student-activity.*') ? 'active' : '' }}">
                    <i class="bi bi-trophy"></i>
                    <span>Kesiswaan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('ppdb.index') }}" class="{{ request()->routeIs('ppdb.*') ? 'active' : '' }}">
                    <i class="bi bi-pencil-square"></i>
                    <span>PPDB Online</span>
                    <span class="nav-badge-pill">Daftar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('contact.show') }}" class="{{ request()->routeIs('contact.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope"></i>
                    <span>Media & Kontak</span>
                </a>
            </li>
        </ul>

        {{-- Mobile Auth CTA --}}
        <div class="nav-mobile-footer">
            @auth
                <a href="{{ auth()->user()->dashboardUrl() }}" class="nav-mobile-cta">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard {{ auth()->user()->role?->name ?? '' }}</span>
                    <span class="nav-arrow">&rarr;</span>
                </a>
            @else
                <a href="{{ url('/login') }}" class="nav-mobile-cta">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Masuk ke Portal Sekolah</span>
                    <span class="nav-arrow">&rarr;</span>
                </a>
            @endauth
        </div>
    </div>

    {{-- Backdrop Overlay untuk Mobile Menu --}}
    <div class="nav-backdrop" id="navBackdrop"></div>
</nav>

<script>
(function() {
    function initNavbar() {
        const toggleBtn = document.getElementById('navToggleBtn');
        const mobileMenu = document.getElementById('navMobileMenu');
        const backdrop = document.getElementById('navBackdrop');

        if (!toggleBtn || !mobileMenu) return;

        function openMenu() {
            toggleBtn.classList.add('is-active');
            toggleBtn.setAttribute('aria-expanded', 'true');
            mobileMenu.classList.add('is-open');
            if (backdrop) backdrop.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            toggleBtn.classList.remove('is-active');
            toggleBtn.setAttribute('aria-expanded', 'false');
            mobileMenu.classList.remove('is-open');
            if (backdrop) backdrop.classList.remove('is-open');
            document.body.style.overflow = '';
        }

        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = mobileMenu.classList.contains('is-open');
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        if (backdrop) {
            backdrop.addEventListener('click', closeMenu);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('is-open')) {
                closeMenu();
            }
        });

        const menuLinks = mobileMenu.querySelectorAll('a');
        menuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                closeMenu();
            });
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 992 && mobileMenu.classList.contains('is-open')) {
                closeMenu();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavbar);
    } else {
        initNavbar();
    }
})();
</script>
