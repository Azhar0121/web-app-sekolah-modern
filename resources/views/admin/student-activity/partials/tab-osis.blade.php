<div class="act-card">
    <div class="act-card-header">
        <div>
            <h2><i class="bi bi-calendar2-event-fill"></i> Agenda & Kegiatan OSIS</h2>
            <p>Kelola program kerja, agenda tahunan, foto kegiatan utama, dan galeri dokumentasi OSIS.</p>
        </div>
        <button type="button" class="act-btn-add" data-bs-toggle="modal" data-bs-target="#createOsisActivityModal">
            <i class="bi bi-plus-lg"></i> Tambah Kegiatan
        </button>
    </div>

    <div class="act-table-wrap">
        <table class="act-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No.</th>
                    <th style="width: 70px;">Foto</th>
                    <th>Nama Kegiatan & Program</th>
                    <th>Tanggal</th>
                    <th>Galeri Foto</th>
                    <th class="text-center" style="width: 100px;">Status</th>
                    <th class="text-end" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($osisActivities as $act)
                    <tr>
                        <td>{{ $loop->iteration + $osisActivities->firstItem() - 1 }}</td>
                        <td>
                            @if ($act->photo_url)
                                <img src="{{ $act->photo_url }}" alt="{{ $act->title }}" class="act-thumb">
                            @else
                                <div class="act-thumb-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                        </td>
                        <td>
                            <strong class="d-block text-dark">{{ $act->title }}</strong>
                            <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">
                                {{ $act->description ?? 'Tidak ada deskripsi singkat.' }}
                            </small>
                        </td>
                        <td>
                            <span class="small text-secondary">
                                <i class="bi bi-calendar3 me-1"></i>{{ $act->date ? $act->date->translatedFormat('d M Y') : '—' }}
                            </span>
                        </td>
                        <td>
                            @if (!empty($act->gallery) && count($act->gallery) > 0)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-2">
                                    <i class="bi bi-images me-1"></i>{{ count($act->gallery) }} foto
                                </span>
                            @else
                                <span class="text-muted small">0 foto</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($act->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="act-actions">
                                <button type="button" class="act-action-btn edit" data-bs-toggle="modal" data-bs-target="#editOsisActivityModal{{ $act->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.student-activities.osis.activities.destroy', $act) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kegiatan OSIS ini?')">
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
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-calendar-x fs-2 d-block mb-1 opacity-50"></i>
                            Belum ada agenda kegiatan OSIS.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($osisActivities->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-light">
            {{ $osisActivities->links() }}
        </div>
    @endif
</div>

<div class="act-card">
    <div class="act-card-header">
        <div>
            <h2><i class="bi bi-people-fill"></i> Kepengurusan & Anggota OSIS</h2>
            <p>Kelola data pengurus OSIS, jabatan, divisi/departemen, kelas, dan foto anggota.</p>
        </div>
        <button type="button" class="act-btn-add" data-bs-toggle="modal" data-bs-target="#createOsisMemberModal">
            <i class="bi bi-plus-lg"></i> Tambah Pengurus
        </button>
    </div>

    <div class="act-table-wrap">
        <table class="act-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No.</th>
                    <th style="width: 60px;">Foto</th>
                    <th>Nama Anggota</th>
                    <th>Jabatan</th>
                    <th>Divisi / Departemen</th>
                    <th>Kelas</th>
                    <th>Periode</th>
                    <th class="text-center" style="width: 90px;">Status</th>
                    <th class="text-end" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($osisMembers as $member)
                    <tr>
                        <td>{{ $loop->iteration + $osisMembers->firstItem() - 1 }}</td>
                        <td>
                            @if ($member->photo_url)
                                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="act-thumb-avatar">
                            @else
                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 44px; height: 44px; font-size: 14px;">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong class="d-block text-dark">{{ $member->name }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-2">
                                {{ $member->position }}
                            </span>
                        </td>
                        <td>{{ $member->department ?? '—' }}</td>
                        <td>{{ $member->class_name ?? '—' }}</td>
                        <td><small class="text-muted">{{ $member->period }}</small></td>
                        <td class="text-center">
                            @if ($member->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="act-actions">
                                <button type="button" class="act-action-btn edit" data-bs-toggle="modal" data-bs-target="#editOsisMemberModal{{ $member->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.student-activities.osis.members.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data pengurus OSIS ini?')">
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
                            <i class="bi bi-people fs-2 d-block mb-1 opacity-50"></i>
                            Belum ada data pengurus OSIS.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($osisMembers->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-light">
            {{ $osisMembers->links() }}
        </div>
    @endif
</div>
