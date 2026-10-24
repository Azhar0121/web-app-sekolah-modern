{{-- Navigasi antar halaman modul Keuangan --}}
@php($active = $active ?? 'transaksi')
<ul class="nav nav-pills mb-4 gap-1">
    <li class="nav-item">
        <a class="nav-link {{ $active === 'transaksi' ? 'active' : '' }}" href="{{ route('admin.finance.index') }}">
            <i class="bi bi-journal-text me-1"></i> Transaksi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active === 'laporan' ? 'active' : '' }}" href="{{ route('admin.finance.report') }}">
            <i class="bi bi-bar-chart-line me-1"></i> Laporan
        </a>
    </li>
    @if ($canManage ?? false)
        <li class="nav-item">
            <a class="nav-link {{ $active === 'kategori' ? 'active' : '' }}" href="{{ route('admin.finance.categories.index') }}">
                <i class="bi bi-tags me-1"></i> Kategori
            </a>
        </li>
    @endif
</ul>
