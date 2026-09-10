@extends('layouts.app')

@section('title', 'Materi Pembelajaran')

@section('content')

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet"
      href="{{ asset('css/siswa/materials/index.css') }}">

<div class="student-materials-page">

    {{-- =====================================================
        HERO
    ====================================================== --}}

    <div class="materials-header mb-4">

        <div class="materials-decoration materials-decoration-one"></div>
        <div class="materials-decoration materials-decoration-two"></div>

        <div class="materials-dot materials-dot-one"></div>
        <div class="materials-dot materials-dot-two"></div>

        <div class="materials-floating-icon materials-floating-one">
            <i class="bi bi-book-fill"></i>
        </div>

        <div class="materials-floating-icon materials-floating-two">
            <i class="bi bi-file-earmark-text-fill"></i>
        </div>

        <div class="materials-header-content">

            <div class="materials-header-icon">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>

            <div class="materials-header-text">

                <span class="materials-eyebrow">
                    AKADEMIK SISWA
                </span>

                <h4>MATERI PEMBELAJARAN</h4>

                <p class="mb-0">
                    Akses dan pelajari materi pembelajaran yang tersedia
                    untuk kelas Anda.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
        BELUM TERDAFTAR KELAS
    ====================================================== --}}

    @if (! $classroom)

        <div class="materials-alert">

            <div class="materials-alert-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div>
                <strong>Belum Terdaftar di Kelas</strong>

                <p>
                    Anda belum terdaftar di kelas manapun pada tahun ajaran ini.
                    Hubungi Tata Usaha / Wali Kelas.
                </p>
            </div>

        </div>


    {{-- =====================================================
        BELUM ADA MATERI
    ====================================================== --}}

    @elseif ($materialsBySubject->every(fn ($materials) => $materials->isEmpty()))

        <div class="materials-empty">

            <div class="empty-icon">
                <i class="bi bi-journal-x"></i>
            </div>

            <h5>Belum Ada Materi</h5>

            <p>
                Belum ada materi yang diunggah untuk kelas
                <strong>{{ $classroom->name }}</strong>.
            </p>

        </div>


    {{-- =====================================================
        DAFTAR MATERI
    ====================================================== --}}

    @else

        @foreach ($materialsBySubject as $subjectName => $materials)

            @continue($materials->isEmpty())

            <div class="material-subject-card mb-3">

                {{-- HEADER MAPEL --}}

                <div class="material-subject-header">

                    <div class="subject-title-wrapper">

                        <div class="subject-icon">
                            <i class="bi bi-book-fill"></i>
                        </div>

                        <div>

                            <span class="subject-eyebrow">
                                MATA PELAJARAN
                            </span>

                            <h5>
                                {{ $subjectName }}
                            </h5>

                        </div>

                    </div>

                    <div class="material-count">
                        {{ $materials->count() }} Materi
                    </div>

                </div>


                {{-- LIST MATERI --}}

                <div class="materials-list">

                    @foreach ($materials as $material)

                        <div class="material-item">

                            <div class="material-main">

                                <div class="material-file-icon">
                                    @if ($material->hasFile())
                                        <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                    @elseif ($material->hasLink())
                                        <i class="bi bi-link-45deg"></i>
                                    @else
                                        <i class="bi bi-file-text-fill"></i>
                                    @endif
                                </div>

                                <div class="material-info">

                                    <div class="material-title">
                                        {{ $material->title }}
                                    </div>

                                    @if ($material->description)

                                        <div class="material-description">
                                            {{ $material->description }}
                                        </div>

                                    @endif

                                    <div class="material-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $material->created_at->format('d M Y') }}
                                    </div>

                                </div>

                            </div>


                            {{-- ACTION --}}

                            <div class="material-actions">

                                @if ($material->hasFile())

                                    <a href="{{ route('siswa.materials.download', $material) }}"
                                       class="material-btn material-btn-download">

                                        <i class="bi bi-download"></i>

                                        <span>Unduh</span>

                                    </a>

                                @endif


                                @if ($material->hasLink())

                                    <a href="{{ $material->link }}"
                                       target="_blank"
                                       class="material-btn material-btn-link">

                                        <i class="bi bi-box-arrow-up-right"></i>

                                        <span>Buka Link</span>

                                    </a>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @endforeach

    @endif


    {{-- =====================================================
        KEMBALI KE DASHBOARD
    ====================================================== --}}

    <div class="materials-back-bottom">

        <a href="{{ route('siswa.dashboard') }}"
           class="back-dashboard">

            <i class="bi bi-arrow-left"></i>

            <span>Kembali</span>

        </a>

    </div>

</div>

@endsection