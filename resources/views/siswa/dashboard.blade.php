@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-2">Dashboard Siswa</h4>
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>.
            Ini halaman placeholder — nanti berisi nilai, presensi, dan materi.
        </p>
    </div>
</div>
@endsection
