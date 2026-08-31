@extends('layouts.admin')

@section('title', 'Tahun Ajaran & Semester')


<link rel="stylesheet" href="{{ asset('css/academic-years.css') }}">


@section('content')

<div class="academic-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="academic-header">

        <div class="academic-header-content">

            <div class="academic-title-area">

                <div class="academic-label">
                    AKADEMIK
                </div>

                <h1>Tahun Ajaran & Semester</h1>

                <p>
                    Kelola tahun ajaran dan semester yang digunakan
                    sebagai acuan kegiatan akademik sekolah.
                </p>

            </div>

            <a href="{{ route('admin.academic-years.create') }}"
               class="academic-add-button">
                <span>+</span>
                Tambah Tahun Ajaran
            </a>

        </div>

    </div>


    {{-- =====================================================
         MAIN CARD
    ====================================================== --}}
    <div class="academic-main-card">

        {{-- =================================================
             CARD TOP
        ================================================== --}}
        <div class="academic-card-top">

            <div class="academic-card-title">

                <div class="academic-title-icon">
                    <svg width="21"
                         height="21"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>

                <div>
                    <h2>Daftar Tahun Ajaran</h2>

                    <p>
                        Tahun ajaran dan semester yang tersedia dalam sistem.
                    </p>
                </div>

            </div>

        </div>


        {{-- =================================================
             TABLE
        ================================================== --}}
        <div class="academic-table-wrapper">

            <table class="academic-table">

                <thead>
                    <tr>
                        <th>Tahun Ajaran</th>
                        <th>Periode</th>
                        <th class="text-center">Jumlah Semester</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($academicYears as $year)

                        <tr>

                            {{-- Tahun Ajaran --}}
                            <td>

                                <div class="academic-year-name">

                                    <div class="academic-year-avatar">
                                        {{ substr($year->name, 0, 2) }}
                                    </div>

                                    <div>
                                        <strong>
                                            {{ $year->name }}
                                        </strong>

                                        <span>
                                            Tahun Ajaran
                                        </span>
                                    </div>

                                </div>

                            </td>


                            {{-- Periode --}}
                            <td>

                                <div class="academic-period">

                                    <svg width="16"
                                         height="16"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <rect x="3"
                                              y="4"
                                              width="18"
                                              height="17"
                                              rx="2"/>
                                        <line x1="16"
                                              y1="2"
                                              x2="16"
                                              y2="6"/>
                                        <line x1="8"
                                              y1="2"
                                              x2="8"
                                              y2="6"/>
                                    </svg>

                                    <span>
                                        {{ $year->start_date->format('d M Y') }}
                                        &ndash;
                                        {{ $year->end_date->format('d M Y') }}
                                    </span>

                                </div>

                            </td>


                            {{-- Jumlah Semester --}}
                            <td class="text-center">

                                <span class="academic-number-badge">
                                    {{ $year->semesters_count }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="text-center">

                                @if ($year->is_active)

                                    <span class="academic-status active">
                                        <span class="status-dot"></span>
                                        Aktif
                                    </span>

                                @else

                                    <span class="academic-status inactive">
                                        <span class="status-dot"></span>
                                        Nonaktif
                                    </span>

                                @endif

                            </td>


                            {{-- Aksi --}}
                            <td class="text-center">

                                <div class="academic-actions">

                                    <a href="{{ route('admin.academic-years.edit', $year) }}"
                                       class="academic-manage-button">

                                        Kelola

                                        <svg width="15"
                                             height="15"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <line x1="5"
                                                  y1="12"
                                                  x2="19"
                                                  y2="12"/>
                                            <polyline points="12 5 19 12 12 19"/>
                                        </svg>

                                    </a>


                                    <form method="POST"
                                          action="{{ route('admin.academic-years.destroy', $year) }}"
                                          class="academic-delete-form"
                                          onsubmit="return confirm('Yakin ingin menghapus tahun ajaran {{ $year->name }}? Semua semester di dalamnya ikut terhapus.');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="academic-delete-button"
                                                @disabled($year->is_active)>

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="academic-empty">

                                <div class="academic-empty-icon">
                                    <svg width="24"
                                         height="24"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <rect x="3"
                                              y="4"
                                              width="18"
                                              height="17"
                                              rx="2"/>
                                        <line x1="16"
                                              y1="2"
                                              x2="16"
                                              y2="6"/>
                                        <line x1="8"
                                              y1="2"
                                              x2="8"
                                              y2="6"/>
                                    </svg>
                                </div>

                                <strong>
                                    Belum ada tahun ajaran
                                </strong>

                                <span>
                                    Tambahkan tahun ajaran untuk mulai mengelola semester.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =====================================================
         INFORMATION BOX
    ====================================================== --}}
    <div class="academic-information">

        <div class="academic-information-icon">

            <svg width="20"
                 height="20"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <circle cx="12"
                        cy="12"
                        r="9"/>
                <line x1="12"
                      y1="10"
                      x2="12"
                      y2="16"/>
                <line x1="12"
                      y1="7"
                      x2="12.01"
                      y2="7"/>
            </svg>

        </div>

        <div class="academic-information-content">

            <h3>Informasi Tahun Ajaran</h3>

            <p>
                Hanya boleh ada 1 tahun ajaran dan 1 semester yang aktif
                dalam sistem pada satu waktu. Semester aktif inilah yang
                nantinya digunakan sebagai acuan modul nilai, presensi,
                dan materi pembelajaran.
            </p>

        </div>

    </div>

</div>

@endsection

