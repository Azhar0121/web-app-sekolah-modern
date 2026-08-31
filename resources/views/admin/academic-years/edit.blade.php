blade
@extends('layouts.admin')

@section('title', 'Kelola Tahun Ajaran')

@section('content')

<link rel="stylesheet" href="{{ asset('css/academic-years-edit.css') }}">

<div class="academic-edit-page">

    {{-- HEADER --}}
    <div class="academic-edit-header">
        <div class="academic-edit-header-content">

            <div class="academic-edit-label">
                TAHUN AJARAN
            </div>

            <h1>
                Kelola Tahun Ajaran
            </h1>

            <p>
                Kelola informasi tahun ajaran dan semester yang digunakan
                dalam sistem sekolah.
            </p>

        </div>
    </div>


    {{-- ERROR --}}
    @if ($errors->any())
        <div class="academic-edit-alert">
            <div class="academic-edit-alert-icon">!</div>

            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif


    {{-- MAIN GRID --}}
    <div class="academic-edit-grid">


        {{-- DATA TAHUN AJARAN --}}
        <div class="academic-edit-card">

            <div class="academic-edit-card-header">

                <div class="academic-edit-card-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M7 3v4M17 3v4M4 9h16M5 5h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"
                              stroke="currentColor"
                              stroke-width="1.8"
                              stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </div>

                <div>
                    <h2>Data Tahun Ajaran</h2>
                    <p>Perbarui informasi tahun ajaran sekolah.</p>
                </div>

            </div>


            <div class="academic-edit-card-body">

                <form method="POST" action="{{ route('admin.academic-years.update', $academicYear) }}">
                    @csrf
                    @method('PUT')


                    {{-- NAMA --}}
                    <div class="academic-edit-form-group">

                        <label for="name">
                            Nama Tahun Ajaran
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $academicYear->name) }}"
                            required
                        >

                    </div>


                    {{-- TANGGAL --}}
                    <div class="academic-edit-date-grid">

                        <div class="academic-edit-form-group">

                            <label for="start_date">
                                Tanggal Mulai
                            </label>

                            <input
                                type="date"
                                name="start_date"
                                id="start_date"
                                value="{{ old('start_date', $academicYear->start_date->format('Y-m-d')) }}"
                                required
                            >

                        </div>


                        <div class="academic-edit-form-group">

                            <label for="end_date">
                                Tanggal Selesai
                            </label>

                            <input
                                type="date"
                                name="end_date"
                                id="end_date"
                                value="{{ old('end_date', $academicYear->end_date->format('Y-m-d')) }}"
                                required
                            >

                        </div>

                    </div>


                    {{-- STATUS --}}
                    @unless ($academicYear->is_active)

                        <div class="academic-edit-active-box">

                            <label class="academic-edit-checkbox">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    id="is_active"
                                    value="1"
                                >

                                <span class="academic-edit-checkmark"></span>

                                <span class="academic-edit-checkbox-text">
                                    <strong>Jadikan tahun ajaran aktif</strong>

                                    <small>
                                        Mengaktifkan tahun ajaran ini akan
                                        menonaktifkan tahun ajaran lainnya.
                                    </small>
                                </span>

                            </label>

                        </div>

                    @else

                        <div class="academic-edit-active-status">

                            <span class="academic-edit-status-dot"></span>

                            <div>
                                <strong>Tahun ajaran aktif</strong>
                                <span>Tahun ajaran ini sedang digunakan dalam sistem.</span>
                            </div>

                        </div>

                    @endunless


                    {{-- ACTION --}}
                    <div class="academic-edit-actions">

                        <a
                            href="{{ route('admin.academic-years.index') }}"
                            class="academic-edit-cancel"
                        >
                            Kembali
                        </a>

                        <button
                            type="submit"
                            class="academic-edit-save"
                        >
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>



        {{-- SEMESTER --}}
        <div class="academic-edit-card">

            <div class="academic-edit-card-header">

                <div class="academic-edit-card-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z"
                              stroke="currentColor"
                              stroke-width="1.8"
                              stroke-linejoin="round"/>
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"
                              stroke="currentColor"
                              stroke-width="1.8"
                              stroke-linecap="round"/>
                    </svg>
                </div>

                <div>
                    <h2>Semester</h2>
                    <p>Kelola semester pada tahun ajaran ini.</p>
                </div>

            </div>


            <div class="academic-edit-card-body">

                @if ($academicYear->semesters->isEmpty())

                    <div class="academic-edit-empty">

                        <div class="academic-edit-empty-icon">
                            +
                        </div>

                        <strong>Belum ada semester</strong>

                        <span>
                            Tambahkan semester untuk tahun ajaran ini.
                        </span>

                    </div>

                @else

                    <div class="academic-edit-semester-list">

                        @foreach ($academicYear->semesters as $semester)

                            <div class="academic-edit-semester-item">

                                <div class="academic-edit-semester-info">

                                    <div class="academic-edit-semester-number">
                                        {{ $loop->iteration }}
                                    </div>

                                    <div>

                                        <strong>
                                            Semester {{ $semester->name }}
                                        </strong>

                                        @if ($semester->is_active)
                                            <span class="academic-edit-active-badge">
                                                Aktif
                                            </span>
                                        @else
                                            <small>
                                                Belum aktif
                                            </small>
                                        @endif

                                    </div>

                                </div>


                                @unless ($semester->is_active)

                                    <div class="academic-edit-semester-actions">

                                        <form
                                            method="POST"
                                            action="{{ route('admin.academic-years.semesters.activate', [$academicYear, $semester]) }}"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="academic-edit-activate"
                                            >
                                                Aktifkan
                                            </button>
                                        </form>


                                        <form
                                            method="POST"
                                            action="{{ route('admin.academic-years.semesters.destroy', [$academicYear, $semester]) }}"
                                            onsubmit="return confirm('Hapus semester {{ $semester->name }}?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="academic-edit-delete"
                                            >
                                                Hapus
                                            </button>
                                        </form>

                                    </div>

                                @endunless

                            </div>

                        @endforeach

                    </div>

                @endif


                {{-- TAMBAH SEMESTER --}}
                @if ($academicYear->semesters->count() < 2)

                    <div class="academic-edit-add-section">

                        <div class="academic-edit-add-title">
                            Tambahkan Semester
                        </div>

                        <form
                            method="POST"
                            action="{{ route('admin.academic-years.semesters.store', $academicYear) }}"
                            class="academic-edit-add-form"
                        >
                            @csrf

                            <select
                                name="name"
                                required
                            >
                                <option value="">
                                    -- Pilih Semester --
                                </option>

                                @foreach (['Ganjil', 'Genap'] as $option)

                                    @unless ($academicYear->semesters->contains('name', $option))

                                        <option value="{{ $option }}">
                                            {{ $option }}
                                        </option>

                                    @endunless

                                @endforeach

                            </select>

                            <button type="submit">
                                + Tambah
                            </button>

                        </form>

                    </div>

                @endif


                {{-- INFORMATION --}}
                <div class="academic-edit-information">

                    <div class="academic-edit-information-icon">
                        i
                    </div>

                    <p>
                        Mengaktifkan sebuah semester otomatis mengaktifkan
                        tahun ajaran ini dan menonaktifkan tahun ajaran /
                        semester lain di seluruh sistem.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

