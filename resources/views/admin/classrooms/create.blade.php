@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/classrooms/create.css') }}">

<div class="classroom-create-page">


{{-- HEADER --}}
<div class="classroom-create-header">

    <div class="classroom-create-header-content">

        <div class="classroom-create-title-area">

            <span class="classroom-create-label">
                DATA AKADEMIK
            </span>

            <h1>
                Tambah Kelas
            </h1>

            <p>
                Tambahkan data kelas baru ke dalam sistem informasi sekolah.
            </p>

        </div>


        <div class="classroom-create-hero-icon">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor"
                 stroke-width="1.7"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="M3 6.5 12 3l9 3.5-9 3.5L3 6.5Z"></path>
                <path d="M5 8.5V15c0 1.1 3.13 3.5 7 3.5s7-2.4 7-3.5V8.5"></path>
                <path d="M21 7v7"></path>
            </svg>
        </div>

    </div>

</div>


{{-- FORM CARD --}}
<div class="classroom-create-card">

    <div class="classroom-create-card-header">

        <div class="classroom-create-icon">
            <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor"
                 stroke-width="1.8"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="M4 19V5C4 3.9 4.9 3 6 3H18C19.1 3 20 3.9 20 5V19"></path>
                <path d="M4 19C4 17.9 4.9 17 6 17H20"></path>
                <path d="M8 7H16M8 11H16"></path>
            </svg>
        </div>

        <div>
            <h2>
                Informasi Kelas
            </h2>

            <p>
                Lengkapi informasi kelas yang akan ditambahkan.
            </p>
        </div>

    </div>


    <div class="classroom-create-body">

        <form method="POST"
              action="{{ route('admin.classrooms.store') }}">

            @csrf

            @php($classroom = null)

            @include('admin.classrooms.form')


            <div class="classroom-form-actions">

                <a href="{{ route('admin.classrooms.index') }}"
                   class="classroom-cancel-button">
                    Batal
                </a>


                <button type="submit"
                        class="classroom-save-button">

                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M5 12L9.5 16.5L19 7"></path>
                    </svg>

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>


</div>

@endsection
