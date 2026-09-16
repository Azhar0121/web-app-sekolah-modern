@extends('layouts.app')

@section('title', 'Dashboard Orang Tua / Wali')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-2">Dashboard Orang Tua / Wali</h4>
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>.
            @if ($children->count() > 1)
                Anda memantau {{ $children->count() }} anak di sekolah ini.
            @endif
        </p>
    </div>
</div>

@if ($children->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center text-muted py-5">
            Belum ada data anak yang tertaut ke akun Anda. Hubungi Tata Usaha kalau ini seharusnya sudah ada.
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach ($children as $child)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">{{ $child->name }}</h5>
                        <p class="text-muted small mb-3">
                            {{ $child->classroomForDisplay?->name ?? 'Kelas belum ditentukan' }}
                        </p>

                        <div class="d-flex gap-2">
                            <a href="{{ route('ortu.attendance.index', $child) }}" class="btn btn-sm btn-outline-primary">
                                📅 Riwayat Presensi
                            </a>
                            <a href="{{ route('ortu.grades.index', $child) }}" class="btn btn-sm btn-outline-primary">
                                📊 Nilai Rapor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
