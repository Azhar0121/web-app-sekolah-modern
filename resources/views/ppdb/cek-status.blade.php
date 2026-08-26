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

            @if ($registration->status === 'accepted')
                <div style="background: #fff8e6; border: 1px solid #ffe6a8; border-radius: 8px; padding: 14px; margin: 12px 0;">
                    <p style="margin: 0 0 4px;"><strong>Batas Waktu Daftar Ulang:</strong></p>
                    <p style="font-size: 18px; font-weight: bold; margin: 0; color: #b8860b;">
                        {{ $registration->reRegistrationDeadlineLabel() }}
                    </p>
                    <p style="margin: 8px 0 0; font-size: 13px; color: #555;">
                        Silakan datang langsung ke sekolah sebelum tanggal tersebut untuk
                        menyelesaikan daftar ulang & pembayaran.
                    </p>
                </div>
            @endif

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
