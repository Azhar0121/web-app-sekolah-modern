@extends('layouts.admin')

@section('title', 'Tambah Mata Pelajaran')

@section('content')

<link rel="stylesheet" href="{{ asset('css/subjects/create.css') }}">

<div class="subject-create-page">

    {{-- HEADER --}}
    <div class="subject-create-header">

        <div class="subject-create-header-content">

            <div class="subject-create-title-area">
                <span class="subject-create-label">
                    KELOLA MATA PELAJARAN
                </span>

                <h1>Tambah Mata Pelajaran</h1>

                <p>
                    Tambahkan mata pelajaran baru ke dalam sistem sekolah.
                </p>
            </div>

            {{-- ICON MATA PELAJARAN --}}
            <div class="subject-create-header-icon" aria-hidden="true">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.7"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>

        </div>

    </div>


    {{-- FORM CARD --}}
    <div class="subject-create-card">

        {{-- CARD HEADER --}}
        <div class="subject-create-card-header">

            <div class="subject-create-icon">

                <svg width="20"
                     height="20"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">

                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>

                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>

                </svg>

            </div>

            <div>

                <h2>Informasi Mata Pelajaran</h2>

                <p>
                    Isi data mata pelajaran yang akan ditambahkan.
                </p>

            </div>

        </div>


        {{-- FORM BODY --}}
        <div class="subject-create-body">

            <form method="POST"
                  action="{{ route('admin.subjects.store') }}">

                @csrf

                @php($subject = null)

                @include('admin.subjects.form')

                <div class="subject-form-actions">

                    <a href="{{ route('admin.subjects.index') }}"
                       class="subject-cancel-button">
                        Batal
                    </a>

                    <button type="submit"
                            class="subject-save-button">

                        <svg width="15"
                             height="15"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>

                        </svg>

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection