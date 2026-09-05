@extends('layouts.app')

@section('title', 'Buat Tugas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/tasks/create.css') }}">

<div class="tasks-create-page">


{{-- =========================
    HEADER
========================== --}}
<div class="tasks-create-header">

    <div class="tasks-create-decoration decoration-one"></div>
    <div class="tasks-create-decoration decoration-two"></div>

    <div class="tasks-create-header-content">

        <div>
            <span class="tasks-create-label">
                TUGAS PEMBELAJARAN
            </span>

            <h1>Buat Tugas</h1>

            <p>
                Buat tugas untuk kelas
                <strong>{{ $teachingAssignment->classroom->name }}</strong>
                &middot;
                {{ $teachingAssignment->subject->name }}
            </p>
        </div>

        <a href="{{ route('guru.teaching-assignments.tasks.index', $teachingAssignment) }}"
           class="tasks-create-back-button">
            ← Kembali
        </a>

    </div>

</div>


{{-- =========================
    FORM CARD
========================== --}}
<div class="tasks-create-card">

    <div class="tasks-create-card-header">

        <div class="tasks-create-icon">
            +
        </div>

        <div>
            <h2>Informasi Tugas</h2>

            <p>
                Isi informasi tugas yang akan diberikan kepada siswa.
            </p>
        </div>

    </div>


    <div class="tasks-create-card-body">

        <form method="POST"
              action="{{ route('guru.teaching-assignments.tasks.store', $teachingAssignment) }}"
              enctype="multipart/form-data">

            @csrf

            @php($task = null)

            @include('guru.tasks.form')


            {{-- FORM ACTIONS --}}
            <div class="tasks-create-form-actions">

                <a href="{{ route('guru.teaching-assignments.tasks.index', $teachingAssignment) }}"
                   class="tasks-create-cancel-button">
                    Batal
                </a>

                <button type="submit"
                        class="tasks-create-submit-button">
                    Simpan Tugas
                </button>

            </div>

        </form>

    </div>

</div>


</div>

@endsection
