@extends('layouts.admin')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
<h4 class="fw-bold mb-4">Tambah Tahun Ajaran</h4>

<div class="card border-0 shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger py-2">
                @foreach ($errors->all() as $error)
                    <div class="small">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.academic-years.store') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Tahun Ajaran</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: 2026/2027"
                       value="{{ old('name') }}" required autofocus>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="form-control"
                           value="{{ old('start_date') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" class="form-control"
                           value="{{ old('end_date') }}" required>
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" @checked(old('is_active'))>
                <label for="is_active" class="form-check-label">
                    Jadikan tahun ajaran aktif (menonaktifkan tahun ajaran lain)
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
