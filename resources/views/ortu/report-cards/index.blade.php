@extends('layouts.app')

@section('title', 'Rapor Digital')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ortu/report-cards/index.css') }}">

<div class="ortu-report-page">

    <div class="orp-container">

        {{-- ANNOUNCEMENTS --}}
        @include('layouts.partials.announcements-banner')

        {{-- HERO --}}
        <section class="orp-hero">
            <div class="orp-hero-content">
                <span class="orp-eyebrow">
                    <i class="bi bi-mortarboard-fill"></i>
                    PORTAL ORANG TUA / WALI
                </span>

                <h1>Rapor Digital</h1>

                <p>
                    Lihat dan unduh dokumen rapor akademik
                    <strong>{{ $student->name }}</strong>
                    dengan mudah melalui portal sekolah.
                </p>

                <div class="orp-hero-meta">
                    <span>
                        <i class="bi bi-file-earmark-pdf"></i>
                        Dokumen rapor siswa
                    </span>
                    <span>
                        <i class="bi bi-shield-check"></i>
                        Akses dokumen resmi
                    </span>
                </div>
            </div>

            <div class="orp-hero-visual" aria-hidden="true">
                <div class="orp-visual-circle orp-circle-one"></div>
                <div class="orp-visual-circle orp-circle-two"></div>

                <div class="orp-document-illustration">
                    <div class="orp-document-icon">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <div class="orp-document-line orp-line-long"></div>
                    <div class="orp-document-line orp-line-medium"></div>
                    <div class="orp-document-line orp-line-short"></div>
                    <div class="orp-document-stamp">
                        <i class="bi bi-check-lg"></i>
                    </div>
                </div>

                <div class="orp-floating-badge">
                    <i class="bi bi-download"></i>
                    PDF siap diunduh
                </div>
            </div>
        </section>

        {{-- SECTION HEADING --}}
        <section class="orp-section">
            <div class="orp-section-heading">
                <div>
                    <span class="orp-section-label">DOKUMEN AKADEMIK</span>
                    <h2>Arsip Rapor Siswa</h2>
                    <p>
                        Pilih rapor berdasarkan semester untuk melihat dan
                        mengunduh dokumen yang telah diterbitkan sekolah.
                    </p>
                </div>

                <div class="orp-total-badge">
                    <span class="orp-total-icon">
                        <i class="bi bi-folder2-open"></i>
                    </span>
                    <span>
                        <strong>{{ $reportCards->count() }}</strong>
                        <small>Dokumen tersedia</small>
                    </span>
                </div>
            </div>

            {{-- REPORT CARDS --}}
            <div class="orp-report-grid">
                @forelse($reportCards as $report)
                    <article class="orp-report-card">
                        <div class="orp-card-top">
                            <div class="orp-pdf-icon">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </div>

                            <span class="orp-document-tag">
                                <i class="bi bi-filetype-pdf"></i>
                                PDF
                            </span>
                        </div>

                        <div class="orp-card-content">
                            <span class="orp-card-label">RAPOR AKADEMIK</span>

                            <h3>{{ $report->semester->name }}</h3>

                            <div class="orp-academic-year">
                                <i class="bi bi-calendar3"></i>
                                <span>
                                    {{ $report->semester->academicYear->name }}
                                </span>
                            </div>

                            @if($report->label)
                                <div class="orp-report-label">
                                    <i class="bi bi-bookmark-fill"></i>
                                    {{ $report->label }}
                                </div>
                            @endif
                        </div>

                        <div class="orp-card-footer">
                            <div class="orp-upload-info">
                                <span class="orp-upload-icon">
                                    <i class="bi bi-cloud-check"></i>
                                </span>
                                <span>
                                    <small>Diunggah pada</small>
                                    <strong>
                                        {{ $report->created_at->format('d/m/Y') }}
                                    </strong>
                                </span>
                            </div>

                            <a
                                href="{{ route('ortu.report-cards.download', $report->id) }}"
                                class="orp-download-button"
                            >
                                <i class="bi bi-download"></i>
                                <span>Download PDF</span>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="orp-empty-state">
                        <div class="orp-empty-icon">
                            <i class="bi bi-folder2-open"></i>
                        </div>

                        <h3>Belum Ada Rapor Tersedia</h3>

                        <p>
                            Saat ini belum ada dokumen rapor yang diterbitkan
                            untuk <strong>{{ $student->name }}</strong>.
                            Silakan hubungi pihak sekolah jika Anda merasa
                            informasi ini tidak sesuai.
                        </p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- INFORMATION NOTE --}}
        <div class="orp-information">
            <span class="orp-information-icon">
                <i class="bi bi-info-circle-fill"></i>
            </span>

            <div>
                <strong>Informasi dokumen</strong>
                <p>
                    Gunakan tombol Download PDF pada kartu rapor untuk
                    mengunduh dokumen yang tersedia. Jika rapor belum muncul,
                    silakan konfirmasi kepada pihak sekolah.
                </p>
            </div>
        </div>

    </div>

    {{-- TOMBOL KEMBALI KE DASHBOARD ORANG TUA --}}
    <div class="orp-bottom-actions">
        <a href="{{ route('ortu.dashboard') }}" class="orp-back-button">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Dasbor</span>
        </a>
    </div>
</div>

@endsection
