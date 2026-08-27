@extends('layouts.admin')

@section('title', 'Kelola Role & Permission')

@section('content')

<link rel="stylesheet" href="{{ asset('css/roles.css') }}">

<div class="roles-page">

    {{-- HEADER --}}
    <div class="roles-header">

        <div class="roles-header-content">

            <div class="roles-title-area">

                <span class="roles-label">
                    ADMINISTRATOR
                </span>

                <h1>
                    Kelola Role & Permission
                </h1>

                <p>
                    Atur role pengguna dan hak akses yang digunakan
                    dalam sistem informasi sekolah.
                </p>

            </div>

        </div>

    </div>


    {{-- MAIN CARD --}}
    <div class="roles-main-card">

        {{-- CARD TOP --}}
        <div class="roles-card-top">

            <div class="roles-card-title">

                <div class="roles-title-icon">

                    <svg
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>

                </div>

                <div>

                    <h2>
                        Daftar Role
                    </h2>

                    <p>
                        Role dan hak akses pengguna sistem
                    </p>

                </div>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="roles-table-wrapper">

            <table class="roles-table">

                <thead>

                    <tr>

                        <th>
                            ROLE
                        </th>

                        <th>
                            DESKRIPSI
                        </th>

                        <th class="text-center">
                            JUMLAH USER
                        </th>

                        <th class="text-center">
                            PERMISSION
                        </th>

                        <th class="text-end">
                            AKSI
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($roles as $role)

                        <tr>

                            {{-- ROLE --}}
                            <td>

                                <div class="role-name">

                                    <div class="role-avatar">
                                        {{ strtoupper(substr($role->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <strong>
                                            {{ $role->name }}
                                        </strong>

                                        <span>
                                            Role sistem
                                        </span>
                                    </div>

                                </div>

                            </td>


                            {{-- DESCRIPTION --}}
                            <td>

                                <div class="role-description">
                                    {{ $role->description ?? '-' }}
                                </div>

                            </td>


                            {{-- USER --}}
                            <td class="text-center">

                                <span class="number-badge user-count">
                                    {{ $role->users_count }}
                                </span>

                            </td>


                            {{-- PERMISSION --}}
                            <td class="text-center">

                                <span class="number-badge permission-count">
                                    {{ $role->permissions_count }}
                                </span>

                            </td>


                            {{-- ACTION --}}
                            <td class="text-end">

                                <a
                                    href="{{ route('admin.roles.edit', $role) }}"
                                    class="manage-button"
                                >

                                    <span>
                                        Kelola Permission
                                    </span>

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
                                        <path d="M5 12h14"></path>
                                        <path d="m13 6 6 6-6 6"></path>
                                    </svg>

                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    {{-- INFORMATION --}}
    <div class="roles-information">

        <div class="information-icon">

            <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <circle cx="12" cy="12" r="9"></circle>
                <path d="M12 8v4"></path>
                <path d="M12 16h.01"></path>
            </svg>

        </div>


        <div class="information-content">

            <h3>
                Informasi Role Sistem
            </h3>

            <p>
                6 role sudah baku sesuai rancangan sistem
                (tidak bisa tambah/hapus role baru dari sini).
                Yang bisa diubah adalah nama tampilan, deskripsi,
                dan permission yang di-assign ke tiap role.
            </p>

        </div>

    </div>

</div>

@endsection