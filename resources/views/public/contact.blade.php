@extends('layouts.public')

@section('title', 'Media & Hubungi Kami — ' . config('app.name'))

@section('content')

{{-- ── HERO HEADER ──────────────────────────────────────────────── --}}
<section class="bg-dark text-white py-5 shadow-sm" style="background: linear-gradient(135deg, #071b35 0%, #0f2747 50%, #1e3a8a 100%);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 small">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-warning text-decoration-none"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">Media & Kontak</li>
            </ol>
        </nav>
        <h1 class="display-6 fw-bold mb-2">Hubungi Kami & Layanan Informasi</h1>
        <p class="lead text-light opacity-75 max-w-2xl mb-0" style="font-size: 1.05rem;">
            Kami siap melayani kebutuhan informasi, pertanyaan seputar akademik, pendaftaran siswa baru, maupun saluran media komunikasi resmi {{ $settings['school_name'] ?? config('app.name') }}.
        </p>
    </div>
</section>


<div class="container py-5">

    {{-- Flash Notifications --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-3 mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill fs-4 flex-shrink-0"></i>
        <div>
            <strong>Pesan Berhasil Terkirim!</strong>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <strong>Terdapat kesalahan pada isian form:</strong>
        </div>
        <ul class="mb-0 ps-4 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- ── SECTION 1: FORMULIR KONTAK & INFO SEKOLAH ───────────────── --}}
    <section id="formulir-kontak" class="mb-5 pt-2">
        <div class="row g-4">
            
            {{-- Left Column: Contact Form --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-3 p-4 p-md-5 h-100">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; flex-shrink:0;">
                            <i class="bi bi-envelope-paper-fill fs-4"></i>
                        </div>
                        <div>
                            <span class="text-warning fw-bold small text-uppercase">LAYANAN KOMUNIKASI</span>
                            <h3 class="fw-bold text-dark mb-0">Kirim Pesan Layanan</h3>
                        </div>
                    </div>
                    <p class="text-muted small mb-4">
                        Silakan lengkapi formulir di bawah ini. Pertanyaan Anda akan ditanggapi oleh staf Humas/Administrasi sekolah kami dalam jam kerja.
                    </p>

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold text-dark small">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold text-dark small">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold text-dark small">Nomor Telepon/WhatsApp</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="081234567890">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="subject" class="form-label fw-semibold text-dark small">Subjek / Topik Pesan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Tanya PPDB / Akademik / Informasi" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label fw-semibold text-dark small">Isi Pesan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Tuliskan pertanyaan atau pesan Anda secara jelas di sini..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 pt-2">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-send-fill"></i> Kirim Pesan Sekarang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right Column: Contact Info Cards --}}
            <div class="col-lg-5">
                <div class="d-flex flex-column gap-3 h-100">
                    
                    {{-- Address Card --}}
                    <div class="card border-0 shadow-sm rounded-3 p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width:46px; height:46px; flex-shrink:0;">
                                <i class="bi bi-geo-alt-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Alamat Kampus Sekolah</h6>
                                <p class="text-secondary small mb-0 leading-relaxed">
                                    {{ $settings['school_address'] ?? 'Jl. Pendidikan No. 1, Yogyakarta, Daerah Istimewa Yogyakarta 55281' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Phone & Email Card --}}
                    <div class="card border-0 shadow-sm rounded-3 p-4">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width:46px; height:46px; flex-shrink:0;">
                                <i class="bi bi-telephone-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Telepon & Fax</h6>
                                <p class="text-secondary small mb-0">
                                    {{ $settings['school_phone'] ?? '(0274) 555-0199 / (0274) 555-0200' }}
                                </p>
                            </div>
                        </div>
                        <hr class="my-2 text-muted opacity-25">
                        <div class="d-flex align-items-start gap-3 pt-2">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width:46px; height:46px; flex-shrink:0;">
                                <i class="bi bi-envelope-at-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Surat Elektronik (Email)</h6>
                                <p class="text-secondary small mb-0">
                                    {{ $settings['school_email'] ?? 'info@sekolahmodern.sch.id' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Operational Hours Card --}}
                    <div class="card border-0 shadow-sm rounded-3 p-4 flex-grow-1">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width:46px; height:46px; flex-shrink:0;">
                                <i class="bi bi-clock-history fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Jam Operasional Pelayanan</h6>
                                <span class="badge text-bg-success small">Jam Kerja Aktif</span>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-secondary mb-0">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>Senin – Kamis</span>
                                <strong class="text-dark">07.00 – 15.30 WIB</strong>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>Jumat</span>
                                <strong class="text-dark">07.00 – 14.30 WIB</strong>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span>Sabtu & Minggu</span>
                                <span class="text-muted">Libur Pelayanan</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </section>

    {{-- ── SECTION 2: PETA LOKASI INTERAKTIF ───────────────────────── --}}
    <section id="peta-lokasi" class="mb-5 pt-3">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; flex-shrink:0;">
                <i class="bi bi-map-fill fs-4"></i>
            </div>
            <div>
                <span class="text-warning fw-bold small text-uppercase">DENAH LOKASI</span>
                <h3 class="fw-bold text-dark mb-0">Peta & Navigasi Kampus</h3>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            @if (!empty($settings['google_maps_embed']))
                {!! $settings['google_maps_embed'] !!}
            @else
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.080512391217!2d110.3705!3d-7.7805!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwNDYnNDkuOCJTIDExMMKwMjInMTMuOCJF!5e0!3m2!1sid!2sid!4v1620000000000!5m2!1sid!2sid" style="width:100%; height:380px; border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            @endif
        </div>
    </section>

    {{-- ── SECTION 3: GALERI MEDIA & KEGIATAN ──────────────────────── --}}
    <section id="galeri-media" class="mb-5 pt-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; flex-shrink:0;">
                    <i class="bi bi-images fs-4"></i>
                </div>
                <div>
                    <span class="text-warning fw-bold small text-uppercase">DOKUMENTASI VISUAL</span>
                    <h3 class="fw-bold text-dark mb-0">Galeri Media & Dokumentasi</h3>
                </div>
            </div>
        </div>

        @if ($galleryMedia->isNotEmpty())
        <div class="row g-3">
            @foreach ($galleryMedia as $media)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="ratio ratio-4x3 rounded-3 overflow-hidden shadow-sm position-relative cursor-pointer" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ asset('storage/' . $media->path) }}" data-img-title="{{ $media->alt_text ?? $media->original_name }}">
                    <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->alt_text ?? $media->original_name }}" class="object-fit-cover w-100 h-100" loading="lazy">
                    <div class="position-absolute bottom-0 start-0 end-0 p-2 text-white bg-dark bg-opacity-75 small text-truncate">
                        <i class="bi bi-eye-fill me-1"></i> {{ $media->alt_text ?? $media->original_name }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="card border-0 shadow-sm rounded-3 p-5 text-center">
            <div class="d-inline-flex align-items-center justify-content-center bg-light text-secondary rounded-circle mx-auto mb-3" style="width:60px; height:60px;">
                <i class="bi bi-image fs-2"></i>
            </div>
            <h5 class="fw-bold text-dark">Galeri Foto Berita & Kegiatan</h5>
            <p class="text-muted small max-w-md mx-auto mb-0">
                Dokumentasi foto kegiatan sekolah, ekstrakurikuler, dan sarana prasarana terbaru akan ditampilkan di sini.
            </p>
        </div>
        @endif
    </section>

    {{-- ── SECTION 4: MEDIA SOSIAL RESMI ──────────────────────────── --}}
    <section id="media-sosial" class="mb-4 pt-3">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3" style="width:46px; height:46px; flex-shrink:0;">
                <i class="bi bi-share-fill fs-4"></i>
            </div>
            <div>
                <span class="text-warning fw-bold small text-uppercase">SALURAN RESMI</span>
                <h3 class="fw-bold text-dark mb-0">Ikuti Media Sosial Sekolah</h3>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-6 col-md-4 col-lg-2-4">
                <a href="{{ $settings['social_instagram'] ?? 'https://instagram.com' }}" target="_blank" rel="noopener" class="card border-0 shadow-sm rounded-3 p-3 text-decoration-none h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center text-white rounded-3 bg-danger" style="width:42px; height:42px; flex-shrink:0;">
                            <i class="bi bi-instagram fs-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <strong class="d-block text-dark small text-truncate">Instagram</strong>
                            <span class="text-muted d-block text-truncate" style="font-size:0.75rem;">@sekolahmodern</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4 col-lg-2-4">
                <a href="{{ $settings['social_youtube'] ?? 'https://youtube.com' }}" target="_blank" rel="noopener" class="card border-0 shadow-sm rounded-3 p-3 text-decoration-none h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center text-white rounded-3 bg-danger" style="width:42px; height:42px; flex-shrink:0;">
                            <i class="bi bi-youtube fs-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <strong class="d-block text-dark small text-truncate">YouTube</strong>
                            <span class="text-muted d-block text-truncate" style="font-size:0.75rem;">Channel Resmi</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4 col-lg-2-4">
                <a href="{{ $settings['social_facebook'] ?? 'https://facebook.com' }}" target="_blank" rel="noopener" class="card border-0 shadow-sm rounded-3 p-3 text-decoration-none h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center text-white rounded-3 bg-primary" style="width:42px; height:42px; flex-shrink:0;">
                            <i class="bi bi-facebook fs-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <strong class="d-block text-dark small text-truncate">Facebook</strong>
                            <span class="text-muted d-block text-truncate" style="font-size:0.75rem;">Halaman Resmi</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4 col-lg-2-4">
                <a href="{{ $settings['social_twitter'] ?? 'https://x.com' }}" target="_blank" rel="noopener" class="card border-0 shadow-sm rounded-3 p-3 text-decoration-none h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center text-white rounded-3 bg-dark" style="width:42px; height:42px; flex-shrink:0;">
                            <i class="bi bi-twitter-x fs-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <strong class="d-block text-dark small text-truncate">Twitter / X</strong>
                            <span class="text-muted d-block text-truncate" style="font-size:0.75rem;">@sekolahmodern</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4 col-lg-2-4">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['school_phone'] ?? '6281234567890') }}" target="_blank" rel="noopener" class="card border-0 shadow-sm rounded-3 p-3 text-decoration-none h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center text-white rounded-3 bg-success" style="width:42px; height:42px; flex-shrink:0;">
                            <i class="bi bi-whatsapp fs-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <strong class="d-block text-dark small text-truncate">WhatsApp</strong>
                            <span class="text-muted d-block text-truncate" style="font-size:0.75rem;">Layanan Informasi</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

</div>

{{-- ── LIGHTBOX IMAGE MODAL ─────────────────────────────────────── --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0 position-relative text-center">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <img id="modalPreviewImage" src="" alt="Preview Foto" class="img-fluid rounded-3 shadow-lg max-h-80vh">
                <p id="modalImageCaption" class="text-white text-center mt-2 small"></p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imgSrc = button.getAttribute('data-img-src');
            const imgTitle = button.getAttribute('data-img-title');
            
            document.getElementById('modalPreviewImage').setAttribute('src', imgSrc);
            document.getElementById('modalImageCaption').textContent = imgTitle || '';
        });
    }
});
</script>
@endpush
