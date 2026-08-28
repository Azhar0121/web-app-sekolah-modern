@extends('layouts.app')

@section('title', 'Koreksi Tugas - ' . $task->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Koreksi Tugas: {{ $task->title }}</h4>
        <p class="text-muted mb-0">
            {{ $teachingAssignment->classroom->name }} &middot; {{ $teachingAssignment->subject->name }}
            &middot; Batas waktu {{ $task->deadline->format('d M Y, H:i') }}
        </p>
    </div>
    <a href="{{ route('guru.teaching-assignments.tasks.index', $teachingAssignment) }}" class="btn btn-outline-secondary btn-sm">
        &larr; Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Siswa</th>
                    <th>Status</th>
                    <th>Jawaban</th>
                    <th class="text-center">Nilai</th>
                    <th>Feedback</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($studentIds as $student)
                    @php($submission = $submissionsByStudent->get($student->id))
                    <tr>
                        <td class="fw-semibold">{{ $student->name }}</td>
                        <td>
                            @if (! $submission)
                                <span class="badge text-bg-light text-muted">Belum Mengumpulkan</span>
                            @elseif ($submission->isLate())
                                <span class="badge text-bg-warning">Terlambat</span>
                            @else
                                <span class="badge text-bg-success">Tepat Waktu</span>
                            @endif
                        </td>
                        <td class="small">
                            @if ($submission?->file_path)
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($submission->file_path) }}" target="_blank">
                                    📎 {{ $submission->file_original_name }}
                                </a><br>
                            @endif
                            @if ($submission?->note)
                                <span class="text-muted">{{ \Illuminate\Support\Str::limit($submission->note, 60) }}</span>
                            @endif
                            @if (! $submission)
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $submission?->grade ?? '-' }}
                        </td>
                        <td class="small text-muted">{{ $submission?->feedback ?? '-' }}</td>
                        <td class="text-end">
                            @if ($submission)
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse"
                                        data-bs-target="#grade-form-{{ $submission->id }}">
                                    {{ $submission->isGraded() ? 'Ubah Nilai' : 'Beri Nilai' }}
                                </button>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @if ($submission)
                        <tr class="collapse" id="grade-form-{{ $submission->id }}">
                            <td colspan="6" class="bg-light">
                                <form method="POST"
                                      action="{{ route('guru.teaching-assignments.tasks.submissions.grade', [$teachingAssignment, $task, $submission]) }}"
                                      class="row g-2 align-items-end py-2">
                                    @csrf
                                    @method('PUT')
                                    <div class="col-md-2">
                                        <label class="form-label small">Nilai (0-100)</label>
                                        <input type="number" name="grade" class="form-control form-control-sm" min="0" max="100"
                                               value="{{ $submission->grade }}" required>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label small">Feedback (opsional)</label>
                                        <input type="text" name="feedback" class="form-control form-control-sm"
                                               value="{{ $submission->feedback }}">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-sm btn-primary w-100">Simpan Nilai</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada siswa di kelas ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
