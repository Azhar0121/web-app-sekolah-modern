@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-2">Dashboard Kepala Sekolah</h4>
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>.
            Ini halaman placeholder — nanti berisi laporan & approval tingkat sekolah.
        </p>
    </div>
</div>
@endsection
