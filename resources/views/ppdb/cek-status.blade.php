<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cek Status PPDB - {{ config('app.name') }}</title>
</head>
<body style="font-family: sans-serif; max-width: 700px; margin: 40px auto;">
    <p><a href="{{ route('ppdb.index') }}">&larr; Kembali</a></p>
    <h2>Cek Status Pendaftaran</h2>

    <form method="POST" action="{{ route('ppdb.cek-status') }}">
        @csrf
        <p>
            <label>Nomor Pendaftaran</label><br>
            <input type="text" name="registration_number" placeholder="PPDB-2026-00001"
                   value="{{ old('registration_number', $registration->registration_number ?? '') }}"
                   style="width: 100%;" required>
        </p>
        <button type="submit">Cek Status</button>
    </form>

    <p style="margin-top: 12px;">
        Lupa nomor pendaftaran Anda?
        <a href="{{ route('ppdb.lupa-nomor.form') }}">Cari di sini</a>
    </p>

    @isset($registration)
        <hr>
        @if ($registration)
            <h3>Hasil Pencarian</h3>
            <p><strong>Nama:</strong> {{ $registration->full_name }}</p>
            <p><strong>Status:</strong> {{ $registration->statusLabel() }}</p>
            @if ($registration->notes)
                <p><strong>Catatan:</strong> {{ $registration->notes }}</p>
            @endif
            <p><strong>Tanggal Daftar:</strong> {{ $registration->created_at->format('d M Y H:i') }}</p>
        @else
            <p style="color: red;">Nomor pendaftaran tidak ditemukan. Periksa kembali penulisannya.</p>
        @endif
    @endisset

    <hr>
    <p style="color:#888; font-size: 13px;">
        [Placeholder — tampilan akan dipercantik dengan Bootstrap di sesi styling berikutnya]
    </p>
</body>
</html>
