<div class="act-card">
    <div class="act-card-header">
        <div>
            <h2><i class="bi bi-trophy-fill"></i> Ekstrakurikuler & Pengembangan Bakat</h2>
            <p>Kelola daftar ekstrakurikuler, kategori minat bakat, pembina, jadwal rutin, dan foto kegiatan ekskul.</p>
        </div>
        <button type="button" class="act-btn-add" data-bs-toggle="modal" data-bs-target="#createEkskulModal">
            <i class="bi bi-plus-lg"></i> Tambah Ekstrakurikuler
        </button>
    </div>

    <div class="act-table-wrap">
        <table class="act-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No.</th>
                    <th style="width: 70px;">Foto</th>
                    <th>Nama Ekstrakurikuler</th>
                    <th>Kategori</th>
                    <th>Pembina / Pelatih</th>
                    <th>Jadwal Rutin</th>
                    <th class="text-center" style="width: 90px;">Status</th>
                    <th class="text-end" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($extracurriculars as $ekskul)
                    <tr>
                        <td>{{ $loop->iteration + $extracurriculars->firstItem() - 1 }}</td>
                        <td>
                            @if ($ekskul->photo_url)
                                <img src="{{ $ekskul->photo_url }}" alt="{{ $ekskul->name }}" class="act-thumb">
                            @else
                                <div class="act-thumb-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                        </td>
                        <td>
                            <strong class="d-block text-dark">{{ $ekskul->name }}</strong>
                            <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">
                                {{ $ekskul->description ?? 'Tidak ada deskripsi singkat.' }}
                            </small>
                        </td>
                        <td>
                            <span class="badge {{ $ekskul->categoryBadgeColor() }} border py-1 px-2">
                                {{ $ekskul->categoryLabel() }}
                            </span>
                        </td>
                        <td>
                            <span class="text-dark">{{ $ekskul->coach_name ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="small text-secondary">
                                <i class="bi bi-clock me-1"></i>{{ $ekskul->schedule_day ?? '—' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if ($ekskul->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="act-actions">
                                <button type="button" class="act-action-btn edit" data-bs-toggle="modal" data-bs-target="#editEkskulModal{{ $ekskul->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.student-activities.extracurriculars.destroy', $ekskul) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus ekstrakurikuler {{ $ekskul->name }}?')">
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
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-folder-x fs-2 d-block mb-1 opacity-50"></i>
                            Belum ada data ekstrakurikuler.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($extracurriculars->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-light">
            {{ $extracurriculars->links() }}
        </div>
    @endif
</div>
