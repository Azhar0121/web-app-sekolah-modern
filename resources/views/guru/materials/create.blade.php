@extends('layouts.app')

@section('title', 'Tambah Materi')

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/materials/create.css') }}">

<div class="materials-create-page">


{{-- Header --}}
<div class="materials-create-header">

    <div class="materials-create-decoration decoration-one"></div>
    <div class="materials-create-decoration decoration-two"></div>

    <div class="materials-create-header-content">
        <div>
            <span class="materials-create-label">MATERI PEMBELAJARAN</span>

            <h1>Tambah Materi</h1>

            <p>
                Tambahkan materi pembelajaran untuk kelas
                <strong>{{ $teachingAssignment->classroom->name }}</strong>
                &middot;
                {{ $teachingAssignment->subject->name }}
            </p>
        </div>

        <a href="{{ route('guru.teaching-assignments.materials.index', $teachingAssignment) }}"
           class="materials-back-button">
            ← Kembali
        </a>
    </div>
</div>


{{-- Form Card --}}
<div class="materials-create-card">

    <div class="materials-create-card-header">
        <div class="materials-create-icon">
            +
        </div>

        <div>
            <h2>Informasi Materi</h2>
            <p>
                Isi informasi materi yang akan diberikan kepada siswa.
            </p>
        </div>
    </div>


    <div class="materials-create-card-body">

        <form method="POST"
              action="{{ route('guru.teaching-assignments.materials.store', $teachingAssignment) }}"
              enctype="multipart/form-data">

            @csrf

            @php($material = null)

            @include('guru.materials.form')

            <div class="materials-form-actions">

                <a href="{{ route('guru.teaching-assignments.materials.index', $teachingAssignment) }}"
                   class="materials-cancel-button">
                    Batal
                </a>

                <button type="submit" class="materials-submit-button">
                    Unggah Materi
                </button>

            </div>

        </form>

    </div>
</div>


</div>

@endsection
