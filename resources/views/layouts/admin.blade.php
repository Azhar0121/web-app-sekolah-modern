<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">{{ config('app.name') }}</span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white-50 small">
                    {{ auth()->user()->name }}
                    <span class="badge text-bg-light text-primary ms-1">{{ auth()->user()->role->name ?? '-' }}</span>
                </span>
                <form method="POST" action="{{ url('/logout') }}" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <aside class="bg-white border-end vh-100 position-sticky top-0" style="width: 240px; flex-shrink: 0;">
            <div class="p-3">
                <div class="text-uppercase text-muted small fw-bold mb-2 px-2">Menu Admin</div>
                <ul class="nav nav-pills flex-column gap-1">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-dark' }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : 'text-dark' }}">
                            Kelola User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}"
                           class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : 'text-dark' }}">
                            Kelola Role & Permission
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="flex-grow-1 p-4">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
