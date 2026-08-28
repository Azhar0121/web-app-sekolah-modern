@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ $task->title }}</h4>
        <p class="text-muted mb-0">
            {{ $task->teachingAssignment->subject->name }} &middot;
            Batas waktu: {{ $task->deadline->format('d M Y, H:i') }}
            @if ($task->isPastDeadline())
                <span class="badge text-bg-danger ms-1">Lewat Batas Waktu</span>
            @endif
        </p>
    </div>
    <a href="{{ route('siswa.tasks.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Instruksi Tugas</div>
            <div class="card-body">
                <p style="white-space: pre-line;">{{ $task->description ?: '-' }}</p>
                @if ($task->hasFile())
                    <a href="{{ route('siswa.tasks.download-attachment', $task) }}" class="btn btn-sm btn-outline-primary">
                        📎 Unduh Lampiran Soal
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Pengumpulan Anda</div>
            <div class="card-body">
                @if ($submission?->isGraded())
                    <div class="alert alert-success">
                        <strong>Nilai: {{ $submission->grade }}</strong>
                        @if ($submission->feedback)
                            <div class="small mt-1">Feedback guru: {{ $submission->feedback }}</div>
                        @endif
                    </div>
                @endif

                @if ($submission)
                    <p class="small text-muted mb-2">
                        Terkumpul: {{ $submission->submitted_at->format('d M Y, H:i') }}
                        @if ($submission->isLate())
                            <span class="badge text-bg-warning">Terlambat</span>
                        @endif
                    </p>
                    @if ($submission->file_path)
                        <p class="small mb-2">
                            <a href="{{ route('siswa.tasks.download-submission', $submission) }}">
                                📎 {{ $submission->file_original_name }}
                            </a>
                        </p>
                    @endif
                    @if ($submission->note)
                        <p class="small text-muted">Catatan Anda: {{ $submission->note }}</p>
                    @endif
                @endif

                @if ($submission?->isGraded())
                    <p class="text-muted small mb-0">Tugas sudah dinilai, pengumpulan tidak bisa diubah lagi.</p>
                @else
                    <hr>
                    <form method="POST" action="{{ route('siswa.tasks.store', $task) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="note" class="form-label small">Catatan (opsional)</label>
                            <textarea name="note" id="note" class="form-control" rows="3">{{ old('note', $submission?->note) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label small">File Jawaban (opsional jika catatan sudah diisi)</label>
                            <input type="file" name="file" id="file" class="form-control">
                            @if ($submission?->file_path)
                                <div class="form-text">File sebelumnya: {{ $submission->file_original_name }}. Upload baru untuk mengganti.</div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">
                            {{ $submission ? 'Perbarui Pengumpulan' : 'Kumpulkan Tugas' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
