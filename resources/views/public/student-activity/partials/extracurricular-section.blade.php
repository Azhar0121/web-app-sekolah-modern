<section id="ekstrakurikuler" class="student-section">
    <div class="student-section-heading">
        <div class="section-heading-icon blue">
            <i class="bi bi-activity"></i>
        </div>
        <div>
            <span>PENGEMBANGAN BAKAT & MINAT</span>
            <h2>Ekstrakurikuler Pilihan & Wajib</h2>
        </div>
        <div class="section-heading-count">
            <strong>{{ $extracurriculars->count() }}+</strong>
            <span>Kegiatan</span>
        </div>
    </div>

    <div class="extracurricular-feature">
        <div class="extracurricular-image">
            <img
                src="https://s3.schoolmedia.id/01-cms-website/smanegeri52jkt.sch.id/editor/xt1Z8Lfzq8FQRzBPXeCGCIskJ0YPJ86vtkRChBDo.jpeg"
                alt="Kegiatan ekstrakurikuler siswa"
            >
            <div class="extracurricular-image-overlay">
                <span>KEGIATAN SISWA</span>
                <strong>Belajar tidak berhenti di dalam ruang kelas.</strong>
            </div>
        </div>

        <div class="extracurricular-text">
            <span class="content-kicker">BAKAT & MINAT</span>
            <h3>Temukan bidang yang <span>paling kamu sukai.</span></h3>
            <p>
                Beragam pilihan kegiatan dirancang untuk membekali siswa dengan soft skills kepemimpinan, sportivitas, daya cipta seni, serta kecakapan teknologi digital abad ke-21.
            </p>

            <div class="activity-mini-list">
                <span><i class="bi bi-check2"></i> Olahraga & Fisik</span>
                <span><i class="bi bi-check2"></i> Seni & Musik</span>
                <span><i class="bi bi-check2"></i> Sains & Teknologi</span>
                <span><i class="bi bi-check2"></i> Kepemimpinan</span>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 mb-4 overflow-auto pb-2 ekskul-filter-pills">
        <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 active filter-ekskul-btn" data-category="all">
            Semua ({{ $extracurriculars->count() }})
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 filter-ekskul-btn" data-category="olahraga">
            Olahraga
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 filter-ekskul-btn" data-category="seni">
            Seni & Budaya
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 filter-ekskul-btn" data-category="teknologi">
            Sains & Teknologi
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 filter-ekskul-btn" data-category="kepemimpinan">
            Kepemimpinan
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 filter-ekskul-btn" data-category="keagamaan">
            Keagamaan
        </button>
    </div>

    @if ($extracurriculars->isNotEmpty())
        <div class="row g-4" id="ekskulGrid">
            @foreach ($extracurriculars as $ekskul)
                <div class="col-md-6 col-lg-3 ekskul-card-item" data-category="{{ strtolower($ekskul->category) }}">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden ekskul-modern-card">
                        <div class="ekskul-cover-box position-relative">
                            @if ($ekskul->photo_url)
                                <img src="{{ $ekskul->photo_url }}" alt="{{ $ekskul->name }}" class="w-100 ekskul-cover-img">
                            @else
                                <div class="w-100 ekskul-cover-placeholder d-flex align-items-center justify-content-center">
                                    <i class="bi bi-trophy fs-1 opacity-25"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 m-2 badge {{ $ekskul->categoryBadgeColor() }} py-1 px-2 border small">
                                {{ $ekskul->categoryLabel() }}
                            </span>
                        </div>

                        <div class="card-body p-3 d-flex flex-column">
                            <h5 class="fw-bold text-dark fs-6 mb-2">{{ $ekskul->name }}</h5>
                            <p class="text-muted small mb-3 flex-grow-1 line-clamp-2">
                                {{ $ekskul->description ?? 'Pengembangan minat bakat siswa bersama pembina berpengalaman.' }}
                            </p>

                            <div class="ekskul-meta-info pt-2 border-top mt-auto small">
                                @if ($ekskul->coach_name)
                                    <div class="d-flex align-items-center gap-2 text-secondary mb-1">
                                        <i class="bi bi-person-badge text-primary"></i>
                                        <span class="text-truncate" title="Pembina: {{ $ekskul->coach_name }}">{{ $ekskul->coach_name }}</span>
                                    </div>
                                @endif
                                @if ($ekskul->schedule_day)
                                    <div class="d-flex align-items-center gap-2 text-secondary">
                                        <i class="bi bi-clock-history text-primary"></i>
                                        <span class="text-truncate">{{ $ekskul->schedule_day }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 bg-white border rounded-3 p-4 text-muted">
            <i class="bi bi-folder-x fs-1 opacity-50 d-block mb-2"></i>
            <p class="mb-0">Belum ada data ekstrakurikuler yang dipublikasikan.</p>
        </div>
    @endif
</section>
