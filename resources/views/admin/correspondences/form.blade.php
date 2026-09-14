@if ($errors->any())
    <div class="alert alert-danger py-2">
        @foreach ($errors->all() as $error)
            <div class="small">{{ $error }}</div>
        @endforeach
    </div>
@endif

<input type="hidden" name="type" value="{{ $type ?? $correspondence->type }}">

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="letter_date" class="form-label">Tanggal Surat</label>
        <input type="date" name="letter_date" id="letter_date" class="form-control"
               value="{{ old('letter_date', ($correspondence->letter_date ?? null)?->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="category" class="form-label">Kategori</label>
        <select name="category" id="category" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categoryOptions as $option)
                <option value="{{ $option }}" @selected(old('category', $correspondence->category ?? null) === $option)>{{ $option }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label for="subject" class="form-label">Perihal</label>
    <input type="text" name="subject" id="subject" class="form-control"
           value="{{ old('subject', $correspondence->subject ?? '') }}" required autofocus>
</div>

<div class="mb-3">
    <label for="correspondent" class="form-label">
        {{ ($type ?? $correspondence->type) === 'masuk' ? 'Pengirim / Instansi Asal' : 'Tujuan Surat / Instansi' }}
    </label>
    <input type="text" name="correspondent" id="correspondent" class="form-control"
           value="{{ old('correspondent', $correspondence->correspondent ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Catatan / Ringkasan Isi (opsional)</label>
    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $correspondence->description ?? '') }}</textarea>
</div>

@if (($type ?? $correspondence->type) === 'masuk')
    <div class="mb-3">
        <label for="disposition" class="form-label">Disposisi / Tindak Lanjut (opsional)</label>
        <textarea name="disposition" id="disposition" class="form-control" rows="2"
                  placeholder="Contoh: Diteruskan ke Kepala Sekolah untuk ditindaklanjuti">{{ old('disposition', $correspondence->disposition ?? '') }}</textarea>
    </div>
@endif

<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select name="status" id="status" class="form-select" required>
        @foreach ($statusOptions as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $correspondence->status ?? null) === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="file" class="form-label">Lampiran / Scan Surat (opsional, PDF/gambar/Word, maks 10MB)</label>
    <input type="file" name="file" id="file" class="form-control">
    @if (($correspondence->file_path ?? null))
        <div class="form-text">
            File saat ini: <strong>{{ $correspondence->file_original_name }}</strong>. Upload file baru untuk mengganti.
        </div>
        <div class="form-check mt-1">
            <input type="checkbox" name="remove_file" id="remove_file" class="form-check-input" value="1">
            <label for="remove_file" class="form-check-label small">Hapus file ini (tanpa mengganti)</label>
        </div>
    @endif
</div>
