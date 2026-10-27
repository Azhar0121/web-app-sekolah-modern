@extends('layouts.admin')

@section('title', 'Kategori Keuangan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/finance/categories.css') }}">

<div class="finance-categories-page">

    {{-- HERO --}}
    <section class="fc-hero">
        <div class="fc-hero-content">
            <div class="fc-eyebrow">
                <i class="bi bi-wallet2"></i>
                <span>PORTAL TATA USAHA</span>
            </div>

            <h1>Kategori Keuangan</h1>

            <p>
                Kelola kategori pemasukan dan pengeluaran yang digunakan
                dalam pencatatan transaksi keuangan sekolah.
            </p>
        </div>

        <div class="fc-hero-icon">
            <i class="bi bi-tags"></i>
        </div>
    </section>

    {{-- NAVIGASI KEUANGAN --}}
    <div class="fc-navigation">
        @include('admin.finance._nav', [
            'active' => 'kategori',
            'canManage' => true
        ])
    </div>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="fc-error-alert">
            <div class="fc-error-icon">
                <i class="bi bi-exclamation-circle"></i>
            </div>

            <div>
                <h3>Data belum dapat diproses</h3>

                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    {{-- TAMBAH KATEGORI --}}
    <section class="fc-card fc-create-card">

        <div class="fc-card-header">
            <div class="fc-heading-icon">
                <i class="bi bi-plus-lg"></i>
            </div>

            <div>
                <h2>Tambah Kategori</h2>
                <p>Tambahkan kategori baru untuk mengelompokkan transaksi.</p>
            </div>
        </div>

        <div class="fc-card-body">
            <form
                method="POST"
                action="{{ route('admin.finance.categories.store') }}"
                class="fc-create-form"
            >
                @csrf

                <div class="fc-field fc-field-type">
                    <label for="new-type">Jenis Kategori <span>*</span></label>

                    <select name="type" id="new-type" class="form-select" required>
                        <option
                            value="income"
                            @selected(old('type', 'income') === 'income')
                        >
                            Pemasukan
                        </option>

                        <option
                            value="expense"
                            @selected(old('type') === 'expense')
                        >
                            Pengeluaran
                        </option>
                    </select>
                </div>

                <div class="fc-field fc-field-name">
                    <label for="new-name">Nama Kategori <span>*</span></label>

                    <input
                        type="text"
                        name="name"
                        id="new-name"
                        class="form-control"
                        maxlength="120"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Dana Operasional"
                        required
                    >
                </div>

                <div class="fc-field fc-field-description">
                    <label for="new-desc">Keterangan</label>

                    <input
                        type="text"
                        name="description"
                        id="new-desc"
                        class="form-control"
                        maxlength="255"
                        value="{{ old('description') }}"
                        placeholder="Keterangan opsional"
                    >
                </div>

                <div class="fc-field fc-field-submit">
                    <label class="fc-submit-label">&nbsp;</label>

                    <button type="submit" class="fc-btn fc-btn-primary">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Kategori
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- DAFTAR KATEGORI --}}
    <div class="fc-section-heading">
        <div class="fc-section-heading__content">
            <span class="fc-section-heading__eyebrow">
                <i class="bi bi-grid-1x2-fill"></i>
                DAFTAR KATEGORI
            </span>

            <h4 class="fc-section-heading__title">Kategori Transaksi</h4>

            <p class="fc-section-heading__description">
                Kelola kategori pemasukan dan pengeluaran sekolah.
            </p>
        </div>

        <div class="fc-section-heading__icon">
            <i class="bi bi-tags-fill"></i>
        </div>
    </div>

    <div class="fc-category-grid">

        @foreach ([
            ['income', 'Kategori Pemasukan', 'income', $income],
            ['expense', 'Kategori Pengeluaran', 'expense', $expense]
        ] as [$key, $title, $color, $items])

            <section class="fc-category-card fc-{{ $color }}">

                <div class="fc-category-header">
                    <div class="fc-category-header-main">
                        <div class="fc-category-icon">
                            <i class="bi {{ $key === 'income' ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}"></i>
                        </div>

                        <div>
                            <h3>{{ $title }}</h3>
                            <p>
                                {{ $key === 'income'
                                    ? 'Sumber penerimaan dana sekolah'
                                    : 'Penggunaan dan pengeluaran dana sekolah' }}
                            </p>
                        </div>
                    </div>

                    <span class="fc-category-count">
                        {{ $items->count() }} kategori
                    </span>
                </div>

                <div class="fc-category-list">
                    @forelse ($items as $category)

                        <article class="fc-category-item">

                            <div class="fc-category-item-main">

                                <div class="fc-category-item-heading">
                                    <h4 class="{{ $category->is_active ? '' : 'is-inactive' }}">
                                        {{ $category->name }}
                                    </h4>

                                    @if ($category->isSystem())
                                        <span class="fc-badge fc-badge-system">
                                            <i class="bi bi-shield-check"></i>
                                            Bawaan
                                        </span>
                                    @endif

                                    @unless ($category->is_active)
                                        <span class="fc-badge fc-badge-inactive">
                                            Nonaktif
                                        </span>
                                    @endunless
                                </div>

                                @if ($category->description)
                                    <p class="fc-category-description">
                                        {{ $category->description }}
                                    </p>
                                @else
                                    <p class="fc-category-description is-empty">
                                        Tidak ada keterangan
                                    </p>
                                @endif

                                <div class="fc-category-meta">
                                    <i class="bi bi-receipt"></i>

                                    <span>
                                        {{ $category->transactions_count }}
                                        transaksi tercatat
                                    </span>
                                </div>

                            </div>

                            {{-- AKSI KATEGORI --}}
                            <div class="fc-category-actions">

                                <button
                                    class="fc-action-btn fc-action-edit"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#edit-{{ $category->id }}"
                                    aria-expanded="false"
                                    aria-controls="edit-{{ $category->id }}"
                                    title="Ubah kategori"
                                    aria-label="Ubah kategori {{ $category->name }}"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                @if ($category->system_key !== 'tagihan_siswa')
                                    <form
                                        method="POST"
                                        action="{{ route('admin.finance.categories.toggle', $category) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="fc-action-btn fc-action-toggle"
                                            title="{{ $category->is_active ? 'Nonaktifkan kategori' : 'Aktifkan kategori' }}"
                                            aria-label="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }} {{ $category->name }}"
                                        >
                                            <i class="bi {{ $category->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                        </button>
                                    </form>
                                @endif

                                @if (! $category->isSystem() && $category->transactions_count === 0)
                                    <form
                                        method="POST"
                                        action="{{ route('admin.finance.categories.destroy', $category) }}"
                                        onsubmit="return confirm('Hapus kategori {{ $category->name }}?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="fc-action-btn fc-action-delete"
                                            title="Hapus kategori"
                                            aria-label="Hapus kategori {{ $category->name }}"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                @endif

                            </div>

                            {{-- FORM UBAH --}}
                            <div class="collapse fc-edit-collapse" id="edit-{{ $category->id }}">
                                <form
                                    method="POST"
                                    action="{{ route('admin.finance.categories.update', $category) }}"
                                    class="fc-edit-form"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div class="fc-field">
                                        <label for="category-name-{{ $category->id }}">
                                            Nama Kategori
                                        </label>

                                        <input
                                            type="text"
                                            id="category-name-{{ $category->id }}"
                                            name="name"
                                            class="form-control"
                                            maxlength="120"
                                            value="{{ $category->name }}"
                                            @disabled($category->isSystem())
                                            required
                                        >
                                    </div>

                                    <div class="fc-field">
                                        <label for="category-description-{{ $category->id }}">
                                            Keterangan
                                        </label>

                                        <input
                                            type="text"
                                            id="category-description-{{ $category->id }}"
                                            name="description"
                                            class="form-control"
                                            maxlength="255"
                                            value="{{ $category->description }}"
                                            placeholder="Keterangan opsional"
                                        >
                                    </div>

                                    <div class="fc-edit-actions">
                                        <button type="submit" class="fc-btn fc-btn-primary">
                                            <i class="bi bi-check2-circle"></i>
                                            Simpan Perubahan
                                        </button>

                                        <button
                                            type="button"
                                            class="fc-btn fc-btn-cancel"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#edit-{{ $category->id }}"
                                        >
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </article>

                    @empty
                        <div class="fc-empty-state">
                            <div class="fc-empty-icon">
                                <i class="bi bi-folder2-open"></i>
                            </div>

                            <h4>Belum Ada Kategori</h4>

                            <p>
                                Kategori {{ strtolower($title) }} belum tersedia.
                                Tambahkan kategori melalui formulir di atas.
                            </p>
                        </div>
                    @endforelse
                </div>

            </section>

        @endforeach

    </div>
</div>
@endsection
