@extends('layouts.admin')

@section('title', 'Edit Penugasan Mengajar')

@section('content')

<link rel="stylesheet" href="{{ asset('css/teaching/edit.css') }}">

<div class="teaching-edit-page">

    {{-- HEADER --}}
    <div class="teaching-edit-header">
        <div class="teaching-edit-header-content">

            <div class="teaching-edit-title-area">

                <span class="teaching-edit-label">
                    PENUGASAN MENGAJAR
                </span>

                <h1>Edit Penugasan</h1>

                <p>
                    Perbarui informasi guru, kelas, dan mata pelajaran pada penugasan ini.
                </p>

            </div>

            <div class="teaching-edit-hero-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 20h9" />
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                </svg>
            </div>

        </div>
    </div>


    {{-- FORM CARD --}}
    <div class="teaching-edit-card">

        {{-- CARD HEADER --}}
        <div class="teaching-edit-card-header">

            <div class="teaching-edit-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 20h9" />
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                </svg>
            </div>

            <div>
                <h2>Data Penugasan</h2>

                <p>
                    Periksa dan ubah data penugasan mengajar sesuai kebutuhan.
                </p>
            </div>

        </div>


        {{-- CARD BODY --}}
        <div class="teaching-edit-card-body">

            <form
                method="POST"
                action="{{ route('admin.teaching-assignments.update', $assignment) }}"
            >
                @csrf
                @method('PUT')

                @include('admin.teaching-assignments.form')


                {{-- ACTION --}}
                <div class="teaching-edit-form-actions">

                    <a
                        href="{{ route('admin.teaching-assignments.index') }}"
                        class="teaching-edit-cancel-button"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="teaching-edit-save-button"
                    >

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z" />
                            <path d="M17 21v-8H7v8" />
                            <path d="M7 3v5h8" />
                        </svg>

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection