@extends('layouts.admin')

@section('title', 'Kelas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/classrooms/index.css') }}">

<div class="classrooms-page">


{{-- HEADER --}}
<div class="classrooms-header">
    <div class="classrooms-header-content">

        <div class="classrooms-title-area">
            <span class="classrooms-label">MANAJEMEN AKADEMIK</span>

            <h1>Kelas</h1>

            <p>
                Kelola data kelas, tingkat, jurusan, dan wali kelas dalam sistem sekolah.
            </p>
        </div>

        <div class="classrooms-hero-icon">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.7"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6.5 12 3l9 3.5-9 3.5L3 6.5Z"></path>
                <path d="M5 8.5V15c0 1.1 3.13 3.5 7 3.5s7-2.4 7-3.5V8.5"></path>
                <path d="M21 7v7"></path>
            </svg>
        </div>

    </div>
</div>


{{-- SEARCH --}}
<div class="classrooms-search-card">

    <div class="classrooms-search-header">

        <div class="classrooms-search-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"></circle>
                <path d="m20 20-3.5-3.5"></path>
            </svg>
        </div>

        <div>
            <h2>Cari Kelas</h2>
            <p>Gunakan pencarian untuk menemukan kelas dengan cepat.</p>
        </div>

    </div>


    <form method="GET"
          action="{{ route('admin.classrooms.index') }}"
          class="classrooms-search-form">

        <div class="classrooms-search-field">

            <label for="classroom-search">
                Nama Kelas
            </label>

            <div class="classrooms-input-wrapper">

                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"></circle>
                    <path d="m20 20-3.5-3.5"></path>
                </svg>

                <input
                    type="text"
                    name="search"
                    id="classroom-search"
                    value="{{ $search }}"
                    placeholder="Ketik nama kelas..."
                >

            </div>

        </div>

        <button type="submit" class="classrooms-search-button">
            Terapkan
        </button>

    </form>

</div>


{{-- TABLE --}}
<div class="classrooms-table-card">

    <div class="classrooms-card-top">

        <div class="classrooms-card-title">

            <div class="classrooms-title-icon">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 5h16v14H4z"></path>
                    <path d="M8 9h8M8 13h5"></path>
                </svg>
            </div>

            <div>
                <h2>Daftar Kelas</h2>
                <p>Data kelas yang tersedia dalam sistem.</p>
            </div>

        </div>


        <a href="{{ route('admin.classrooms.create') }}"
           class="classrooms-add-button">

            <span>+</span>
            Tambah Kelas

        </a>

    </div>


    <div class="classrooms-table-wrapper">

        <table class="classrooms-table">

            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Wali Kelas</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>


            <tbody>

                @forelse ($classrooms as $classroom)

                    <tr>

                        <td>

                            <div class="classroom-name">

                                <div class="classroom-avatar">
                                    {{ strtoupper(substr($classroom->name, 0, 1)) }}
                                </div>

                                <div>
                                    <strong>
                                        {{ $classroom->name }}
                                    </strong>

                                    <span>
                                        Kelas
                                    </span>
                                </div>

                            </div>

                        </td>


                        <td>

                            <span class="classroom-grade">
                                {{ $classroom->grade_level }}
                            </span>

                        </td>


                        <td>

                            <span class="classroom-major">
                                {{ $classroom->major ?? '-' }}
                            </span>

                        </td>


                        <td>

                            <span class="classroom-teacher">
                                {{ $classroom->homeroomTeacher?->name ?? '-' }}
                            </span>

                        </td>


                        <td class="text-center">

                            @if ($classroom->is_active)

                                <span class="classroom-status active">
                                    <span class="status-dot"></span>
                                    Aktif
                                </span>

                            @else

                                <span class="classroom-status inactive">
                                    <span class="status-dot"></span>
                                    Nonaktif
                                </span>

                            @endif

                        </td>


                        <td class="text-center">

                            <div class="classroom-actions">

                                <a href="{{ route('admin.classrooms.edit', $classroom) }}"
                                   class="classroom-edit-button">
                                    Edit
                                </a>


                                <form method="POST"
                                      action="{{ route('admin.classrooms.destroy', $classroom) }}"
                                      class="classroom-delete-form"
                                      onsubmit="return confirm('Yakin ingin menghapus kelas {{ $classroom->name }}?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="classroom-delete-button">
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="classrooms-empty">

                            <div class="classrooms-empty-icon">

                                <svg width="25" height="25" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="1.8"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path d="m20 20-3.5-3.5"></path>
                                </svg>

                            </div>

                            <strong>
                                Tidak ada kelas yang cocok.
                            </strong>

                            <span>
                                Coba gunakan kata kunci pencarian yang berbeda.
                            </span>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    @if ($classrooms->hasPages())

        <div class="classrooms-pagination">
            {{ $classrooms->links() }}
        </div>

    @endif

</div>


</div>

@endsection
