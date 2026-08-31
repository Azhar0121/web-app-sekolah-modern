@extends('layouts.admin')

@section('title', 'Tambah Kelas')

<link rel="stylesheet" href="{{ asset('css/classrooms/create.css') }}">

@section('content')

<div class="classroom-create-page">


{{-- HEADER --}}
<div class="classroom-create-header">
    <div class="classroom-create-header-content">
        <div>
            <span class="classroom-create-label">DATA AKADEMIK</span>
            <h1>Tambah Kelas</h1>
            <p>
                Tambahkan data kelas baru ke dalam sistem informasi sekolah.
            </p>
        </div>

        <a href="{{ route('admin.classrooms.index') }}" class="classroom-back-button">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                <path d="M19 12H5"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"/>
                <path d="M12 19L5 12L12 5"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

{{-- FORM CARD --}}
<div class="classroom-create-card">

    <div class="classroom-create-card-header">
        <div class="classroom-create-icon">
            <svg width="21" height="21" viewBox="0 0 24 24" fill="none">
                <path d="M4 19V5C4 3.9 4.9 3 6 3H18C19.1 3 20 3.9 20 5V19"
                      stroke="currentColor"
                      stroke-width="1.8"
                      stroke-linecap="round"/>
                <path d="M4 19C4 17.9 4.9 17 6 17H20"
                      stroke="currentColor"
                      stroke-width="1.8"
                      stroke-linecap="round"/>
                <path d="M8 7H16M8 11H16"
                      stroke="currentColor"
                      stroke-width="1.8"
                      stroke-linecap="round"/>
            </svg>
        </div>

        <div>
            <h2>Informasi Kelas</h2>
            <p>Lengkapi informasi kelas yang akan ditambahkan.</p>
        </div>
    </div>

    <div class="classroom-create-body">

        <form method="POST" action="{{ route('admin.classrooms.store') }}">
            @csrf

            @php($classroom = null)

            @include('admin.classrooms.form')

            <div class="classroom-form-actions">
                <a
                    href="{{ route('admin.classrooms.index') }}"
                    class="classroom-cancel-button"
                >
                    Batal
                </a>

                <button type="submit" class="classroom-save-button">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12L9.5 16.5L19 7"
                              stroke="currentColor"
                              stroke-width="2"
                              stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>


</div>
@endsection
