@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-2">Dashboard Super Admin</h4>
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>.
            Ini halaman placeholder untuk modul berikutnya sesuai roadmap
            (User & Permission Management, Master Data, CMS Control, dst).
        </p>
    </div>
</div>
@endsection
