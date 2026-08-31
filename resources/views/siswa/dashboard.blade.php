@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-2">Dashboard Siswa</h4>
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>.
            @if ($classroom = auth()->user()->currentClassroom())
                Kelas Anda saat ini: <strong>{{ $classroom->name }}</strong>.
            @else
                Anda belum terdaftar di kelas manapun pada tahun ajaran ini.
            @endif
        </p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <a href="{{ route('siswa.schedule.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-1">📅 Jadwal Pelajaran</h5>
                    <p class="text-muted small mb-0">Lihat jadwal pelajaran kelas Anda per hari.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('siswa.materials.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-1">📘 Materi Pembelajaran</h5>
                    <p class="text-muted small mb-0">Lihat & unduh materi dari guru untuk setiap mata pelajaran.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('siswa.tasks.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-1">📝 Tugas</h5>
                    <p class="text-muted small mb-0">Lihat tugas, batas waktu, dan kumpulkan jawaban Anda.</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
