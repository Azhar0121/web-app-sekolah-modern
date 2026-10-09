<div class="staff-grid-wrapper">

    @if ($staff->isNotEmpty())
        <div class="row g-4" id="staffListRow">
            @foreach ($staff as $item)
                <div class="col-sm-6 col-md-4 col-lg-3 staff-search-item"
                     data-name="{{ strtolower($item->name) }}">

                    <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3 teacher-card-modern">
                        {{-- AVATAR FOTO --}}
                        <div class="position-relative d-inline-block mx-auto mb-3">
                            @if ($item->photo_url)
                                <img src="{{ $item->photo_url }}"
                                     alt="{{ $item->name }}"
                                     class="rounded-circle object-fit-cover shadow-sm border border-3 border-white"
                                     style="width: 100px; height: 100px;">
                            @else
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold shadow-sm border border-3 border-white"
                                     style="width: 100px; height: 100px; background: linear-gradient(135deg, #1e293b 0%, #475569 100%); font-size: 2.2rem;">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                            @endif

                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle shadow-sm" title="Staf Aktif">
                                <span class="visually-hidden">Aktif</span>
                            </span>
                        </div>

                        {{-- NAMA & JABATAN --}}
                        <h5 class="fw-bold text-dark mb-1 fs-6 text-truncate" title="{{ $item->name }}">
                            {{ $item->name }}
                        </h5>

                        <div class="badge bg-secondary-subtle text-secondary-emphasis border rounded-pill px-3 py-1 mb-2 text-truncate"
                             style="font-size: 0.72rem;">
                            <i class="bi bi-briefcase me-1"></i>Tenaga Administrasi & TU
                        </div>

                        <div class="border-top pt-2 mt-auto">
                            <span class="text-muted small d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                <i class="bi bi-envelope"></i>
                                <span class="text-truncate" style="max-width: 170px;">{{ $item->email }}</span>
                            </span>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- STATE KOSONG PENCARIAN --}}
        <div id="noStaffFound" class="d-none text-center py-5 bg-white rounded-4 border">
            <i class="bi bi-search fs-1 text-muted opacity-50 d-block mb-2"></i>
            <h6 class="fw-bold text-dark mb-1">Staf Tidak Ditemukan</h6>
            <p class="text-muted small mb-0">Coba gunakan kata kunci pencarian nama lain.</p>
        </div>

    @else
        <div class="text-center py-5 bg-white rounded-4 border">
            <i class="bi bi-person-x fs-1 text-muted opacity-50 d-block mb-2"></i>
            <h5 class="fw-bold text-dark mb-1">Belum Ada Data Staf Administrasi</h5>
            <p class="text-muted small mb-0">Data staf tata usaha akan dimuat secara otomatis dari sistem.</p>
        </div>
    @endif

</div>
