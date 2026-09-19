@extends('layouts.app')

@section('title', 'Edit Surat')

@section('content')

<link rel="stylesheet" href="{{ asset('css/correspondences/edit.css') }}">

<div class="correspondence-create-page">


{{-- HEADER --}}
<div class="correspondence-create-header">

    <div class="correspondence-create-header-content">

        <span class="correspondence-create-label">
            PERSURATAN DIGITAL
        </span>

        <h1>
            Edit Surat
            <code>{{ $correspondence->number }}</code>
        </h1>

        <p>
            Jenis surat & nomor tidak bisa diubah, hanya detail isinya.
        </p>

    </div>

    <div class="correspondence-create-header-mark">
        <span class="correspondence-create-header-dot"></span>
        <span>EDIT SURAT</span>
    </div>

</div>


{{-- FORM CARD --}}
<div class="correspondence-create-card">

    {{-- CARD HEADER --}}
    <div class="correspondence-create-card-header">

        <div>
            <span class="correspondence-create-card-label">
                FORMULIR
            </span>

            <h2>
                Detail Surat
            </h2>
        </div>

    </div>


    {{-- CARD BODY --}}
    <div class="correspondence-create-card-body">

        <form method="POST"
              action="{{ route('admin.correspondences.update', $correspondence) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            {{-- FORM SURAT --}}
            @include('admin.correspondences.form')


            {{-- ACTION --}}
            <div class="correspondence-create-actions">

                <button type="submit"
                        class="correspondence-create-submit">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.correspondences.show', $correspondence) }}"
                   class="correspondence-create-cancel">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>


</div>

@endsection
