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
            @php
                $hasLatest = $latestBatch || !empty($latestImportCredentials);
                $latestCount = $latestBatch ? $latestBatch->total_accounts : count($latestImportCredentials ?? []);
            @endphp
            @if ($hasLatest && $latestCount > 0)
                <a href="{{ route('admin.users.import.print-slips') }}" target="_blank" class="btn-import-users" style="background: #047857; text-decoration: none;" title="Cetak slip kartu akun untuk import batch terbaru ({{ $latestCount }} akun)">
                    <i class="bi bi-printer-fill"></i>
                    <span>Cetak Slip Terbaru ({{ $latestCount }})</span>
                </a>

                <a href="{{ route('admin.users.import.export-rekap') }}" class="btn-import-users" style="background: #0284c7; text-decoration: none;" title="Unduh rekap master password acak import terbaru (CSV)">
                    <i class="bi bi-file-earmark-excel-fill"></i>
                    <span>Unduh Rekap CSV</span>
                </a>
            @endif

            @if (($totalBatches ?? 0) > 0)
                <a href="{{ route('admin.users.import.history') }}" class="btn-import-users" style="background: #334155; text-decoration: none;" title="Lihat riwayat dan arsip seluruh batch import sebelumnya">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Import ({{ $totalBatches }})</span>
                </a>
            @endif

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

    {{-- ALERT ERROR IMPORT (JIKA ADA BARIS CSV YANG DILEWATI SAAT IMPORT) --}}
    @if (!empty(session('import_errors')))
        <div class="alert alert-warning mb-4" style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; padding: 14px 18px;">
            <div class="fw-semibold text-danger small mb-1">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Catatan: {{ count(session('import_errors')) }} baris file CSV dilewati:
            </div>
            <ul class="mb-0 ps-3 small text-muted">
                @foreach (session('import_errors') as $err)
                    <li>Baris {{ $err['row'] }} ({{ $err['email'] }}): {{ $err['reason'] }}</li>
                @endforeach
            </ul>
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
                                    class="d-inline user-delete-form"
                                    data-user-name="{{ $user->name }}"
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const SCROLL_KEY = 'admin_users_scroll_y';
    let isFetchingUsers = false;

    const savedY = sessionStorage.getItem(SCROLL_KEY);
    if (savedY !== null) {
        const targetY = parseInt(savedY, 10);
        window.scrollTo({
            top: targetY,
            behavior: 'instant'
        });
        requestAnimationFrame(() => {
            window.scrollTo({
                top: targetY,
                behavior: 'instant'
            });
        });
        sessionStorage.removeItem(SCROLL_KEY);
    }

    document.querySelectorAll('.users-page form').forEach(form => {
        if (!form.classList.contains('user-delete-form') && !form.classList.contains('users-filter-form')) {
            form.addEventListener('submit', () => {
                sessionStorage.setItem(SCROLL_KEY, window.scrollY);
            });
        }
    });

    function showToast(message, type = 'success') {
        let container = document.getElementById('admin-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'admin-toast-container';
            container.style.cssText = 'position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; pointer-events: none;';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = 'admin-toast-alert';
        toast.style.cssText = 'background: #071b35; color: #ffffff; padding: 12px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; box-shadow: 0 8px 24px rgba(0,0,0,0.22); display: flex; align-items: center; gap: 10px; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); transform: translateY(20px); opacity: 0; pointer-events: auto; border-left: 4px solid #10b981; font-family: "Plus Jakarta Sans", sans-serif;';
        toast.innerHTML = `<i class="bi bi-check-circle-fill" style="color: #10b981; font-size: 16px;"></i> <span>${message}</span>`;

        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.transform = 'translateY(0)';
            toast.style.opacity = '1';
        });

        setTimeout(() => {
            toast.style.transform = 'translateY(20px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    async function loadUsers(url, updateHistory = true) {
        if (isFetchingUsers) return;
        isFetchingUsers = true;

        const tableCard = document.querySelector('.users-table-card');
        const tabsContainer = document.querySelector('.user-role-tabs');
        const filterForm = document.querySelector('.users-filter-form');

        if (tableCard) {
            tableCard.style.opacity = '0.55';
            tableCard.style.pointerEvents = 'none';
            tableCard.style.transition = 'opacity 0.15s ease';
        }

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal memuat data pengguna');
            }

            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');

            const newTabs = doc.querySelector('.user-role-tabs');
            if (tabsContainer && newTabs) {
                tabsContainer.innerHTML = newTabs.innerHTML;
            }

            const newTableCard = doc.querySelector('.users-table-card');
            if (tableCard && newTableCard) {
                tableCard.innerHTML = newTableCard.innerHTML;
            }

            const newRoleSelect = doc.querySelector('.users-filter-form select[name="role"]');
            const currentRoleSelect = filterForm ? filterForm.querySelector('select[name="role"]') : null;
            if (currentRoleSelect && newRoleSelect) {
                currentRoleSelect.value = newRoleSelect.value;
            }

            const newSearchInput = doc.querySelector('.users-filter-form input[name="search"]');
            const currentSearchInput = filterForm ? filterForm.querySelector('input[name="search"]') : null;
            if (currentSearchInput && newSearchInput) {
                currentSearchInput.value = newSearchInput.value;
            }

            if (updateHistory) {
                window.history.pushState({ path: url }, '', url);
            }

            bindRoleTabs();
            bindPaginationLinks();
            bindDeleteForms();

        } catch (error) {
            window.location.href = url;
        } finally {
            if (tableCard) {
                tableCard.style.opacity = '1';
                tableCard.style.pointerEvents = '';
            }
            isFetchingUsers = false;
        }
    }

    function bindRoleTabs() {
        document.querySelectorAll('.user-role-tabs .role-tab-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (this.classList.contains('active') && !isFetchingUsers) return;
                loadUsers(this.href);
            });
        });
    }

    function bindPaginationLinks() {
        document.querySelectorAll('.users-pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                loadUsers(this.href);
            });
        });
    }

    const filterForm = document.querySelector('.users-filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const params = new URLSearchParams(new FormData(this)).toString();
            loadUsers(`${this.action}?${params}`);
        });

        const roleSelect = filterForm.querySelector('select[name="role"]');
        if (roleSelect) {
            roleSelect.addEventListener('change', function() {
                const params = new URLSearchParams(new FormData(filterForm)).toString();
                loadUsers(`${filterForm.action}?${params}`);
            });
        }
    }

    window.addEventListener('popstate', () => {
        loadUsers(window.location.href, false);
    });

    function bindDeleteForms() {
        document.querySelectorAll('.user-delete-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const userName = this.getAttribute('data-user-name') || 'user ini';
                if (!confirm(`Yakin ingin menghapus user ${userName}?`)) {
                    return;
                }

                sessionStorage.setItem(SCROLL_KEY, window.scrollY);

                const submitBtn = this.querySelector('button[type="submit"]');
                const tr = this.closest('tr');
                const originalText = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="width: 11px; height: 11px; border-width: 2px;"></span>';

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                                      || this.querySelector('input[name="_token"]')?.value;

                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(this)
                    });

                    if (!response.ok) {
                        const errData = await response.json().catch(() => null);
                        throw new Error(errData?.message || 'Gagal menghapus akun.');
                    }

                    const data = await response.json();

                    sessionStorage.removeItem(SCROLL_KEY);

                    if (tr) {
                        tr.style.transition = 'all 0.32s ease';
                        tr.style.backgroundColor = '#fee2e2';
                        tr.style.opacity = '0';
                        tr.style.transform = 'scale(0.98)';

                        setTimeout(() => {
                            tr.remove();

                            // Update user-total badge
                            const totalBadge = document.querySelector('.user-total');
                            if (totalBadge) {
                                const match = totalBadge.textContent.match(/\d+/);
                                if (match) {
                                    const newTotal = Math.max(0, parseInt(match[0], 10) - 1);
                                    totalBadge.textContent = `${newTotal} User`;
                                }
                            }

                            // Jika tabel menjadi kosong
                            const tbody = document.querySelector('.users-table tbody');
                            if (tbody && tbody.querySelectorAll('tr').length === 0) {
                                tbody.innerHTML = '<tr><td colspan="5" class="users-empty">Tidak ada user yang cocok.</td></tr>';
                            }
                        }, 320);
                    }

                    showToast(data.message || `User ${userName} berhasil dihapus.`);

                } catch (error) {
                    alert(error.message || 'Terjadi kesalahan saat menghapus user.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        });

    bindRoleTabs();
    bindPaginationLinks();
    bindDeleteForms();
});
</script>

@endsection