@extends('layouts.admin')

@section('title', 'Kategori Keuangan')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <span class="text-uppercase small fw-semibold text-primary">Portal Tata Usaha</span>
        <h3 class="fw-bold mb-1">Keuangan Sekolah</h3>
        <p class="text-muted mb-0">Kelola kategori pemasukan dan pengeluaran yang dipakai pada form transaksi.</p>
    </div>

    @include('admin.finance._nav', ['active' => 'kategori', 'canManage' => true])

    @if ($errors->any())
        <div class="alert alert-danger py-2">
            @foreach ($errors->all() as $error)
                <div class="small">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- TAMBAH --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.finance.categories.store') }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-3">
                    <label for="new-type" class="form-label small mb-1">Jenis</label>
                    <select name="type" id="new-type" class="form-select" required>
                        <option value="income" @selected(old('type') === 'income')>Pemasukan</option>
                        <option value="expense" @selected(old('type') === 'expense')>Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="new-name" class="form-label small mb-1">Nama Kategori</label>
                    <input type="text" name="name" id="new-name" class="form-control" maxlength="120" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-3">
                    <label for="new-desc" class="form-label small mb-1">Keterangan (opsional)</label>
                    <input type="text" name="description" id="new-desc" class="form-control" maxlength="255" value="{{ old('description') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i> Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @foreach ([['income', 'Kategori Pemasukan', 'success', $income], ['expense', 'Kategori Pengeluaran', 'danger', $expense]] as [$key, $title, $color, $items])
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold text-{{ $color }}">{{ $title }}</div>
                    <ul class="list-group list-group-flush">
                        @forelse ($items as $category)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="fw-semibold {{ $category->is_active ? '' : 'text-muted' }}">
                                            {{ $category->name }}
                                            @if ($category->isSystem()) <span class="badge text-bg-light border">Bawaan</span> @endif
                                            @unless ($category->is_active) <span class="badge text-bg-secondary">Nonaktif</span> @endunless
                                        </div>
                                        @if ($category->description)
                                            <div class="small text-muted">{{ $category->description }}</div>
                                        @endif
                                        <div class="small text-muted">{{ $category->transactions_count }} transaksi</div>
                                    </div>

                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#edit-{{ $category->id }}" title="Ubah">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        @if ($category->system_key !== 'tagihan_siswa')
                                            <form method="POST" action="{{ route('admin.finance.categories.toggle', $category) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-secondary" title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="bi {{ $category->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if (! $category->isSystem() && $category->transactions_count === 0)
                                            <form method="POST" action="{{ route('admin.finance.categories.destroy', $category) }}"
                                                  onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="collapse mt-3" id="edit-{{ $category->id }}">
                                    <form method="POST" action="{{ route('admin.finance.categories.update', $category) }}" class="row g-2">
                                        @csrf
                                        @method('PUT')
                                        <div class="col-md-5">
                                            <input type="text" name="name" class="form-control form-control-sm" maxlength="120"
                                                   value="{{ $category->name }}" @disabled($category->isSystem()) required>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" name="description" class="form-control form-control-sm" maxlength="255"
                                                   value="{{ $category->description }}" placeholder="Keterangan">
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-sm btn-primary w-100">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center py-4">Belum ada kategori.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
