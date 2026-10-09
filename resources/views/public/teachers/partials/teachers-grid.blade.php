<div class="teachers-grid-wrapper">

    @if ($teachers->isNotEmpty())
        <div class="row g-4" id="teachersListRow">
            @foreach ($teachers as $teacher)
                @php
                    $subjectsTaught = $teacher->teachingAssignments
                        ->pluck('subject.name')
                        ->unique()
                        ->filter()
                        ->implode(', ');
                    $classesTaught = $teacher->teachingAssignments
                        ->pluck('classroom.name')
                        ->unique()
                        ->filter()
                        ->take(3)
                        ->implode(', ');
                @endphp

                <div class="col-sm-6 col-md-4 col-lg-3 teacher-search-item"
                     data-name="{{ strtolower($teacher->name) }}"
                     data-subject="{{ strtolower($subjectsTaught) }}">

                    <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3 teacher-card-modern">
                        {{-- AVATAR FOTO --}}
                        <div class="position-relative d-inline-block mx-auto mb-3">
                            @if ($teacher->photo_url)
                                <img src="{{ $teacher->photo_url }}"
                                     alt="{{ $teacher->name }}"
                                     class="rounded-circle object-fit-cover shadow-sm border border-3 border-white"
                                     style="width: 100px; height: 100px;">
                            @else
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold shadow-sm border border-3 border-white"
                                     style="width: 100px; height: 100px; background: linear-gradient(135deg, #071b35 0%, #1769d5 100%); font-size: 2.2rem;">
                                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                </div>
                            @endif

                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle shadow-sm" title="Pendidik Aktif">
                                <span class="visually-hidden">Aktif</span>
                            </span>
                        </div>

                        {{-- NAMA & JABATAN --}}
                        <h5 class="fw-bold text-dark mb-1 fs-6 text-truncate" title="{{ $teacher->name }}">
                            {{ $teacher->name }}
                        </h5>

                        <div class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 mb-2 text-truncate"
                             style="font-size: 0.72rem;"
                             title="{{ $subjectsTaught ?: 'Tenaga Pendidik' }}">
                            <i class="bi bi-book-half me-1"></i>{{ $subjectsTaught ?: 'Tenaga Pendidik' }}
                        </div>

                        @if ($classesTaught)
                            <div class="small text-muted mb-2 text-truncate" style="font-size: 0.72rem;" title="Kelas: {{ $classesTaught }}">
                                <i class="bi bi-mortarboard me-1"></i>Kelas {{ $classesTaught }}
                            </div>
                        @endif

                        <div class="border-top pt-2 mt-auto">
                            <span class="text-muted small d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                <i class="bi bi-envelope"></i>
                                <span class="text-truncate" style="max-width: 170px;">{{ $teacher->email }}</span>
                            </span>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- STATE KOSONG PENCARIAN --}}
        <div id="noTeacherFound" class="d-none text-center py-5 bg-white rounded-4 border">
            <i class="bi bi-search fs-1 text-muted opacity-50 d-block mb-2"></i>
            <h6 class="fw-bold text-dark mb-1">Pengajar Tidak Ditemukan</h6>
            <p class="text-muted small mb-0">Coba gunakan kata kunci pencarian nama atau mata pelajaran lain.</p>
        </div>

    @else
        <div class="text-center py-5 bg-white rounded-4 border">
            <i class="bi bi-person-x fs-1 text-muted opacity-50 d-block mb-2"></i>
            <h5 class="fw-bold text-dark mb-1">Belum Ada Data Pengajar</h5>
            <p class="text-muted small mb-0">Data pengajar akan dimuat secara otomatis saat admin menambahkan akun guru.</p>
        </div>
    @endif

</div>

<style>
.teacher-card-modern {
    transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    border: 1px solid #e2e8f0 !important;
}
.teacher-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(7, 27, 53, 0.1) !important;
    border-color: #93c5fd !important;
}
</style>
