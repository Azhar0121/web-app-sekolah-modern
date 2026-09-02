<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: sans-serif; color: #222; max-width: 600px; margin: 0 auto;">
    <h2 style="color: #1769d5;">Selamat Datang, {{ $registration->full_name }}!</h2>

    <p>
        Daftar ulang Anda sebagai siswa baru di {{ config('app.name') }} telah kami
        konfirmasi. Akun untuk masuk ke Portal Siswa sudah kami buatkan, berikut detailnya:
    </p>

    <div style="background: #eaf3ff; border: 1px solid #c7dbf3; border-radius: 8px; padding: 16px; margin: 20px 0;">
        <p style="margin: 0 0 8px;"><strong>Email Login:</strong> {{ $email }}</p>
        <p style="margin: 0;"><strong>Password Sementara:</strong>
            <span style="font-family: monospace; font-size: 16px; background: #fff; padding: 2px 8px; border-radius: 4px;">{{ $password }}</span>
        </p>
        <p style="margin: 10px 0 0; font-size: 13px; color: #555;">
            Demi keamanan, segera ganti password ini setelah pertama kali login.
        </p>
    </div>

    @if ($classroom)
        <p>
            Anda telah ditempatkan di kelas <strong>{{ $classroom->name }}</strong>
            untuk tahun ajaran ini.
        </p>
    @else
        <p style="color: #b8860b;">
            Penempatan kelas Anda akan diinfokan menyusul oleh Tata Usaha karena
            kapasitas kelas saat ini sedang penuh.
        </p>
    @endif

    <p>
        Silakan login di: <a href="{{ url('/login') }}">{{ url('/login') }}</a>
    </p>

    <p style="color: #888; font-size: 13px; margin-top: 30px;">
        Jika ada pertanyaan, silakan hubungi Tata Usaha {{ config('app.name') }}.
    </p>
</body>
</html>
