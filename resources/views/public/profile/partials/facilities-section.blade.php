{{-- =========================================================
     SECTION 6 — FASILITAS KAMPUS & LABORATORIUM
     ========================================================= --}}

<link rel="stylesheet" href="{{ asset('css/public/profile.css') }}">

<section id="fasilitas" class="profile-section profile-section-last">

    <div class="profile-section-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">

        <div class="profile-section-heading d-flex align-items-center gap-3">
            <div class="profile-heading-icon"
                 style="width: 46px; height: 46px; border-radius: 10px; background: #eaf3ff; color: #1769d5; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="bi bi-building-gear"></i>
            </div>

            <div>
                <span class="profile-eyebrow text-uppercase fw-bold text-primary"
                      style="font-size: 0.75rem; letter-spacing: 1px;">
                    SARANA & PRASARANA
                </span>

                <h2 class="h4 fw-bold mb-0 text-dark">
                    Fasilitas Kampus & Laboratorium
                </h2>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill font-monospace"
                  style="font-size: 0.78rem;">
                <i class="bi bi-door-open me-1"></i>
                {{ $facilities->count() }} Ruangan & Fasilitas
            </span>
        </div>

    </div>

    <p class="text-muted small mb-4">
        Klik pada salah satu kartu fasilitas di bawah ini untuk melihat detail foto galeri ruangan, kapasitas, dan spesifikasi sarana penunjang pembelajaran.
    </p>

    @if ($facilities->isNotEmpty())

        <div class="row g-4">

            @foreach ($facilities as $facility)

                @php
                    $allPhotos = $facility->galleryUrls();
                    $mainPhoto = $facility->photo_url ?: ($allPhotos[0] ?? null);
                    $photoCount = count($allPhotos);

                    $modalId = 'facilityModal' . $facility->id;
                    $carouselId = 'facilityCarousel' . $facility->id;
                @endphp

                {{-- KARTU FASILITAS --}}
                <div class="col-sm-6 col-lg-4 col-xl-3">

                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden facility-interactive-card"
                         role="button"
                         tabindex="0"
                         aria-haspopup="dialog"
                         aria-controls="{{ $modalId }}"
                         aria-label="Lihat detail fasilitas {{ $facility->name }}"
                         data-facility-modal="{{ $modalId }}">

                        {{-- THUMBNAIL FOTO --}}
                        <div class="position-relative overflow-hidden bg-light"
                             style="height: 160px;">

                            @if ($mainPhoto)

                                <img src="{{ $mainPhoto }}"
                                     alt="{{ $facility->name }}"
                                     class="w-100 h-100 object-fit-cover facility-thumb-img"
                                     loading="lazy">

                            @else

                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted bg-slate-100">
                                    <i class="bi bi-building fs-1 opacity-50 mb-1"></i>
                                    <span style="font-size: 11px;">Belum ada foto</span>
                                </div>

                            @endif

                            {{-- BADGE TIPE --}}
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-75 text-white backdrop-blur shadow-sm"
                                  style="font-size: 0.7rem;">
                                {{ $facility->typeLabel() }}
                            </span>

                            @if ($photoCount > 1)

                                <span class="position-absolute bottom-0 end-0 m-2 badge bg-primary text-white shadow-sm"
                                      style="font-size: 0.7rem;">
                                    <i class="bi bi-images me-1"></i>
                                    {{ $photoCount }} Foto
                                </span>

                            @endif

                        </div>

                        {{-- KONTEN KARTU --}}
                        <div class="card-body p-3 d-flex flex-column justify-content-between">

                            <div>

                                <div class="d-flex align-items-center justify-content-between mb-1">

                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace"
                                          style="font-size: 0.65rem;">
                                        {{ $facility->code }}
                                    </span>

                                    <small class="text-muted"
                                           style="font-size: 0.72rem;">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ $facility->location ?: 'Gedung Sekolah' }}
                                    </small>

                                </div>

                                <h3 class="h6 fw-bold text-dark mb-1 text-truncate"
                                    title="{{ $facility->name }}">
                                    {{ $facility->name }}
                                </h3>

                                <p class="text-muted small mb-2 text-truncate-2"
                                   style="font-size: 0.78rem; min-height: 2.2em; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $facility->description ?: 'Fasilitas pembelajaran berstandar modern untuk mendukung kegiatan belajar mengajar siswa.' }}
                                </p>

                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-2">

                                <span class="small text-secondary fw-semibold"
                                      style="font-size: 0.75rem;">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $facility->capacity ? $facility->capacity . ' Kursi' : 'Standar Kelas' }}
                                </span>

                                {{-- TOMBOL DETAIL --}}
                                <button type="button"
                                        class="btn btn-link text-primary small fw-semibold text-decoration-none p-0 facility-link-text"
                                        data-open-facility="{{ $modalId }}"
                                        style="font-size: 0.75rem;"
                                        aria-label="Lihat detail {{ $facility->name }}">
                                    Lihat Detail
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- =====================================================
                     MODAL DETAIL FASILITAS
                     ===================================================== --}}
                <div class="modal fade"
                     id="{{ $modalId }}"
                     tabindex="-1"
                     aria-labelledby="{{ $modalId }}Label"
                     aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">

                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                            {{-- MODAL HEADER --}}
                            <div class="modal-header bg-light border-bottom px-4 py-3">

                                <div>

                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">

                                        <span class="badge bg-primary px-2 py-1 font-monospace"
                                              style="font-size: 0.7rem;">
                                            {{ $facility->code }}
                                        </span>

                                        <span class="badge bg-secondary-subtle text-secondary border"
                                              style="font-size: 0.7rem;">
                                            {{ $facility->typeLabel() }}
                                        </span>

                                        @if ($facility->is_active)

                                            <span class="badge bg-success-subtle text-success border border-success-subtle"
                                                  style="font-size: 0.7rem;">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Siap Pakai
                                            </span>

                                        @endif

                                    </div>

                                    <h5 class="modal-title fw-bold text-dark mb-0"
                                        id="{{ $modalId }}Label">
                                        {{ $facility->name }}
                                    </h5>

                                </div>

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Tutup">
                                </button>

                            </div>

                            {{-- MODAL BODY --}}
                            <div class="modal-body p-4">

                                {{-- GALERI FOTO --}}
                                @if (!empty($allPhotos) && count($allPhotos) > 0)

                                    <div class="facility-modal-gallery mb-4">

                                        @if (count($allPhotos) === 1)

                                            <div class="rounded-3 overflow-hidden shadow-sm bg-dark"
                                                 style="max-height: 380px;">

                                                <img src="{{ $allPhotos[0] }}"
                                                     alt="{{ $facility->name }}"
                                                     class="w-100 h-100 object-fit-contain"
                                                     style="max-height: 380px;">

                                            </div>

                                        @else

                                            {{-- CAROUSEL --}}
                                            <div id="{{ $carouselId }}"
                                                 class="carousel slide rounded-3 overflow-hidden shadow-sm bg-dark"
                                                 data-bs-interval="false">

                                                <div class="carousel-indicators">

                                                    @foreach ($allPhotos as $i => $photoItem)

                                                        <button type="button"
                                                                data-bs-target="#{{ $carouselId }}"
                                                                data-bs-slide-to="{{ $i }}"
                                                                class="{{ $i === 0 ? 'active' : '' }}"
                                                                @if ($i === 0) aria-current="true" @endif
                                                                aria-label="Foto {{ $i + 1 }}">
                                                        </button>

                                                    @endforeach

                                                </div>

                                                <div class="carousel-inner"
                                                     style="max-height: 400px;">

                                                    @foreach ($allPhotos as $i => $photoItem)

                                                        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}"
                                                             style="max-height: 400px; background: #000;">

                                                            <img src="{{ $photoItem }}"
                                                                 class="d-block w-100 object-fit-contain"
                                                                 style="height: 380px;"
                                                                 alt="{{ $facility->name }} - Foto {{ $i + 1 }}"
                                                                 loading="{{ $i === 0 ? 'eager' : 'lazy' }}">

                                                        </div>

                                                    @endforeach

                                                </div>

                                                {{-- TOMBOL SEBELUMNYA --}}
                                                <button class="carousel-control-prev"
                                                        type="button"
                                                        data-bs-target="#{{ $carouselId }}"
                                                        data-bs-slide="prev"
                                                        aria-label="Foto sebelumnya">

                                                    <span class="carousel-control-prev-icon"
                                                          aria-hidden="true">
                                                    </span>

                                                </button>

                                                {{-- TOMBOL BERIKUTNYA --}}
                                                <button class="carousel-control-next"
                                                        type="button"
                                                        data-bs-target="#{{ $carouselId }}"
                                                        data-bs-slide="next"
                                                        aria-label="Foto berikutnya">

                                                    <span class="carousel-control-next-icon"
                                                          aria-hidden="true">
                                                    </span>

                                                </button>

                                            </div>

                                            {{-- THUMBNAIL FOTO --}}
                                            <div class="d-flex flex-wrap gap-2 mt-2 pt-1">

                                                @foreach ($allPhotos as $i => $photoItem)

                                                    <button type="button"
                                                            class="border-0 bg-transparent p-0"
                                                            data-facility-slide="{{ $carouselId }}"
                                                            data-slide-index="{{ $i }}"
                                                            aria-label="Tampilkan foto {{ $i + 1 }}">

                                                        <img src="{{ $photoItem }}"
                                                             alt="Thumbnail foto {{ $i + 1 }}"
                                                             class="rounded border object-fit-cover"
                                                             style="width: 60px; height: 42px; cursor: pointer;"
                                                             loading="lazy">

                                                    </button>

                                                @endforeach

                                            </div>

                                        @endif

                                    </div>

                                @else

                                    <div class="p-4 mb-4 text-center rounded-3 bg-light border text-muted">

                                        <i class="bi bi-image fs-1 opacity-50 d-block mb-1"></i>

                                        Foto dokumentasi ruangan ini sedang diperbarui oleh pihak sekolah.

                                    </div>

                                @endif

                                {{-- INFORMASI SPESIFIKASI --}}
                                <div class="row g-3 mb-4">

                                    <div class="col-sm-4">

                                        <div class="p-3 rounded-3 bg-light border text-center h-100">

                                            <i class="bi bi-geo-alt text-primary fs-4 d-block mb-1"></i>

                                            <span class="text-muted d-block small"
                                                  style="font-size: 0.72rem;">
                                                Lokasi Ruangan
                                            </span>

                                            <strong class="text-dark small">
                                                {{ $facility->location ?: 'Gedung Utama' }}
                                            </strong>

                                        </div>

                                    </div>

                                    <div class="col-sm-4">

                                        <div class="p-3 rounded-3 bg-light border text-center h-100">

                                            <i class="bi bi-people text-success fs-4 d-block mb-1"></i>

                                            <span class="text-muted d-block small"
                                                  style="font-size: 0.72rem;">
                                                Kapasitas Ruangan
                                            </span>

                                            <strong class="text-dark small">
                                                {{ $facility->capacity ? $facility->capacity . ' Kursi / Orang' : 'Kapasitas Fleksibel' }}
                                            </strong>

                                        </div>

                                    </div>

                                    <div class="col-sm-4">

                                        <div class="p-3 rounded-3 bg-light border text-center h-100">

                                            <i class="bi bi-grid-fill text-warning fs-4 d-block mb-1"></i>

                                            <span class="text-muted d-block small"
                                                  style="font-size: 0.72rem;">
                                                Kategori Sarana
                                            </span>

                                            <strong class="text-dark small">
                                                {{ $facility->typeLabel() }}
                                            </strong>

                                        </div>

                                    </div>

                                </div>

                                {{-- DESKRIPSI FASILITAS --}}
                                <div class="border-top pt-3">

                                    <h6 class="fw-bold text-dark mb-2">
                                        <i class="bi bi-card-text me-1 text-primary"></i>
                                        Penjelasan & Perlengkapan Fasilitas
                                    </h6>

                                    <div class="text-secondary small lh-lg"
                                         style="font-size: 0.9rem;">

                                        @if ($facility->description)

                                            {!! nl2br(e($facility->description)) !!}

                                        @else

                                            <p class="mb-0 text-muted">
                                                Ruangan ini didesain dan dirawat secara berkala untuk memenuhi standar kenyamanan serta kelayakan sarana pendidikan. Dilengkapi dengan pencahayaan optimal, sirkulasi udara baik, dan sarana multimedia penunjang kegiatan belajar mengajar aktif.
                                            </p>

                                        @endif

                                    </div>

                                </div>

                            </div>

                            {{-- MODAL FOOTER --}}
                            <div class="modal-footer bg-light border-top px-4 py-2">

                                <button type="button"
                                        class="btn btn-secondary btn-sm px-4 rounded-pill"
                                        data-bs-dismiss="modal">
                                    Tutup
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        {{-- KONDISI BELUM ADA FASILITAS --}}
        <div class="text-center py-5 bg-white rounded-3 border">

            <i class="bi bi-building fs-1 text-muted opacity-50 d-block mb-2"></i>

            <h5 class="fw-bold text-dark mb-1">
                Belum Ada Fasilitas Terdaftar
            </h5>

            <p class="text-muted small mb-0">
                Data fasilitas dan sarana ruangan akan diperbarui oleh admin sistem.
            </p>

        </div>

    @endif

</section>

{{-- =========================================================
     JAVASCRIPT INTERAKSI FASILITAS
     Tidak mengubah CSS yang sudah ada.
     ========================================================= --}}

<script>
(function () {
    'use strict';

    function initFacilityInteractions() {
        // Cegah pemasangan event listener berulang.
        if (document.documentElement.dataset.facilityInteractionsReady === 'true') {
            return;
        }

        document.documentElement.dataset.facilityInteractionsReady = 'true';

        // Membuka modal fasilitas.
        function openFacilityModal(modalId) {
            const modalElement = document.getElementById(modalId);

            if (!modalElement) {
                console.error('Modal tidak ditemukan:', modalId);
                return;
            }

            if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                console.error('Bootstrap Modal tidak tersedia.');
                return;
            }

            // Pastikan modal berada langsung di dalam body,
            // bukan di dalam elemen yang membatasi posisi modal.
            if (modalElement.parentElement !== document.body) {
                document.body.appendChild(modalElement);
            }

            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            modal.show();
        }

        // Menangani klik kartu, tombol detail, dan thumbnail galeri.
        document.addEventListener('click', function (event) {
            const target = event.target;

            if (!(target instanceof Element)) {
                return;
            }

            // Klik thumbnail foto.
            const thumbnail = target.closest('[data-facility-slide]');

            if (thumbnail) {
                event.preventDefault();

                const carouselId = thumbnail.dataset.facilitySlide;
                const slideIndex = Number(thumbnail.dataset.slideIndex);
                const carouselElement = document.getElementById(carouselId);

                if (
                    carouselElement &&
                    Number.isInteger(slideIndex) &&
                    typeof bootstrap !== 'undefined' &&
                    bootstrap.Carousel
                ) {
                    bootstrap.Carousel
                        .getOrCreateInstance(carouselElement, {
                            interval: false
                        })
                        .to(slideIndex);
                }

                return;
            }

            // Klik tombol Lihat Detail.
            const detailButton = target.closest('[data-open-facility]');

            if (detailButton) {
                event.preventDefault();
                event.stopPropagation();

                openFacilityModal(detailButton.dataset.openFacility);
                return;
            }

            // Klik bagian kartu selain tombol atau kontrol interaktif.
            const card = target.closest('[data-facility-modal]');

            if (!card) {
                return;
            }

            if (
                target.closest(
                    'button, a, input, select, textarea, label, ' +
                    '[data-bs-dismiss], [data-bs-slide], [data-bs-toggle]'
                )
            ) {
                return;
            }

            openFacilityModal(card.dataset.facilityModal);
        });

        // Dukungan keyboard Enter dan Space pada kartu fasilitas.
        document.addEventListener('keydown', function (event) {
            const target = event.target;

            if (!(target instanceof Element)) {
                return;
            }

            const card = target.closest('[data-facility-modal]');

            if (!card || target !== card) {
                return;
            }

            if (event.key !== 'Enter' && event.key !== ' ') {
                return;
            }

            event.preventDefault();
            openFacilityModal(card.dataset.facilityModal);
        });
    }

    // Tunggu sampai DOM selesai dimuat.
    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            initFacilityInteractions,
            { once: true }
        );
    } else {
        initFacilityInteractions();
    }
})();
</script>
