@extends('layouts.app')

@section('title', 'Tugas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/siswa/tasks/index.css') }}">

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="student-tasks-page">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="tasks-header">

        <div class="tasks-header-content">

            <div class="tasks-header-icon">
                <i class="bi bi-journal-text"></i>
            </div>

            <div class="tasks-header-text">
                <span class="tasks-eyebrow">AKADEMIK SISWA</span>
                <h2>Tugas</h2>

                @if ($classroom)
                    <p>
                        Daftar tugas untuk kelas
                        <strong>{{ $classroom->name }}</strong>.
                    </p>
                @else
                    <p>Kelola dan pantau tugas pembelajaran Anda.</p>
                @endif
            </div>

        </div>

        {{-- Dekorasi hero --}}
        <div class="tasks-decoration tasks-decoration-one"></div>
        <div class="tasks-decoration tasks-decoration-two"></div>

        <div class="tasks-dot tasks-dot-one"></div>
        <div class="tasks-dot tasks-dot-two"></div>

        <div class="tasks-floating-icon tasks-floating-one">
            <i class="bi bi-pencil-square"></i>
        </div>

        <div class="tasks-floating-icon tasks-floating-two">
            <i class="bi bi-check2-square"></i>
        </div>

    </div>


    {{-- =========================================================
        ALERT KELAS
    ========================================================== --}}
    @if (! $classroom)

        <div class="tasks-alert">
            <div class="tasks-alert-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div>
                <strong>Belum Terdaftar di Kelas</strong>
                <p>
                    Anda belum terdaftar di kelas manapun pada tahun ajaran ini.
                    Hubungi Tata Usaha / Wali Kelas.
                </p>
            </div>
        </div>

    @else

        {{-- =====================================================
            TABLE TUGAS
        ====================================================== --}}
        <div class="tasks-card">

            <div class="tasks-card-top">

                <div>
                    <span class="tasks-section-label">
                        DAFTAR TUGAS
                    </span>

                    <h5>
                        Tugas Pembelajaran
                    </h5>
                </div>

                <div class="tasks-total">
                    <i class="bi bi-list-check"></i>
                    {{ $tasks->count() }} Tugas
                </div>

            </div>


            <div class="table-responsive tasks-table-wrapper">

                <table class="table align-middle mb-0 tasks-table">

                    <thead>
                        <tr>
                            <th>
                                <i class="bi bi-book"></i>
                                Mata Pelajaran
                            </th>

                            <th>
                                <i class="bi bi-file-earmark-text"></i>
                                Judul Tugas
                            </th>

                            <th>
                                <i class="bi bi-calendar-event"></i>
                                Batas Waktu
                            </th>

                            <th class="text-center">
                                <i class="bi bi-clipboard-check"></i>
                                Status
                            </th>

                            <th class="text-center">
                                <i class="bi bi-award"></i>
                                Nilai
                            </th>

                            <th class="text-end">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($tasks as $task)

                            <tr>

                                {{-- Mata Pelajaran --}}
                                <td>
                                    <div class="subject-cell">
                                        <span class="subject-icon">
                                            <i class="bi bi-book-half"></i>
                                        </span>

                                        <span>
                                            {{ $task->teachingAssignment->subject->name }}
                                        </span>
                                    </div>
                                </td>


                                {{-- Judul --}}
                                <td>
                                    <div class="task-title-cell">
                                        {{ $task->title }}
                                    </div>
                                </td>


                                {{-- Deadline --}}
                                <td>

                                    <div class="deadline-cell">

                                        <div>
                                            <i class="bi bi-clock"></i>
                                            {{ $task->deadline->format('d M Y, H:i') }}
                                        </div>

                                        @if (! $task->mySubmission && $task->isPastDeadline())
                                            <span class="deadline-late">
                                                <i class="bi bi-exclamation-circle"></i>
                                                Lewat
                                            </span>
                                        @endif

                                    </div>

                                </td>


                                {{-- Status --}}
                                <td class="text-center">

                                    @if (! $task->mySubmission)

                                        <span class="task-status status-pending">
                                            <i class="bi bi-hourglass-split"></i>
                                            Belum Dikumpulkan
                                        </span>

                                    @elseif ($task->mySubmission->isLate())

                                        <span class="task-status status-late">
                                            <i class="bi bi-exclamation-circle"></i>
                                            Terlambat
                                        </span>

                                    @else

                                        <span class="task-status status-done">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Sudah Dikumpulkan
                                        </span>

                                    @endif

                                </td>


                                {{-- Nilai --}}
                                <td class="text-center">

                                    @if ($task->mySubmission?->grade !== null)

                                        <span class="grade-value">
                                            {{ $task->mySubmission->grade }}
                                        </span>

                                    @else

                                        <span class="grade-empty">-</span>

                                    @endif

                                </td>


                                {{-- Aksi --}}
                                <td class="text-end">

                                    <a href="{{ route('siswa.tasks.show', $task) }}"
                                       class="task-view-btn">

                                        <i class="bi bi-eye"></i>
                                        Lihat

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6">

                                    <div class="tasks-empty">

                                        <div class="empty-icon">
                                            <i class="bi bi-journal-x"></i>
                                        </div>

                                        <h6>Belum Ada Tugas</h6>

                                        <p>
                                            Belum ada tugas untuk kelas Anda.
                                        </p>

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    @endif


    {{-- =========================================================
        BACK BUTTON
    ========================================================== --}}
    <div class="tasks-back-bottom">

        <a href="{{ route('siswa.dashboard') }}"
           class="back-dashboard">

            <i class="bi bi-arrow-left"></i>
            Kembali

        </a>

    </div>

</div>

@endsection