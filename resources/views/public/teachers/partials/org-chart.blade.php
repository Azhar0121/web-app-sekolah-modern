@php
    $principalUser = $principals->first();
    $principalName = $principalUser?->name ?? ($settings['principal_name'] ?? 'Drs. H. Ahmad Dahlan, M.Pd.');
@endphp

<div class="org-chart-wrapper my-4">

    {{-- LEVEL 1: KEPALA SEKOLAH --}}
    <div class="d-flex justify-content-center mb-4">
        <div class="org-node org-node-principal text-center p-3 rounded-4 shadow-sm bg-white border border-2 border-primary" style="max-width: 320px; width: 100%;">
            <div class="mb-2">
                @if ($principalUser && $principalUser->photo_url)
                    <img src="{{ $principalUser->photo_url }}"
                         alt="{{ $principalName }}"
                         class="rounded-circle object-fit-cover shadow border border-3 border-primary"
                         style="width: 84px; height: 84px;">
                @else
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold shadow border border-3 border-primary"
                         style="width: 84px; height: 84px; background: linear-gradient(135deg, #071b35, #1769d5); font-size: 1.8rem;">
                        {{ strtoupper(substr($principalName, 0, 1)) }}
                    </div>
                @endif
            </div>

            <span class="badge bg-primary text-white text-uppercase px-3 py-1 rounded-pill mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                KEPALA SEKOLAH
            </span>
            <h5 class="fw-bold text-dark mb-0 fs-6 mt-1">{{ $principalName }}</h5>
            <small class="text-muted d-block" style="font-size: 0.72rem;">Penanggung Jawab & Pimpinan Tertinggi</small>
        </div>
    </div>

    {{-- CONNECTOR LINE --}}
    <div class="org-connector-v"></div>

    {{-- LEVEL 2: WAKIL KEPALA SEKOLAH & KEPALA TATA USAHA --}}
    <div class="row g-3 justify-content-center mb-4 position-relative">
        <div class="col-md-6 col-lg-3">
            <div class="org-node text-center p-3 rounded-3 shadow-sm bg-white border h-100">
                <div class="org-avatar-mini mb-2">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-primary bg-primary-subtle fw-bold"
                         style="width: 60px; height: 60px; font-size: 1.2rem;">
                        WK
                    </div>
                </div>
                <span class="badge bg-info-subtle text-info-emphasis border px-2 py-1 rounded-pill mb-1" style="font-size: 0.65rem;">
                    WAKA KURIKULUM
                </span>
                <h6 class="fw-bold text-dark mb-1 small">Dra. Hj. Siti Aminah, M.Si.</h6>
                <small class="text-muted" style="font-size: 0.7rem;">Pengembangan Akademik & Silabus</small>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="org-node text-center p-3 rounded-3 shadow-sm bg-white border h-100">
                <div class="org-avatar-mini mb-2">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-success bg-success-subtle fw-bold"
                         style="width: 60px; height: 60px; font-size: 1.2rem;">
                        WS
                    </div>
                </div>
                <span class="badge bg-success-subtle text-success-emphasis border px-2 py-1 rounded-pill mb-1" style="font-size: 0.65rem;">
                    WAKA KESISWAAN
                </span>
                <h6 class="fw-bold text-dark mb-1 small">Bambang Susilo, S.Pd.</h6>
                <small class="text-muted" style="font-size: 0.7rem;">Pembinaan Karakter & Ekstrakurikuler</small>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="org-node text-center p-3 rounded-3 shadow-sm bg-white border h-100">
                <div class="org-avatar-mini mb-2">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-warning bg-warning-subtle fw-bold"
                         style="width: 60px; height: 60px; font-size: 1.2rem;">
                        WP
                    </div>
                </div>
                <span class="badge bg-warning-subtle text-warning-emphasis border px-2 py-1 rounded-pill mb-1" style="font-size: 0.65rem;">
                    WAKA SARANA PRASARANA
                </span>
                <h6 class="fw-bold text-dark mb-1 small">Ir. Eko Prasetyo, M.T.</h6>
                <small class="text-muted" style="font-size: 0.7rem;">Pemeliharaan Fasilitas & Ruangan</small>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="org-node text-center p-3 rounded-3 shadow-sm bg-white border h-100">
                <div class="org-avatar-mini mb-2">
                    @php $tuFirst = $staff->first(); @endphp
                    @if ($tuFirst && $tuFirst->photo_url)
                        <img src="{{ $tuFirst->photo_url }}"
                             alt="{{ $tuFirst->name }}"
                             class="rounded-circle object-fit-cover shadow-sm border border-2 border-secondary"
                             style="width: 60px; height: 60px;">
                    @else
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-secondary bg-secondary-subtle fw-bold"
                             style="width: 60px; height: 60px; font-size: 1.2rem;">
                            TU
                        </div>
                    @endif
                </div>
                <span class="badge bg-secondary-subtle text-secondary-emphasis border px-2 py-1 rounded-pill mb-1" style="font-size: 0.65rem;">
                    KEPALA TATA USAHA
                </span>
                <h6 class="fw-bold text-dark mb-1 small">{{ $tuFirst?->name ?? 'Dra. Endang Purwanti' }}</h6>
                <small class="text-muted" style="font-size: 0.7rem;">Administrasi & Kepegawaian</small>
            </div>
        </div>
    </div>

    {{-- LEVEL 3: DEWAN GURU & PELAKSANA --}}
    <div class="p-4 rounded-4 bg-light border text-center">
        <div class="row align-items-center justify-content-between g-3">
            <div class="col-md-6 text-md-start">
                <h6 class="fw-bold text-dark mb-1">
                    <i class="bi bi-people-fill text-primary me-2"></i>Dewan Pendidik & Staf Operasional
                </h6>
                <p class="text-muted small mb-0">
                    Didukung oleh <strong>{{ $teachers->count() }} orang Guru</strong> bersertifikasi dan <strong>{{ $staff->count() }} orang Staf Administrasi</strong> profesional.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <button type="button" class="btn btn-primary btn-sm px-3 rounded-pill" onclick="document.getElementById('tab-guru-btn').click();">
                    <i class="bi bi-person-workspace me-1"></i> Lihat Daftar Guru
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-pill ms-2" onclick="document.getElementById('tab-staf-btn').click();">
                    <i class="bi bi-briefcase me-1"></i> Lihat Staf TU
                </button>
            </div>
        </div>
    </div>

</div>

<style>
.org-connector-v {
    width: 2px;
    height: 24px;
    background: #cbd5e1;
    margin: 0 auto 12px;
}
.org-node {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.org-node:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 24px rgba(7, 27, 53, 0.08) !important;
}
</style>
