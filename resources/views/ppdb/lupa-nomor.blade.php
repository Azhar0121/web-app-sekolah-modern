<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Nomor Pendaftaran - {{ config('app.name') }}</title>
</head>
<body style="font-family: sans-serif; max-width: 700px; margin: 40px auto;">
    <p><a href="{{ route('ppdb.cek-status.form') }}">&larr; Kembali</a></p>
    <h2>Lupa Nomor Pendaftaran</h2>
    <p style="color: #666;">
        Masukkan nama lengkap dan tanggal lahir calon siswa persis seperti saat
        mendaftar. Kedua data ini dipakai untuk memverifikasi identitas Anda
        sebelum menampilkan nomor pendaftaran.
    </p>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ppdb.lupa-nomor') }}">
        @csrf
        <p>
            <label>Nama Lengkap Calon Siswa*</label><br>
            <input type="text" name="full_name" value="{{ old('full_name') }}" style="width: 100%;" required>
        </p>
        <p>
            <label>Tanggal Lahir*</label><br>
            <input type="date" name="birth_date" value="{{ old('birth_date') }}" required>
        </p>
        <button type="submit">Cari Nomor Pendaftaran</button>
    </form>

    @isset($registrations)
        <hr>
        @if ($registrations->isEmpty())
            <p style="color: red;">
                Tidak ditemukan pendaftaran dengan nama dan tanggal lahir tersebut.
                Periksa kembali penulisan nama (harus persis sama dengan saat mendaftar).
            </p>
        @else
            <h3>Ditemukan {{ $registrations->count() }} pendaftaran:</h3>
            @foreach ($registrations as $registration)
                <div style="border: 1px solid #ddd; border-radius: 6px; padding: 12px; margin-bottom: 10px;">
                    <p style="font-size: 18px; font-weight: bold; margin: 0 0 6px;">
                        {{ $registration->registration_number }}
                    </p>
                    <p style="margin: 0; color: #555;">
                        Status: {{ $registration->statusLabel() }} &middot;
                        Didaftarkan pada {{ $registration->created_at->format('d M Y') }}
                    </p>
                </div>
            @endforeach
        @endif
    @endisset

    <hr>
    <p style="color:#888; font-size: 13px;">
        [Placeholder — tampilan akan dipercantik dengan Bootstrap di sesi styling berikutnya]
    </p>
</body>
</html>
