@extends('layouts.admin')

@section('title', 'Tambah Tahun Ajaran')

<link rel="stylesheet" href="{{ asset('css/academic-years-create.css') }}">

@section('content')

<div class="academic-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="academic-header">

        <div class="academic-header-content">

            <div class="academic-title-area">

                <div class="academic-label">
                    Tahun Ajaran
                </div>

                <h1>
                    Tambah Tahun Ajaran
                </h1>

                <p>
                    Tambahkan tahun ajaran baru dan tentukan periode
                    pembelajaran yang akan digunakan dalam sistem.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
         FORM CARD
    ====================================================== --}}
    <div class="academic-form-card">

        <div class="academic-form-header">

            <div class="academic-form-icon">
                <svg width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="2">
                    <rect x="3" y="4" width="18" height="18"
                          rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>

            <div>
                <h2>Informasi Tahun Ajaran</h2>

                <p>
                    Lengkapi data berikut untuk membuat tahun ajaran baru.
                </p>
            </div>

        </div>


        <div class="academic-form-body">

            {{-- =================================================
                 ERROR
            ================================================== --}}
            @if ($errors->any())

                <div class="academic-alert">

                    <div class="academic-alert-icon">
                        !
                    </div>

                    <div class="academic-alert-content">

                        <strong>
                            Terdapat kesalahan
                        </strong>

                        @foreach ($errors->all() as $error)
                            <div>
                                {{ $error }}
                            </div>
                        @endforeach

                    </div>

                </div>

            @endif


            {{-- =================================================
                 FORM
            ================================================== --}}
            <form method="POST"
                  action="{{ route('admin.academic-years.store') }}">

                @csrf


                {{-- NAMA TAHUN AJARAN --}}
                <div class="academic-form-group">

                    <label for="name">
                        Nama Tahun Ajaran
                    </label>

                    <span class="academic-required">
                        *
                    </span>

                    <div class="academic-input-wrapper">

                        <span class="academic-input-icon">

                            <svg width="18" height="18"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <rect x="3" y="4"
                                      width="18"
                                      height="18"
                                      rx="2"></rect>

                                <line x1="16" y1="2"
                                      x2="16" y2="6"></line>

                                <line x1="8" y1="2"
                                      x2="8" y2="6"></line>

                                <line x1="3" y1="10"
                                      x2="21" y2="10"></line>

                            </svg>

                        </span>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            placeholder="Contoh: 2026/2027"
                            value="{{ old('name') }}"
                            required
                            autofocus
                        >

                    </div>

                    <small>
                        Gunakan format tahun ajaran, misalnya 2026/2027.
                    </small>

                </div>


                {{-- =================================================
                     TANGGAL
                ================================================== --}}
                <div class="academic-date-grid">

                    {{-- TANGGAL MULAI --}}
                    <div class="academic-form-group">

                        <label for="start_date">
                            Tanggal Mulai
                        </label>

                        <span class="academic-required">
                            *
                        </span>

                        <div class="academic-input-wrapper">

                            <span class="academic-input-icon">

                                <svg width="18" height="18"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <rect x="3" y="4"
                                          width="18"
                                          height="18"
                                          rx="2"></rect>

                                    <line x1="16" y1="2"
                                          x2="16" y2="6"></line>

                                    <line x1="8" y1="2"
                                          x2="8" y2="6"></line>

                                    <line x1="3" y1="10"
                                          x2="21" y2="10"></line>

                                </svg>

                            </span>

                            <input
                                type="date"
                                name="start_date"
                                id="start_date"
                                value="{{ old('start_date') }}"
                                required
                            >

                        </div>

                    </div>


                    {{-- TANGGAL SELESAI --}}
                    <div class="academic-form-group">

                        <label for="end_date">
                            Tanggal Selesai
                        </label>

                        <span class="academic-required">
                            *
                        </span>

                        <div class="academic-input-wrapper">

                            <span class="academic-input-icon">

                                <svg width="18" height="18"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <rect x="3" y="4"
                                          width="18"
                                          height="18"
                                          rx="2"></rect>

                                    <line x1="16" y1="2"
                                          x2="16" y2="6"></line>

                                    <line x1="8" y1="2"
                                          x2="8" y2="6"></line>

                                    <line x1="3" y1="10"
                                          x2="21" y2="10"></line>

                                </svg>

                            </span>

                            <input
                                type="date"
                                name="end_date"
                                id="end_date"
                                value="{{ old('end_date') }}"
                                required
                            >

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ACTIVE CHECKBOX
                ================================================== --}}
                <div class="academic-active-box">

                    <label class="academic-checkbox">

                        <input
                            type="checkbox"
                            name="is_active"
                            id="is_active"
                            value="1"
                            @checked(old('is_active'))
                        >

                        <span class="academic-checkmark"></span>

                        <span class="academic-checkbox-text">

                            <strong>
                                Jadikan tahun ajaran aktif
                            </strong>

                            <small>
                                Mengaktifkan tahun ajaran ini dan
                                menonaktifkan tahun ajaran lainnya.
                            </small>

                        </span>

                    </label>

                </div>


                {{-- =================================================
                     ACTION
                ================================================== --}}
                <div class="academic-form-actions">

                    <a
                        href="{{ route('admin.academic-years.index') }}"
                        class="academic-cancel-button"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="academic-save-button"
                    >
                        <span>Simpan</span>

                        <svg width="17" height="17"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path d="M19 12H5"></path>
                            <path d="M12 19l7-7-7-7"></path>

                        </svg>

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         INFORMATION
    ====================================================== --}}
    <div class="academic-information">

        <div class="academic-information-icon">

            <svg width="19" height="19"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">

                <circle cx="12" cy="12" r="9"></circle>
                <line x1="12" y1="10" x2="12" y2="16"></line>
                <line x1="12" y1="7" x2="12.01" y2="7"></line>

            </svg>

        </div>

        <div class="academic-information-content">

            <h3>
                Perhatian
            </h3>

            <p>
                Pastikan periode tahun ajaran sudah sesuai.
                Tahun ajaran yang aktif akan digunakan sebagai
                acuan untuk pengelolaan semester, nilai, presensi,
                dan materi pembelajaran.
            </p>

        </div>

    </div>

</div>

@endsection

