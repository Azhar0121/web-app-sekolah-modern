@extends('layouts.app')

@section('title', 'Materi - ' . $teachingAssignment->subject->name)

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/materials/index.css') }}">

<div class="materials-page">

{{-- =========================
    HEADER
========================== --}}
<div class="materials-header">

    <div class="materials-header-info">
        <div class="materials-label">
            MATERI PEMBELAJARAN
        </div>

        <h4 class="materials-title">
            Materi Pembelajaran
        </h4>

        <div class="materials-subtitle">
            <span>{{ $teachingAssignment->classroom->name }}</span>
            <span class="separator">•</span>
            <span>{{ $teachingAssignment->subject->name }}</span>
        </div>
    </div>

    <div class="materials-header-actions">
        <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            &larr; Dashboard
        </a>

        <a href="{{ route('guru.teaching-assignments.materials.create', $teachingAssignment) }}"
           class="btn btn-primary btn-sm">
            + Tambah Materi
        </a>
    </div>

</div>


{{-- =========================
    TABLE CARD
========================== --}}
<div class="materials-card">

    <div class="materials-card-header">
        <div>
            <h5>Daftar Materi</h5>
            <p>Kelola materi pembelajaran untuk kelas ini.</p>
        </div>
    </div>

    <div class="table-responsive">

        <table class="table materials-table align-middle mb-0">

            <thead>
                <tr>
                    <th>Judul Materi</th>
                    <th>Lampiran</th>
                    <th class="text-center">Status</th>
                    <th>Diunggah</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($materials as $material)

                    <tr>

                        {{-- JUDUL --}}
                        <td>
                            <div class="material-title">
                                {{ $material->title }}
                            </div>

                            @if ($material->description)
                                <div class="material-description">
                                    {{ \Illuminate\Support\Str::limit($material->description, 80) }}
                                </div>
                            @endif
                        </td>


                        {{-- LAMPIRAN --}}
                        <td>

                            <div class="material-attachments">

                                @if ($material->hasFile())
                                    <span class="material-badge file-badge">
                                        📎 {{ $material->file_original_name }}
                                    </span>
                                @endif

                                @if ($material->hasLink())
                                    <a href="{{ $material->link }}"
                                       target="_blank"
                                       class="material-badge link-badge text-decoration-none">
                                        🔗 Link
                                    </a>
                                @endif

                            </div>

                        </td>


                        {{-- STATUS --}}
                        <td class="text-center">

                            @if ($material->is_published)
                                <span class="status-badge status-published">
                                    Terbit
                                </span>
                            @else
                                <span class="status-badge status-draft">
                                    Draft
                                </span>
                            @endif

                        </td>


                        {{-- TANGGAL --}}
                        <td>
                            <span class="material-date">
                                {{ $material->created_at->format('d M Y') }}
                            </span>
                        </td>


                        {{-- AKSI --}}
                        <td class="text-end">

                            <div class="material-actions">

                                <a href="{{ route('guru.teaching-assignments.materials.edit', [$teachingAssignment, $material]) }}"
                                   class="btn-action btn-edit">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('guru.teaching-assignments.materials.destroy', [$teachingAssignment, $material]) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus materi {{ $material->title }}?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn-action btn-delete">
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5">

                            <div class="materials-empty">

                                <div class="empty-icon">
                                    📚
                                </div>

                                <div class="empty-title">
                                    Belum Ada Materi
                                </div>

                                <div class="empty-text">
                                    Belum ada materi yang diunggah untuk pembelajaran ini.
                                </div>

                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

@endsection
