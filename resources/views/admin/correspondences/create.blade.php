@extends('layouts.app')

@section('title', 'Catat Surat')

@section('content')

<link rel="stylesheet" href="{{ asset('css/correspondences/create.css') }}">

<div class="correspondence-create-page">


{{-- HEADER --}}
<div class="correspondence-create-header">
    <div class="correspondence-create-header-content">
        <span class="correspondence-create-label">
            PERSURATAN DIGITAL
        </span>

        <h1>
            {{ $type === 'masuk' ? 'Catat Surat Masuk' : 'Buat Surat Keluar' }}
        </h1>

        <p>
            Nomor {{ $type === 'masuk' ? 'agenda' : 'surat resmi' }} akan
            digenerate otomatis begitu disimpan.
        </p>
    </div>

    <div class="correspondence-create-header-mark">
        <span class="correspondence-create-header-dot"></span>
        <span>
            {{ $type === 'masuk' ? 'SURAT MASUK' : 'SURAT KELUAR' }}
        </span>
    </div>
</div>


{{-- FORM CARD --}}
<div class="correspondence-create-card">

    <div class="correspondence-create-card-header">
        <div>
            <span class="correspondence-create-card-label">
                FORMULIR SURAT
            </span>

            <h2>
                {{ $type === 'masuk' ? 'Data Surat Masuk' : 'Data Surat Keluar' }}
            </h2>
        </div>
    </div>

    <div class="correspondence-create-card-body">

        <form method="POST"
              action="{{ route('admin.correspondences.store') }}"
              enctype="multipart/form-data">

            @csrf

            @php($correspondence = null)

            @include('admin.correspondences.form')

            <div class="correspondence-create-actions">
                <button type="submit"
                        class="correspondence-create-submit">
                    Simpan
                </button>

                <a href="{{ route('admin.correspondences.index', ['type' => $type]) }}"
                   class="correspondence-create-cancel">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>


</div>

@endsection
