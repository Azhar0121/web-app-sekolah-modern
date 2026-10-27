{{-- Navigasi antar halaman modul Keuangan --}}
@php
    $active = $active ?? 'transaksi';
@endphp

<link rel="stylesheet" href="{{ asset('css/admin/finance/navigation.css') }}">

<nav class="finance-navigation" aria-label="Navigasi modul keuangan">
    <div class="finance-navigation__list">

        {{-- Transaksi --}}
        <a
            href="{{ route('admin.finance.index') }}"
            class="finance-navigation__link {{ $active === 'transaksi' ? 'is-active' : '' }}"
            @if ($active === 'transaksi') aria-current="page" @endif
        >
            <i class="bi bi-journal-text" aria-hidden="true"></i>
            <span>Transaksi</span>
        </a>

        {{-- Laporan --}}
        <a
            href="{{ route('admin.finance.report') }}"
            class="finance-navigation__link {{ $active === 'laporan' ? 'is-active' : '' }}"
            @if ($active === 'laporan') aria-current="page" @endif
        >
            <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
            <span>Laporan</span>
        </a>

        {{-- Kategori --}}
        @if ($canManage ?? false)
            <a
                href="{{ route('admin.finance.categories.index') }}"
                class="finance-navigation__link {{ $active === 'kategori' ? 'is-active' : '' }}"
                @if ($active === 'kategori') aria-current="page" @endif
            >
                <i class="bi bi-tags" aria-hidden="true"></i>
                <span>Kategori</span>
            </a>
        @endif

    </div>
</nav>