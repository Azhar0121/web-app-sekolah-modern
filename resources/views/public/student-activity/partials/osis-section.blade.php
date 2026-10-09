<section id="osis" class="student-section">
    <div class="student-section-heading">
        <div class="section-heading-icon blue">
            <i class="bi bi-person-lines-fill"></i>
        </div>
        <div>
            <span>KEPEMIMPINAN SISWA</span>
            <h2>OSIS & Majelis Perwakilan Kelas</h2>
        </div>
    </div>

    <div class="osis-layout">
        <div class="osis-image">
            <img
                src="https://cdn.medcom.id/dynamic/content/2025/05/14/1758912/BxaCzTX4oF.jpg?w=1024"
                alt="Aktivitas siswa berorganisasi di sekolah"
            >
            <div class="image-caption">
                <strong>Ruang Belajar & Berorganisasi</strong>
                <span>Membentuk generasi pemimpin yang berintegritas, mandiri, dan kolaboratif.</span>
            </div>
        </div>

        <div class="osis-content">
            <span class="content-kicker">ORGANISASI SISWA INTRA SEKOLAH</span>
            <h3>Ruang tumbuh untuk kepemimpinan, kreativitas, dan kontribusi nyata.</h3>
            <p>
                Organisasi Siswa Intra Sekolah (OSIS) merupakan wadah sentral kepemimpinan siswa dalam merancang program kerja, menggerakkan kegiatan keagamaan, sosial, sains teknologi, seni budaya, dan olahraga sekolah.
            </p>

            <div class="osis-vision">
                <div class="vision-icon">
                    <i class="bi bi-quote"></i>
                </div>
                <div>
                    <span>VISI KEPENGURUSAN OSIS 2026/2027</span>
                    <p>
                        "Mewujudkan OSIS yang Inklusif, Kreatif, Berprestasi, Berkarakter Pancasila, serta Tanggap Terhadap Perkembangan Teknologi Digital."
                    </p>
                </div>
            </div>

            <div class="osis-points">
                <div>
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Kepemimpinan Berkarakter</span>
                </div>
                <div>
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Aksi Sosial & Kepedulian</span>
                </div>
                <div>
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Inovasi Digital Siswa</span>
                </div>
            </div>
        </div>
    </div>

    <div class="osis-programs-wrapper mt-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="text-primary fw-bold small text-uppercase letter-spacing-1">
                    <i class="bi bi-calendar2-event me-1"></i> Agenda & Dokumentasi
                </span>
                <h3 class="h4 fw-bold text-dark mb-0">Program Kerja & Kegiatan OSIS</h3>
            </div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-2 px-3 fw-semibold">
                {{ $osisActivities->count() }} Kegiatan Aktif
            </span>
        </div>

        @if ($osisActivities->isNotEmpty())
            <div class="row g-4">
                @foreach ($osisActivities as $act)
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden osis-activity-card">
                            {{-- FOTO UTAMA KEGIATAN --}}
                            <div class="position-relative overflow-hidden osis-act-img-box">
                                @if ($act->photo_url)
                                    <img src="{{ $act->photo_url }}" alt="{{ $act->title }}" class="w-100 osis-act-img">
                                @else
                                    <div class="w-100 bg-light d-flex align-items-center justify-content-center text-muted osis-act-img-placeholder">
                                        <i class="bi bi-image fs-1 opacity-50"></i>
                                    </div>
                                @endif

                                @if ($act->date)
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-75 backdrop-blur text-white py-1 px-2 small">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $act->date->translatedFormat('d M Y') }}
                                    </span>
                                @endif

                                @if ($act->is_featured)
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark py-1 px-2 small fw-bold">
                                        <i class="bi bi-star-fill me-1"></i>Unggulan
                                    </span>
                                @endif
                            </div>

                            <div class="card-body p-3 d-flex flex-column">
                                <h5 class="card-title fw-bold text-dark fs-6 mb-2 line-clamp-2">{{ $act->title }}</h5>
                                <p class="card-text text-muted small line-clamp-3 mb-3 flex-grow-1">
                                    {{ $act->description ?? 'Dokumentasi kegiatan resmi pengurus OSIS.' }}
                                </p>

                                {{-- TOMBOL LIHAT GALERI DOKUMENTASI JIKA ADA --}}
                                @if (!empty($act->gallery) && count($act->gallery) > 0)
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1 mt-auto" data-bs-toggle="modal" data-bs-target="#galleryModal{{ $act->id }}">
                                        <i class="bi bi-images"></i> Lihat Galeri ({{ count($act->gallery) + ($act->photo_url ? 1 : 0) }} Foto)
                                    </button>
                                @elseif ($act->photo_url)
                                    <button type="button" class="btn btn-light btn-sm text-secondary w-100 d-inline-flex align-items-center justify-content-center gap-1 mt-auto" data-bs-toggle="modal" data-bs-target="#galleryModal{{ $act->id }}">
                                        <i class="bi bi-arrows-fullscreen"></i> Perbesar Foto
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 bg-white border rounded-3 p-4 text-muted">
                <i class="bi bi-calendar-x fs-1 opacity-50 d-block mb-2"></i>
                <p class="mb-0">Belum ada agenda atau dokumentasi kegiatan OSIS yang dipublikasikan.</p>
            </div>
        @endif
    </div>

    <div class="osis-members-wrapper mt-5 pt-4 border-top">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="text-primary fw-bold small text-uppercase letter-spacing-1">
                    <i class="bi bi-people me-1"></i> Struktur Kepengurusan
                </span>
                <h3 class="h4 fw-bold text-dark mb-0">Pengurus & Anggota OSIS</h3>
            </div>
            <span class="badge bg-success-subtle text-success border border-success-subtle py-2 px-3 fw-semibold">
                Periode 2026/2027
            </span>
        </div>

        @if ($osisMembers->isNotEmpty())
            <div class="row g-4 row-cols-2 row-cols-md-3 row-cols-lg-6">
                @foreach ($osisMembers as $member)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-3 text-center p-3 osis-member-card">
                            <div class="member-avatar-box mx-auto mb-3">
                                @if ($member->photo_url)
                                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="member-avatar-img">
                                @else
                                    <div class="member-avatar-placeholder">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <h5 class="fw-bold text-dark fs-6 mb-1 text-truncate" title="{{ $member->name }}">{{ $member->name }}</h5>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-2 small mb-1">
                                {{ $member->position }}
                            </span>
                            <small class="text-muted d-block text-truncate" style="font-size: 11px;">
                                {{ $member->department ?? 'Pengurus OSIS' }}
                            </small>
                            @if ($member->class_name)
                                <span class="badge bg-light text-secondary border mt-2 small" style="font-size: 10px;">
                                    Kelas {{ $member->class_name }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 bg-white border rounded-3 p-4 text-muted">
                <i class="bi bi-people fs-1 opacity-50 d-block mb-2"></i>
                <p class="mb-0">Data kepengurusan OSIS sedang dalam proses pembaruan.</p>
            </div>
        @endif
    </div>
</section>
