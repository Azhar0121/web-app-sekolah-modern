@extends('layouts.admin')

@section('title', 'Edit Kelas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/classrooms/edit.css') }}">

<div class="classroom-edit-page">

    {{-- HEADER --}}
    <div class="classroom-edit-header">
        <div>
            <span class="classroom-edit-label">KELOLA KELAS</span>
            <h1>Edit Kelas</h1>
            <p>Perbarui informasi kelas yang sudah terdaftar di sistem.</p>
        </div>

        <a href="{{ route('admin.classrooms.index') }}" class="back-button">
            ← Kembali
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="classroom-edit-card">

        <div class="classroom-edit-card-header">
            <div class="edit-icon">
                ✎
            </div>

            <div>
                <h2>Informasi Kelas</h2>
                <p>Silakan perbarui data kelas di bawah ini.</p>
            </div>
        </div>

        <div class="classroom-edit-card-body">

            <form method="POST" action="{{ route('admin.classrooms.update', $classroom) }}">
                @csrf
                @method('PUT')

                @include('admin.classrooms.form')

                <div class="classroom-edit-actions">
                    <a href="{{ route('admin.classrooms.index') }}" class="cancel-button">
                        Batal
                    </a>

                    <button type="submit" class="save-button">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection

