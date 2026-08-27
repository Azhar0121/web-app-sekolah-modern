@extends('layouts.admin')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<h4 class="fw-bold mb-4">Tambah Mata Pelajaran</h4>

<div class="card border-0 shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.subjects.store') }}">
            @csrf
            @php($subject = null)
            @include('admin.subjects.form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
