@extends('layouts.admin')

@section('title', 'Mata Pelajaran')

@section('content')

<link rel="stylesheet" href="{{ asset('css/subjects/index.css') }}">

<div class="subjects-page">


{{-- HEADER --}}
<div class="subjects-header">

    <div class="subjects-header-content">

        <div class="subjects-title-area">

            <span class="subjects-label">
                DATA AKADEMIK
            </span>

            <h1>Mata Pelajaran</h1>

            <p>
                Kelola daftar mata pelajaran yang tersedia
                di dalam sistem sekolah.
            </p>

        </div>

        <a href="{{ route('admin.subjects.create') }}"
           class="subjects-add-button">

            <svg width="17" height="17"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <path d="M12 5v14"></path>
                <path d="M5 12h14"></path>

            </svg>

            <span>Tambah Mata Pelajaran</span>

        </a>

    </div>

</div>


{{-- SEARCH --}}
<div class="subjects-filter-card">

    <div class="subjects-filter-icon">

        <svg width="19" height="19"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">

            <circle cx="11" cy="11" r="7"></circle>
            <path d="m20 20-4-4"></path>

        </svg>

    </div>

    <div class="subjects-filter-content">

        <div class="subjects-filter-title">
            Cari Mata Pelajaran
        </div>

        <div class="subjects-filter-subtitle">
            Cari berdasarkan nama atau kode mata pelajaran.
        </div>

    </div>

    <form method="GET"
          action="{{ route('admin.subjects.index') }}"
          class="subjects-search-form">

        <input
            type="text"
            name="search"
            value="{{ $search }}"
            class="subjects-search-input"
            placeholder="Ketik nama atau kode...">

        <button type="submit"
                class="subjects-search-button">

            <svg width="15" height="15"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <path d="M21 21l-4.35-4.35"></path>
                <circle cx="11" cy="11" r="7"></circle>

            </svg>

            Terapkan

        </button>

    </form>

</div>


{{-- TABLE CARD --}}
<div class="subjects-main-card">

    <div class="subjects-card-top">

        <div class="subjects-card-title">

            <div class="subjects-title-icon">

                <svg width="20" height="20"
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

                <h2>Daftar Mata Pelajaran</h2>

                <p>
                    Data mata pelajaran yang terdaftar dalam sistem.
                </p>

            </div>

        </div>

    </div>


    <div class="subjects-table-wrapper">

        <table class="subjects-table">

            <thead>

                <tr>

                    <th>Kode</th>

                    <th>Nama Mata Pelajaran</th>

                    <th class="text-center">
                        Status
                    </th>

                    <th class="text-center">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse ($subjects as $subject)

                    <tr>

                        <td>

                            <span class="subject-code">
                                {{ $subject->code }}
                            </span>

                        </td>

                        <td>

                            <div class="subject-name">
                                {{ $subject->name }}
                            </div>

                        </td>

                        <td class="text-center">

                            @if ($subject->is_active)

                                <span class="subject-status active">
                                    Aktif
                                </span>

                            @else

                                <span class="subject-status inactive">
                                    Nonaktif
                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            <div class="subject-actions">

                                <a href="{{ route('admin.subjects.edit', $subject) }}"
                                   class="subject-edit-button">

                                    <svg width="14" height="14"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">

                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>

                                    </svg>

                                    Edit

                                </a>


                                <form method="POST"
                                      action="{{ route('admin.subjects.destroy', $subject) }}"
                                      class="subject-delete-form"
                                      onsubmit="return confirm('Yakin ingin menghapus mata pelajaran {{ $subject->name }}?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="subject-delete-button">

                                        <svg width="14" height="14"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">

                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6l-1 14H6L5 6"></path>
                                            <path d="M10 11v6"></path>
                                            <path d="M14 11v6"></path>
                                            <path d="M9 6V4h6v2"></path>

                                        </svg>

                                        Hapus

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4"
                            class="subjects-empty">

                            <div class="subjects-empty-icon">

                                <svg width="22" height="22"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">

                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M8 12h8"></path>

                                </svg>

                            </div>

                            <strong>
                                Tidak ada mata pelajaran
                            </strong>

                            <span>
                                Tidak ditemukan mata pelajaran
                                yang sesuai dengan pencarian.
                            </span>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    @if ($subjects->hasPages())

        <div class="subjects-pagination">
            {{ $subjects->links() }}
        </div>

    @endif

</div>


</div>

@endsection
