<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ================= AUTH =================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// ================= PORTAL SUPER ADMIN =================
Route::middleware(['auth', 'role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // User Management
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
});

// ================= PORTAL GURU / WALI KELAS =================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->group(function () {
    Route::view('/dashboard', 'guru.dashboard')->name('guru.dashboard');
});

// ================= PORTAL SISWA =================
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->group(function () {
    Route::view('/dashboard', 'siswa.dashboard')->name('siswa.dashboard');
});

// ================= PORTAL ORANG TUA / WALI =================
Route::middleware(['auth', 'role:ortu'])->prefix('ortu')->group(function () {
    Route::view('/dashboard', 'ortu.dashboard')->name('ortu.dashboard');
});

// ================= PORTAL TATA USAHA =================
Route::middleware(['auth', 'role:tu'])->prefix('tu')->group(function () {
    Route::view('/dashboard', 'tu.dashboard')->name('tu.dashboard');
});

// ================= PORTAL KEPALA SEKOLAH =================
Route::middleware(['auth', 'role:kepsek'])->prefix('kepsek')->group(function () {
    Route::view('/dashboard', 'kepsek.dashboard')->name('kepsek.dashboard');
});