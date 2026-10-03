@if ($errors->any())
    <div class="alert alert-danger py-2">
        @foreach ($errors->all() as $error)
            <div class="small">{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="mb-3">
    <label for="name" class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $subject?->name) }}" placeholder="Contoh: Pemrograman Web dan Perangkat Bergerak" required autofocus>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="code" class="form-label">Kode <span class="text-danger">*</span></label>
    <input type="text" name="code" id="code" class="form-control text-uppercase @error('code') is-invalid @enderror" maxlength="20"
           placeholder="Contoh: RPL-WEB" value="{{ old('code', $subject?->code) }}" required>
    @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="department_id" class="form-label">Jurusan / Program Keahlian</label>
    <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror">
        <option value="">-- Semua Jurusan (Mata Pelajaran Umum) --</option>
        @foreach ($departments as $dept)
            <option value="{{ $dept->id }}" @selected(old('department_id', $subject?->department_id) == $dept->id)>
                {{ $dept->name }} ({{ $dept->code }})
            </option>
        @endforeach
    </select>
    <div class="form-text text-muted small">
        Pilih jurusan jika mata pelajaran ini merupakan muatan kejuruan/peminatan spesifik. Kosongkan jika merupakan mata pelajaran umum untuk seluruh siswa.
    </div>
    @error('department_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Deskripsi (opsional)</label>
    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Penjelasan singkat mengenai silabus atau cakupan materi...">{{ old('description', $subject?->description) }}</textarea>
</div>

<div class="form-check">
    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
           @checked(old('is_active', $subject?->is_active ?? true))>
    <label for="is_active" class="form-check-label">Mata pelajaran aktif</label>
</div>
