@extends('layouts.admin')

@section('title', 'Kelola Tahun Ajaran')

@section('content')
<h4 class="fw-bold mb-4">Kelola Tahun Ajaran: {{ $academicYear->name }}</h4>

@if ($errors->any())
    <div class="alert alert-danger py-2">
        @foreach ($errors->all() as $error)
            <div class="small">{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Data Tahun Ajaran</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.academic-years.update', $academicYear) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Tahun Ajaran</label>
                        <input type="text" name="name" id="name" class="form-control"
                               value="{{ old('name', $academicYear->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                               value="{{ old('start_date', $academicYear->start_date->format('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                               value="{{ old('end_date', $academicYear->end_date->format('Y-m-d')) }}" required>
                    </div>

                    @unless ($academicYear->is_active)
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1">
                            <label for="is_active" class="form-check-label">
                                Jadikan tahun ajaran aktif
                            </label>
                        </div>
                    @else
                        <p class="small text-success mb-3">
                            <span class="badge text-bg-success">Aktif</span> Tahun ajaran ini sedang aktif.
                        </p>
                    @endunless

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Semester</div>
            <div class="card-body">
                @if ($academicYear->semesters->isEmpty())
                    <p class="text-muted small">Belum ada semester untuk tahun ajaran ini.</p>
                @else
                    <ul class="list-group mb-3">
                        @foreach ($academicYear->semesters as $semester)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    Semester {{ $semester->name }}
                                    @if ($semester->is_active)
                                        <span class="badge text-bg-success ms-1">Aktif</span>
                                    @endif
                                </span>
                                <span class="d-flex gap-1">
                                    @unless ($semester->is_active)
                                        <form method="POST" action="{{ route('admin.academic-years.semesters.activate', [$academicYear, $semester]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">Aktifkan</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.academic-years.semesters.destroy', [$academicYear, $semester]) }}"
                                              onsubmit="return confirm('Hapus semester {{ $semester->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    @endunless
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if ($academicYear->semesters->count() < 2)
                    <form method="POST" action="{{ route('admin.academic-years.semesters.store', $academicYear) }}" class="d-flex gap-2">
                        @csrf
                        <select name="name" class="form-select" required>
                            <option value="">-- Pilih Semester --</option>
                            @foreach (['Ganjil', 'Genap'] as $option)
                                @unless ($academicYear->semesters->contains('name', $option))
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endunless
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary text-nowrap">+ Tambah</button>
                    </form>
                @endif

                <p class="text-muted small mt-3 mb-0">
                    Mengaktifkan sebuah semester otomatis mengaktifkan tahun ajaran ini
                    dan menonaktifkan tahun ajaran / semester lain di seluruh sistem.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
