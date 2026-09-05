@extends('layouts.app')

@section('title', 'Edit Materi')

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/materials/edit.css') }}">

<div class="materials-edit-page">


{{-- Header --}}
<div class="materials-edit-header">

    <div class="materials-edit-decoration decoration-one"></div>
    <div class="materials-edit-decoration decoration-two"></div>

    <div class="materials-edit-header-content">

        <div>
            <span class="materials-edit-label">MATERI PEMBELAJARAN</span>

            <h1>Edit Materi</h1>

            <p>
                Perbarui materi untuk kelas
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
<div class="materials-edit-card">

    <div class="materials-edit-card-header">

        <div class="materials-edit-icon">
            ✎
        </div>

        <div>
            <h2>Perbarui Informasi Materi</h2>

            <p>
                Ubah informasi materi pembelajaran sesuai kebutuhan.
            </p>
        </div>

    </div>


    <div class="materials-edit-card-body">

        <form method="POST"
              action="{{ route('guru.teaching-assignments.materials.update', [$teachingAssignment, $material]) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            @include('guru.materials.form')

            <div class="materials-form-actions">

                <a href="{{ route('guru.teaching-assignments.materials.index', $teachingAssignment) }}"
                   class="materials-cancel-button">
                    Batal
                </a>

                <button type="submit" class="materials-submit-button">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


</div>

@endsection
