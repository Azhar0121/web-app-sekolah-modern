<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar PPDB - {{ config('app.name') }}</title>
</head>
<body style="font-family: sans-serif; max-width: 700px; margin: 40px auto;">
    <p><a href="{{ route('ppdb.index') }}">&larr; Kembali</a></p>
    <h2>Formulir Pendaftaran PPDB</h2>
    <p>{{ $activePeriod->name }}</p>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ppdb.store') }}" enctype="multipart/form-data">
        @csrf

        <fieldset style="margin-bottom: 16px;">
            <legend><strong>Data Calon Siswa</strong></legend>

            <p>
                <label>Nama Lengkap*</label><br>
                <input type="text" name="full_name" value="{{ old('full_name') }}" style="width: 100%;" required>
            </p>
            <p>
                <label>NISN</label><br>
                <input type="text" name="nisn" value="{{ old('nisn') }}" style="width: 100%;">
            </p>
            <p>
                <label>NIK</label><br>
                <input type="text" name="nik" value="{{ old('nik') }}" style="width: 100%;">
            </p>
            <p>
                <label>Jenis Kelamin*</label><br>
                <select name="gender" required>
                    <option value="">-- Pilih --</option>
                    <option value="L" @selected(old('gender') === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('gender') === 'P')>Perempuan</option>
                </select>
            </p>
            <p>
                <label>Tempat Lahir*</label><br>
                <input type="text" name="birth_place" value="{{ old('birth_place') }}" style="width: 100%;" required>
            </p>
            <p>
                <label>Tanggal Lahir*</label><br>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}" required>
            </p>
            <p>
                <label>Alamat*</label><br>
                <textarea name="address" style="width: 100%;" required>{{ old('address') }}</textarea>
            </p>
            <p>
                <label>No. HP Calon Siswa*</label><br>
                <input type="text" name="phone" value="{{ old('phone') }}" style="width: 100%;" required>
            </p>
        </fieldset>

        <fieldset style="margin-bottom: 16px;">
            <legend><strong>Data Orang Tua/Wali</strong></legend>
            <p>
                <label>Nama Orang Tua/Wali*</label><br>
                <input type="text" name="parent_name" value="{{ old('parent_name') }}" style="width: 100%;" required>
            </p>
            <p>
                <label>No. HP Orang Tua/Wali*</label><br>
                <input type="text" name="parent_phone" value="{{ old('parent_phone') }}" style="width: 100%;" required>
            </p>
        </fieldset>

        <fieldset style="margin-bottom: 16px;">
            <legend><strong>Asal Sekolah</strong></legend>
            <p>
                <label>Nama Sekolah Asal (SMP)*</label><br>
                <input type="text" name="previous_school" value="{{ old('previous_school') }}" style="width: 100%;" required>
            </p>
        </fieldset>

        <fieldset style="margin-bottom: 16px;">
            <legend><strong>Dokumen (opsional, bisa dilengkapi menyusul)</strong></legend>
            <p>
                <label>Kartu Keluarga</label><br>
                <input type="file" name="documents[]">
                <input type="hidden" name="document_types[]" value="kartu_keluarga">
            </p>
            <p>
                <label>Akta Lahir</label><br>
                <input type="file" name="documents[]">
                <input type="hidden" name="document_types[]" value="akta_lahir">
            </p>
            <p>
                <label>Rapor</label><br>
                <input type="file" name="documents[]">
                <input type="hidden" name="document_types[]" value="rapor">
            </p>
        </fieldset>

        <button type="submit">Kirim Pendaftaran</button>
    </form>

    <hr>
    <p style="color:#888; font-size: 13px;">
        [Placeholder — tampilan akan dipercantik dengan Bootstrap di sesi styling berikutnya]
    </p>
</body>
</html>
