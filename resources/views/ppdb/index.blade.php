<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PPDB Online - {{ config('app.name') }}</title>
</head>
<body style="font-family: sans-serif; max-width: 700px; margin: 40px auto;">
    <p><a href="{{ url('/') }}">&larr; Beranda</a></p>
    <h2>PPDB Online</h2>

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if ($activePeriod)
        <p><strong>{{ $activePeriod->name }}</strong></p>
        <p>Periode: {{ $activePeriod->start_date->format('d M Y') }} - {{ $activePeriod->end_date->format('d M Y') }}</p>
        <p>Status: {{ $activePeriod->isOpenForRegistration() ? 'DIBUKA' : 'DITUTUP' }}</p>

        @if ($activePeriod->isOpenForRegistration())
            <p><a href="{{ route('ppdb.create') }}">Daftar Sekarang &rarr;</a></p>
        @endif
    @else
        <p>Belum ada periode PPDB yang aktif.</p>
    @endif

    <p><a href="{{ route('ppdb.cek-status.form') }}">Cek Status Pendaftaran</a></p>

    <hr>
    <p style="color:#888; font-size: 13px;">
        [Placeholder — tampilan akan dipercantik dengan Bootstrap di sesi styling berikutnya]
    </p>
</body>
</html>
