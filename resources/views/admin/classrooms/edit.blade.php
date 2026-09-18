@extends('layouts.admin')

@section('title', 'Edit Kelas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/classrooms/edit.css') }}">

<div class="classroom-edit-page">

    {{-- HEADER --}}
    <div class="classroom-edit-header">

        <div class="classroom-edit-header-content">

            <div class="classroom-edit-title-area">

                <span class="classroom-edit-label">
                    KELOLA KELAS
                </span>

                <h1>
                    Edit Kelas
                </h1>

                <p>
                    Perbarui informasi kelas yang sudah terdaftar di sistem.
                </p>

            </div>

            <div class="classroom-edit-hero-icon">
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
    <div class="classroom-edit-card">

        <div class="classroom-edit-card-header">

            <div class="edit-icon">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor"
                     stroke-width="1.8"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"></path>
                </svg>
            </div>

            <div>
                <h2>Informasi Kelas</h2>
                <p>Silakan perbarui data kelas di bawah ini.</p>
            </div>

        </div>

        <div class="classroom-edit-card-body">

            <form method="POST"
                  action="{{ route('admin.classrooms.update', $classroom) }}">

                @csrf
                @method('PUT')

                @include('admin.classrooms.form')

                <div class="classroom-edit-actions">

                    <a href="{{ route('admin.classrooms.index') }}"
                       class="cancel-button">
                        Batal
                    </a>

                    <button type="submit"
                            class="save-button">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection