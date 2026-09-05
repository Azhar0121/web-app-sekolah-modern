@extends('layouts.app')

@section('title', 'Koreksi Tugas - ' . $task->title)

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/tasks/submissions.css') }}">

<div class="task-submissions-page">


{{-- =========================
    HEADER
========================== --}}
<div class="task-submissions-header">

    <div class="task-submissions-decoration decoration-one"></div>
    <div class="task-submissions-decoration decoration-two"></div>

    <div class="task-submissions-header-content">

        <div>
            <span class="task-submissions-label">
                PENILAIAN TUGAS
            </span>

            <h1>
                Koreksi Tugas
            </h1>

            <div class="task-name">
                {{ $task->title }}
            </div>

            <p>
                {{ $teachingAssignment->classroom->name }}
                &middot;
                {{ $teachingAssignment->subject->name }}
                &middot;
                Batas waktu
                <strong>{{ $task->deadline->format('d M Y, H:i') }}</strong>
            </p>
        </div>

        <a href="{{ route('guru.teaching-assignments.tasks.index', $teachingAssignment) }}"
           class="task-submissions-back-button">
            ← Kembali
        </a>

    </div>

</div>


{{-- =========================
    TABLE CARD
========================== --}}
<div class="task-submissions-card">

    <div class="task-submissions-card-header">

        <div class="task-submissions-icon">
            ✓
        </div>

        <div>
            <h2>Daftar Pengumpulan Siswa</h2>

            <p>
                Periksa jawaban dan berikan nilai serta feedback kepada siswa.
            </p>
        </div>

    </div>


    <div class="task-submissions-table-wrapper">

        <table class="task-submissions-table">

            <thead>
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

                        {{-- NAMA --}}
                        <td>
                            <div class="student-name">
                                {{ $student->name }}
                            </div>
                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if (! $submission)

                                <span class="submission-status status-not-submitted">
                                    <span class="status-dot"></span>
                                    Belum Mengumpulkan
                                </span>

                            @elseif ($submission->isLate())

                                <span class="submission-status status-late">
                                    <span class="status-dot"></span>
                                    Terlambat
                                </span>

                            @else

                                <span class="submission-status status-on-time">
                                    <span class="status-dot"></span>
                                    Tepat Waktu
                                </span>

                            @endif

                        </td>


                        {{-- JAWABAN --}}
                        <td>

                            <div class="submission-answer">

                                @if ($submission?->file_path)

                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($submission->file_path) }}"
                                       target="_blank"
                                       class="submission-file">

                                        <span class="file-icon">📎</span>

                                        {{ $submission->file_original_name }}

                                    </a>

                                @endif


                                @if ($submission?->note)

                                    <div class="submission-note">
                                        {{ \Illuminate\Support\Str::limit($submission->note, 60) }}
                                    </div>

                                @endif


                                @if (! $submission)

                                    <span class="submission-empty">
                                        -
                                    </span>

                                @endif

                            </div>

                        </td>


                        {{-- NILAI --}}
                        <td class="text-center">

                            @if ($submission?->grade !== null)

                                <span class="grade-value">
                                    {{ $submission->grade }}
                                </span>

                            @else

                                <span class="grade-empty">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- FEEDBACK --}}
                        <td>

                            <div class="feedback-text">
                                {{ $submission?->feedback ?? '-' }}
                            </div>

                        </td>


                        {{-- AKSI --}}
                        <td class="task-submissions-actions">

                            @if ($submission)

                                <button type="button"
                                        class="grade-button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#grade-form-{{ $submission->id }}">

                                    {{ $submission->isGraded() ? 'Ubah Nilai' : 'Beri Nilai' }}

                                </button>

                            @else

                                <span class="action-empty">
                                    -
                                </span>

                            @endif

                        </td>

                    </tr>


                    {{-- =========================
                        FORM NILAI
                    ========================== --}}
                    @if ($submission)

                        <tr class="collapse grade-row"
                            id="grade-form-{{ $submission->id }}">

                            <td colspan="6">

                                <div class="grade-form-container">

                                    <div class="grade-form-title">
                                        <span class="grade-form-icon">
                                            ✎
                                        </span>

                                        <div>
                                            <strong>
                                                {{ $submission->isGraded() ? 'Perbarui Nilai' : 'Beri Nilai' }}
                                            </strong>

                                            <small>
                                                Penilaian untuk {{ $student->name }}
                                            </small>
                                        </div>
                                    </div>


                                    <form method="POST"
                                          action="{{ route('guru.teaching-assignments.tasks.submissions.grade', [$teachingAssignment, $task, $submission]) }}"
                                          class="grade-form">

                                        @csrf
                                        @method('PUT')


                                        <div class="grade-field grade-field-small">

                                            <label>
                                                Nilai
                                                <span>(0-100)</span>
                                            </label>

                                            <input type="number"
                                                   name="grade"
                                                   min="0"
                                                   max="100"
                                                   value="{{ $submission->grade }}"
                                                   required>

                                        </div>


                                        <div class="grade-field grade-field-large">

                                            <label>
                                                Feedback
                                                <span>(opsional)</span>
                                            </label>

                                            <input type="text"
                                                   name="feedback"
                                                   value="{{ $submission->feedback }}"
                                                   placeholder="Tulis feedback untuk siswa...">

                                        </div>


                                        <button type="submit"
                                                class="save-grade-button">
                                            Simpan Nilai
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endif


                @empty

                    <tr>

                        <td colspan="6">

                            <div class="submissions-empty">

                                <div class="submissions-empty-icon">
                                    ✓
                                </div>

                                <h3>Belum Ada Siswa</h3>

                                <p>
                                    Belum ada siswa di kelas ini.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


</div>

@endsection
