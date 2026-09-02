<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

    @include('layouts.partials.topbar', ['showSidebarToggle' => true])

    <div class="app-shell">

        @include('layouts.partials.sidebar')

        <main class="app-main">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif

            @yield('content')
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('[data-sidebar]');
            const backdrop = document.querySelector('[data-sidebar-backdrop]');

            document.querySelectorAll('[data-sidebar-toggle]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    sidebar?.classList.toggle('is-open');
                    backdrop?.classList.toggle('is-open');
                });
            });

            backdrop?.addEventListener('click', () => {
                sidebar?.classList.remove('is-open');
                backdrop?.classList.remove('is-open');
            });
        });
    </script>

</body>
</html>
