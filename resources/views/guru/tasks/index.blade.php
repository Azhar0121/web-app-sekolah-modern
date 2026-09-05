@extends('layouts.app')

@section('title', 'Tugas - ' . $teachingAssignment->subject->name)

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/tasks/index.css') }}">

<div class="tasks-page">


{{-- Header --}}
<div class="tasks-header">

    <div class="tasks-decoration tasks-decoration-one"></div>
    <div class="tasks-decoration tasks-decoration-two"></div>

    <div class="tasks-header-content">

        <div>
            <span class="tasks-label">TUGAS PEMBELAJARAN</span>

            <h1>Tugas</h1>

            <p>
                {{ $teachingAssignment->classroom->name }}
                &middot;
                {{ $teachingAssignment->subject->name }}
            </p>
        </div>

    </div>

    <div class="tasks-header-actions">

        <a href="{{ route('guru.dashboard') }}"
           class="tasks-dashboard-button">
            ← Dashboard
        </a>

        <a href="{{ route('guru.teaching-assignments.tasks.create', $teachingAssignment) }}"
           class="tasks-create-button">
            + Buat Tugas
        </a>

    </div>

</div>


{{-- Daftar Tugas --}}
<div class="tasks-card">

    <div class="tasks-card-header">

        <div class="tasks-card-icon">
            ✓
        </div>

        <div>
            <h2>Daftar Tugas</h2>

            <p>
                Kelola tugas dan pengumpulan siswa untuk kelas ini.
            </p>
        </div>

    </div>


    <div class="tasks-table-wrapper">

        <table class="tasks-table">

            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Batas Waktu</th>
                    <th class="text-center">Pengumpulan</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($tasks as $task)

                    <tr>

                        <td>
                            <div class="task-title">
                                {{ $task->title }}
                            </div>
                        </td>


                        <td>

                            <div class="task-deadline">
                                {{ $task->deadline->format('d M Y, H:i') }}

                                @if ($task->isPastDeadline())
                                    <span class="task-deadline-badge">
                                        Lewat
                                    </span>
                                @endif
                            </div>

                        </td>


                        <td class="text-center">

                            <a href="{{ route('guru.teaching-assignments.tasks.submissions', [$teachingAssignment, $task]) }}"
                               class="submission-link">
                                {{ $task->submissions_count }} siswa
                            </a>

                        </td>


                        <td class="text-center">

                            @if ($task->is_published)

                                <span class="task-status status-published">
                                    <span class="status-dot"></span>
                                    Terbit
                                </span>

                            @else

                                <span class="task-status status-draft">
                                    <span class="status-dot"></span>
                                    Draft
                                </span>

                            @endif

                        </td>


                        <td class="task-actions">

                            <a href="{{ route('guru.teaching-assignments.tasks.submissions', [$teachingAssignment, $task]) }}"
                               class="task-action task-correct">
                                Koreksi
                            </a>

                            <a href="{{ route('guru.teaching-assignments.tasks.edit', [$teachingAssignment, $task]) }}"
                               class="task-action task-edit">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('guru.teaching-assignments.tasks.destroy', [$teachingAssignment, $task]) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus tugas {{ $task->title }}? Semua pengumpulan siswa ikut terhapus.');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="task-action task-delete">
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5">

                            <div class="tasks-empty">

                                <div class="tasks-empty-icon">
                                    +
                                </div>

                                <h3>Belum Ada Tugas</h3>

                                <p>
                                    Belum ada tugas yang dibuat untuk kelas ini.
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
