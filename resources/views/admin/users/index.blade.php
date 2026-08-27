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

        <a href="{{ route('admin.users.create') }}" class="add-user-button">
            <span class="add-user-icon">+</span>
            Tambah User
        </a>

    </div>


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

                    <option value="">
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

                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
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
                {{ $users->links() }}
            </div>

        @endif

    </div>

</div>

@endsection