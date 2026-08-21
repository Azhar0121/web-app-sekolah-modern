@extends('layouts.guest')

@section('title', 'Login - ' . config('app.name'))

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">{{ config('app.name') }}</h4>
            <p class="text-muted small mb-0">Masuk ke portal sekolah</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2">
                @foreach ($errors->all() as $error)
                    <div class="small">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password"
                       class="form-control" required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label for="remember" class="form-check-label small">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>

        <hr>
        <p class="text-muted small mb-0 text-center">
            Akun demo (password: <code>password123</code>):<br>
            admin@sekolah.test &middot; guru@sekolah.test &middot; siswa@sekolah.test<br>
            ortu@sekolah.test &middot; tu@sekolah.test &middot; kepsek@sekolah.test
        </p>
    </div>
</div>
@endsection
