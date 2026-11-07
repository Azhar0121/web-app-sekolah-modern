@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin-user.css') }}">

<div class="users-page">

    {{-- HEADER --}}
    <div class="users-header">

        <div class="users-header-content">

            <span class="users-label">
                ADMINISTRATOR
            </span>

            <h1>
                Kelola User
            </h1>

            <p>
                Kelola pengguna, role, dan status akun dalam sistem informasi sekolah.
            </p>

        </div>

        <div class="users-header-actions">
            <button type="button" class="btn-import-users" data-bs-toggle="modal" data-bs-target="#importUserModal">
                <i class="bi bi-file-earmark-arrow-up-fill"></i>
                <span>Import Guru &amp; TU</span>
            </button>

            <a href="{{ route('admin.users.create') }}" class="add-user-button">
                <span class="add-user-icon">+</span>
                <span>Tambah User</span>
            </a>
        </div>

    </div>

    {{-- HASIL IMPORT MASSAL (JIKA BARU SAJA IMPORT) --}}
    @if (session('import_results'))
        <div class="import-result-banner mb-4">
            <div class="import-result-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="import-result-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold fs-6">Import Akun Massal Berhasil!</h3>
                        <p class="mb-0 text-muted small">
                            Berhasil membuat <strong>{{ count(session('import_results')) }}</strong> akun Guru &amp; Staf TU dengan kata sandi acak unik.
                        </p>
                    </div>
                </div>
                <div class="import-result-actions d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.users.import.export-rekap') }}" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-excel-fill"></i> Unduh Rekap Master (Excel/CSV)
                    </a>
                    <a href="{{ route('admin.users.import.print-slips') }}" target="_blank" class="btn btn-sm btn-primary fw-semibold d-inline-flex align-items-center gap-2">
                        <i class="bi bi-printer-fill"></i> Cetak Slip Akun (PDF)
                    </a>
                </div>
            </div>

            @if (!empty(session('import_errors')))
                <div class="mt-3 p-3 bg-light rounded border">
                    <div class="fw-semibold text-danger small mb-1">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Catatan ({{ count(session('import_errors')) }} baris dilewati):
                    </div>
                    <ul class="mb-0 ps-3 small text-muted">
                        @foreach (session('import_errors') as $err)
                            <li>Baris {{ $err['row'] }} ({{ $err['email'] }}): {{ $err['reason'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif


    {{-- FILTER --}}
    <div class="users-filter-card">

        <div class="filter-header">

            <div class="filter-icon">

                <svg
                    width="21"
                    height="21"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle cx="11" cy="11" r="7"></circle>
                    <path d="m20 20-4-4"></path>
                </svg>

            </div>

            <div>
                <h2>Filter User</h2>
                <p>Cari user berdasarkan nama, email, atau role.</p>
            </div>

        </div>


        <form
            method="GET"
            action="{{ route('admin.users.index') }}"
            class="users-filter-form"
        >

            <div class="user-filter-group">

                <label>
                    Cari nama / email
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Ketik untuk mencari..."
                >

            </div>


            <div class="user-filter-group">

                <label>
                    Filter Role
                </label>

                <select name="role">

                    <option value="all" @selected($roleFilter === 'all')>
                        Semua Role
                    </option>

                    @foreach ($roles as $role)

                        <option
                            value="{{ $role->slug }}"
                            @selected($roleFilter === $role->slug)
                        >
                            {{ $role->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="user-filter-action">

                <button type="submit">

                    <svg
                        width="17"
                        height="17"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-4-4"></path>
                    </svg>

                    Terapkan

                </button>

            </div>

        </form>

    </div>


    {{-- ROLE QUICK TABS --}}
    <div class="user-role-tabs">
        <a href="{{ route('admin.users.index', array_filter(['search' => $search, 'role' => 'all'])) }}"
           class="role-tab-btn {{ $roleFilter === 'all' ? 'active' : '' }}">
            Semua Role
        </a>

        @foreach ($roles as $role)
            <a href="{{ route('admin.users.index', array_filter(['search' => $search, 'role' => $role->slug])) }}"
               class="role-tab-btn {{ $roleFilter === $role->slug ? 'active' : '' }}">
                {{ $role->name }}
            </a>
        @endforeach
    </div>


    {{-- USER TABLE --}}
    <div class="users-table-card">

        <div class="users-table-header">

            <div>

                <h2>
                    Daftar User
                </h2>

                <p>
                    Data pengguna yang terdaftar dalam sistem.
                </p>

            </div>

            <div class="user-total">
                {{ $users->total() }} User
            </div>

        </div>


        <div class="users-table-wrapper">

            <table class="users-table">

                <thead>

                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="user-action-heading">Aksi</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($users as $user)

                        <tr>

                            {{-- NAMA --}}
                            <td>

                                <div class="user-name">

                                    <div class="user-avatar overflow-hidden">
                                        @if ($user->photo_url)
                                            <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                                        @else
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        @endif
                                    </div>

                                    <div>

                                        <strong>
                                            {{ $user->name }}
                                        </strong>

                                    </div>

                                </div>

                            </td>


                            {{-- EMAIL --}}
                            <td>

                                <span class="user-email">
                                    {{ $user->email }}
                                </span>

                            </td>


                            {{-- ROLE --}}
                            <td>

                                @if ($user->role)

                                    <span class="user-role-badge">
                                        {{ $user->role->name }}
                                    </span>

                                @else

                                    <span class="user-no-role">
                                        Belum ada role
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if ($user->is_active)

                                    <span class="user-status active">
                                        <span></span>
                                        Aktif
                                    </span>

                                @else

                                    <span class="user-status inactive">
                                        <span></span>
                                        Nonaktif
                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td class="user-actions">

                                <a
                                    href="{{ route('admin.users.edit', $user) }}"
                                    class="user-edit-button"
                                >
                                    Edit
                                </a>


                                <form
                                    method="POST"
                                    action="{{ route('admin.users.destroy', $user) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="user-delete-button"
                                        @disabled($user->id === auth()->id())
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="users-empty"
                            >
                                Tidak ada user yang cocok.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if ($users->hasPages())

            <div class="users-pagination">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>

        @endif

    </div>

</div>

{{-- MODAL IMPORT GURU & TU --}}
<div class="modal fade" id="importUserModal" tabindex="-1" aria-labelledby="importUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-3 px-4" style="background: #f8fbfe;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px; background: #0b294d;">
                        <i class="bi bi-file-earmark-arrow-up-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-dark" id="importUserModalLabel">Import Massal Akun Guru &amp; TU</h5>
                        <small class="text-muted">Buat banyak akun sekaligus dari file CSV dengan password acak yang unik</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    {{-- Langkah 1: Template --}}
                    <div class="p-3 mb-4 rounded-3 border" style="background: #f4f9fd; border-color: #d1e5f7 !important;">
                        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap flex-sm-nowrap">
                            <div>
                                <span class="badge bg-primary text-white mb-2">Langkah 1: Siapkan File</span>
                                <h6 class="fw-bold mb-1 text-dark">Gunakan Template Resmi Sekolah</h6>
                                <p class="text-muted small mb-0">
                                    Unduh template format CSV berikut, lalu isi kolom <strong>nama</strong>, <strong>email</strong>, dan <strong>role</strong> (isi <code>guru</code> atau <code>tu</code>). Kolom password tidak perlu diisi.
                                </p>
                            </div>
                            <a href="{{ route('admin.users.import.template') }}" class="btn btn-sm btn-outline-primary text-nowrap fw-semibold d-inline-flex align-items-center gap-2 mt-2 mt-sm-0">
                                <i class="bi bi-download"></i> Unduh Template CSV
                            </a>
                        </div>
                    </div>

                    {{-- Informasi Keamanan Password Otomatis --}}
                    <div class="p-3 mb-4 rounded-3 border" style="background: #fdfaf3; border-color: #f7e6c4 !important;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-shield-lock-fill text-warning fs-5 flex-shrink-0"></i>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark small">Keamanan Kata Sandi Otomatis</h6>
                                <p class="text-muted small mb-0" style="font-size: 12px; line-height: 1.5;">
                                    Sistem akan otomatis menghasilkan <strong>kata sandi acak unik</strong> untuk setiap akun (misal: <code>Muly6834</code>, <code>Siti7429</code>) yang aman dan bebas dari karakter membingungkan sehingga mudah dibaca oleh guru/staf senior. Setelah import, Anda dapat mengunduh <strong>rekap arsip TU</strong> dan <strong>mencetak slip kartu fisik</strong>.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Langkah 2: Upload File --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">
                            Langkah 2: Pilih File CSV yang Telah Diisi <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="file" class="form-control" accept=".csv,.txt" required>
                        <div class="form-text text-muted" style="font-size: 11px;">
                            Format file yang didukung: <code>.csv</code> (Maksimal 5 MB).
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-3 px-4 bg-light">
                    <button type="button" class="btn btn-sm btn-light border px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-semibold d-inline-flex align-items-center gap-2">
                        <i class="bi bi-cloud-arrow-up-fill"></i> Unggah &amp; Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection