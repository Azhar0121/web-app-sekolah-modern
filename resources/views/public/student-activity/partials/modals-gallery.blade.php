@foreach ($osisActivities as $act)
    @php
        $galleryList = $act->galleryUrls();
    @endphp

    @if (!empty($galleryList))
        <div class="modal fade" id="galleryModal{{ $act->id }}" tabindex="-1" aria-labelledby="galleryModalLabel{{ $act->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
                    <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                        <div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle small mb-1">
                                <i class="bi bi-camera-fill me-1"></i> Galeri Dokumentasi
                            </span>
                            <h5 class="modal-title fw-bold text-white fs-6 mb-0" id="galleryModalLabel{{ $act->id }}">{{ $act->title }}</h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        @if ($act->description)
                            <p class="text-secondary small mb-3">{{ $act->description }}</p>
                        @endif

                        {{-- CAROUSEL / GRID FOTO --}}
                        <div id="carouselActivity{{ $act->id }}" class="carousel slide rounded-3 overflow-hidden shadow-sm mb-3" data-bs-ride="carousel">
                            <div class="carousel-inner bg-dark text-center" style="max-height: 480px;">
                                @foreach ($galleryList as $index => $gUrl)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $gUrl }}" class="d-block w-100 object-fit-contain" style="max-height: 480px; background: #071b35;" alt="Dokumentasi {{ $act->title }}">
                                    </div>
                                @endforeach
                            </div>
                            @if (count($galleryList) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselActivity{{ $act->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselActivity{{ $act->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>

                        {{-- THUMBNAILS ROW --}}
                        @if (count($galleryList) > 1)
                            <div class="d-flex gap-2 overflow-auto py-2">
                                @foreach ($galleryList as $index => $gUrl)
                                    <button type="button" class="btn p-0 border rounded overflow-hidden flex-shrink-0" style="width: 72px; height: 50px;" data-bs-target="#carouselActivity{{ $act->id }}" data-bs-slide-to="{{ $index }}">
                                        <img src="{{ $gUrl }}" class="w-100 h-100 object-fit-cover" alt="Thumb">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer bg-white border-top py-2 px-4 justify-content-between">
                        <small class="text-muted"><i class="bi bi-images me-1"></i> Total {{ count($galleryList) }} foto dokumentasi</small>
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
