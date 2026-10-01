@extends('layouts.public')

@section('title', 'Media & Hubungi Kami — ' . config('app.name'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/public/contact.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="contact-page">

    {{-- =========================================================
        HERO
    ========================================================= --}}
    <section class="contact-hero">

        <div class="container">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Beranda</a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Media & Hubungi Kami
                    </li>
                </ol>
            </nav>


            <div class="hero-layout">

                {{-- HERO KIRI --}}
                <div class="hero-content">

                    <span class="hero-label">
                        MEDIA & HUBUNGI KAMI
                    </span>

                    <h1>
                        Hubungi Kami & Layanan Informasi
                    </h1>

                    <p>
                        Sampaikan pertanyaan, kebutuhan informasi, maupun pesan
                        kepada sekolah melalui layanan komunikasi yang tersedia.
                    </p>

                    <div class="hero-actions">

                        <a href="#formulir-kontak" class="hero-action-primary">
                            <i class="bi bi-chat-left-text"></i>
                            Hubungi Sekolah
                        </a>

                        <a href="#peta-lokasi" class="hero-action-secondary">
                            <i class="bi bi-geo-alt"></i>
                            Lihat Lokasi
                        </a>

                    </div>

                </div>


                {{-- HERO KANAN --}}
                <div class="hero-visual">

                    <div class="hero-visual-card">

                        <div class="hero-visual-top">

                            <div class="hero-visual-icon">
                                <i class="bi bi-building"></i>
                            </div>

                            <div>
                                <span>
                                    PORTAL INFORMASI
                                </span>

                                <strong>
                                    Layanan Sekolah
                                </strong>
                            </div>

                        </div>


                        <div class="hero-visual-line"></div>


                        <div class="hero-visual-list">

                            <div class="hero-visual-item">

                                <div class="hero-item-icon">
                                    <i class="bi bi-chat-dots"></i>
                                </div>

                                <div>
                                    <strong>
                                        Layanan Komunikasi
                                    </strong>

                                    <span>
                                        Sampaikan pertanyaan kepada sekolah
                                    </span>
                                </div>

                            </div>


                            <div class="hero-visual-item">

                                <div class="hero-item-icon">
                                    <i class="bi bi-images"></i>
                                </div>

                                <div>
                                    <strong>
                                        Dokumentasi Sekolah
                                    </strong>

                                    <span>
                                        Lihat kegiatan dan informasi visual
                                    </span>
                                </div>

                            </div>


                            <div class="hero-visual-item">

                                <div class="hero-item-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>

                                <div>
                                    <strong>
                                        Lokasi Kampus
                                    </strong>

                                    <span>
                                        Temukan lokasi sekolah melalui peta
                                    </span>
                                </div>

                            </div>

                        </div>


                        <div class="hero-visual-footer">

                            <div>
                                <span>AKSES INFORMASI</span>
                                <strong>Terhubung dengan Sekolah</strong>
                            </div>

                            <i class="bi bi-arrow-up-right"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        ALERT
    ========================================================= --}}

    @if (session('success'))

        <div class="contact-alert alert-success">

            <div class="alert-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <div>
                <strong>Berhasil</strong>
                {{ session('success') }}
            </div>

            <button
                type="button"
                class="btn-close ms-auto"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif


    @if (session('error'))

        <div class="contact-alert alert-danger">

            <div class="alert-icon">
                <i class="bi bi-exclamation-lg"></i>
            </div>

            <div>
                <strong>Terjadi Kesalahan</strong>
                {{ session('error') }}
            </div>

            <button
                type="button"
                class="btn-close ms-auto"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif


    {{-- =========================================================
        1. LAYANAN KOMUNIKASI
    ========================================================= --}}
    <section id="formulir-kontak" class="contact-section">

        <div class="section-heading">

            <span>
                LAYANAN KOMUNIKASI
            </span>

            <h2>
                Hubungi Sekolah
            </h2>

            <p>
                Sampaikan pertanyaan atau kebutuhan informasi Anda melalui
                formulir layanan yang tersedia.
            </p>

        </div>


        <div class="contact-main-grid">

            {{-- FORM --}}
            <div class="contact-form-card">

                <div class="card-heading">

                    <div class="heading-icon">
                        <i class="bi bi-chat-left-text"></i>
                    </div>

                    <div>
                        <span>
                            FORMULIR LAYANAN
                        </span>

                        <h3>
                            Kirim Pesan
                        </h3>
                    </div>

                </div>

                <p class="card-description">
                    Lengkapi data berikut dengan benar agar pihak sekolah
                    dapat memberikan informasi dan tanggapan yang sesuai.
                </p>


                <form
                    action="{{ route('contact.store') }}"
                    method="POST"
                >

                    @csrf

                    <div class="form-grid">

                        {{-- NAMA --}}
                        <div class="form-group">

                            <label for="name">
                                Nama Lengkap <span>*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                class="@error('name') is-invalid @enderror"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- EMAIL --}}
                        <div class="form-group">

                            <label for="email">
                                Email <span>*</span>
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Masukkan alamat email"
                                class="@error('email') is-invalid @enderror"
                                required
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- TELEPON --}}
                        <div class="form-group">

                            <label for="phone">
                                Nomor Telepon
                            </label>

                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="Masukkan nomor telepon"
                                class="@error('phone') is-invalid @enderror"
                            >

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- SUBJEK --}}
                        <div class="form-group">

                            <label for="subject">
                                Subjek <span>*</span>
                            </label>

                            <input
                                type="text"
                                id="subject"
                                name="subject"
                                value="{{ old('subject') }}"
                                placeholder="Masukkan subjek pesan"
                                class="@error('subject') is-invalid @enderror"
                                required
                            >

                            @error('subject')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- PESAN --}}
                        <div class="form-group form-group-full">

                            <label for="message">
                                Pesan <span>*</span>
                            </label>

                            <textarea
                                id="message"
                                name="message"
                                rows="6"
                                placeholder="Tuliskan pesan atau kebutuhan informasi Anda..."
                                class="@error('message') is-invalid @enderror"
                                required
                            >{{ old('message') }}</textarea>

                            @error('message')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- BUTTON --}}
                        <div class="form-submit">

                            <button type="submit">
                                <i class="bi bi-send"></i>
                                <span>Kirim Pesan</span>
                            </button>

                        </div>

                    </div>

                </form>

            </div>


            {{-- INFORMASI --}}
            <div class="contact-info">

                {{-- ALAMAT --}}
                <div class="info-card">

                    <div class="info-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <div>

                        <span class="info-label">
                            ALAMAT SEKOLAH
                        </span>

                        <h4>
                            Alamat
                        </h4>

                        <p>
                            {{ data_get($settings, 'address', 'Alamat sekolah belum tersedia.') }}
                        </p>

                    </div>

                </div>


                {{-- TELEPON + EMAIL --}}
                <div class="info-card info-card-stack">

                    <div class="info-row">

                        <div class="info-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>

                        <div>

                            <span class="info-label">
                                TELEPON
                            </span>

                            <h4>
                                {{ data_get($settings, 'phone', 'Belum tersedia') }}
                            </h4>

                            <p>
                                Hubungi sekolah pada jam operasional.
                            </p>

                        </div>

                    </div>


                    <div class="info-divider"></div>


                    <div class="info-row">

                        <div class="info-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>

                        <div>

                            <span class="info-label">
                                EMAIL
                            </span>

                            <h4>
                                {{ data_get($settings, 'email', 'Belum tersedia') }}
                            </h4>

                            <p>
                                Gunakan email untuk kebutuhan informasi.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- JAM OPERASIONAL --}}
                <div class="info-card operational-card">

                    <div class="info-row">

                        <div class="info-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>

                        <div>

                            <span class="info-label">
                                JAM OPERASIONAL
                            </span>

                            <h4>
                                Senin — Jumat
                            </h4>

                            <p>
                                Layanan sekolah tersedia pada jam kerja.
                            </p>

                            <span class="status-badge">
                                <i class="bi bi-circle-fill"></i>
                                Layanan Aktif
                            </span>

                        </div>

                    </div>


                    <div class="schedule-list">

                        <div>
                            <span>Senin — Kamis</span>
                            <strong>07.00 — 15.00</strong>
                        </div>

                        <div>
                            <span>Jumat</span>
                            <strong>07.00 — 14.00</strong>
                        </div>

                        <div>
                            <span>Sabtu — Minggu</span>
                            <span class="closed">Tutup</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        2. DOKUMENTASI VISUAL
    ========================================================= --}}
    <section id="galeri-media" class="contact-section">

        <div class="contact-content-card">

            <div class="section-heading">

                <span>
                    DOKUMENTASI VISUAL
                </span>

                <h2>
                    Galeri Media & Dokumentasi
                </h2>

            </div>


            @if(isset($galleryMedia) && $galleryMedia->count())

                <div class="gallery-grid">

                    @foreach($galleryMedia as $media)

                        <div
                            class="gallery-item"
                            data-bs-toggle="modal"
                            data-bs-target="#imageModal"
                            data-image="{{ $media->image_url ?? asset('storage/' . $media->image) }}"
                            data-caption="{{ $media->title ?? $media->caption ?? 'Dokumentasi Sekolah' }}"
                        >

                            <img
                                src="{{ $media->image_url ?? asset('storage/' . $media->image) }}"
                                alt="{{ $media->title ?? $media->caption ?? 'Dokumentasi Sekolah' }}"
                                loading="lazy"
                            >

                            <div class="gallery-caption">

                                <i class="bi bi-images"></i>

                                <span>
                                    {{ $media->title ?? $media->caption ?? 'Dokumentasi Sekolah' }}
                                </span>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-gallery">

                    <div class="empty-gallery-icon">
                        <i class="bi bi-images"></i>
                    </div>

                    <h5>
                        Galeri Belum Tersedia
                    </h5>

                    <p>
                        Dokumentasi visual sekolah akan ditampilkan
                        pada halaman ini.
                    </p>

                </div>

            @endif

        </div>

    </section>


    {{-- =========================================================
        3. SALURAN RESMI
    ========================================================= --}}
    <section id="media-sosial" class="contact-section">

        <div class="contact-content-card">

            <div class="section-heading">

                <span>
                    SALURAN RESMI
                </span>

                <h2>
                    Ikuti Media Sosial Sekolah
                </h2>

            </div>


            <div class="social-grid">

                <a
                    href="{{ data_get($settings, 'instagram_url', '#') }}"
                    class="social-card"
                    target="_blank"
                    rel="noopener noreferrer"
                >

                    <div class="social-icon instagram">
                        <i class="bi bi-instagram"></i>
                    </div>

                    <div>

                        <strong>
                            Instagram
                        </strong>

                        <span>
                            Ikuti kegiatan sekolah
                        </span>

                    </div>

                </a>


                <a
                    href="{{ data_get($settings, 'youtube_url', '#') }}"
                    class="social-card"
                    target="_blank"
                    rel="noopener noreferrer"
                >

                    <div class="social-icon youtube">
                        <i class="bi bi-youtube"></i>
                    </div>

                    <div>

                        <strong>
                            YouTube
                        </strong>

                        <span>
                            Video dan dokumentasi
                        </span>

                    </div>

                </a>


                <a
                    href="{{ data_get($settings, 'facebook_url', '#') }}"
                    class="social-card"
                    target="_blank"
                    rel="noopener noreferrer"
                >

                    <div class="social-icon facebook">
                        <i class="bi bi-facebook"></i>
                    </div>

                    <div>

                        <strong>
                            Facebook
                        </strong>

                        <span>
                            Informasi sekolah
                        </span>

                    </div>

                </a>


                <a
                    href="{{ data_get($settings, 'twitter_url', '#') }}"
                    class="social-card"
                    target="_blank"
                    rel="noopener noreferrer"
                >

                    <div class="social-icon twitter">
                        <i class="bi bi-twitter-x"></i>
                    </div>

                    <div>

                        <strong>
                            X / Twitter
                        </strong>

                        <span>
                            Informasi terbaru
                        </span>

                    </div>

                </a>


                <a
                    href="{{ data_get($settings, 'whatsapp_url', '#') }}"
                    class="social-card"
                    target="_blank"
                    rel="noopener noreferrer"
                >

                    <div class="social-icon whatsapp">
                        <i class="bi bi-whatsapp"></i>
                    </div>

                    <div>

                        <strong>
                            WhatsApp
                        </strong>

                        <span>
                            Hubungi sekolah
                        </span>

                    </div>

                </a>

            </div>

        </div>

    </section>


    {{-- =========================================================
        4. DENAH LOKASI
    ========================================================= --}}
    <section id="peta-lokasi" class="contact-section">

        <div class="contact-content-card">

            <div class="section-heading">

                <span>
                    DENAH LOKASI
                </span>

                <h2>
                    Peta & Navigasi Kampus
                </h2>

            </div>


            <div class="map-card">

                @if(data_get($settings, 'google_maps_embed'))

                    {!! data_get($settings, 'google_maps_embed') !!}

                @elseif(data_get($settings, 'map_embed_url'))

                    <iframe
                        src="{{ data_get($settings, 'map_embed_url') }}"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Lokasi Sekolah">
                    </iframe>

                @else

                    <iframe
                        src="https://www.google.com/maps?q={{ urlencode(data_get($settings, 'address', config('app.name'))) }}&output=embed"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Lokasi Sekolah">
                    </iframe>

                @endif

            </div>

        </div>

    </section>


</div>


{{-- =========================================================
    IMAGE MODAL
========================================================= --}}
<div
    class="modal fade"
    id="imageModal"
    tabindex="-1"
    aria-labelledby="imageModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-body text-center position-relative">

                <button
                    type="button"
                    class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

                <img
                    id="modalPreviewImage"
                    src=""
                    alt="Preview Dokumentasi"
                    class="img-fluid rounded"
                >

                <p
                    id="modalImageCaption"
                    class="mt-3">
                </p>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const imageModal = document.getElementById('imageModal');

    if (!imageModal) {
        return;
    }

    imageModal.addEventListener('show.bs.modal', function (event) {

        const trigger = event.relatedTarget;

        if (!trigger) {
            return;
        }

        const image = trigger.getAttribute('data-image');
        const caption = trigger.getAttribute('data-caption');

        const previewImage = document.getElementById('modalPreviewImage');
        const previewCaption = document.getElementById('modalImageCaption');

        if (previewImage) {
            previewImage.src = image || '';
        }

        if (previewCaption) {
            previewCaption.textContent = caption || '';
        }

    });

    imageModal.addEventListener('hidden.bs.modal', function () {

        const previewImage = document.getElementById('modalPreviewImage');
        const previewCaption = document.getElementById('modalImageCaption');

        if (previewImage) {
            previewImage.src = '';
        }

        if (previewCaption) {
            previewCaption.textContent = '';
        }

    });

});
</script>

@endpush