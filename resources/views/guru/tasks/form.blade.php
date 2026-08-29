@if ($errors->any())
    <div class="alert alert-danger py-2">
        @foreach ($errors->all() as $error)
            <div class="small">{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="mb-3">
    <label for="title" class="form-label">Judul Tugas</label>
    <input type="text" name="title" id="title" class="form-control"
           value="{{ old('title', $task?->title) }}" required autofocus>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Instruksi / Deskripsi Tugas</label>
    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $task?->description) }}</textarea>
</div>

<div class="mb-3">
    <label for="deadline" class="form-label">Batas Waktu Pengumpulan</label>
    <input type="datetime-local" name="deadline" id="deadline" class="form-control"
           value="{{ old('deadline', $task?->deadline?->format('Y-m-d\TH:i')) }}" required>
</div>

<div class="mb-3">
    <label for="file" class="form-label">Lampiran Soal/Instruksi (opsional, maks 10MB)</label>
    <input type="file" name="file" id="file" class="form-control">
    @if ($task?->hasFile())
        <div class="form-text">
            File saat ini:
            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($task->file_path) }}" target="_blank">
                <strong>{{ $task->file_original_name }}</strong>
            </a>. Upload file baru untuk mengganti.
        </div>
        <div class="form-check mt-1">
            <input type="checkbox" name="remove_file" id="remove_file" class="form-check-input" value="1">
            <label for="remove_file" class="form-check-label small">Hapus file ini (tanpa mengganti)</label>
        </div>
    @endif
</div>

<div class="form-check">
    <input type="checkbox" name="is_published" id="is_published" class="form-check-input" value="1"
           @checked(old('is_published', $task?->is_published ?? true))>
    <label for="is_published" class="form-check-label">Terbitkan (terlihat oleh siswa)</label>
</div>
