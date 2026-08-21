@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')

@section('content')
<h4 class="fw-bold mb-4">Dashboard Super Admin</h4>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total User</div>
                <div class="fs-3 fw-bold">{{ \App\Models\User::count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Role</div>
                <div class="fs-3 fw-bold">{{ \App\Models\Role::count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Permission</div>
                <div class="fs-3 fw-bold">{{ \App\Models\Permission::count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Gunakan menu di samping
            untuk mengelola user dan permission tiap role. Modul lain (CMS, PPDB, dst) akan
            menyusul sesuai roadmap Fase 1.
        </p>
    </div>
</div>
@endsection
