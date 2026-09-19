@extends('layouts.app')

@section('title', 'Detail Surat - ' . $correspondence->number)

@section('content')

<link rel="stylesheet" href="{{ asset('css/correspondences/show.css') }}">

<div class="correspondence-detail-page">


{{-- HERO --}}
<div class="correspondence-detail-header">

    <div class="correspondence-detail-header-content">

        <span class="correspondence-detail-label">
            {{ $correspondence->type === 'masuk' ? 'SURAT MASUK' : 'SURAT KELUAR' }}
        </span>

        <h1>
            {{ $correspondence->number }}
        </h1>

        <p>
            {{ $correspondence->subject }}
        </p>

    </div>

    <div class="correspondence-detail-header-mark">
        <span class="correspondence-detail-header-dot"></span>
        <span>DETAIL SURAT</span>
    </div>

</div>


{{-- CONTENT --}}
<div class="correspondence-detail-grid">

    {{-- =====================================================
         INFORMASI SURAT
         ===================================================== --}}
    <div class="correspondence-detail-main">

        <div class="correspondence-detail-card">

            <div class="correspondence-detail-card-header">

                <div>
                    <span class="correspondence-detail-card-label">
                        INFORMASI
                    </span>

                    <h2>
                        Informasi Surat
                    </h2>
                </div>

            </div>


            <div class="correspondence-detail-card-body">

                <dl class="correspondence-detail-list">

                    {{-- NOMOR SURAT --}}
                    <div class="correspondence-detail-row">
                        <dt>Nomor Surat</dt>

                        <dd>
                            <code class="correspondence-detail-number">
                                {{ $correspondence->number }}
                            </code>
                        </dd>
                    </div>


                    {{-- TANGGAL SURAT --}}
                    <div class="correspondence-detail-row">
                        <dt>Tanggal Surat</dt>

                        <dd>
                            {{ $correspondence->letter_date->format('d M Y') }}
                        </dd>
                    </div>


                    {{-- KATEGORI --}}
                    <div class="correspondence-detail-row">
                        <dt>Kategori</dt>

                        <dd>
                            {{ $correspondence->category }}
                        </dd>
                    </div>


                    {{-- PERIHAL --}}
                    <div class="correspondence-detail-row">
                        <dt>Perihal</dt>

                        <dd>
                            {{ $correspondence->subject }}
                        </dd>
                    </div>


                    {{-- PENGIRIM / TUJUAN --}}
                    <div class="correspondence-detail-row">
                        <dt>
                            {{ $correspondence->type === 'masuk' ? 'Pengirim' : 'Tujuan' }}
                        </dt>

                        <dd>
                            {{ $correspondence->correspondent }}
                        </dd>
                    </div>


                    {{-- CATATAN --}}
                    @if ($correspondence->description)

                        <div class="correspondence-detail-row">

                            <dt>Catatan</dt>

                            <dd class="correspondence-detail-preline">
                                {{ $correspondence->description }}
                            </dd>

                        </div>

                    @endif


                    {{-- DISPOSISI --}}
                    @if ($correspondence->type === 'masuk' && $correspondence->disposition)

                        <div class="correspondence-detail-row">

                            <dt>Disposisi</dt>

                            <dd class="correspondence-detail-preline">
                                {{ $correspondence->disposition }}
                            </dd>

                        </div>

                    @endif


                    {{-- DICATAT OLEH --}}
                    <div class="correspondence-detail-row">

                        <dt>Dicatat oleh</dt>

                        <dd>
                            {{ $correspondence->creator?->name ?? '-' }}

                            <span class="correspondence-detail-separator">
                                &middot;
                            </span>

                            {{ $correspondence->created_at->format('d M Y, H:i') }}
                        </dd>

                    </div>

                </dl>

            </div>

        </div>


        {{-- KEMBALI DI BAWAH CARD INFORMASI --}}
        <div class="correspondence-detail-main-actions">

            <a href="{{ route('admin.correspondences.index', ['type' => $correspondence->type]) }}"
               class="correspondence-detail-action correspondence-detail-back">
                &larr; Kembali
            </a>

        </div>

    </div>


    {{-- =====================================================
         SIDEBAR
         ===================================================== --}}
    <div class="correspondence-detail-sidebar">


        {{-- =================================================
             STATUS
             ================================================= --}}
        <div class="correspondence-detail-card correspondence-detail-status-card">

            <div class="correspondence-detail-card-header">

                <div>
                    <span class="correspondence-detail-card-label">
                        STATUS
                    </span>

                    <h2>
                        Status Surat
                    </h2>
                </div>

                <span class="correspondence-detail-card-icon correspondence-detail-status-icon">
                    ST
                </span>

            </div>


            <div class="correspondence-detail-card-body">

                @php
                    $statusClass = match ($correspondence->status) {
                        'selesai', 'terkirim' => 'success',
                        'diproses' => 'warning',
                        default => 'secondary',
                    };
                @endphp

                <div class="correspondence-detail-status correspondence-detail-status-{{ $statusClass }}">

                    <span class="correspondence-detail-status-dot"></span>

                    {{ \App\Models\Correspondence::statusOptions($correspondence->type)[$correspondence->status] ?? $correspondence->status }}

                </div>

            </div>

        </div>


        {{-- =================================================
             DOKUMEN / LAMPIRAN
             ================================================= --}}
        <div class="correspondence-detail-card correspondence-detail-document-card">

            <div class="correspondence-detail-card-header">

                <div>
                    <span class="correspondence-detail-card-label">
                        DOKUMEN
                    </span>

                    <h2>
                        Lampiran
                    </h2>
                </div>

                <span class="correspondence-detail-card-icon correspondence-detail-document-icon">
                    DOC
                </span>

            </div>


            <div class="correspondence-detail-card-body">

                @if ($correspondence->hasFile())

                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($correspondence->file_path) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="correspondence-detail-file">

                        <span class="correspondence-detail-file-icon">
                            PDF
                        </span>

                        <span class="correspondence-detail-file-content">

                            <span class="correspondence-detail-file-name">
                                {{ $correspondence->file_original_name }}
                            </span>

                            <span class="correspondence-detail-file-action">
                                Buka lampiran
                            </span>

                        </span>

                    </a>

                @else

                    <div class="correspondence-detail-empty-file">

                        <span class="correspondence-detail-empty-icon">
                            —
                        </span>

                        <p>
                            Tidak ada lampiran atau scan surat.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- =================================================
             EDIT + HAPUS
             ================================================= --}}
        <div class="correspondence-detail-actions">

            {{-- EDIT --}}
            <a href="{{ route('admin.correspondences.edit', $correspondence) }}"
               class="correspondence-detail-action correspondence-detail-edit">
                Edit
            </a>


            {{-- HAPUS --}}
            <form method="POST"
                  action="{{ route('admin.correspondences.destroy', $correspondence) }}"
                  class="correspondence-detail-delete-form"
                  onsubmit="return confirm('Hapus surat {{ $correspondence->number }} dari arsip? Tindakan ini tidak bisa dibatalkan.');">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="correspondence-detail-action correspondence-detail-delete">
                    Hapus
                </button>

            </form>

        </div>

    </div>

</div>


</div>

@endsection
