<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Berhasil - {{ config('app.name') }}</title>
</head>
<body style="font-family: sans-serif; max-width: 700px; margin: 40px auto;">
    <h2 style="color: green;">Pendaftaran Berhasil Dikirim</h2>

    <p>Nomor Pendaftaran Anda:</p>
    <p style="font-size: 24px; font-weight: bold;">{{ $registration->registration_number }}</p>

    <p>
        Nomor ini juga sudah kami kirimkan ke email
        <strong>{{ $registration->email }}</strong>. Simpan email tersebut untuk
        mengecek status pendaftaran Anda nanti.
    </p>
    <p>Status saat ini: <strong>{{ $registration->statusLabel() }}</strong></p>

    <p>
        <a href="{{ route('ppdb.cek-status.form') }}">Cek Status Pendaftaran</a> |
        <a href="{{ route('ppdb.lupa-nomor.form') }}">Lupa Nomor Pendaftaran?</a> |
        <a href="{{ url('/') }}">Kembali ke Beranda</a>
    </p>

    <hr>
    <p style="color:#888; font-size: 13px;">
        [Placeholder — tampilan akan dipercantik dengan Bootstrap di sesi styling berikutnya]
    </p>
</body>
</html>
