<?php

use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\PpdbController as AdminPpdbController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\StudentPlacementController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeachingAssignmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Guru\AttendanceController as GuruAttendanceController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\MaterialController as GuruMaterialController;
use App\Http\Controllers\Guru\ScheduleController as GuruScheduleController;
use App\Http\Controllers\Guru\TaskController as GuruTaskController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\Siswa\AttendanceController as SiswaAttendanceController;
use App\Http\Controllers\Siswa\MaterialController as SiswaMaterialController;
use App\Http\Controllers\Siswa\QrCodeController as SiswaQrCodeController;
use App\Http\Controllers\Siswa\ScheduleController as SiswaScheduleController;
use App\Http\Controllers\Siswa\TaskController as SiswaTaskController;
use App\Http\Controllers\Tu\DashboardController as TuDashboardController;
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
    Route::get('/cetak/{registrationNumber}', [PpdbController::class, 'print'])->name('cetak');
    Route::get('/cek-status', [PpdbController::class, 'checkStatusForm'])->name('cek-status.form');
    Route::post('/cek-status', [PpdbController::class, 'checkStatus'])->name('cek-status');
    Route::get('/lupa-nomor', [PpdbController::class, 'forgotNumberForm'])->name('lupa-nomor.form');
    Route::post('/lupa-nomor', [PpdbController::class, 'forgotNumber'])->name('lupa-nomor');
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

    // Master Data Akademik: Tahun Ajaran & Semester
    Route::resource('academic-years', AcademicYearController::class)->except(['show']);
    Route::post('academic-years/{academicYear}/semesters', [SemesterController::class, 'store'])
        ->name('academic-years.semesters.store');
    Route::post('academic-years/{academicYear}/semesters/{semester}/activate', [SemesterController::class, 'activate'])
        ->name('academic-years.semesters.activate');
    Route::delete('academic-years/{academicYear}/semesters/{semester}', [SemesterController::class, 'destroy'])
        ->name('academic-years.semesters.destroy');

    // Master Data Akademik: Mata Pelajaran
    Route::resource('subjects', SubjectController::class)->except(['show']);

    // Master Data Akademik: Kelas
    Route::resource('classrooms', ClassroomController::class)->except(['show']);

    // Penugasan Mengajar (guru-kelas-mapel per tahun ajaran)
    Route::resource('teaching-assignments', TeachingAssignmentController::class)->except(['show']);

    // Penempatan Siswa ke Kelas per tahun ajaran
    Route::get('student-placements', [StudentPlacementController::class, 'index'])->name('student-placements.index');
    Route::get('student-placements/{classroom}', [StudentPlacementController::class, 'manage'])->name('student-placements.manage');
    Route::post('student-placements/{classroom}', [StudentPlacementController::class, 'store'])->name('student-placements.store');
    Route::delete('student-placements/entry/{classroomStudent}', [StudentPlacementController::class, 'destroy'])->name('student-placements.destroy');

    // Jadwal Pelajaran
    Route::resource('schedules', AdminScheduleController::class)->except(['show']);
});

// ================= KELOLA PPDB (SUPER ADMIN & TU, by permission) =================
Route::middleware(['auth', 'permission:ppdb.manage'])->prefix('admin/ppdb')->name('admin.ppdb.')->group(function () {
    Route::get('/', [AdminPpdbController::class, 'index'])->name('index');
    Route::get('/{ppdbRegistration}', [AdminPpdbController::class, 'show'])->name('show');
    Route::put('/{ppdbRegistration}/status', [AdminPpdbController::class, 'updateStatus'])->name('update-status');
    Route::put('/{ppdbRegistration}/re-registration', [AdminPpdbController::class, 'confirmReRegistration'])->name('confirm-re-registration');
});

// ================= PORTAL GURU / WALI KELAS =================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    // Materi Pembelajaran (nested di bawah kelas+mapel yang diampu)
    Route::resource('teaching-assignments.materials', GuruMaterialController::class)
        ->parameters(['materials' => 'material'])
        ->except(['show']);

    // Tugas & Koreksi Pengumpulan
    Route::resource('teaching-assignments.tasks', GuruTaskController::class)
        ->parameters(['tasks' => 'task'])
        ->except(['show']);
    Route::get('teaching-assignments/{teachingAssignment}/tasks/{task}/submissions', [GuruTaskController::class, 'submissions'])
        ->name('teaching-assignments.tasks.submissions');
    Route::put('teaching-assignments/{teachingAssignment}/tasks/{task}/submissions/{submission}/grade', [GuruTaskController::class, 'grade'])
        ->name('teaching-assignments.tasks.submissions.grade');

    // Jadwal Mengajar Pribadi
    Route::get('/jadwal', [GuruScheduleController::class, 'index'])->name('schedule.index');

    // Presensi/Absensi QR Code
    Route::get('/presensi', [GuruAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/presensi/{schedule}/kelola', [GuruAttendanceController::class, 'session'])->name('attendance.session');
    Route::post('/presensi/sesi/{attendanceSession}/scan', [GuruAttendanceController::class, 'scan'])->name('attendance.scan');
    Route::put('/presensi/sesi/{attendanceSession}/siswa/{student}/status', [GuruAttendanceController::class, 'updateStatus'])->name('attendance.update-status');
    Route::post('/presensi/sesi/{attendanceSession}/tutup', [GuruAttendanceController::class, 'close'])->name('attendance.close');
});

// ================= PORTAL SISWA =================
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::view('/dashboard', 'siswa.dashboard')->name('dashboard');

    // Jadwal Pelajaran
    Route::get('/jadwal', [SiswaScheduleController::class, 'index'])->name('schedule.index');

    // Presensi/Absensi QR Code
    Route::get('/kartu-pelajar', [SiswaQrCodeController::class, 'show'])->name('qr-code.show');
    Route::get('/presensi', [SiswaAttendanceController::class, 'index'])->name('attendance.index');

    // Materi Pembelajaran
    Route::get('/materi', [SiswaMaterialController::class, 'index'])->name('materials.index');
    Route::get('/materi/{material}/unduh', [SiswaMaterialController::class, 'download'])->name('materials.download');

    // Tugas & Pengumpulan
    Route::get('/tugas', [SiswaTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tugas/{task}', [SiswaTaskController::class, 'show'])->name('tasks.show');
    Route::post('/tugas/{task}', [SiswaTaskController::class, 'store'])->name('tasks.store');
    Route::get('/tugas/{task}/lampiran', [SiswaTaskController::class, 'downloadAttachment'])->name('tasks.download-attachment');
    Route::get('/pengumpulan/{submission}/unduh', [SiswaTaskController::class, 'downloadSubmission'])->name('tasks.download-submission');
});

// ================= PORTAL ORANG TUA / WALI =================
Route::middleware(['auth', 'role:ortu'])->prefix('ortu')->group(function () {
    Route::view('/dashboard', 'ortu.dashboard')->name('ortu.dashboard');
});

// ================= PORTAL TATA USAHA =================
Route::middleware(['auth', 'role:tu'])->prefix('tu')->group(function () {
    Route::get('/dashboard', [TuDashboardController::class, 'index'])->name('tu.dashboard');
});

// ================= PORTAL KEPALA SEKOLAH =================
Route::middleware(['auth', 'role:kepsek'])->prefix('kepsek')->group(function () {
    Route::view('/dashboard', 'kepsek.dashboard')->name('kepsek.dashboard');
});