<?php

use App\Http\Controllers\Admin\PpdbController as AdminPpdbController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PpdbController;
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

// ================= PPDB ONLINE (PUBLIK, TANPA LOGIN) =================
Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::get('/', [PpdbController::class, 'index'])->name('index');
    Route::get('/daftar', [PpdbController::class, 'create'])->name('create');
    Route::post('/daftar', [PpdbController::class, 'store'])->name('store');
    Route::get('/sukses/{registrationNumber}', [PpdbController::class, 'success'])->name('sukses');
    Route::get('/cek-status', [PpdbController::class, 'checkStatusForm'])->name('cek-status.form');
    Route::post('/cek-status', [PpdbController::class, 'checkStatus'])->name('cek-status');
});

// ================= PORTAL SUPER ADMIN =================
Route::middleware(['auth', 'role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // User Management
    Route::resource('users', UserController::class)->except(['show']);

    // Role & Permission Management
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
});

// ================= KELOLA PPDB (SUPER ADMIN & TU, by permission) =================
Route::middleware(['auth', 'permission:ppdb.manage'])->prefix('admin/ppdb')->name('admin.ppdb.')->group(function () {
    Route::get('/', [AdminPpdbController::class, 'index'])->name('index');
    Route::get('/{ppdbRegistration}', [AdminPpdbController::class, 'show'])->name('show');
    Route::put('/{ppdbRegistration}/status', [AdminPpdbController::class, 'updateStatus'])->name('update-status');
    Route::put('/{ppdbRegistration}/re-registration', [AdminPpdbController::class, 'confirmReRegistration'])->name('confirm-re-registration');
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