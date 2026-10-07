@extends('layouts.admin')

@section('title', 'Biodata Siswa')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/student-profiles/index.css') }}">

<div class="container-fluid student-profiles-page">

    {{-- HERO --}}
    <section class="student-profile-hero">

        <div class="student-profile-hero-content">

            <div class="student-profile-hero-icon">
                <i class="bi bi-person-vcard-fill"></i>
            </div>

            <div>
                <span class="student-profile-hero-label">
                    DATA SISWA
                </span>

                <h1>
                    Biodata Siswa
                </h1>

                <p>
                    Kelola dan periksa informasi biodata siswa secara terpusat.
                </p>
            </div>

        </div>

        <div class="student-profile-hero-info">

            <div class="student-profile-info-icon">
                <i class="bi bi-people-fill"></i>
            </div>

            <div>
                <span>DATA SISWA</span>
                <strong>{{ $students->total() }} Siswa</strong>
            </div>

        </div>

    </section>


    {{-- FILTER --}}
    <section class="student-profile-filter-card">

        <div class="student-profile-filter-header">

            <div class="student-profile-filter-title">

                <div class="student-profile-filter-icon">
                    <i class="bi bi-funnel-fill"></i>
                </div>

                <div>
                    <span>FILTER DATA</span>
                    <h2>Cari & Filter Siswa</h2>
                </div>

            </div>

        </div>


        <div class="student-profile-filter-body">

            <form
                method="GET"
                action="{{ route('admin.student-profiles.index') }}"
                class="student-profile-filter-form"
            >

                <div class="student-profile-filter-field search-field">

                    <label for="search">
                        Pencarian
                    </label>

                    <div class="student-profile-input-wrap">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ $search }}"
                            class="form-control"
                            placeholder="Cari nama, email, atau NISN..."
                        >

                    </div>

                </div>


                <div class="student-profile-filter-field">

                    <label for="classroom_id">
                        Kelas
                    </label>

                    <div class="student-profile-input-wrap">

                        <i class="bi bi-mortarboard-fill"></i>

                        <select
                            name="classroom_id"
                            id="classroom_id"
                            class="form-select"
                        >

                            <option value="">
                                Semua Kelas
                            </option>

                            @foreach ($classrooms as $classroom)

                                <option
                                    value="{{ $classroom->id }}"
                                    @selected($classroomId == $classroom->id)
                                >
                                    {{ $classroom->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="student-profile-filter-action">

                    <button
                        type="submit"
                        class="student-profile-filter-btn"
                    >

                        <i class="bi bi-search"></i>

                        Filter Data

                    </button>

                </div>

            </form>

        </div>

    </section>


    {{-- DATA SISWA --}}
    <section class="student-profile-table-card">

        <div class="student-profile-table-header">

            <div>

                <span class="student-profile-table-label">
                    DAFTAR SISWA
                </span>

                <h2>
                    Data Biodata Siswa
                </h2>

                <p>
                    Informasi dasar siswa yang tersedia di sistem.
                </p>

            </div>

            <div class="student-profile-count">

                <i class="bi bi-people-fill"></i>

                <span>
                    {{ $students->total() }} siswa
                </span>

            </div>

        </div>


        <div class="student-profile-table-wrapper">

            <div class="table-responsive">

                <table class="table student-profile-table align-middle mb-0">

                    <thead>

                        <tr>

                            <th>
                                Nama Siswa
                            </th>

                            <th>
                                NISN
                            </th>

                            <th>
                                Kelas
                            </th>

                            <th>
                                No. HP
                            </th>

                            <th class="text-end">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($students as $student)

                            <tr>

                                {{-- NAMA --}}
                                <td>

                                    <div class="student-profile-name">

                                        <div class="student-avatar">
                                            <i class="bi bi-person-fill"></i>
                                        </div>

                                        <div>

                                            <strong>
                                                {{ $student->name }}
                                            </strong>

                                            <span>
                                                {{ $student->email }}
                                            </span>

                                        </div>

                                    </div>

                                </td>


                                {{-- NISN --}}
                                <td>

                                    <span class="student-profile-value">
                                        {{ $student->studentProfile?->nisn ?? '-' }}
                                    </span>

                                </td>


                                {{-- KELAS --}}
                                <td>

                                    @if ($student->classroomForDisplay?->name)

                                        <span class="student-class-badge">
                                            <i class="bi bi-mortarboard-fill"></i>
                                            {{ $student->classroomForDisplay->name }}
                                        </span>

                                    @else

                                        <span class="student-empty-value">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- NO HP --}}
                                <td>

                                    @if ($student->studentProfile?->phone)

                                        <span class="student-phone">
                                            <i class="bi bi-telephone-fill"></i>
                                            {{ $student->studentProfile->phone }}
                                        </span>

                                    @else

                                        <span class="student-empty-value">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td class="text-end">

                                    <a
                                        href="{{ route('admin.student-profiles.edit', $student) }}"
                                        class="student-profile-action-btn"
                                    >

                                        <i class="bi bi-pencil-square"></i>

                                        Kelola Biodata

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5">

                                    <div class="student-profile-empty">

                                        <div class="student-profile-empty-icon">
                                            <i class="bi bi-person-x-fill"></i>
                                        </div>

                                        <div>

                                            <strong>
                                                Data siswa tidak ditemukan
                                            </strong>

                                            <span>
                                                Tidak ada siswa yang cocok dengan pencarian atau filter yang dipilih.
                                            </span>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PAGINATION --}}
        @if ($students->hasPages())

            <div class="student-profile-pagination">

                <div class="student-profile-pagination-info">
                    Menampilkan halaman data siswa
                </div>

                <div>
                    {{ $students->links() }}
                </div>

            </div>

        @endif

    </section>

</div>

@endsection