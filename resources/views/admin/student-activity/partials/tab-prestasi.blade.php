<div class="act-card">
    <div class="act-card-header">
        <div>
            <h2><i class="bi bi-award-fill"></i> Rekam Prestasi Siswa & Prestasi Sekolah</h2>
            <p>Kelola data raihan juara, penghargaan kompetisi, sertifikat/medali, dan dokumentasi foto siswa berprestasi.</p>
        </div>
        <button type="button" class="act-btn-add" data-bs-toggle="modal" data-bs-target="#createAchievementModal">
            <i class="bi bi-plus-lg"></i> Tambah Prestasi
        </button>
    </div>

    <div class="act-table-wrap">
        <table class="act-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No.</th>
                    <th style="width: 70px;">Foto</th>
                    <th>Judul Prestasi / Kejuaraan</th>
                    <th>Nama Siswa & Kelas</th>
                    <th>Tingkat</th>
                    <th>Kategori</th>
                    <th>Tahun</th>
                    <th class="text-center" style="width: 90px;">Status</th>
                    <th class="text-end" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($achievements as $ach)
                    <tr>
                        <td>{{ $loop->iteration + $achievements->firstItem() - 1 }}</td>
                        <td>
                            @if ($ach->photo_url)
                                <img src="{{ $ach->photo_url }}" alt="{{ $ach->title }}" class="act-thumb">
                            @else
                                <div class="act-thumb-placeholder"><i class="bi bi-award"></i></div>
                            @endif
                        </td>
                        <td>
                            <strong class="d-block text-dark">{{ $ach->title }}</strong>
                            @if ($ach->organizer)
                                <small class="text-muted d-block">Penyelenggara: {{ $ach->organizer }}</small>
                            @endif
                            @if ($ach->is_featured)
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle mt-1" style="font-size: 10px;">
                                    <i class="bi bi-star-fill me-1"></i>Unggulan
                                </span>
                            @endif
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $ach->student_name }}</strong>
                            <span class="small text-muted">{{ $ach->student_class ?? 'Siswa' }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $ach->levelBadgeColor() }} py-1 px-2">
                                {{ $ach->levelLabel() }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary border py-1 px-2">
                                {{ $ach->categoryLabel() }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $ach->year }}</span>
                        </td>
                        <td class="text-center">
                            @if ($ach->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="act-actions">
                                <button type="button" class="act-action-btn edit" data-bs-toggle="modal" data-bs-target="#editAchievementModal{{ $ach->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.student-activities.achievements.destroy', $ach) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus prestasi {{ $ach->title }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="act-action-btn delete" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="bi bi-award fs-2 d-block mb-1 opacity-50"></i>
                            Belum ada rekam data prestasi siswa.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($achievements->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-light">
            {{ $achievements->links() }}
        </div>
    @endif
</div>
