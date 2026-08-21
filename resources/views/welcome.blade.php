<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand fw-bold">{{ config('app.name') }}</span>
        </div>
    </nav>

    <div class="container py-5 text-center">
        <h1 class="fw-bold mb-3">Selamat Datang di {{ config('app.name') }}</h1>
        <p class="text-muted mb-4">
            Halaman publik (landing page CMS) akan dibangun di modul Fase 1 berikutnya.
            Untuk sekarang, silakan masuk ke portal internal.
        </p>
        <a href="http://localhost:8000/login" class="btn btn-primary btn-lg">Masuk ke Portal</a>
    </div>
</body>
</html>