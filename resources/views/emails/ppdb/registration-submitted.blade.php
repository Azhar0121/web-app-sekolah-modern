<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: sans-serif; color: #222; max-width: 600px; margin: 0 auto;">
    <h2 style="color: #0d6efd;">{{ config('app.name') }}</h2>

    <p>Halo <strong>{{ $registration->full_name }}</strong>,</p>

    <p>Terima kasih telah mendaftar PPDB Online di {{ config('app.name') }}. Pendaftaran Anda telah kami terima dengan nomor pendaftaran berikut:</p>

    <div style="background: #f1f5f9; border: 1px solid #dbe3ea; border-radius: 8px; padding: 16px; text-align: center; margin: 20px 0;">
        <span style="font-size: 22px; font-weight: bold; letter-spacing: 1px;">{{ $registration->registration_number }}</span>
    </div>

    <p><strong>Simpan email ini</strong> — nomor pendaftaran di atas akan Anda perlukan untuk mengecek status pendaftaran kapan saja.</p>

    <p>
        Cek status pendaftaran Anda di:<br>
        <a href="{{ route('ppdb.cek-status.form') }}">{{ route('ppdb.cek-status.form') }}</a>
    </p>

    <p style="color: #888; font-size: 13px; margin-top: 30px;">
        Jika Anda tidak merasa melakukan pendaftaran ini, abaikan email ini.
    </p>
</body>
</html>
