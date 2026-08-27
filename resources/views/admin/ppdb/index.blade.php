@extends('layouts.admin')

@section('title', 'Kelola PPDB')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin-ppdb.css') }}">

<div class="ppdb-page">

    {{-- HEADER --}}
    <div class="ppdb-header">

        <div class="ppdb-header-content">

            <span class="ppdb-label">
                ADMINISTRATOR
            </span>

            <h1>
                Kelola Pendaftar PPDB
            </h1>

            <p>
                Kelola, pantau, dan verifikasi data pendaftar
                PPDB sekolah melalui halaman administrasi.
            </p>

        </div>

    </div>


    {{-- FILTER --}}
    <div class="ppdb-filter-card">

        <div class="ppdb-filter-info">

            <div class="ppdb-filter-icon">

                <svg
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M4 6h16"></path>
                    <path d="M7 12h10"></path>
                    <path d="M10 18h4"></path>
                </svg>

            </div>

            <div>

                <div class="ppdb-filter-title">
                    Filter Status Pendaftar
                </div>

                <div class="ppdb-filter-description">
                    Tampilkan pendaftar berdasarkan status proses PPDB.
                </div>

            </div>

        </div>


        <div class="ppdb-filter-form">

            <form
                method="GET"
                action="{{ route('admin.ppdb.index') }}"
            >

                <select
                    name="status"
                    class="ppdb-status-select"
                    onchange="this.form.submit()"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="submitted"
                        @selected($statusFilter === 'submitted')
                    >
                        Menunggu Verifikasi
                    </option>

                    <option
                        value="verified"
                        @selected($statusFilter === 'verified')
                    >
                        Terverifikasi
                    </option>

                    <option
                        value="accepted"
                        @selected($statusFilter === 'accepted')
                    >
                        Diterima — Menunggu Daftar Ulang
                    </option>

                    <option
                        value="registered_ulang"
                        @selected($statusFilter === 'registered_ulang')
                    >
                        Daftar Ulang Selesai
                    </option>

                    <option
                        value="rejected"
                        @selected($statusFilter === 'rejected')
                    >
                        Ditolak
                    </option>

                </select>

            </form>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="ppdb-table-card">

        <div class="ppdb-table-wrapper">

            <table class="ppdb-table">

                <thead>

                    <tr>

                        <th>
                            No. Pendaftaran
                        </th>

                        <th>
                            Nama
                        </th>

                        <th>
                            Asal Sekolah
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Tanggal Daftar
                        </th>

                        <th class="ppdb-action-column">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($registrations as $registration)

                        <tr>

                            <td>

                                <code class="ppdb-registration-code">
                                    {{ $registration->registration_number }}
                                </code>

                            </td>


                            <td>

                                <span class="ppdb-name">
                                    {{ $registration->full_name }}
                                </span>

                            </td>


                            <td>

                                <span class="ppdb-school">
                                    {{ $registration->previous_school }}
                                </span>

                            </td>


                            <td>

                                <span class="ppdb-status-badge">
                                    {{ $registration->statusLabel() }}
                                </span>

                            </td>


                            <td>

                                <span class="ppdb-date">
                                    {{ $registration->created_at->format('d M Y') }}
                                </span>

                            </td>


                            <td class="ppdb-action-column">

                                <div class="ppdb-action">

                                    <a
                                        href="{{ route('admin.ppdb.show', $registration) }}"
                                        class="ppdb-detail-button"
                                    >
                                        Detail
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="ppdb-empty"
                            >

                                <div class="ppdb-empty-icon">

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
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>

                                </div>

                                Belum ada pendaftar.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if ($registrations->hasPages())

            <div class="ppdb-pagination">

                {{ $registrations->links() }}

            </div>

        @endif

    </div>

</div>

@endsection