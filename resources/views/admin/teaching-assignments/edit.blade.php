@extends('layouts.admin')

@section('title', 'Edit Penugasan Mengajar')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/teaching/create.css') }}">

    <div class="teaching-create-page">

        {{-- HEADER --}}
        <div class="teaching-create-header">
            <div class="teaching-create-header-content">

                <div class="teaching-create-title">

                    <div class="teaching-create-title-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                        </svg>
                    </div>

                    <div>
                        <span class="teaching-create-label">
                            PENUGASAN MENGAJAR
                        </span>

                        <h1>Edit Penugasan</h1>

                        <p>
                            Perbarui informasi guru, kelas, dan mata pelajaran pada penugasan ini.
                        </p>
                    </div>

                </div>


                <a href="{{ route('admin.teaching-assignments.index') }}" class="teaching-back-button">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5" />
                        <path d="M12 19l-7-7 7-7" />
                    </svg>

                    Kembali

                </a>

            </div>
        </div>


        {{-- FORM CARD --}}
        <div class="teaching-create-card">

            <div class="teaching-create-card-header">

                <div class="teaching-create-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                    </svg>
                </div>

                <div>
                    <h2>Data Penugasan</h2>

                    <p>
                        Silakan ubah data penugasan mengajar sesuai kebutuhan.
                    </p>
                </div>

            </div>


            <div class="teaching-create-card-body">

                <form method="POST" action="{{ route('admin.teaching-assignments.update', $assignment) }}">

                    @csrf
                    @method('PUT')

                    @include('admin.teaching-assignments.form')


                    {{-- ACTION --}}
                    <div class="teaching-form-actions">

                        <a href="{{ route('admin.teaching-assignments.index') }}" class="teaching-cancel-button">
                            Batal
                        </a>

                        <button type="submit" class="teaching-save-button">

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
