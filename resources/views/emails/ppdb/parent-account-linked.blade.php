<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: sans-serif; color: #222; max-width: 600px; margin: 0 auto;">

    @if ($isNewAccount)
        <h2 style="color: #1769d5;">Selamat Datang di Portal Orang Tua, {{ $parentUser->name }}!</h2>

        <p>
            Putra/putri Anda, <strong>{{ $registration->full_name }}</strong>, telah dikonfirmasi
            daftar ulang sebagai siswa baru di {{ config('app.name') }}. Kami sudah membuatkan
            akun Portal Orang Tua untuk Anda, berikut detailnya:
        </p>

        <div style="background: #eaf3ff; border: 1px solid #c7dbf3; border-radius: 8px; padding: 16px; margin: 20px 0;">
            <p style="margin: 0 0 8px;"><strong>Email Login:</strong> {{ $parentUser->email }}</p>
            <p style="margin: 0;"><strong>Password Sementara:</strong>
                <span style="font-family: monospace; font-size: 16px; background: #fff; padding: 2px 8px; border-radius: 4px;">{{ $password }}</span>
            </p>
            <p style="margin: 10px 0 0; font-size: 13px; color: #555;">
                Demi keamanan, segera ganti password ini setelah pertama kali login.
            </p>
        </div>

        <p>
            Lewat Portal Orang Tua, Anda bisa memantau kehadiran harian dan
            perkembangan nilai akademik putra/putri Anda kapan saja.
        </p>
    @else
        <h2 style="color: #1769d5;">Anak Baru Ditautkan ke Akun Anda</h2>

        <p>
            Putra/putri Anda, <strong>{{ $registration->full_name }}</strong>, telah dikonfirmasi
            daftar ulang sebagai siswa baru di {{ config('app.name') }} dan sekarang sudah
            ditautkan ke akun Portal Orang Tua Anda yang sudah ada
            (<strong>{{ $parentUser->email }}</strong>).
        </p>

        <p>
            Anda tidak perlu membuat akun baru — cukup login seperti biasa, dan
            data putra/putri Anda yang baru akan langsung terlihat di Portal Orang Tua.
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
