@extends('layouts.admin')

@section('title', 'Audit Log & Security Trail')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/audit-logs/index.css') }}">

<div class="audit-logs-page">


{{-- HEADER --}}
<div class="audit-logs-header">
    <div class="audit-logs-header-content">

        <div class="audit-logs-title-area">

            <span class="audit-logs-label">
                AUDIT & KEAMANAN
            </span>

            <h1>
                Audit Log & Rekam Jejak Digital
            </h1>

            <p>
                Pantau dan telusuri aktivitas pengguna dalam sistem sekolah secara terstruktur.
            </p>

        </div>

        <div class="audit-logs-hero-icon">

            <svg width="35" height="35" viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.7"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <path d="M12 3 20 6v5.5c0 4.7-3.1 8.1-8 9.5-4.9-1.4-8-4.8-8-9.5V6l8-3Z"></path>
                <path d="m9 12 2 2 4-4"></path>

            </svg>

        </div>

    </div>
</div>


{{-- FILTER --}}
<div class="audit-logs-filter-card">

    <div class="audit-logs-filter-header">

        <div class="audit-logs-filter-title">

            <div class="audit-logs-filter-icon">

                <svg width="20" height="20" viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.9"
                     stroke-linecap="round"
                     stroke-linejoin="round">

                    <path d="M4 5h16"></path>
                    <path d="M7 12h10"></path>
                    <path d="M10 19h4"></path>

                </svg>

            </div>

            <div>
                <h2>
                    Filter Aktivitas
                </h2>

                <p>
                    Gunakan filter untuk menemukan riwayat aktivitas dengan cepat.
                </p>
            </div>

        </div>

    </div>


    <form method="GET"
          action="{{ route('admin.audit-logs.index') }}"
          class="audit-logs-filter-form">

        <div class="audit-logs-filter-field role-field">

            <label for="audit-role">
                Peran Pengguna (Role)
            </label>

            <select name="role"
                    id="audit-role"
                    class="audit-logs-select"
                    onchange="this.form.submit()">

                <option value="">
                    Semua Peran / Role
                </option>

                @foreach ($roles as $role)
                    <option value="{{ $role->slug }}"
                            @selected($roleFilter === $role->slug)>
                        {{ $role->name }}
                    </option>
                @endforeach

            </select>

        </div>

        <div class="audit-logs-filter-field event-field">

            <label for="audit-event">
                Jenis Event
            </label>

            <select name="event"
                    id="audit-event"
                    class="audit-logs-select"
                    onchange="this.form.submit()">

                <option value="">
                    Semua Event Aktivitas
                </option>

                <option value="login"
                        @selected($eventFilter === 'login')>
                    Login
                </option>

                <option value="logout"
                        @selected($eventFilter === 'logout')>
                    Logout
                </option>

                <option value="create"
                        @selected($eventFilter === 'create')>
                    Tambah Data (Create)
                </option>

                <option value="update"
                        @selected($eventFilter === 'update')>
                    Edit Data (Update)
                </option>

                <option value="delete"
                        @selected($eventFilter === 'delete')>
                    Hapus Data (Delete)
                </option>

                <option value="download"
                        @selected($eventFilter === 'download')>
                    Download Dokumen
                </option>

                <option value="approve"
                        @selected($eventFilter === 'approve')>
                    Persetujuan (Approve)
                </option>

            </select>

        </div>


        <div class="audit-logs-filter-field search-field">

            <label for="audit-search">
                Pencarian
            </label>

            <div class="audit-logs-input-wrapper">

                <svg width="18" height="18" viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">

                    <circle cx="11" cy="11" r="7"></circle>
                    <path d="m20 20-3.5-3.5"></path>

                </svg>

                <input type="text"
                       name="search"
                       id="audit-search"
                       value="{{ $search }}"
                       placeholder="Cari nama user, deskripsi aktivitas, atau IP Address...">

            </div>

        </div>


        <button type="submit"
                class="audit-logs-filter-button">

            Terapkan

        </button>

        @if ($roleFilter || $eventFilter || $search)
            <a href="{{ route('admin.audit-logs.index') }}"
               class="audit-logs-reset-button"
               title="Bersihkan filter">
                Reset
            </a>
        @endif

    </form>

</div>


{{-- TABLE --}}
<div class="audit-logs-table-card">

    <div class="audit-logs-card-top">

        <div class="audit-logs-card-title">

            <div class="audit-logs-title-icon">

                <svg width="21" height="21" viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8"
                     stroke-linecap="round"
                     stroke-linejoin="round">

                    <path d="M4 5h16v14H4z"></path>
                    <path d="M8 9h8"></path>
                    <path d="M8 13h5"></path>
                    <path d="M8 17h6"></path>

                </svg>

            </div>

            <div>

                <h2>
                    Riwayat Aktivitas
                </h2>

                <p>
                    Daftar aktivitas pengguna yang tercatat dalam sistem.
                </p>

            </div>

        </div>

    </div>


    <div class="audit-logs-table-wrapper">

        <table class="audit-logs-table">

            <thead>

                <tr>

                    <th style="width: 165px;">
                        Waktu
                    </th>

                    <th style="width: 190px;">
                        Pengguna
                    </th>

                    <th class="text-center" style="width: 120px;">
                        Event
                    </th>

                    <th>
                        Deskripsi Aktivitas
                    </th>

                    <th style="width: 165px;">
                        IP Address
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse ($logs as $log)

                    @php

                        $eventClass = match ($log->event) {

                            'create' => 'create',

                            'update' => 'update',

                            'delete' => 'delete',

                            'download' => 'download',

                            'login' => 'login',

                            'logout' => 'logout',

                            'approve' => 'approve',

                            default => 'default',

                        };


                        $eventIcon = match ($log->event) {

                            'create' => 'M12 5v14M5 12h14',

                            'update' => 'M12 20h9',

                            'delete' => 'M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6',

                            'download' => 'M12 3v12M7 10l5 5 5-5M5 21h14',

                            'login' => 'M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4',

                            'logout' => 'M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4',

                            'approve' => 'M5 12l4 4L19 6',

                            default => 'M12 8v4l3 2',

                        };

                    @endphp


                    <tr>

                        {{-- WAKTU --}}
                        <td>

                            <div class="audit-logs-time">

                                <div class="audit-logs-time-icon">

                                    <svg width="15" height="15"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.8"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">

                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="M12 7v5l3 2"></path>

                                    </svg>

                                </div>

                                <div>

                                    <strong>
                                        {{ $log->created_at->format('d M Y') }}
                                    </strong>

                                    <span>
                                        {{ $log->created_at->format('H:i:s') }}
                                    </span>

                                </div>

                            </div>

                        </td>


                        {{-- PENGGUNA --}}
                        <td>

                            <div class="audit-logs-user">

                                <div class="audit-logs-avatar">

                                    {{ strtoupper(substr($log->user_name ?? 'U', 0, 1)) }}

                                </div>

                                <div class="audit-logs-user-info">

                                    <strong>
                                        {{ $log->user_name }}
                                    </strong>

                                    @if ($log->user?->role)

                                        <span>
                                            {{ $log->user->role->name }}
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </td>


                        {{-- EVENT --}}
                        <td class="text-center">

                            <span class="audit-logs-event {{ $eventClass }}">

                                <svg width="12" height="12"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">

                                    <path d="{{ $eventIcon }}"></path>

                                </svg>

                                {{ strtoupper($log->event) }}

                            </span>

                        </td>


                        {{-- DESKRIPSI --}}
                        <td>

                            <div class="audit-logs-description">

                                {{ $log->description }}

                            </div>

                        </td>


                        {{-- IP --}}
                        <td>

                            <div class="audit-logs-ip">

                                <svg width="14" height="14"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="1.8"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">

                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M3 12h18"></path>
                                    <path d="M12 3c2.2 2.5 3.3 5.5 3.3 9S14.2 18.5 12 21"></path>
                                    <path d="M12 3c-2.2 2.5-3.3 5.5-3.3 9S9.8 18.5 12 21"></path>

                                </svg>

                                <span>
                                    {{ $log->ip_address ?? '—' }}
                                </span>

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td colspan="5" class="audit-logs-empty">

                            <div class="audit-logs-empty-icon">

                                <svg width="25" height="25"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="1.8"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">

                                    <path d="M12 3 20 6v5.5c0 4.7-3.1 8.1-8 9.5-4.9-1.4-8-4.8-8-9.5V6l8-3Z"></path>
                                    <path d="M9 12h6"></path>

                                </svg>

                            </div>

                            <strong>
                                Belum ada catatan audit log.
                            </strong>

                            <span>
                                Aktivitas pengguna yang tercatat akan muncul di sini.
                            </span>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}
    @if ($logs->hasPages())

        <div class="audit-logs-pagination">

            {{ $logs->links() }}

        </div>

    @endif

</div>


</div>

@endsection
