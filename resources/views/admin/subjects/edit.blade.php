blade
@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran')

@section('content')

<link rel="stylesheet" href="{{ asset('css/subjects/edit.css') }}">

<div class="subject-edit-page">

    {{-- HEADER --}}
    <div class="subject-edit-header">

        <div class="subject-edit-header-content">

            <div>
                <span class="subject-edit-label">KELOLA MATA PELAJARAN</span>

                <h1>Edit Mata Pelajaran</h1>

                <p>
                    Perbarui informasi mata pelajaran yang sudah terdaftar di sistem.
                </p>
            </div>

            <a href="{{ route('admin.subjects.index') }}" class="subject-back-button">
                <span class="back-icon">←</span>
                Kembali
            </a>

        </div>

    </div>


    {{-- FORM CARD --}}
    <div class="subject-edit-card">

        {{-- CARD HEADER --}}
        <div class="subject-edit-card-header">

            <div class="subject-edit-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                </svg>
            </div>

            <div>
                <h2>Informasi Mata Pelajaran</h2>

                <p>
                    Silakan perbarui data mata pelajaran di bawah ini.
                </p>
            </div>

        </div>


        {{-- CARD BODY --}}
        <div class="subject-edit-body">

            <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">

                @csrf
                @method('PUT')

                @include('admin.subjects.form')


                {{-- ACTION BUTTON --}}
                <div class="subject-form-actions">

                    <a href="{{ route('admin.subjects.index') }}"
                       class="subject-cancel-button">
                        Batal
                    </a>

                    <button type="submit" class="subject-save-button">

                        <svg width="17" height="17"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>

                        <span>Simpan Perubahan</span>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

