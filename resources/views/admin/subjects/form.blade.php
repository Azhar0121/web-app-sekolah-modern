@if ($errors->any())
    <div class="alert alert-danger py-2">
        @foreach ($errors->all() as $error)
            <div class="small">{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="mb-3">
    <label for="name" class="form-label">Nama Mata Pelajaran</label>
    <input type="text" name="name" id="name" class="form-control"
           value="{{ old('name', $subject?->name) }}" required autofocus>
</div>

<div class="mb-3">
    <label for="code" class="form-label">Kode</label>
    <input type="text" name="code" id="code" class="form-control text-uppercase" maxlength="20"
           placeholder="Contoh: MTK" value="{{ old('code', $subject?->code) }}" required>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Deskripsi (opsional)</label>
    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $subject?->description) }}</textarea>
</div>

<div class="form-check">
    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
           @checked(old('is_active', $subject?->is_active ?? true))>
    <label for="is_active" class="form-check-label">Mata pelajaran aktif</label>
</div>
