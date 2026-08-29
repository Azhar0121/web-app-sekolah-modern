@if ($errors->any())
    <div class="alert alert-danger py-2">
        @foreach ($errors->all() as $error)
            <div class="small">{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="mb-3">
    <label for="title" class="form-label">Judul Materi</label>
    <input type="text" name="title" id="title" class="form-control"
           value="{{ old('title', $material?->title) }}" required autofocus>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Deskripsi (opsional)</label>
    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $material?->description) }}</textarea>
</div>

<div class="mb-3">
    <label for="file" class="form-label">File Materi (PDF/Word/PPT/Excel/ZIP, maks 10MB)</label>
    <input type="file" name="file" id="file" class="form-control">
    @if ($material?->hasFile())
        <div class="form-text">
            File saat ini:
            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($material->file_path) }}" target="_blank">
                <strong>{{ $material->file_original_name }}</strong>
            </a>.
            Upload file baru untuk mengganti.
        </div>
        <div class="form-check mt-1">
            <input type="checkbox" name="remove_file" id="remove_file" class="form-check-input" value="1">
            <label for="remove_file" class="form-check-label small">Hapus file ini (tanpa mengganti)</label>
        </div>
    @endif
</div>

<div class="mb-3">
    <label for="link" class="form-label">atau Link Eksternal (video, dsb, opsional)</label>
    <input type="url" name="link" id="link" class="form-control" placeholder="https://..."
           value="{{ old('link', $material?->link) }}">
</div>

<p class="text-muted small">Isi salah satu: File atau Link.</p>

<div class="form-check">
    <input type="checkbox" name="is_published" id="is_published" class="form-check-input" value="1"
           @checked(old('is_published', $material?->is_published ?? true))>
    <label for="is_published" class="form-check-label">Terbitkan (terlihat oleh siswa)</label>
</div>
