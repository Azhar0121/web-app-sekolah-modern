<nav class="school-navbar">
    <div class="navbar-container">

        <a href="{{ url('/') }}" class="school-brand">
            <div class="brand-logo">
                {{ strtoupper(substr(config('app.name'), 0, 1)) }}
            </div>
            <div class="brand-info">
                <strong>{{ config('app.name') }}</strong>
                <span>Modern School Management System</span>
            </div>
        </a>

        <ul class="nav-menu">
            <li>
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                    Beranda
                </a>
            </li>
            <li><a href="#" class="nav-disabled" title="Segera hadir">Profil Sekolah</a></li>
            <li><a href="#" class="nav-disabled" title="Segera hadir">Akademik</a></li>
            <li><a href="#" class="nav-disabled" title="Segera hadir">Kesiswaan & Alumni</a></li>
            <li>
                <a href="{{ route('ppdb.index') }}" class="{{ request()->routeIs('ppdb.*') ? 'active' : '' }}">
                    PPDB Online
                </a>
            </li>
            <li><a href="#" class="nav-disabled" title="Segera hadir">Media & Kontak</a></li>
        </ul>

        <a href="{{ url('/login') }}" class="nav-login">
            <span>Masuk ke Portal</span>
            <span class="nav-arrow">&rarr;</span>
        </a>

    </div>
</nav>
