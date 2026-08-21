<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="w-100" style="max-width: 420px;">
            @yield('content')
        </div>
    </div>
</body>
</html>
