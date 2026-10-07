@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/announcements/index.css') }}">

<div class="announcement-page">


{{-- HERO --}}
<div class="announcement-hero">
    <div class="announcement-hero-content">

        <div class="announcement-hero-icon">
            <i class="bi bi-megaphone-fill"></i>
        </div>

        <div>
            <span class="announcement-eyebrow">
                MANAJEMEN INFORMASI
            </span>

            <h1>Kelola Pengumuman</h1>

            <p>
                Kelola informasi dan pengumuman yang ditampilkan kepada pengguna sekolah.
            </p>
        </div>

    </div>

    <a
        href="{{ route('admin.announcements.create') }}"
        class="btn-create-announcement"
    >
        <i class="bi bi-plus-lg"></i>
        <span>Buat Pengumuman</span>
    </a>
</div>


{{-- CONTENT CARD --}}
<div class="announcement-card">

    {{-- FILTER TABS --}}
    <div class="announcement-tabs">

        <a
            href="{{ route('admin.announcements.index', ['status' => 'all']) }}"
            class="announcement-tab {{ request('status') === null || request('status') === 'all' ? 'active' : '' }}"
        >
            <i class="bi bi-grid"></i>
            <span>Semua</span>
        </a>

        <a
            href="{{ route('admin.announcements.index', ['status' => 'published']) }}"
            class="announcement-tab {{ request('status') === 'published' ? 'active' : '' }}"
        >
            <i class="bi bi-check-circle"></i>
            <span>Published</span>
        </a>

        <a
            href="{{ route('admin.announcements.index', ['status' => 'draft']) }}"
            class="announcement-tab {{ request('status') === 'draft' ? 'active' : '' }}"
        >
            <i class="bi bi-file-earmark"></i>
            <span>Draft</span>
        </a>

    </div>


    {{-- TABLE --}}
    <div class="announcement-table-wrapper">

        <div class="table-responsive">

            <table class="announcement-table">

                <thead>
                    <tr>
                        <th class="col-title">Judul</th>
                        <th>Target</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($announcements as $ann)

                        <tr>

                            {{-- JUDUL --}}
                            <td class="announcement-title-cell">

                                <div class="announcement-title">
                                    {{ $ann->title }}
                                </div>

                            </td>


                            {{-- TARGET --}}
                            <td>

                                @php
                                    $roles = is_array($ann->target_roles)
                                        ? $ann->target_roles
                                        : json_decode($ann->target_roles, true);
                                @endphp

                                <div class="badge-group">

                                    @if($roles && in_array('all', $roles))

                                        <span class="status-badge target-all">
                                            <i class="bi bi-people-fill"></i>
                                            Semua
                                        </span>

                                    @else

                                        @foreach($roles ?? [] as $role)

                                            <span class="status-badge target-role">
                                                {{ ucfirst($role) }}
                                            </span>

                                        @endforeach

                                    @endif

                                </div>

                            </td>


                            {{-- PRIORITAS --}}
                            <td>

                                @if($ann->priority === 'urgent')

                                    <span class="status-badge priority-urgent">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        Urgent
                                    </span>

                                @elseif($ann->priority === 'penting')

                                    <span class="status-badge priority-important">
                                        <i class="bi bi-star-fill"></i>
                                        Penting
                                    </span>

                                @else

                                    <span class="status-badge priority-normal">
                                        Normal
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td>

                                <form
                                    action="{{ route('admin.announcements.toggle', $ann->id) }}"
                                    method="POST"
                                    class="status-form"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="publish-toggle {{ $ann->is_published ? 'published' : 'draft' }}"
                                        title="Klik untuk mengubah status"
                                    >

                                        <span class="status-dot"></span>

                                        {{ $ann->is_published ? 'Published' : 'Draft' }}

                                    </button>

                                </form>

                            </td>


                            {{-- TANGGAL --}}
                            <td class="date-cell">

                                <div class="date-value">
                                    {{ $ann->created_at->format('d/m/Y') }}
                                </div>

                                <div class="time-value">
                                    {{ $ann->created_at->format('H:i') }}
                                </div>

                            </td>


                            {{-- AKSI --}}
                            <td>

                                <div class="action-buttons">

                                    <a
                                        href="{{ route('admin.announcements.edit', $ann->id) }}"
                                        class="action-btn action-edit"
                                        title="Edit pengumuman"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Edit</span>
                                    </a>


                                    <form
                                        action="{{ route('admin.announcements.destroy', $ann->id) }}"
                                        method="POST"
                                        class="delete-form"
                                        onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-btn action-delete"
                                            title="Hapus pengumuman"
                                        >
                                            <i class="bi bi-trash3"></i>
                                            <span>Hapus</span>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="bi bi-megaphone"></i>
                                    </div>

                                    <h3>Belum Ada Pengumuman</h3>

                                    <p>
                                        Belum ada pengumuman yang tersedia pada kategori ini.
                                    </p>

                                    <a
                                        href="{{ route('admin.announcements.create') }}"
                                        class="empty-action"
                                    >
                                        <i class="bi bi-plus-lg"></i>
                                        Buat Pengumuman
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- PAGINATION --}}
    @if($announcements->hasPages())

        <div class="announcement-pagination">
            {{ $announcements->links() }}
        </div>

    @endif

</div>


</div>

@endsection
