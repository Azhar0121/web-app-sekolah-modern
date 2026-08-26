<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: sans-serif; color: #222; max-width: 600px; margin: 0 auto;">
    <h2 style="color: #198754;">Selamat, {{ $registration->full_name }}!</h2>

    <p>Kami dengan senang hati menginformasikan bahwa Anda <strong>DITERIMA</strong> sebagai calon siswa baru di {{ config('app.name') }}.</p>

    <p><strong>Nomor Pendaftaran:</strong> {{ $registration->registration_number }}</p>

    <div style="background: #fff8e6; border: 1px solid #ffe6a8; border-radius: 8px; padding: 16px; margin: 20px 0;">
        <p style="margin: 0 0 8px;"><strong>Batas Waktu Daftar Ulang:</strong></p>
        <p style="font-size: 20px; font-weight: bold; margin: 0; color: #b8860b;">
            {{ $registration->reRegistrationDeadlineLabel() }}
        </p>
        <p style="margin: 10px 0 0; font-size: 14px; color: #555;">
            Silakan datang langsung ke sekolah sebelum tanggal tersebut untuk
            menyelesaikan proses daftar ulang dan pembayaran. Jika melewati
            batas waktu ini, kursi Anda berpotensi dialihkan ke calon siswa lain.
        </p>
    </div>

    <p>Mohon membawa dokumen asli (Kartu Keluarga, Akta Lahir, Rapor) saat datang daftar ulang.</p>

    <p>
        Cek status pendaftaran Anda kapan saja di:<br>
        <a href="{{ route('ppdb.cek-status.form') }}">{{ route('ppdb.cek-status.form') }}</a>
    </p>

    <p style="color: #888; font-size: 13px; margin-top: 30px;">
        Jika ada pertanyaan, silakan hubungi Tata Usaha {{ config('app.name') }}.
    </p>
</body>
</html>
