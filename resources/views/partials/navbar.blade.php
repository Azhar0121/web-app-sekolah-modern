<nav class="school-navbar">
    <div class="navbar-container">

        <a href="{{ url('/') }}" class="school-brand">

            {{-- LOGO SEKOLAH --}}
            <div class="brand-logo">
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
            </div>

            {{-- NAMA SEKOLAH --}}
            <div class="brand-info">
                <strong>SEKOLAH MODERN</strong>
                <span>Modern School Management System</span>
            </div>

        </a>


        <ul class="nav-menu">

            <li>
                <a href="{{ url('/') }}"
                   class="{{ request()->is('/') ? 'active' : '' }}">
                    Beranda
                </a>
            </li>

            <li>
                <a href="#"
                   class="nav-disabled"
                   title="Segera hadir">
                    Profil Sekolah
                </a>
            </li>

            <li>
                <a href="#"
                   class="nav-disabled"
                   title="Segera hadir">
                    Akademik
                </a>
            </li>

            <li>
                <a href="#"
                   class="nav-disabled"
                   title="Segera hadir">
                    Kesiswaan & Alumni
                </a>
            </li>

            <li>
                <a href="{{ route('ppdb.index') }}"
                   class="{{ request()->routeIs('ppdb.*') ? 'active' : '' }}">
                    PPDB Online
                </a>
            </li>

            <li>
                <a href="#"
                   class="nav-disabled"
                   title="Segera hadir">
                    Media & Kontak
                </a>
            </li>

        </ul>


        {{-- LOGIN --}}
        <a href="{{ url('/login') }}" class="nav-login">
            <span>Masuk ke Portal</span>
            <span class="nav-arrow">&rarr;</span>
        </a>

    </div>
</nav>
