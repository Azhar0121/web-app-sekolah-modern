@extends('layouts.app')

@section('title', 'Materi - ' . $teachingAssignment->subject->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Materi Pembelajaran</h4>
        <p class="text-muted mb-0">
            {{ $teachingAssignment->classroom->name }} &middot; {{ $teachingAssignment->subject->name }}
        </p>
    </div>
    <div>
        <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-secondary btn-sm">&larr; Dashboard</a>
        <a href="{{ route('guru.teaching-assignments.materials.create', $teachingAssignment) }}" class="btn btn-primary btn-sm">
            + Tambah Materi
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Lampiran</th>
                    <th class="text-center">Status</th>
                    <th>Diunggah</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($materials as $material)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $material->title }}</div>
                            @if ($material->description)
                                <div class="text-muted small">{{ \Illuminate\Support\Str::limit($material->description, 80) }}</div>
                            @endif
                        </td>
                        <td>
                            @if ($material->hasFile())
                                <span class="badge text-bg-secondary">📎 {{ $material->file_original_name }}</span>
                            @endif
                            @if ($material->hasLink())
                                <a href="{{ $material->link }}" target="_blank" class="badge text-bg-info text-decoration-none">🔗 Link</a>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($material->is_published)
                                <span class="badge text-bg-success">Terbit</span>
                            @else
                                <span class="badge text-bg-light text-muted">Draft</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $material->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('guru.teaching-assignments.materials.edit', [$teachingAssignment, $material]) }}"
                               class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('guru.teaching-assignments.materials.destroy', [$teachingAssignment, $material]) }}"
                                  class="d-inline" onsubmit="return confirm('Hapus materi {{ $material->title }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada materi yang diunggah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
