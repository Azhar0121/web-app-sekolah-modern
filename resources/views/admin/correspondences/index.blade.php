@extends('layouts.app')

@section('title', 'Persuratan Digital')

@section('content')

<link rel="stylesheet" href="{{ asset('css/correspondences/index.css') }}">

<div class="correspondence-page">


{{-- =====================================================
     HEADER
     ===================================================== --}}
<div class="correspondence-header">

    <div class="correspondence-header-content">

        <span class="correspondence-label">
            PORTAL TATA USAHA
        </span>

        <h4>
            Persuratan Digital
        </h4>

        <p>
            Kelola surat masuk & keluar dengan penomoran otomatis.
        </p>

    </div>

    <div class="correspondence-header-actions">

        <a href="{{ route('tu.dashboard') }}"
           class="correspondence-btn correspondence-btn-back">
            &larr; Dashboard
        </a>

        <a href="{{ route('admin.correspondences.create', ['type' => $type]) }}"
           class="correspondence-btn correspondence-btn-primary">
            + {{ $type === 'masuk' ? 'Catat Surat Masuk' : 'Buat Surat Keluar' }}
        </a>

    </div>

</div>


{{-- =====================================================
     TABS
     ===================================================== --}}
<div class="correspondence-tabs">

    <a href="{{ route('admin.correspondences.index', ['type' => 'masuk']) }}"
       class="correspondence-tab {{ $type === 'masuk' ? 'active' : '' }}">

        <span>Surat Masuk</span>

        <span class="correspondence-count">
            {{ $counts['masuk'] }}
        </span>

    </a>

    <a href="{{ route('admin.correspondences.index', ['type' => 'keluar']) }}"
       class="correspondence-tab {{ $type === 'keluar' ? 'active' : '' }}">

        <span>Surat Keluar</span>

        <span class="correspondence-count">
            {{ $counts['keluar'] }}
        </span>

    </a>

</div>


{{-- =====================================================
     SEARCH CARD
     ===================================================== --}}
<div class="correspondence-search-card">

    <form method="GET"
          action="{{ route('admin.correspondences.index') }}"
          class="correspondence-search-form">

        <input type="hidden"
               name="type"
               value="{{ $type }}">

        <div class="correspondence-search-field">

            <label for="correspondence-search">
                Cari nomor / perihal /
                {{ $type === 'masuk' ? 'pengirim' : 'tujuan' }}
            </label>

            <input type="text"
                   id="correspondence-search"
                   name="search"
                   value="{{ $search }}"
                   class="correspondence-input"
                   placeholder="Ketik untuk mencari...">

        </div>

        <div class="correspondence-search-action">

            <button type="submit"
                    class="correspondence-apply-button">
                Terapkan
            </button>

        </div>

    </form>

</div>


{{-- =====================================================
     TABLE CARD
     ===================================================== --}}
<div class="correspondence-table-card">

    <div class="correspondence-table-wrapper">

        <table class="correspondence-table">

            <thead>

                <tr>

                    <th class="column-number">
                        Nomor
                    </th>

                    <th class="column-date">
                        Tanggal
                    </th>

                    <th class="column-subject">
                        Perihal
                    </th>

                    <th class="column-correspondent">
                        {{ $type === 'masuk' ? 'Pengirim' : 'Tujuan' }}
                    </th>

                    <th class="column-status text-center">
                        Status
                    </th>

                    <th class="column-action text-end">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse ($correspondences as $item)

                    <tr>

                        {{-- NOMOR --}}
                        <td class="column-number">

                            <code class="correspondence-number">
                                {{ $item->number }}
                            </code>

                        </td>


                        {{-- TANGGAL --}}
                        <td class="column-date">

                            <span class="correspondence-date">
                                {{ $item->letter_date->format('d M Y') }}
                            </span>

                        </td>


                        {{-- PERIHAL --}}
                        <td class="column-subject">

                            <div class="correspondence-subject">
                                {{ $item->subject }}
                            </div>

                            <div class="correspondence-category">
                                {{ $item->category }}
                            </div>

                        </td>


                        {{-- PENGIRIM / TUJUAN --}}
                        <td class="column-correspondent">

                            <span class="correspondence-person">
                                {{ $item->correspondent }}
                            </span>

                        </td>


                        {{-- STATUS --}}
                        <td class="column-status text-center">

                            @php
                                $statusColor = match ($item->status) {
                                    'selesai', 'terkirim' => 'success',
                                    'diproses' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp

                            <span class="correspondence-status correspondence-status-{{ $statusColor }}">
                                {{ \App\Models\Correspondence::statusOptions($item->type)[$item->status] ?? $item->status }}
                            </span>

                        </td>


                        {{-- AKSI --}}
                        <td class="column-action text-end">

                            <a href="{{ route('admin.correspondences.show', $item) }}"
                               class="correspondence-detail-button">
                                Detail
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="correspondence-empty">

                            Belum ada
                            {{ $type === 'masuk' ? 'surat masuk' : 'surat keluar' }}
                            yang tercatat.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =================================================
         PAGINATION
         ================================================= --}}
    @if ($correspondences->hasPages())

        <div class="correspondence-pagination">

            {{ $correspondences->links() }}

        </div>

    @endif

</div>


</div>

@endsection
