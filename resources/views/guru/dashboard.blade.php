@extends('layouts.app')

@section('title', 'Dashboard Guru / Wali Kelas')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-2">Dashboard Guru / Wali Kelas</h4>
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>.
            Ini halaman placeholder — nanti berisi input nilai, presensi QR, dan materi kelas.
        </p>
    </div>
</div>
@endsection
