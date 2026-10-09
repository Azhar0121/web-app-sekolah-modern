<section id="prestasi" class="student-section">
    <div class="student-section-heading">
        <div class="section-heading-icon yellow">
            <i class="bi bi-award-fill"></i>
        </div>
        <div>
            <span>REKAM CAPAIAN SISWA</span>
            <h2>Prestasi & Kejuaraan Siswa</h2>
        </div>
        <div class="section-heading-count">
            <strong>{{ $achievements->count() }}</strong>
            <span>Capaian</span>
        </div>
    </div>

    @php
        $featuredAchievement = $achievements->where('is_featured', true)->first() ?? $achievements->first();
        $otherAchievements = $achievements->where('id', '!=', $featuredAchievement?->id);
    @endphp

    @if ($featuredAchievement)
        <div class="achievement-layout mb-5">
            <div class="achievement-feature">
                @if ($featuredAchievement->photo_url)
                    <div class="achievement-feature-photo-wrap mb-3">
                        <img src="{{ $featuredAchievement->photo_url }}" alt="{{ $featuredAchievement->title }}" class="achievement-feature-photo">
                    </div>
                @else
                    <div class="achievement-feature-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                @endif

                <span class="badge {{ $featuredAchievement->levelBadgeColor() }} mb-2">
                    {{ $featuredAchievement->levelLabel() }}
                </span>

                <h3>{{ $featuredAchievement->title }}</h3>

                <p>
                    {{ $featuredAchievement->description ?? 'Pencapaian gemilang yang diraih melalui dedikasi, latihan konsisten, dan bimbingan guru pembina.' }}
                    @if ($featuredAchievement->organizer)
                        <br><small class="text-white-50">Diselenggarakan oleh: {{ $featuredAchievement->organizer }} (Tahun {{ $featuredAchievement->year }})</small>
                    @endif
                </p>

                <div class="achievement-person">
                    <small>DIRAIH OLEH</small>
                    <strong>
                        {{ $featuredAchievement->student_name }}
                        <span>— {{ $featuredAchievement->student_class ?? 'Siswa Berprestasi' }}</span>
                    </strong>
                </div>
            </div>

            <div class="achievement-list">
                @forelse ($otherAchievements->take(3) as $ach)
                    <div class="achievement-row {{ $loop->iteration % 2 == 0 ? 'green' : 'blue' }}">
                        @if ($ach->photo_url)
                            <img src="{{ $ach->photo_url }}" alt="{{ $ach->title }}" class="achievement-row-thumb">
                        @else
                            <div class="achievement-row-icon">
                                <i class="bi bi-award-fill"></i>
                            </div>
                        @endif

                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge {{ $ach->levelBadgeColor() }} py-1 px-2" style="font-size: 10px;">
                                    {{ $ach->levelLabel() }}
                                </span>
                                <span class="badge bg-light text-secondary border" style="font-size: 10px;">
                                    {{ $ach->categoryLabel() }}
                                </span>
                                <small class="text-muted ms-auto">{{ $ach->year }}</small>
                            </div>
                            <h3>{{ $ach->title }}</h3>
                            @if ($ach->organizer)
                                <p class="mb-1 text-muted small">Penyelenggara: {{ $ach->organizer }}</p>
                            @endif
                            <small class="text-dark fw-bold">
                                {{ $ach->student_name }} <span class="text-muted fw-normal">— {{ $ach->student_class ?? 'Siswa' }}</span>
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="p-4 bg-white border text-center text-muted">
                        <p class="mb-0">Belum ada rekam prestasi lainnya.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    @if ($achievements->count() > 4)
        <div class="mt-4">
            <h4 class="h5 fw-bold text-dark mb-3"><i class="bi bi-collection me-2"></i>Daftar Prestasi Siswa Lainnya</h4>
            <div class="row g-3">
                @foreach ($achievements->skip(4) as $ach)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm p-3 rounded-3 achievement-sub-card">
                            <div class="d-flex align-items-start gap-3">
                                @if ($ach->photo_url)
                                    <img src="{{ $ach->photo_url }}" alt="{{ $ach->title }}" class="rounded object-fit-cover flex-shrink-0" style="width: 56px; height: 56px; border: 1px solid #cbd5e1;">
                                @else
                                    <div class="rounded bg-light border d-flex align-items-center justify-content-center text-warning flex-shrink-0" style="width: 56px; height: 56px; font-size: 22px;">
                                        <i class="bi bi-trophy"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex align-items-center gap-1 mb-1">
                                        <span class="badge {{ $ach->levelBadgeColor() }}" style="font-size: 9px;">{{ $ach->levelLabel() }}</span>
                                        <span class="text-muted ms-auto small">{{ $ach->year }}</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $ach->title }}">{{ $ach->title }}</h6>
                                    <p class="small text-muted mb-0 text-truncate">{{ $ach->student_name }} ({{ $ach->student_class ?? 'Siswa' }})</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>
