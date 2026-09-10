@extends('layouts.app')

@section('title', $task->title)

@section('content')

<link rel="stylesheet" href="{{ asset('css/siswa/tasks/show.css') }}">

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="student-task-detail">

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <div class="task-detail-header">

        <div class="task-detail-header-content">

            <div class="task-detail-icon">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>

            <div class="task-detail-info">

                <span class="task-detail-eyebrow">
                    DETAIL TUGAS
                </span>

                <h2>{{ $task->title }}</h2>

                <div class="task-detail-meta">

                    <span>
                        <i class="bi bi-book"></i>
                        {{ $task->teachingAssignment->subject->name }}
                    </span>

                    <span class="meta-divider"></span>

                    <span>
                        <i class="bi bi-calendar-event"></i>
                        {{ $task->deadline->format('d M Y, H:i') }}
                    </span>

                    @if ($task->isPastDeadline())
                        <span class="deadline-badge">
                            <i class="bi bi-exclamation-circle"></i>
                            Lewat Batas Waktu
                        </span>
                    @endif

                </div>

            </div>

        </div>


        {{-- Dekorasi --}}
        <div class="detail-decoration detail-decoration-one"></div>
        <div class="detail-decoration detail-decoration-two"></div>

        <div class="detail-dot detail-dot-one"></div>
        <div class="detail-dot detail-dot-two"></div>

        <div class="detail-floating-icon detail-floating-one">
            <i class="bi bi-pencil-square"></i>
        </div>

        <div class="detail-floating-icon detail-floating-two">
            <i class="bi bi-check2-circle"></i>
        </div>

    </div>


    {{-- =========================================================
        CONTENT
    ========================================================== --}}
    <div class="row g-4 task-detail-grid">

        {{-- =====================================================
            INSTRUKSI
        ====================================================== --}}
        <div class="col-lg-6">

            <div class="detail-card">

                <div class="detail-card-header">

                    <div class="detail-card-title">

                        <div class="card-title-icon">
                            <i class="bi bi-journal-text"></i>
                        </div>

                        <div>
                            <span>INFORMASI TUGAS</span>
                            <h5>Instruksi Tugas</h5>
                        </div>

                    </div>

                </div>


                <div class="detail-card-body">

                    <div class="instruction-box">

                        <div class="instruction-label">
                            <i class="bi bi-info-circle"></i>
                            Instruksi
                        </div>

                        <p style="white-space: pre-line;">
                            {{ $task->description ?: '-' }}
                        </p>

                    </div>


                    @if ($task->hasFile())

                        <div class="attachment-box">

                            <div class="attachment-icon">
                                <i class="bi bi-paperclip"></i>
                            </div>

                            <div class="attachment-info">
                                <strong>Lampiran Soal</strong>
                                <span>File tambahan dari guru</span>
                            </div>

                            <a href="{{ route('siswa.tasks.download-attachment', $task) }}"
                               class="download-btn">

                                <i class="bi bi-download"></i>
                                Unduh

                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =====================================================
            PENGUMPULAN
        ====================================================== --}}
        <div class="col-lg-6">

            <div class="detail-card submission-card">

                <div class="detail-card-header">

                    <div class="detail-card-title">

                        <div class="card-title-icon submission-icon">
                            <i class="bi bi-send-check"></i>
                        </div>

                        <div>
                            <span>STATUS TUGAS</span>
                            <h5>Pengumpulan Anda</h5>
                        </div>

                    </div>

                </div>


                <div class="detail-card-body">

                    {{-- NILAI --}}
                    @if ($submission?->isGraded())

                        <div class="grade-box">

                            <div class="grade-icon">
                                <i class="bi bi-award-fill"></i>
                            </div>

                            <div class="grade-info">
                                <span>Nilai Tugas</span>
                                <strong>{{ $submission->grade }}</strong>
                            </div>

                        </div>

                        @if ($submission->feedback)

                            <div class="feedback-box">

                                <div class="feedback-title">
                                    <i class="bi bi-chat-left-text"></i>
                                    Feedback Guru
                                </div>

                                <p>
                                    {{ $submission->feedback }}
                                </p>

                            </div>

                        @endif

                    @endif


                    {{-- DATA SUBMISSION --}}
                    @if ($submission)

                        <div class="submission-info">

                            <div class="submission-row">

                                <div class="submission-label">
                                    <i class="bi bi-clock-history"></i>
                                    Waktu Pengumpulan
                                </div>

                                <div class="submission-value">
                                    {{ $submission->submitted_at->format('d M Y, H:i') }}

                                    @if ($submission->isLate())
                                        <span class="late-badge">
                                            Terlambat
                                        </span>
                                    @endif
                                </div>

                            </div>


                            @if ($submission->file_path)

                                <div class="submission-row">

                                    <div class="submission-label">
                                        <i class="bi bi-file-earmark"></i>
                                        File Jawaban
                                    </div>

                                    <div class="submission-value">

                                        <a href="{{ route('siswa.tasks.download-submission', $submission) }}"
                                           class="submission-file">

                                            <i class="bi bi-paperclip"></i>
                                            {{ $submission->file_original_name }}

                                        </a>

                                    </div>

                                </div>

                            @endif


                            @if ($submission->note)

                                <div class="submission-note">

                                    <div class="submission-note-title">
                                        <i class="bi bi-chat-square-text"></i>
                                        Catatan Anda
                                    </div>

                                    <p>
                                        {{ $submission->note }}
                                    </p>

                                </div>

                            @endif

                        </div>

                    @endif


                    {{-- FORM --}}
                    @if ($submission?->isGraded())

                        <div class="graded-notice">

                            <i class="bi bi-lock-fill"></i>

                            <span>
                                Tugas sudah dinilai, pengumpulan tidak bisa
                                diubah lagi.
                            </span>

                        </div>

                    @else

                        <div class="submission-form">

                            <div class="form-divider">
                                <span>
                                    {{ $submission ? 'Perbarui Pengumpulan' : 'Kumpulkan Tugas' }}
                                </span>
                            </div>

                            <form method="POST"
                                  action="{{ route('siswa.tasks.store', $task) }}"
                                  enctype="multipart/form-data">

                                @csrf

                                <div class="form-group">

                                    <label for="note">
                                        <i class="bi bi-chat-left-text"></i>
                                        Catatan
                                        <small>(opsional)</small>
                                    </label>

                                    <textarea
                                        name="note"
                                        id="note"
                                        class="task-form-control"
                                        rows="3"
                                        placeholder="Tulis catatan untuk guru jika diperlukan..."
                                    >{{ old('note', $submission?->note) }}</textarea>

                                </div>


                                <div class="form-group">

                                    <label for="file">
                                        <i class="bi bi-paperclip"></i>
                                        File Jawaban
                                        <small>(opsional jika catatan sudah diisi)</small>
                                    </label>

                                    <div class="file-upload-wrapper">

                                        <input
                                            type="file"
                                            name="file"
                                            id="file"
                                            class="task-file-input"
                                        >

                                        <label for="file" class="file-upload-label">

                                            <i class="bi bi-cloud-arrow-up"></i>

                                            <span>
                                                Pilih file jawaban
                                                <small>Klik untuk memilih file</small>
                                            </span>

                                        </label>

                                    </div>

                                    @if ($submission?->file_path)

                                        <div class="previous-file">
                                            <i class="bi bi-info-circle"></i>
                                            File sebelumnya:
                                            <strong>
                                                {{ $submission->file_original_name }}
                                            </strong>.
                                            Upload baru untuk mengganti.
                                        </div>

                                    @endif

                                </div>


                                <button type="submit" class="submit-task-btn">

                                    <i class="bi bi-send-fill"></i>

                                    {{ $submission
                                        ? 'Perbarui Pengumpulan'
                                        : 'Kumpulkan Tugas'
                                    }}

                                </button>

                            </form>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        BACK BUTTON
    ========================================================== --}}
    <div class="task-detail-back">

        <a href="{{ route('siswa.tasks.index') }}"
           class="back-task-btn">

            <i class="bi bi-arrow-left"></i>
            Kembali ke Tugas

        </a>

    </div>

</div>

@endsection