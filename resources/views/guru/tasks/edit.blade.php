@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/tasks/edit.css') }}">

<div class="tasks-edit-page">


{{-- =========================
    HEADER
========================== --}}
<div class="tasks-edit-header">

    <div class="tasks-edit-decoration decoration-one"></div>
    <div class="tasks-edit-decoration decoration-two"></div>

    <div class="tasks-edit-header-content">

        <div>
            <span class="tasks-edit-label">
                TUGAS PEMBELAJARAN
            </span>

            <h1>Edit Tugas</h1>

            <p>
                Perbarui tugas untuk kelas
                <strong>{{ $teachingAssignment->classroom->name }}</strong>
                &middot;
                {{ $teachingAssignment->subject->name }}
            </p>
        </div>

        <a href="{{ route('guru.teaching-assignments.tasks.index', $teachingAssignment) }}"
           class="tasks-edit-back-button">
            ← Kembali
        </a>

    </div>

</div>


{{-- =========================
    FORM CARD
========================== --}}
<div class="tasks-edit-card">

    <div class="tasks-edit-card-header">

        <div class="tasks-edit-icon">
            ✎
        </div>

        <div>
            <h2>Perbarui Informasi Tugas</h2>

            <p>
                Ubah informasi tugas sesuai kebutuhan pembelajaran.
            </p>
        </div>

    </div>


    <div class="tasks-edit-card-body">

        <form method="POST"
              action="{{ route('guru.teaching-assignments.tasks.update', [$teachingAssignment, $task]) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            @include('guru.tasks.form')


            {{-- FORM ACTIONS --}}
            <div class="tasks-edit-form-actions">

                <a href="{{ route('guru.teaching-assignments.tasks.index', $teachingAssignment) }}"
                   class="tasks-edit-cancel-button">
                    Batal
                </a>

                <button type="submit"
                        class="tasks-edit-submit-button">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


</div>

@endsection
